@extends('user.layouts.master')
@section('title', 'Privacy Policy — Jobs in USA')
@section('meta_description', 'Read the Jobs in USA Privacy Policy describing how we collect, use, share and protect your information, your rights, and our cookie practices.')
@section('content')

<style>
    .legal-wrap { background: #f7f8fa; padding: 50px 0 70px; }
    .legal-wrap .container { max-width: 920px; }
    .legal-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 42px 44px;
        box-shadow: 0 6px 18px rgba(15,23,42,.05);
    }
    .legal-card h1 {
        font-size: clamp(28px, 3vw, 38px);
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 8px;
        letter-spacing: -.6px;
    }
    .legal-card .updated {
        display: inline-block;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        background: #f3f4f6;
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 22px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .legal-card h2 {
        font-size: 20px;
        font-weight: 800;
        color: #0a0a0a;
        margin: 32px 0 10px;
        letter-spacing: -.2px;
        padding-top: 18px;
        border-top: 1px solid #ececec;
    }
    .legal-card h2:first-of-type { padding-top: 0; border-top: 0; margin-top: 0; }
    .legal-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 20px 0 8px;
    }
    .legal-card p, .legal-card li {
        color: #444;
        font-size: 15px;
        line-height: 1.75;
    }
    .legal-card ul { padding-left: 22px; margin: 8px 0 14px; }
    .legal-card ul li { margin-bottom: 6px; }
    .legal-card a { color: #ff8a00; text-decoration: none; font-weight: 600; }
    .legal-card a:hover { text-decoration: underline; }
    .legal-card strong { color: #0a0a0a; }
    .legal-toc {
        background: #f8f9fb;
        border: 1px solid #ececec;
        border-radius: 10px;
        padding: 18px 22px;
        margin-bottom: 32px;
    }
    .legal-toc h4 { font-size: 13px; font-weight: 800; color: #555; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 10px; }
    .legal-toc ol { margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.9; }
    .legal-toc a { color: #0a0a0a; font-weight: 500; }
    .legal-toc a:hover { color: #ff8a00; }

    /* Dark mode */
    html.dark-mode .legal-wrap { background: var(--site-bg) !important; }
    html.dark-mode .legal-card {
        background: var(--site-card-bg) !important;
        border-color: var(--site-card-bd) !important;
        color: var(--site-text) !important;
        box-shadow: var(--site-shadow) !important;
    }
    html.dark-mode .legal-card h1,
    html.dark-mode .legal-card h2,
    html.dark-mode .legal-card h3 { color: #fff !important; }
    html.dark-mode .legal-card h2 { border-top-color: var(--site-card-bd) !important; }
    html.dark-mode .legal-card .updated {
        background: rgba(255,255,255,.06) !important;
        color: #cbd5e1 !important;
    }
    html.dark-mode .legal-card p,
    html.dark-mode .legal-card li { color: #cbd5e1 !important; }
    html.dark-mode .legal-card strong { color: #fff !important; }
    html.dark-mode .legal-toc {
        background: rgba(255,255,255,.04) !important;
        border-color: var(--site-card-bd) !important;
    }
    html.dark-mode .legal-toc h4 { color: #cbd5e1 !important; }
    html.dark-mode .legal-toc a { color: #e5e7eb !important; }
</style>

<section class="legal-wrap">
    <div class="container">
        <div class="legal-card">
            <h1>Privacy Policy</h1>
            <span class="updated">Last updated: {{ now()->format('F j, Y') }}</span>

            <p>Jobs in USA ("we", "our", "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>

            <div class="legal-toc">
                <h4>Contents</h4>
                <ol>
                    <li><a href="#info">Information We Collect</a></li>
                    <li><a href="#use">How We Use Your Information</a></li>
                    <li><a href="#share">How We Share Your Information</a></li>
                    <li><a href="#cookies">Cookies &amp; Tracking Technologies</a></li>
                    <li><a href="#security">Data Security</a></li>
                    <li><a href="#retention">Data Retention</a></li>
                    <li><a href="#rights">Your Rights &amp; Choices</a></li>
                    <li><a href="#children">Children's Privacy</a></li>
                    <li><a href="#third">Third-Party Services</a></li>
                    <li><a href="#international">International Users</a></li>
                    <li><a href="#changes">Changes to This Policy</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ol>
            </div>

            <h2 id="info">1. Information We Collect</h2>

            <h3>Information You Provide</h3>
            <ul>
                <li><strong>Account information:</strong> name, email address, phone number, password, and role (job seeker, employer, or admin) when you register.</li>
                <li><strong>Profile information:</strong> resume content, work history, education, skills, profile photo, salary expectations, and career preferences.</li>
                <li><strong>Employer information:</strong> company name, logo, business description, job listings, and billing details (where applicable).</li>
                <li><strong>Application data:</strong> job applications you submit, cover letters, and any messages you send through our platform.</li>
                <li><strong>Communications:</strong> any messages or feedback you send to us through contact forms, email, or support channels.</li>
            </ul>

            <h3>Information Collected Automatically</h3>
            <ul>
                <li><strong>Technical data:</strong> IP address, browser type and version, device type, operating system, language preference, and screen resolution.</li>
                <li><strong>Usage data:</strong> pages visited, links clicked, search queries, time spent on pages, referring URLs, and timestamps.</li>
                <li><strong>Location data:</strong> approximate location derived from your IP address to show relevant jobs by region.</li>
            </ul>

            <h2 id="use">2. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Create and manage your account and provide our services.</li>
                <li>Match job seekers with relevant openings and recommend roles based on your profile.</li>
                <li>Allow employers to find and contact qualified candidates.</li>
                <li>Process applications and facilitate communication between job seekers and employers.</li>
                <li>Send transactional emails (account confirmations, application updates, password resets).</li>
                <li>Send job alerts and newsletters where you have opted in (you may unsubscribe at any time).</li>
                <li>Improve our platform, debug issues, and analyze trends in aggregate.</li>
                <li>Detect and prevent fraud, spam, and abuse, including violations of our Terms of Service.</li>
                <li>Comply with legal obligations and respond to lawful requests from authorities.</li>
            </ul>

            <h2 id="share">3. How We Share Your Information</h2>
            <p>We do not sell your personal information. We share information only in the following circumstances:</p>
            <ul>
                <li><strong>With employers:</strong> when you apply for a job, your profile and application are shared with that employer.</li>
                <li><strong>With job seekers:</strong> employer profiles, job listings, and company information are publicly visible.</li>
                <li><strong>With service providers:</strong> hosting, email delivery, analytics, and payment processors that help us operate the platform, under written agreements that restrict their use of your data.</li>
                <li><strong>For legal reasons:</strong> when required by law, court order, subpoena, or to protect the rights, property, or safety of Jobs in USA, our users, or the public.</li>
                <li><strong>Business transfers:</strong> in the event of a merger, acquisition, or sale of assets, your information may be transferred, subject to the same protections described in this policy.</li>
            </ul>

            <h2 id="cookies">4. Cookies &amp; Tracking Technologies</h2>
            <p>We use cookies and similar technologies to remember your preferences, keep you signed in, analyze usage, and deliver personalized content. Categories of cookies we use:</p>
            <ul>
                <li><strong>Essential cookies:</strong> required for core functionality such as authentication and security. These cannot be disabled.</li>
                <li><strong>Performance cookies:</strong> help us understand how visitors use our site so we can improve it.</li>
                <li><strong>Functional cookies:</strong> remember your settings (language, dark/light mode) for a better experience.</li>
                <li><strong>Analytics cookies:</strong> we use Google Analytics to collect anonymized usage data.</li>
            </ul>
            <p>You can control cookies via your browser settings. Disabling cookies may limit certain features.</p>

            <h2 id="security">5. Data Security</h2>
            <p>We implement industry-standard security measures including HTTPS encryption, hashed passwords, server-side input validation, and access controls. While we strive to protect your information, no system is 100% secure. We will notify affected users promptly if a breach occurs that materially affects your personal data, as required by law.</p>

            <h2 id="retention">6. Data Retention</h2>
            <p>We retain your information for as long as your account is active or as needed to provide our services and comply with legal obligations. When you delete your account, we remove or anonymize your personal data within 30 days, except where retention is required for legal, tax, or fraud-prevention purposes.</p>

            <h2 id="rights">7. Your Rights &amp; Choices</h2>
            <p>Depending on your jurisdiction (including the EU under GDPR and California under CCPA), you may have the following rights:</p>
            <ul>
                <li><strong>Access:</strong> request a copy of the personal information we hold about you.</li>
                <li><strong>Correction:</strong> update or correct inaccurate information through your account or by contacting us.</li>
                <li><strong>Deletion:</strong> request deletion of your account and personal data.</li>
                <li><strong>Portability:</strong> receive your data in a structured, machine-readable format.</li>
                <li><strong>Objection:</strong> object to or restrict certain processing activities, including direct marketing.</li>
                <li><strong>Opt-out of sale:</strong> California residents may opt out of any "sale" of personal information (we do not sell your data).</li>
                <li><strong>Withdraw consent:</strong> where processing is based on consent, you may withdraw it at any time without affecting prior processing.</li>
            </ul>
            <p>To exercise these rights, email us at <a href="mailto:privacy@jobsinusa.com">privacy@jobsinusa.com</a>. We will respond within 30 days.</p>

            <h2 id="children">8. Children's Privacy</h2>
            <p>Our services are intended for users 16 years and older. We do not knowingly collect personal information from children under 16. If we learn we have collected such data, we will delete it promptly. Parents or guardians who believe their child has provided information to us should contact us immediately.</p>

            <h2 id="third">9. Third-Party Services</h2>
            <p>Our site may contain links to third-party websites, employer pages, or applicant tracking systems. We are not responsible for the privacy practices of those third parties. We encourage you to read their privacy policies before providing any personal information.</p>

            <h2 id="international">10. International Users</h2>
            <p>Jobs in USA is operated from the United States. If you access our services from outside the U.S., your information may be transferred to, stored, and processed in the United States, where data protection laws may differ from those of your country.</p>

            <h2 id="changes">11. Changes to This Policy</h2>
            <p>We may update this Privacy Policy from time to time. Material changes will be communicated via email or a prominent notice on our website at least 14 days before they take effect. The "Last updated" date at the top of this page indicates the most recent revision.</p>

            <h2 id="contact">12. Contact Us</h2>
            <p>If you have questions or concerns about this Privacy Policy or our data practices, please contact us:</p>
            <ul>
                <li>Email: <a href="mailto:privacy@jobsinusa.com">privacy@jobsinusa.com</a></li>
                <li>Contact form: <a href="{{ url('/contact-us') }}">Contact Us page</a></li>
            </ul>
        </div>
    </div>
</section>

@endsection
