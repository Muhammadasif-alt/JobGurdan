<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Central AI content service powered by Google Gemini.
 *
 * Used across the site for:
 *  - Job description generation (company + admin job forms)
 *  - Bio / headline polishing (seeker profile)
 *  - Skills extraction (seeker profile)
 *  - Category description generation (admin)
 *  - Blog content / excerpt / meta generation (admin)
 *  - Spam detection (contact messages, jobs)
 *  - Job matching score (seeker dashboard recommendations)
 */
class AiContentService
{
    public function isConfigured(): bool
    {
        return ! empty(config('services.gemini.key'));
    }

    /* ====================================================================
     | JOB DESCRIPTION GENERATOR
     ==================================================================== */

    /**
     * Generate a complete job description from a title + optional context.
     */
    public function generateJobDescription(string $title, ?string $company = null, ?string $keywords = null, ?string $location = null): ?string
    {
        $title = trim($title);
        if ($title === '') {
            return null;
        }

        $context = collect([
            $company ? "Company: $company" : null,
            $location ? "Location: $location" : null,
            $keywords ? "Key skills/keywords: $keywords" : null,
        ])->filter()->implode("\n");

        $prompt = <<<PROMPT
Write a complete, professional job description for a "$title" position.

$context

Format the output as plain text with these sections, each starting with a bold heading on its own line:

About the Role
(2-3 sentences explaining what the role does and why it matters)

Key Responsibilities
- Bullet 1
- Bullet 2
- ... (5-7 bullets, action-oriented, specific)

Requirements
- Bullet 1
- Bullet 2
- ... (5-6 bullets covering experience, skills, education)

Nice to Have
- Bullet 1
- Bullet 2
- ... (2-3 bullets)

What We Offer
- Bullet 1
- Bullet 2
- ... (3-4 bullets)

Rules:
- Output plain text ONLY. No markdown headers (#), no asterisks for bold, no code fences.
- Keep tone professional but warm. No corporate jargon.
- Do not invent specific salary numbers, company names, or perks if none were given.
- Do not include any text before "About the Role" or after the last bullet.
PROMPT;

        return $this->generateText($prompt, ['maxOutputTokens' => 1500, 'temperature' => 0.6]);
    }

    /* ====================================================================
     | SEEKER PROFILE: BIO / HEADLINE / SKILLS
     ==================================================================== */

    /**
     * Polish or generate a professional bio.
     */
    public function polishBio(?string $currentBio, ?string $headline = null, ?string $skills = null, ?string $experience = null): ?string
    {
        $currentBio = trim((string) $currentBio);
        $context = collect([
            $headline ? "Headline: $headline" : null,
            $skills ? "Skills: $skills" : null,
            $experience ? "Years of experience: $experience" : null,
        ])->filter()->implode("\n");

        if ($currentBio !== '') {
            $prompt = <<<PROMPT
Polish this professional bio for a job seeker. Keep it under 500 characters, written in first person, ATS-friendly, and engaging.

$context

Current bio:
"""
$currentBio
"""

Rules:
- Output ONLY the polished bio text. No quotes, no preamble, no commentary.
- Keep the candidate's actual experience and tone — don't fabricate.
- Use active voice. Lead with the strongest credential.
- 2-4 sentences max.
PROMPT;
        } else {
            $prompt = <<<PROMPT
Write a professional bio for a job seeker based on this info:

$context

Rules:
- Output ONLY the bio text. No quotes, no preamble, no commentary.
- Under 500 characters, written in first person, ATS-friendly.
- 2-4 sentences. Active voice. Lead with strongest credential.
- If info is sparse, write a clean general-purpose bio at the right experience level.
PROMPT;
        }

        return $this->generateText($prompt, ['maxOutputTokens' => 400, 'temperature' => 0.5]);
    }

    /**
     * Polish or generate a professional headline (one-line title).
     */
    public function polishHeadline(?string $currentHeadline, ?string $skills = null, ?string $experience = null): ?string
    {
        $currentHeadline = trim((string) $currentHeadline);
        $context = collect([
            $skills ? "Skills: $skills" : null,
            $experience ? "Years of experience: $experience" : null,
        ])->filter()->implode("\n");

        if ($currentHeadline !== '') {
            $prompt = <<<PROMPT
Polish this job seeker headline into a professional one-liner under 100 characters. It should read like a LinkedIn headline — title + key expertise area.

$context

Current headline:
"""
$currentHeadline
"""

Rules:
- Output ONLY the polished headline. No quotes, no commentary.
- Single line, under 100 characters.
- Format: "Senior {Title} | {Specialty} | {Tech/Domain}" or similar.
- Don't invent credentials.
PROMPT;
        } else {
            $prompt = <<<PROMPT
Write a professional one-line job seeker headline based on this info:

$context

Rules:
- Output ONLY the headline. No quotes, no commentary.
- Single line, under 100 characters.
- Format: "Senior {Title} | {Specialty} | {Tech/Domain}" or similar.
PROMPT;
        }

        return $this->generateText($prompt, ['maxOutputTokens' => 100, 'temperature' => 0.4]);
    }

    /**
     * Extract a clean skills CSV from free-form text (bio, experience, etc).
     */
    public function extractSkills(string $sourceText): ?string
    {
        $sourceText = trim($sourceText);
        if ($sourceText === '') {
            return null;
        }
        $sourceText = mb_substr($sourceText, 0, 8000);

        $prompt = <<<PROMPT
Extract a clean list of professional skills from this text. Return ONLY the skills as a comma-separated list, no commentary.

Rules:
- Maximum 15 skills.
- Use canonical names (e.g., "JavaScript" not "JS", "MySQL" not "mysql db").
- Mix of hard skills (technologies, tools) and relevant soft skills.
- No duplicates. No generic words like "communication" unless clearly emphasized.

Text:
"""
$sourceText
"""

Output (comma-separated, no other text):
PROMPT;

        return $this->generateText($prompt, ['maxOutputTokens' => 200, 'temperature' => 0.2]);
    }

    /* ====================================================================
     | ADMIN: CATEGORIES
     ==================================================================== */

    public function generateCategoryDescription(string $name, ?string $type = 'job category'): ?string
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        $prompt = <<<PROMPT
Write a clear 2-3 sentence description for a $type called "$name" on a job portal website.

Rules:
- Output ONLY the description text. No quotes, no preamble.
- 2-3 sentences, ~250-400 characters total.
- Explain what kinds of roles fall under this category and what skills/interests they involve.
- Friendly, professional tone. No marketing fluff.
PROMPT;

        return $this->generateText($prompt, ['maxOutputTokens' => 250, 'temperature' => 0.5]);
    }

    /* ====================================================================
     | ADMIN: BLOG
     ==================================================================== */

    /**
     * Generate full blog article content from title + optional context.
     * Returns plain text ready for textarea (no markdown headers).
     */
    public function generateBlogContent(string $title, ?string $keywords = null, ?string $tone = null): ?string
    {
        $title = trim($title);
        if ($title === '') {
            return null;
        }

        $context = collect([
            $keywords ? "Keywords to cover: $keywords" : null,
            $tone ? "Tone: $tone" : 'Tone: friendly, informative, professional',
        ])->filter()->implode("\n");

        $prompt = <<<PROMPT
Write a complete blog article titled "$title" for a job portal blog. Target length: 600-900 words.

$context

Structure:
- Opening hook (1 short paragraph)
- 3-5 logical sections with descriptive headings on their own line (no #, just plain bold-style text)
- Practical tips, examples, or actionable advice
- Closing paragraph with a takeaway or call to action

Rules:
- Output plain text only. NO markdown (#, *, **, _, code fences). NO HTML tags.
- Section headings: just a short title on its own line followed by paragraphs/bullets. Bullets use "- ".
- Real, useful content. No filler. No "in today's fast-paced world" intros.
- Don't fabricate specific statistics, names, or quotes.
PROMPT;

        return $this->generateText($prompt, ['maxOutputTokens' => 2200, 'temperature' => 0.7]);
    }

    public function generateBlogExcerpt(string $title, ?string $content = null): ?string
    {
        $title = trim($title);
        if ($title === '') {
            return null;
        }
        $contentSnippet = $content ? mb_substr(trim($content), 0, 2000) : '';

        $prompt = <<<PROMPT
Write a compelling 1-2 sentence blog excerpt (under 200 characters) for an article titled "$title".

Article snippet:
"""
$contentSnippet
"""

Rules:
- Output ONLY the excerpt text. No quotes, no preamble.
- Under 200 characters. Hook the reader. No clickbait.
PROMPT;

        return $this->generateText($prompt, ['maxOutputTokens' => 150, 'temperature' => 0.6]);
    }

    /**
     * Generate SEO meta title + description as JSON: ['meta_title' => ..., 'meta_description' => ...]
     */
    public function generateBlogMeta(string $title, ?string $content = null): ?array
    {
        $title = trim($title);
        if ($title === '') {
            return null;
        }
        $contentSnippet = $content ? mb_substr(trim($content), 0, 1500) : '';

        $prompt = <<<PROMPT
Generate SEO meta tags for a blog article titled "$title".

Article snippet:
"""
$contentSnippet
"""

Return JSON with EXACTLY this shape:
{
  "meta_title": "SEO title under 60 chars",
  "meta_description": "Compelling meta description under 155 chars"
}

Rules:
- meta_title: max 60 chars, includes the main keyword.
- meta_description: max 155 chars, action-oriented, includes a benefit.
- Output VALID JSON only. No markdown, no commentary.
PROMPT;

        $json = $this->generateText($prompt, [
            'maxOutputTokens' => 300,
            'temperature' => 0.4,
            'responseMimeType' => 'application/json',
        ]);
        if (! $json) {
            return null;
        }
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    /* ====================================================================
     | SPAM DETECTION
     ==================================================================== */

    /**
     * Score text for spam likelihood. Returns ['is_spam' => bool, 'score' => int 0-100, 'reason' => string]
     */
    public function detectSpam(string $text, ?string $context = 'contact form message'): ?array
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }
        $text = mb_substr($text, 0, 4000);

        $prompt = <<<PROMPT
You are a spam classifier for a $context. Analyze this submission and return JSON.

Text:
"""
$text
"""

Return JSON with EXACTLY this shape:
{
  "is_spam": true|false,
  "score": 0-100,
  "reason": "short explanation under 120 chars"
}

Spam signals: SEO link offers, casino/gambling, crypto pumps, escort/adult, mass-marketing pitches, fake guest post offers, link insertion requests, unsolicited service sales, generic "I noticed your website" pitches, content/article writing offers.
Legitimate: real questions, support requests, hiring/business inquiries from named people about specific things.

Rules:
- score: 0-30 = legit, 31-69 = suspicious, 70-100 = spam
- is_spam: true if score >= 60
- Output VALID JSON only. No markdown, no commentary.
PROMPT;

        $json = $this->generateText($prompt, [
            'maxOutputTokens' => 200,
            'temperature' => 0.1,
            'responseMimeType' => 'application/json',
        ]);
        if (! $json) {
            return null;
        }
        $data = json_decode($json, true);
        if (! is_array($data) || ! isset($data['score'])) {
            return null;
        }
        return [
            'is_spam' => (bool) ($data['is_spam'] ?? ((int) $data['score'] >= 60)),
            'score'   => max(0, min(100, (int) $data['score'])),
            'reason'  => (string) ($data['reason'] ?? ''),
        ];
    }

    /* ====================================================================
     | JOB MATCHING (Seeker dashboard)
     ==================================================================== */

    /**
     * Score how well a seeker matches a job. Returns 0-100.
     *
     * For batch matching, prefer matchSeekerToJobs() to avoid N API calls.
     */
    public function scoreJobMatch(array $seekerProfile, array $job): int
    {
        $seekerJson = json_encode($seekerProfile, JSON_UNESCAPED_UNICODE);
        $jobJson = json_encode($job, JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
Score how well this job seeker matches this job. Return JSON: {"score": 0-100}

Seeker:
$seekerJson

Job:
$jobJson

Score 0-30 = poor fit, 31-69 = partial, 70-100 = strong fit.
Consider: skills overlap, experience level, location preference, role title alignment.
Output VALID JSON only.
PROMPT;

        $json = $this->generateText($prompt, [
            'maxOutputTokens' => 100,
            'temperature' => 0.2,
            'responseMimeType' => 'application/json',
        ]);
        if (! $json) {
            return 0;
        }
        $data = json_decode($json, true);
        return is_array($data) ? max(0, min(100, (int) ($data['score'] ?? 0))) : 0;
    }

    /**
     * Batch match a seeker to a list of jobs. Returns array of [job_id => score].
     * Sends one API call instead of N.
     */
    public function matchSeekerToJobs(array $seekerProfile, array $jobs): array
    {
        if (empty($jobs)) {
            return [];
        }
        $seekerJson = json_encode($seekerProfile, JSON_UNESCAPED_UNICODE);
        $jobsJson = json_encode($jobs, JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
You are a job-matching engine. Score how well this seeker matches each job (0-100).

Seeker profile:
$seekerJson

Jobs (array of {id, title, description, skills, location, experience_required}):
$jobsJson

Return a JSON array with EXACTLY this shape:
[
  {"id": <job_id>, "score": 0-100, "reason": "short fit reason under 80 chars"}
]

Scoring:
- 0-30 = poor fit (wrong field/level)
- 31-69 = partial fit (some overlap)
- 70-100 = strong fit (skills + level + role aligned)

Consider: skills overlap, experience level, role title alignment, location preference.
Output VALID JSON only. No markdown, no commentary.
PROMPT;

        $json = $this->generateText($prompt, [
            'maxOutputTokens' => 2000,
            'temperature' => 0.2,
            'responseMimeType' => 'application/json',
        ]);
        if (! $json) {
            return [];
        }
        $data = json_decode($json, true);
        if (! is_array($data)) {
            return [];
        }

        $out = [];
        foreach ($data as $row) {
            if (! is_array($row) || ! isset($row['id'])) {
                continue;
            }
            $out[(int) $row['id']] = [
                'score'  => max(0, min(100, (int) ($row['score'] ?? 0))),
                'reason' => (string) ($row['reason'] ?? ''),
            ];
        }
        return $out;
    }

    /* ====================================================================
     | LOW-LEVEL: Gemini text generation
     ==================================================================== */

    /**
     * Generic text generation against Gemini. Returns the model's text or null on failure.
     *
     * @param  array{maxOutputTokens?: int, temperature?: float, topP?: float, responseMimeType?: string}  $options
     */
    public function generateText(string $prompt, array $options = []): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $generationConfig = array_filter([
            'temperature'      => $options['temperature']      ?? 0.5,
            'topP'             => $options['topP']             ?? 0.95,
            'maxOutputTokens'  => $options['maxOutputTokens']  ?? 1024,
            'responseMimeType' => $options['responseMimeType'] ?? null,
        ], fn ($v) => $v !== null);

        try {
            $response = Http::timeout(45)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/'
                    .config('services.gemini.model').':generateContent?key='
                    .config('services.gemini.key'),
                    [
                        'contents' => [[
                            'parts' => [['text' => $prompt]],
                        ]],
                        'generationConfig' => $generationConfig,
                    ]
                );

            if (! $response->successful()) {
                Log::warning('AiContentService: API call failed', [
                    'status' => $response->status(),
                    'body'   => mb_substr($response->body(), 0, 500),
                ]);
                return null;
            }

            $body = $response->json();
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (! $text) {
                Log::warning('AiContentService: empty response', ['body' => $body]);
                return null;
            }

            return trim($text);
        } catch (\Throwable $e) {
            Log::warning('AiContentService: exception', ['err' => $e->getMessage()]);
            return null;
        }
    }
}
