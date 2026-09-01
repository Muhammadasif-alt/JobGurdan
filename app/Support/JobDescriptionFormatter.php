<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Pretty-print job descriptions that arrive from Excel/Jobg8 as messy
 * plain-text or minimal HTML. Detects headings, bullet lists, paragraphs,
 * and inline links/emails — turns them into structured HTML.
 *
 * Intentionally NOT a markdown parser: real-world Jobg8 feeds are
 * irregular plain-text with mixed conventions, so this is rule-based.
 */
class JobDescriptionFormatter
{
    /**
     * Convert a raw description string to clean HTML.
     */
    public static function toHtml(?string $raw): string
    {
        $text = self::stripTrackingArtifacts(trim((string) $raw));
        if ($text === '') {
            return '<p class="jd-empty">No description provided.</p>';
        }

        // If already heavily HTML (has block tags), normalize lightly.
        if (self::looksLikeHtml($text)) {
            return self::normalizeHtml($text);
        }

        return self::formatPlainText($text);
    }

    /**
     * Strip Jobg8/ad-network tracking pixels and broken <img> residue that
     * Jobg8 feeds inject (zero-size images, orphaned attribute fragments).
     */
    private static function stripTrackingArtifacts(string $text): string
    {
        // 1. Complete <img ...> tags — descriptions never need inline images,
        //    and these are almost always 1×1 / 0×0 tracking pixels.
        $text = preg_replace('/<img\b[^>]*>/i', '', $text);

        // 2. Orphaned img-tag residue after partial HTML mangling, e.g.
        //    `https://jobg8.com/Tracking.aspx?abc" width="0" height="0" />`
        $text = preg_replace('/\S+"\s+width="?0"?\s+height="?0"?\s*\/?>/i', '', $text);

        // 3. Bare tracking URLs from known networks (jobg8, doubleclick, etc.)
        $text = preg_replace(
            '#https?://(?:www\.)?(?:jobg8\.com/Tracking|doubleclick\.net|googleadservices\.com|google-analytics\.com|googletagmanager\.com)[^\s<>"\']+#i',
            '',
            $text
        );

        // 4. Common Microsoft Office / Word junk (<o:p>, <w:wordDocument>, etc.)
        $text = preg_replace('/<\/?(?:o|w|m|st1):[a-z]+[^>]*>/i', '', $text);

        return trim($text);
    }

    private static function looksLikeHtml(string $text): bool
    {
        return (bool) preg_match('/<(p|ul|ol|li|h[1-6]|div|section|article|br)\b/i', $text);
    }

    /** Light cleanup for already-HTML descriptions (decode, strip dangerous tags). */
    private static function normalizeHtml(string $html): string
    {
        // Strip script/style/iframe entirely
        $html = preg_replace('/<(script|style|iframe)\b[^>]*>.*?<\/\1>/is', '', $html);
        // Decode common HTML entities once so we don't render escaped text
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return self::linkify($html);
    }

    /** The interesting path: structure a plain-text blob. */
    private static function formatPlainText(string $text): string
    {
        // Normalize line endings + collapse spaces
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[\t ]+/', ' ', $text);
        $text = trim($text);

        // Split into logical blocks (one or more blank lines)
        $blocks = preg_split('/\n\s*\n+/', $text);

        $html = '';
        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') {
                continue;
            }
            $html .= self::renderBlock($block);
        }

        return $html ?: '<p>'.e($text).'</p>';
    }

    private static function renderBlock(string $block): string
    {
        $lines = array_filter(array_map('trim', explode("\n", $block)));
        if (empty($lines)) {
            return '';
        }

        // Single-line block → check if it's a heading
        if (count($lines) === 1) {
            $line = reset($lines);
            if (self::looksLikeHeading($line)) {
                return '<h3 class="jd-h">'.e(rtrim($line, ':')).'</h3>';
            }

            return '<p>'.self::linkify(e($line)).'</p>';
        }

        // Multi-line: bullets, numbered list, heading+content, or paragraph
        // First line might be a heading like "Requirements:" with bullets below
        $headingHtml = '';
        if (self::looksLikeHeading($lines[0])) {
            $headingHtml = '<h3 class="jd-h">'.e(rtrim(array_shift($lines), ':')).'</h3>';
        }

        // What kind of list is the body?
        if (self::allBullets($lines)) {
            $items = '';
            foreach ($lines as $l) {
                $items .= '<li>'.self::linkify(e(self::stripBulletPrefix($l))).'</li>';
            }

            return $headingHtml.'<ul class="jd-ul">'.$items.'</ul>';
        }
        if (self::allNumbered($lines)) {
            $items = '';
            foreach ($lines as $l) {
                $items .= '<li>'.self::linkify(e(self::stripNumberPrefix($l))).'</li>';
            }

            return $headingHtml.'<ol class="jd-ol">'.$items.'</ol>';
        }

        // Mixed body → join with <br> inside a paragraph
        $joined = implode('<br>', array_map(fn ($l) => self::linkify(e($l)), $lines));

        return $headingHtml.'<p>'.$joined.'</p>';
    }

    /**
     * Heuristics: "Requirements:", "RESPONSIBILITIES", "About the role" etc.
     * Short lines that are all-caps OR end with a colon are treated as headings.
     */
    private static function looksLikeHeading(string $line): bool
    {
        if (mb_strlen($line) > 80) {
            return false;
        }
        if (Str::endsWith($line, ':')) {
            return true;
        }
        // All-caps title (allow digits/spaces), at least 2 words
        if (preg_match('/^[A-Z][A-Z0-9 \-\/&]+$/', $line) && str_word_count($line) >= 2) {
            return true;
        }

        return false;
    }

    private static function allBullets(array $lines): bool
    {
        foreach ($lines as $l) {
            if (! preg_match('/^[\-\*•●▪▫◦‣]/u', $l)) {
                return false;
            }
        }

        return true;
    }

    private static function allNumbered(array $lines): bool
    {
        foreach ($lines as $l) {
            if (! preg_match('/^\d+[\.\)]\s+/', $l)) {
                return false;
            }
        }

        return true;
    }

    private static function stripBulletPrefix(string $line): string
    {
        return ltrim(preg_replace('/^[\-\*•●▪▫◦‣]\s*/u', '', $line));
    }

    private static function stripNumberPrefix(string $line): string
    {
        return ltrim(preg_replace('/^\d+[\.\)]\s*/', '', $line));
    }

    /** Auto-link URLs, emails, and phone numbers in already-escaped text. */
    private static function linkify(string $html): string
    {
        // URLs (http/https)
        $html = preg_replace_callback(
            '/(https?:\/\/[^\s<>"\'()]+)/i',
            fn ($m) => '<a href="'.$m[1].'" target="_blank" rel="nofollow noopener" class="jd-link">'.$m[1].'</a>',
            $html
        );
        // Emails
        $html = preg_replace_callback(
            '/([a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,})/i',
            fn ($m) => '<a href="mailto:'.$m[1].'" class="jd-link">'.$m[1].'</a>',
            $html
        );

        return $html;
    }
}
