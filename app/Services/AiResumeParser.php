<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AI-powered resume parser using Google Gemini API (free tier).
 *
 * Falls back to nothing if no API key is configured — the caller should
 * then use the regex-based ResumeParser as a fallback.
 *
 * Get a free API key: https://aistudio.google.com/app/apikey
 * Add to .env: GEMINI_API_KEY=your_key_here
 */
class AiResumeParser
{
    public function isConfigured(): bool
    {
        return ! empty(config('services.gemini.key'));
    }

    /**
     * Parse a CV text into structured sections using Gemini AI.
     * Returns the same shape as ResumeParser::parseSections() so it's
     * a drop-in replacement.
     *
     * @return array{
     *   summary?: string, experience?: string, education?: string,
     *   skills?: string, certifications?: string, projects?: string,
     *   languages?: string, awards?: string, interests?: string,
     *   references?: string, raw_text: string,
     *   structured?: array, // optional richer data when AI returns it
     * }
     */
    public function parseSections(string $resumeText): array
    {
        $resumeText = trim($resumeText);
        if ($resumeText === '' || ! $this->isConfigured()) {
            return ['raw_text' => $resumeText];
        }

        // Trim very long resumes to keep API call fast & under quota
        $input = mb_strlen($resumeText) > 12000 ? mb_substr($resumeText, 0, 12000) : $resumeText;

        $prompt = $this->buildPrompt($input);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/'
                    .config('services.gemini.model').':generateContent?key='
                    .config('services.gemini.key'),
                    [
                        'contents' => [[
                            'parts' => [['text' => $prompt]],
                        ]],
                        'generationConfig' => [
                            'temperature'      => 0.1,
                            'topP'             => 0.95,
                            'maxOutputTokens'  => 4096,
                            'responseMimeType' => 'application/json',
                        ],
                    ]
                );

            if (! $response->successful()) {
                Log::warning('AiResumeParser: API call failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['raw_text' => $resumeText];
            }

            $body = $response->json();
            $jsonText = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
            if (! $jsonText) {
                return ['raw_text' => $resumeText];
            }

            $structured = json_decode($jsonText, true);
            if (! is_array($structured)) {
                Log::warning('AiResumeParser: invalid JSON from model', ['snippet' => mb_substr($jsonText, 0, 300)]);
                return ['raw_text' => $resumeText];
            }

            return $this->normalizeStructured($structured, $resumeText);
        } catch (\Throwable $e) {
            Log::warning('AiResumeParser: exception', ['err' => $e->getMessage()]);
            return ['raw_text' => $resumeText];
        }
    }

    /**
     * Use AI to also extract simple top-level fields (phone, headline, skills…)
     * for auto-filling the user's profile.
     */
    public function extractProfileFields(string $resumeText): array
    {
        $sections = $this->parseSections($resumeText);
        $structured = $sections['structured'] ?? [];

        $skillsList = $structured['skills'] ?? [];
        $skillsCsv  = is_array($skillsList) ? implode(', ', array_slice($skillsList, 0, 12)) : null;

        // Build a flat profile bundle
        return array_filter([
            'name'             => $structured['contact']['name']     ?? null,
            'email'            => $structured['contact']['email']    ?? null,
            'phone'            => $structured['contact']['phone']    ?? null,
            'preferred_city'   => $structured['contact']['location'] ?? null,
            'headline'         => $structured['headline']            ?? null,
            'bio'              => $structured['summary']             ?? null,
            'skills'           => $skillsCsv,
            'experience_years' => isset($structured['total_experience_years']) ? (int) $structured['total_experience_years'] : null,
        ], fn($v) => $v !== null && $v !== '');
    }

    /**
     * Pass through the AI's structured sections + provide flat text for legacy callers.
     *
     * Output:
     *  - raw_text, structured (debug)
     *  - summary, skills (flat strings for the auto-fill flow)
     *  - sections: ordered array of {heading, type, paragraphs?, bullets?, items?} for the view
     */
    protected function normalizeStructured(array $s, string $rawText): array
    {
        $out = [
            'raw_text'   => $rawText,
            'structured' => $s,
            'sections'   => [],
        ];

        // Flat helpers for the auto-fill flow
        if (! empty($s['summary'])) {
            $out['summary'] = trim($s['summary']);
        }
        if (! empty($s['skills']) && is_array($s['skills'])) {
            $out['skills'] = implode(', ', array_filter($s['skills']));
        }

        // Pass through sections from AI verbatim (preserve original order + structure)
        if (! empty($s['sections']) && is_array($s['sections'])) {
            foreach ($s['sections'] as $sec) {
                if (! is_array($sec)) continue;
                $heading = trim((string) ($sec['heading'] ?? ''));
                if ($heading === '') continue;

                $clean = ['heading' => $heading, 'type' => (string) ($sec['type'] ?? 'paragraph')];

                if (! empty($sec['paragraphs']) && is_array($sec['paragraphs'])) {
                    $clean['paragraphs'] = array_values(array_filter(array_map('trim', $sec['paragraphs'])));
                }
                if (! empty($sec['bullets']) && is_array($sec['bullets'])) {
                    $clean['bullets'] = array_values(array_filter(array_map('trim', $sec['bullets'])));
                }
                if (! empty($sec['items']) && is_array($sec['items'])) {
                    $items = [];
                    foreach ($sec['items'] as $item) {
                        if (! is_array($item)) continue;
                        $cleanItem = array_filter([
                            'title'    => trim((string) ($item['title']    ?? '')),
                            'subtitle' => trim((string) ($item['subtitle'] ?? '')),
                            'meta'     => trim((string) ($item['meta']     ?? '')),
                        ], fn ($v) => $v !== '');
                        if (! empty($item['bullets']) && is_array($item['bullets'])) {
                            $cleanItem['bullets'] = array_values(array_filter(array_map('trim', $item['bullets'])));
                        }
                        if (! empty($item['paragraphs']) && is_array($item['paragraphs'])) {
                            $cleanItem['paragraphs'] = array_values(array_filter(array_map('trim', $item['paragraphs'])));
                        }
                        if (! empty($cleanItem)) {
                            $items[] = $cleanItem;
                        }
                    }
                    if (! empty($items)) {
                        $clean['items'] = $items;
                    }
                }

                // Skip empty sections (no content at all)
                if (empty($clean['paragraphs']) && empty($clean['bullets']) && empty($clean['items'])) {
                    continue;
                }
                $out['sections'][] = $clean;
            }
        }

        return $out;
    }

    /** The system prompt that asks Gemini for clean structured JSON. */
    protected function buildPrompt(string $resumeText): string
    {
        return <<<PROMPT
You are a profession-agnostic resume parser. The CV may be a DEVELOPER, LAWYER, ACCOUNTANT, DOCTOR, TEACHER, DESIGNER, MARKETER, NURSE, CHEF, ENGINEER, etc. Each profession has its OWN typical sections — preserve them exactly.

The text was extracted from a multi-column PDF and may arrive jumbled (e.g. job titles on one line, company names several lines later, dates floating on the right, bullets after that). YOUR JOB IS TO REASSEMBLE IT CORRECTLY using your understanding of resume conventions.

Output VALID JSON only — no markdown, no commentary, no code fences. Use this exact shape (omit fields that aren't present — never invent data):

{
  "contact": {
    "name": "Full Name",
    "email": "...",
    "phone": "...",
    "location": "City, ST/Country"
  },
  "headline": "Latest Title (e.g. Senior Lawyer at XYZ Firm / Tax Accountant / Full Stack Developer)",
  "summary": "Short professional summary paragraph (≤500 chars).",
  "total_experience_years": 5,
  "skills": ["..."],
  "sections": [
    {
      "heading": "ORIGINAL SECTION HEADING from the resume (verbatim — keep its case)",
      "type": "experience" | "education" | "projects" | "list" | "paragraph" | "skills",
      "paragraphs": ["Optional paragraph text, one entry per paragraph"],
      "bullets": ["Optional simple bullet 1", "Optional simple bullet 2"],
      "items": [
        {
          "title":    "Bold primary line (job title, project name, degree, certification name, case name)",
          "subtitle": "Bold secondary line (company name, institution, issuer, stack used)",
          "meta":     "Lighter italic line (city · dates, OR just dates)",
          "bullets":  ["Bullet 1 belonging to THIS item only", "Bullet 2"],
          "paragraphs": ["Optional paragraph(s) for this item"]
        }
      ]
    }
  ]
}

CRITICAL — multi-column reassembly:
- Job/project bullets BELONG to the entry above them. Do NOT mix bullets across companies.
- A typical experience entry looks like:
    "Software Engineer"  ← title
    "Acme Corp"          ← subtitle (company)
    "Lahore, Pakistan · Mar 2026 – Present"  ← meta
    Then bullets describing what they did at THAT job.
- The next title/company starts a NEW item with its OWN bullets. Never carry bullets forward.
- "Projects" entries are NOT experience entries — keep them in a separate section with heading "Projects" (or whatever the resume calls it).
- "Certifications" with single-line entries → use "bullets" array, not "items".
- "Skills" / "Technical Skills" → use "skills" type with "bullets" as the skill list (one skill per bullet).

Section structure rules:
- The "sections" array MUST appear in the same ORDER they appear in the resume.
- Use the verbatim ORIGINAL heading text (e.g. keep "PROFESSIONAL SUMMARY", "BAR ADMISSIONS", "AUDIT ENGAGEMENTS", "TECHNICAL SKILLS" exactly as written).
- For each section, pick ONE of: paragraphs, bullets, items, or skills — whichever matches the source content. Combine if needed (e.g. a section with intro paragraphs followed by items).
- type: "experience" → use items with title/subtitle/meta/bullets
- type: "education" → use items with title (degree) / subtitle (institution) / meta (location · dates) / bullets (coursework, GPA, honors)
- type: "projects" → use items with title (project name) / subtitle (stack/tech) / meta (year) / bullets
- type: "list" → simple bullet list (Bar Admissions, Notable Cases, Memberships, Awards)
- type: "paragraph" → free-form prose (Professional Summary, Objective, About)
- type: "skills" → skill chips, use "bullets" as the array

UNIVERSAL FIELDS:
- contact, headline, summary: same as in source
- skills: a flat array of skill strings (deduplicated, canonical names)
- total_experience_years: integer if obvious, otherwise omit

Examples of profession-specific sections you MUST preserve verbatim:
  • Developer: Projects, Tech Stack, Open Source, Publications
  • Lawyer: Bar Admissions, Notable Cases, Practice Areas, Court Appearances, Publications
  • Accountant: Audit Engagements, Tax Compliance, Industry Experience, Software, CPA/ACCA Certifications
  • Doctor: Medical License, Specializations, Hospital Affiliations, Procedures, Research
  • Teacher: Subjects Taught, Curriculum Development, Student Outcomes
  • Designer: Portfolio, Tools, Exhibitions
  • Universal: Certifications, Languages, Awards, Volunteer Work, Memberships, References, Interests

CV TEXT:
---
$resumeText
---
PROMPT;
    }
}
