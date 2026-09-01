<?php

namespace App\Services;

/**
 * Lightweight resume / CV text extractor + heuristic field parser.
 *
 *   - PDF text via smalot/pdfparser
 *   - DOCX text via native ZipArchive (word/document.xml)
 *   - DOC (legacy) → text not extracted; an empty bundle is returned
 *
 * The parse() method returns a flat array of best-guess profile fields.
 * Callers should only fill columns that are currently empty so the user's
 * own input is never overwritten.
 */
class ResumeParser
{
    /** Extract text from the given file (PDF or DOCX). Returns "" on failure. */
    public function extractText(string $absolutePath, string $extension): string
    {
        $extension = strtolower($extension);
        try {
            if ($extension === 'pdf' && class_exists(\Smalot\PdfParser\Parser::class)) {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($absolutePath);
                return (string) $pdf->getText();
            }
            if ($extension === 'docx') {
                return $this->extractDocxText($absolutePath);
            }
        } catch (\Throwable $e) {
            // Silent fail — parser is best-effort, never blocks the upload
            \Log::warning('ResumeParser extractText failed', ['err' => $e->getMessage()]);
        }
        return '';
    }

    /** DOCX = ZIP with word/document.xml inside. Strip tags = readable text. */
    protected function extractDocxText(string $path): string
    {
        if (! class_exists(\ZipArchive::class)) return '';
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) return '';
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        if (! $xml) return '';

        // Replace paragraph & break tags with newlines so text stays readable
        $xml = preg_replace('/<w:p[\s>]/', "\n<w:p ", $xml);
        $xml = preg_replace('/<w:br\s*\/>/', "\n", $xml);
        $text = strip_tags($xml);
        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /** Parse the resume file → bundle of best-guess profile fields. */
    public function parse(string $absolutePath, string $extension): array
    {
        $text = $this->extractText($absolutePath, $extension);
        if (trim($text) === '') {
            return [];
        }
        return [
            'email'            => $this->guessEmail($text),
            'phone'            => $this->guessPhone($text),
            'name'             => $this->guessName($text),
            'headline'         => $this->guessHeadline($text),
            'skills'           => $this->guessSkills($text),
            'preferred_city'   => $this->guessCity($text),
            'experience_years' => $this->guessExperienceYears($text),
            'bio'              => $this->guessBio($text),
        ];
    }

    /**
     * Full structured section extraction — splits resume text by recognized
     * section headers (Experience, Education, Skills, Certifications…) so each
     * block can be displayed verbatim on the seeker's profile page.
     *
     * Returns: [
     *   'summary'        => "...",
     *   'experience'     => "...",
     *   'education'      => "...",
     *   'skills'         => "...",
     *   'certifications' => "...",
     *   'projects'       => "...",
     *   'languages'      => "...",
     *   'awards'         => "...",
     *   'raw_text'       => "...",  // full extracted text as fallback
     * ]
     */
    public function parseSections(string $absolutePath, string $extension): array
    {
        $text = $this->extractText($absolutePath, $extension);
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", trim($text));

        if ($text === '') {
            return ['raw_text' => ''];
        }

        // Section name → list of header aliases (case-insensitive)
        $headers = [
            'summary'        => ['professional summary', 'summary', 'profile', 'about me', 'about', 'objective', 'career objective'],
            'experience'     => ['work experience', 'professional experience', 'employment history', 'experience', 'career history', 'work history'],
            'education'      => ['education', 'academic background', 'qualifications', 'academic qualifications'],
            'skills'         => ['skills', 'technical skills', 'core skills', 'key skills', 'competencies', 'areas of expertise'],
            'certifications' => ['certifications', 'certificates', 'licenses', 'licences', 'professional certifications'],
            'projects'       => ['projects', 'key projects', 'notable projects', 'project experience'],
            'languages'      => ['languages', 'language proficiency'],
            'awards'         => ['awards', 'achievements', 'honors', 'honours', 'accomplishments', 'awards & achievements'],
            'interests'      => ['interests', 'hobbies', 'personal interests'],
            'references'     => ['references', 'professional references'],
        ];

        // Build a flat regex of all aliases mapped back to canonical names
        $aliasMap = [];
        foreach ($headers as $key => $aliases) {
            foreach ($aliases as $a) {
                $aliasMap[mb_strtolower($a)] = $key;
            }
        }
        $aliasRegex = collect(array_keys($aliasMap))
            ->map(fn($a) => preg_quote($a, '/'))
            ->implode('|');

        // Find all header positions in the text. A header is a line ≤ 50 chars,
        // standalone (followed by newline), matching one of our aliases.
        $matches = [];
        if (preg_match_all('/(?:^|\n)\s*('.$aliasRegex.')\s*[:\-]?\s*\n/i', $text, $m, PREG_OFFSET_CAPTURE)) {
            foreach ($m[1] as $i => $hit) {
                $matches[] = [
                    'key'   => $aliasMap[mb_strtolower(trim($hit[0]))] ?? null,
                    'start' => $m[0][$i][1],            // offset of "\nHeader\n"
                    'end'   => $m[0][$i][1] + strlen($m[0][$i][0]), // where body starts
                ];
            }
        }

        // Build sections by slicing the text from each header → next header
        $sections = ['raw_text' => $text];
        $count = count($matches);
        for ($i = 0; $i < $count; $i++) {
            $cur  = $matches[$i];
            $next = $matches[$i + 1] ?? null;
            $bodyStart = $cur['end'];
            $bodyEnd   = $next['start'] ?? mb_strlen($text);
            $body      = trim(mb_substr($text, $bodyStart, $bodyEnd - $bodyStart));
            if ($cur['key'] && $body !== '') {
                // If a section appears twice, keep the longer body
                if (! isset($sections[$cur['key']]) || mb_strlen($body) > mb_strlen($sections[$cur['key']])) {
                    $sections[$cur['key']] = $body;
                }
            }
        }

        return $sections;
    }

    // ---------------------------------------------------------------- regex helpers

    protected function guessEmail(string $text): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $m)) {
            return strtolower($m[0]);
        }
        return null;
    }

    protected function guessPhone(string $text): ?string
    {
        // US format with optional country code, parens, dashes, dots
        if (preg_match('/(?:\+?1[\s.-]?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}/', $text, $m)) {
            return trim($m[0]);
        }
        // International fallback (10–15 digits, may start with +)
        if (preg_match('/\+?\d[\d\s().-]{8,16}\d/', $text, $m)) {
            return trim($m[0]);
        }
        return null;
    }

    /** First non-empty line that looks like a person's name. */
    protected function guessName(string $text): ?string
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            // Skip lines that obviously aren't names (emails, urls, addresses, headers)
            if (preg_match('/[@:\/]|^\d/', $line)) continue;
            // Names are usually 2–5 words, mostly letters
            $words = preg_split('/\s+/', $line);
            if (count($words) >= 2 && count($words) <= 5 && preg_match('/^[A-Za-z][A-Za-z\.\s\'-]+$/', $line)) {
                return $line;
            }
            // Stop after a couple of failed attempts (name should be near the top)
            if (count($lines) > 0 && array_search($line, $lines) > 5) break;
        }
        return null;
    }

    /** Tagline / role title — usually the line right after the name. */
    protected function guessHeadline(string $text): ?string
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
        $jobTitleHints = ['engineer','developer','manager','specialist','analyst','designer','consultant','officer','executive','coordinator','assistant','technician','nurse','accountant','driver','operator','representative','seeker','professional'];

        foreach (array_slice($lines, 0, 12) as $line) {
            $low = mb_strtolower($line);
            foreach ($jobTitleHints as $hint) {
                if (str_contains($low, $hint) && mb_strlen($line) <= 100 && mb_strlen($line) >= 8) {
                    return $line;
                }
            }
        }
        return null;
    }

    /** Match against a known skill bank (~80 common U.S. job skills). */
    protected function guessSkills(string $text): ?string
    {
        $known = [
            // Tech
            'PHP','Laravel','JavaScript','TypeScript','React','Vue','Angular','Node.js','Python','Java','Go','Ruby','Rails','Django','Flask','C++','C#','.NET','SQL','MySQL','PostgreSQL','MongoDB','Redis','GraphQL','REST API','Docker','Kubernetes','AWS','Azure','GCP','Linux','Git','CI/CD','HTML','CSS','Tailwind','Bootstrap','jQuery','WordPress','Shopify',
            // Office / general
            'Excel','Word','PowerPoint','Google Sheets','Outlook','SAP','Salesforce','HubSpot','Tableau','Power BI','Photoshop','Illustrator','Figma','Sketch',
            // Trades / labor
            'Forklift','CDL','OSHA','Welding','HVAC','Carpentry','Plumbing','Electrical','Heavy Equipment','Hazmat','DOT',
            // Healthcare
            'BLS','ACLS','CPR','RN','LPN','CNA','Phlebotomy','Patient Care','EHR','EMR',
            // Finance / accounting
            'QuickBooks','GAAP','SOX','Audit','Tax','Payroll','Bookkeeping',
            // Soft / role-related
            'Communication','Leadership','Teamwork','Problem Solving','Project Management','Customer Service','Bilingual','Spanish','French','German','Mandarin','Time Management','Sales','Marketing','SEO','Content Writing','Data Entry','Inventory','Scheduling',
        ];

        $lower = mb_strtolower($text);
        $found = [];
        foreach ($known as $skill) {
            // Word-boundary match, case-insensitive — accepts "Forklift" / "forklift" / "FORKLIFT"
            if (preg_match('/\b'.preg_quote($skill, '/').'\b/i', $lower)) {
                $found[] = $skill;
                if (count($found) >= 12) break;
            }
        }
        return $found ? implode(', ', $found) : null;
    }

    /** Detect U.S. city, optionally with state — "City, ST" pattern. */
    protected function guessCity(string $text): ?string
    {
        // "City, ST" — e.g. "Houston, TX"
        if (preg_match('/\b([A-Z][a-zA-Z]+(?:\s[A-Z][a-zA-Z]+){0,2}),\s*([A-Z]{2})\b/', $text, $m)) {
            return trim($m[1].', '.$m[2]);
        }
        // Fall back: bare city from a small list of major U.S. cities
        $cities = ['Houston','Dallas','Austin','New York','Los Angeles','Chicago','Phoenix','Philadelphia','San Antonio','San Diego','San Jose','Jacksonville','Fort Worth','Columbus','Charlotte','Indianapolis','Seattle','Denver','Boston','Portland','Atlanta','Miami','Orlando','Minneapolis','Pittsburgh','Detroit'];
        foreach ($cities as $city) {
            if (preg_match('/\b'.preg_quote($city, '/').'\b/i', $text)) return $city;
        }
        return null;
    }

    /** "5+ years experience", "8 years of experience", "10 yrs". */
    protected function guessExperienceYears(string $text): ?int
    {
        if (preg_match('/(\d{1,2})\s*\+?\s*(?:years?|yrs?)(?:\s+of)?\s+(?:professional\s+)?experience/i', $text, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    /** A short bio = first 280 chars after the headline / contact block. */
    protected function guessBio(string $text): ?string
    {
        // Remove emails, URLs, phone-like sequences first
        $clean = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '', $text);
        $clean = preg_replace('/\+?\d[\d\s().-]{8,16}\d/', '', $clean);
        $clean = preg_replace('/https?:\/\/\S+/', '', $clean);

        // Look for a "Summary" or "Profile" section
        if (preg_match('/(?:professional\s+summary|summary|profile|about\s+me|objective)\s*[:\n]+\s*([\s\S]{50,400}?)(?:\n\s*\n|\nexperience|\neducation|\nskills)/i', $clean, $m)) {
            $bio = trim(preg_replace('/\s+/', ' ', $m[1]));
            if (mb_strlen($bio) > 50) return mb_substr($bio, 0, 400);
        }
        return null;
    }
}
