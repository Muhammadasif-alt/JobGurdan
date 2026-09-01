<?php

namespace App\Services;

/**
 * Rule-based job-keyword/skill extractor.
 *
 * Scans a job's position + description against a curated dictionary of
 * 400+ skills, tools, frameworks, certifications, soft skills, and
 * industry terms. Returns a deduplicated list of matched keywords,
 * sorted by relevance (skills that appear in the title beat
 * description-only matches).
 *
 * NO external API. NO LLM cost. Deterministic — same input → same output.
 */
class JobKeywordExtractor
{
    /** Max keywords kept per job. Past this, relevance drops. */
    public const MAX_KEYWORDS = 20;

    /**
     * The keyword dictionary. Grouped by category for maintainability.
     * Matching is case-insensitive whole-word/word-boundary.
     */
    private const DICTIONARY = [
        // === Programming Languages ===
        'languages' => [
            'JavaScript', 'TypeScript', 'Python', 'Java', 'PHP', 'Ruby', 'Go', 'Golang', 'Rust',
            'C#', 'C++', 'Swift', 'Kotlin', 'Scala', 'R', 'MATLAB', 'Perl', 'Dart', 'Elixir',
            'Haskell', 'Lua', 'Objective-C', 'Bash', 'PowerShell', 'SQL', 'HTML', 'CSS', 'SASS', 'LESS',
        ],
        // === Frameworks / Libraries ===
        'frameworks' => [
            'React', 'React.js', 'Next.js', 'Vue', 'Vue.js', 'Nuxt', 'Angular', 'Svelte', 'jQuery',
            'Node.js', 'Express', 'NestJS', 'Laravel', 'Symfony', 'Django', 'Flask', 'FastAPI',
            'Ruby on Rails', 'Spring', 'Spring Boot', 'ASP.NET', '.NET', '.NET Core', 'Flutter',
            'React Native', 'Ionic', 'Xamarin', 'Tailwind', 'Bootstrap', 'Material UI', 'Redux',
            'Pinia', 'Zustand', 'GraphQL', 'Apollo', 'tRPC', 'WordPress', 'Shopify', 'Magento',
            'Drupal', 'Joomla', 'Livewire', 'Inertia',
        ],
        // === Databases ===
        'databases' => [
            'MySQL', 'PostgreSQL', 'Postgres', 'MongoDB', 'Redis', 'SQLite', 'Oracle', 'MariaDB',
            'Microsoft SQL Server', 'MSSQL', 'DynamoDB', 'Cassandra', 'Elasticsearch', 'Neo4j',
            'Firestore', 'Firebase', 'Supabase', 'Snowflake', 'BigQuery', 'Redshift', 'ClickHouse',
        ],
        // === Cloud / DevOps ===
        'cloud_devops' => [
            'AWS', 'Amazon Web Services', 'Azure', 'GCP', 'Google Cloud', 'Cloudflare', 'Heroku',
            'DigitalOcean', 'Vercel', 'Netlify', 'Docker', 'Kubernetes', 'K8s', 'Terraform',
            'Ansible', 'Jenkins', 'GitHub Actions', 'GitLab CI', 'CircleCI', 'CI/CD', 'DevOps',
            'Linux', 'Ubuntu', 'CentOS', 'Nginx', 'Apache', 'Lambda', 'EC2', 'S3', 'RDS',
            'CloudFront', 'Route 53', 'IAM', 'EKS', 'ECS', 'CloudFormation',
        ],
        // === Data / AI / ML ===
        'data_ai' => [
            'Machine Learning', 'ML', 'Deep Learning', 'AI', 'Artificial Intelligence', 'Data Science',
            'TensorFlow', 'PyTorch', 'Keras', 'scikit-learn', 'NumPy', 'Pandas', 'Jupyter',
            'Apache Spark', 'Hadoop', 'Airflow', 'dbt', 'Tableau', 'Power BI', 'Looker', 'Qlik',
            'NLP', 'Computer Vision', 'OpenCV', 'LLM', 'GPT', 'OpenAI', 'Hugging Face', 'LangChain',
            'Data Analysis', 'Data Engineering', 'ETL', 'Data Warehouse', 'Data Pipeline',
        ],
        // === Design / Creative ===
        'design' => [
            'Figma', 'Adobe XD', 'Sketch', 'Photoshop', 'Illustrator', 'InDesign', 'After Effects',
            'Premiere Pro', 'Final Cut Pro', 'DaVinci Resolve', 'Canva', 'UI/UX', 'UX Design',
            'UI Design', 'Wireframing', 'Prototyping', 'User Research', 'Design Systems', 'Branding',
            'Typography', 'Motion Graphics', '3D Modeling', 'Blender', 'AutoCAD', 'SolidWorks',
        ],
        // === Marketing / Sales ===
        'marketing' => [
            'SEO', 'SEM', 'PPC', 'Google Ads', 'Facebook Ads', 'Meta Ads', 'LinkedIn Ads', 'Email Marketing',
            'Content Marketing', 'Social Media Marketing', 'Influencer Marketing', 'Affiliate Marketing',
            'Marketing Automation', 'HubSpot', 'Salesforce', 'Mailchimp', 'Klaviyo', 'Google Analytics',
            'GA4', 'Tag Manager', 'CRM', 'Lead Generation', 'Cold Calling', 'B2B Sales', 'B2C Sales',
            'Account Management', 'Customer Success', 'Negotiation', 'Pipeline Management',
        ],
        // === Healthcare ===
        'healthcare' => [
            'Registered Nurse', 'RN', 'LPN', 'CNA', 'Nurse Practitioner', 'NP', 'Physician Assistant',
            'PA-C', 'MD', 'EMT', 'Paramedic', 'Pharmacist', 'Physical Therapist', 'PT', 'OT',
            'Respiratory Therapist', 'Medical Assistant', 'Phlebotomy', 'EHR', 'Epic', 'Cerner',
            'HIPAA', 'Patient Care', 'Clinical', 'ICU', 'ER', 'Telemetry', 'BLS', 'ACLS', 'PALS',
        ],
        // === Trades / Construction / Logistics ===
        'trades' => [
            'CDL', 'CDL-A', 'CDL-B', 'Forklift', 'OSHA', 'Welding', 'Plumbing', 'Electrical',
            'HVAC', 'Carpentry', 'Roofing', 'Construction', 'Heavy Equipment', 'Crane Operator',
            'Truck Driver', 'Dispatcher', 'Warehouse', 'Inventory', 'Shipping', 'Receiving',
            'Forklift Certified', 'Material Handler', 'Logistics', 'Supply Chain', 'Fleet Management',
        ],
        // === Finance / Accounting ===
        'finance' => [
            'CPA', 'CFA', 'CMA', 'QuickBooks', 'SAP', 'Oracle Financials', 'NetSuite', 'Xero',
            'GAAP', 'IFRS', 'Tax Preparation', 'Auditing', 'Bookkeeping', 'Payroll', 'AP', 'AR',
            'Accounts Payable', 'Accounts Receivable', 'Financial Reporting', 'Budgeting', 'Forecasting',
            'Financial Analysis', 'Risk Management', 'Compliance', 'AML', 'KYC',
        ],
        // === Methodologies / Process ===
        'methodologies' => [
            'Agile', 'Scrum', 'Kanban', 'SAFe', 'Lean', 'Six Sigma', 'Waterfall', 'PMP', 'PRINCE2',
            'ITIL', 'CMMI', 'TDD', 'BDD', 'DevSecOps', 'XP', 'Pair Programming', 'Code Review',
            'A/B Testing', 'OKRs', 'KPIs', 'SaaS', 'PaaS', 'IaaS', 'Microservices', 'REST API',
            'Web Services', 'API Development',
        ],
        // === Soft Skills ===
        'soft_skills' => [
            'Communication', 'Leadership', 'Problem Solving', 'Time Management', 'Teamwork',
            'Critical Thinking', 'Adaptability', 'Creativity', 'Collaboration', 'Public Speaking',
            'Presentation Skills', 'Conflict Resolution', 'Decision Making', 'Mentorship',
            'Coaching', 'Customer Service', 'Detail-Oriented', 'Self-Motivated', 'Multitasking',
        ],
        // === Job-type / Setting ===
        'job_setting' => [
            'Remote', 'Hybrid', 'On-Site', 'Onsite', 'Work From Home', 'WFH', 'Travel Required',
            'Full Time', 'Full-Time', 'Part Time', 'Part-Time', 'Contract', 'Contract-to-Hire',
            'Internship', 'Entry Level', 'Mid Level', 'Senior Level', 'Manager', 'Director',
            'VP', 'C-Level', 'CTO', 'CEO', 'CFO', 'COO', 'Lead', 'Principal', 'Staff',
        ],
        // === Certifications ===
        'certifications' => [
            'AWS Certified', 'Azure Certified', 'Google Cloud Certified', 'PMP', 'PMI', 'CCNA',
            'CCNP', 'CISSP', 'CompTIA', 'Security+', 'Network+', 'A+', 'Linux+', 'CKA', 'CKAD',
            'Salesforce Certified', 'Scrum Master', 'CSM', 'PSM', 'ITIL Foundation', 'Lean Six Sigma',
        ],
    ];

    /**
     * Extract keywords from a job's title + description.
     *
     * @return array<int, string> e.g. ['React', 'Node.js', 'Remote', 'AWS']
     */
    public function extract(?string $title, ?string $description): array
    {
        $haystack = mb_strtolower(($title ?? '').' '.($description ?? ''));
        if (trim($haystack) === '') {
            return [];
        }
        $titleLower = mb_strtolower((string) $title);

        $matches = []; // keyword => score (higher = more relevant)
        foreach (self::DICTIONARY as $group) {
            foreach ($group as $keyword) {
                $needle = mb_strtolower($keyword);
                // Use word-boundary-ish matching to avoid "Java" matching inside "JavaScript"
                // when the dictionary contains both ("Java" and "JavaScript" are both listed,
                // and the regex anchors prevent overlap).
                $pattern = '/(?<![a-z0-9])'.preg_quote($needle, '/').'(?![a-z0-9])/iu';
                if (preg_match($pattern, $haystack)) {
                    // Higher score if keyword appears in title (boost relevance)
                    $score = (mb_strpos($titleLower, $needle) !== false) ? 100 : 50;
                    // Longer keywords score slightly higher (more specific)
                    $score += mb_strlen($keyword);
                    $matches[$keyword] = max($matches[$keyword] ?? 0, $score);
                }
            }
        }

        if (empty($matches)) {
            return [];
        }

        // Sort by score desc, then alphabetical, cap at MAX_KEYWORDS
        arsort($matches);

        return array_slice(array_keys($matches), 0, self::MAX_KEYWORDS);
    }

    /**
     * Build a short SEO-friendly meta description from the job description.
     * Strips HTML, trims to ~160 chars, ends on a word boundary.
     */
    public function buildMetaDescription(?string $title, ?string $description, ?string $location = null): string
    {
        $title = trim((string) $title);
        $loc = trim((string) $location);
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags((string) $description)));

        // Lead with the title + location for SEO
        $prefix = $title;
        if ($loc !== '') {
            $prefix .= ' in '.$loc;
        }
        $prefix .= ' — ';

        $available = 300 - mb_strlen($prefix); // leave room for "..."
        if ($available <= 20) {
            return mb_substr($prefix.$clean, 0, 300);
        }

        if (mb_strlen($clean) <= $available) {
            $body = $clean;
        } else {
            $cut = mb_substr($clean, 0, $available);
            $cut = preg_replace('/\s+\S*$/u', '', $cut); // trim to word boundary
            $body = $cut.'…';
        }

        return trim($prefix.$body);
    }
}
