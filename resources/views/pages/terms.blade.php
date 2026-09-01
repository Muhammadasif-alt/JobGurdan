@extends('user.layouts.master')
@section('title', 'Terms of Service — Jobs in USA')
@section('meta_description', 'Read the Jobs in USA Terms of Service covering account registration, acceptable use, employer and job seeker responsibilities, intellectual property, and limitation of liability.')
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
            <h1>Terms of Service</h1>
            <span class="updated">Last updated: {{ now()->format('F j, Y') }}</span>

            <p>Welcome to Jobs in USA. These Terms of Service ("Terms") govern your access to and use of our website, mobile applications, and related services (the "Service"). By accessing or using the Service, you agree to be bound by these Terms. If you do not agree, please do not use the Service.</p>

            <div class="legal-toc">
                <h4>Contents</h4>
                <ol>
                    <li><a href="#acceptance">Acceptance of Terms</a></li>
                    <li><a href="#eligibility">Eligibility</a></li>
                    <li><a href="#accounts">Account Registration &amp; Security</a></li>
                    <li><a href="#conduct">User Conduct</a></li>
                    <li><a href="#jobseekers">Job Seekers</a></li>
                    <li><a href="#employers">Employers</a></li>
                    <li><a href="#content">User Content</a></li>
                    <li><a href="#ip">Intellectual Property</a></li>
                    <li><a href="#thirdparty">Third-Party Links &amp; Services</a></li>
                    <li><a href="#disclaimers">Disclaimers</a></li>
                    <li><a href="#liability">Limitation of Liability</a></li>
                    <li><a href="#indemnification">Indemnification</a></li>
                    <li><a href="#termination">Termination</a></li>
                    <li><a href="#governing">Governing Law &amp; Disputes</a></li>
                    <li><a href="#changes">Changes to These Terms</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ol>
            </div>

            <h2 id="acceptance">1. Acceptance of Terms</h2>
            <p>By creating an account, posting a job, applying to a job, or otherwise using the Service, you confirm that you have read, understood, and agree to be legally bound by these Terms and our <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>. If you are using the Service on behalf of an employer or organization, you represent that you are authorized to bind that entity to these Terms.</p>

            <h2 id="eligibility">2. Eligibility</h2>
            <p>You must be at least 16 years old to create an account. By using the Service you represent that:</p>
            <ul>
                <li>You meet the minimum age requirement.</li>
                <li>You have the legal capacity to enter into a binding contract.</li>
                <li>You are not barred from using the Service under applicable law.</li>
                <li>You have not previously been suspended or removed from the Service.</li>
            </ul>

            <h2 id="accounts">3. Account Registration &amp; Security</h2>
            <p>To access certain features you must register for an account. You agree to:</p>
            <ul>
                <li>Provide accurate, current, and complete information during registration.</li>
                <li>Keep your password confidential and not share your account with others.</li>
                <li>Notify us immediately of any unauthorized access or security breach.</li>
                <li>Be responsible for all activities that occur under your account.</li>
            </ul>
            <p>We reserve the right to suspend or terminate any account that contains false, misleading, or inappropriate information, or that has been used in violation of these Terms.</p>

            <h2 id="conduct">4. User Conduct</h2>
            <p>You agree not to:</p>
            <ul>
                <li>Use the Service for any unlawful purpose or in violation of any applicable law or regulation.</li>
                <li>Post discriminatory, defamatory, harassing, threatening, obscene, or otherwise objectionable content.</li>
                <li>Impersonate any person or entity, or misrepresent your affiliation with any person or entity.</li>
                <li>Submit false, misleading, or fraudulent job listings or applications.</li>
                <li>Collect or harvest personal information of other users without their consent.</li>
                <li>Use bots, scrapers, or other automated means to access the Service without our prior written permission.</li>
                <li>Interfere with, disrupt, or attempt to gain unauthorized access to the Service or its security features.</li>
                <li>Upload viruses, malware, or other malicious code.</li>
                <li>Charge job seekers any fee in exchange for considering an application.</li>
            </ul>

            <h2 id="jobseekers">5. Job Seekers</h2>
            <p>If you use the Service as a job seeker:</p>
            <ul>
                <li>Information you provide in your profile, resume, and applications must be truthful and your own.</li>
                <li>By submitting an application you authorize us to share your information with the employer who posted the job.</li>
                <li>The Service is free for job seekers. We do not charge fees to apply, save jobs, or set up alerts.</li>
                <li>We do not guarantee that you will be hired, contacted, or considered for any role.</li>
            </ul>

            <h2 id="employers">6. Employers</h2>
            <p>If you use the Service to post jobs or recruit candidates:</p>
            <ul>
                <li>Each job listing must reflect a genuine, active opening within your organization.</li>
                <li>Job listings must comply with all applicable employment, labor, anti-discrimination, and advertising laws.</li>
                <li>You may not post listings for multi-level marketing, get-rich-quick schemes, adult content, or roles requiring payment from applicants.</li>
                <li>You may not use applicant data for any purpose other than evaluating the candidate for the role they applied to.</li>
                <li>You are responsible for all communications and any hiring decisions made on the basis of information obtained through the Service.</li>
                <li>Paid services (where applicable) are governed by the order form or pricing terms you accept at purchase.</li>
            </ul>

            <h2 id="content">7. User Content</h2>
            <p>"User Content" means any content you submit to the Service, including resumes, job listings, profile information, comments, and messages.</p>
            <p>You retain ownership of your User Content. By submitting User Content, you grant Jobs in USA a worldwide, non-exclusive, royalty-free, sublicensable license to host, store, reproduce, display, and distribute that content solely for the purpose of operating and improving the Service.</p>
            <p>You represent and warrant that you have all rights necessary to grant this license and that your User Content does not violate any third-party rights or these Terms.</p>
            <p>We reserve the right (but have no obligation) to remove or refuse to display any User Content that we believe violates these Terms or is otherwise objectionable.</p>

            <h2 id="ip">8. Intellectual Property</h2>
            <p>The Service, including its design, text, graphics, logos, software, and underlying technology, is owned by Jobs in USA or its licensors and is protected by copyright, trademark, and other intellectual property laws. You may not copy, modify, distribute, reverse-engineer, or create derivative works of any part of the Service without our prior written consent.</p>
            <p>"Jobs in USA" and our logo are trademarks of our company. You may not use them without our prior written permission.</p>

            <h2 id="thirdparty">9. Third-Party Links &amp; Services</h2>
            <p>The Service may contain links to third-party websites, applicant tracking systems, or external services. We do not endorse and are not responsible for the content, practices, or policies of any third party. Your interactions with third-party services are governed by their own terms and privacy policies.</p>

            <h2 id="disclaimers">10. Disclaimers</h2>
            <p>The Service is provided <strong>"as is"</strong> and <strong>"as available"</strong> without warranties of any kind, either express or implied. To the maximum extent permitted by law, Jobs in USA disclaims all warranties, including merchantability, fitness for a particular purpose, non-infringement, and accuracy of information.</p>
            <p>We do not warrant that:</p>
            <ul>
                <li>The Service will be uninterrupted, timely, secure, or error-free.</li>
                <li>Information obtained through the Service is accurate or reliable.</li>
                <li>Any job listing represents an actual or available position.</li>
                <li>You will obtain employment or any specific outcome through the Service.</li>
            </ul>

            <h2 id="liability">11. Limitation of Liability</h2>
            <p>To the maximum extent permitted by law, in no event shall Jobs in USA, its officers, directors, employees, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages, including lost profits, lost data, or business interruption, arising out of or related to your use of the Service.</p>
            <p>Our total cumulative liability to you for any claim arising from your use of the Service shall not exceed the greater of (a) the amount you paid us in the twelve months preceding the claim, or (b) USD $100.</p>
            <p>Some jurisdictions do not allow the exclusion of certain warranties or limitations of liability, so these limitations may not apply to you.</p>

            <h2 id="indemnification">12. Indemnification</h2>
            <p>You agree to indemnify, defend, and hold harmless Jobs in USA and its officers, directors, employees, and affiliates from any claims, damages, losses, liabilities, costs, and expenses (including reasonable attorneys' fees) arising out of your use of the Service, your User Content, your violation of these Terms, or your violation of any rights of a third party.</p>

            <h2 id="termination">13. Termination</h2>
            <p>You may terminate your account at any time by contacting us or by deleting your account through your account settings. We may suspend or terminate your access at any time, with or without notice, if we believe you have violated these Terms or for any other reason at our discretion.</p>
            <p>Sections that by their nature should survive termination (including ownership, disclaimers, limitation of liability, and indemnification) will survive.</p>

            <h2 id="governing">14. Governing Law &amp; Disputes</h2>
            <p>These Terms are governed by and construed in accordance with the laws of the United States and the State of Delaware, without regard to conflict-of-law principles. Any dispute arising out of or relating to these Terms or the Service shall be resolved exclusively in the state or federal courts located in Delaware, and you consent to the personal jurisdiction of those courts.</p>
            <p>You agree that any dispute resolution proceedings will be conducted only on an individual basis, not as a class, consolidated, or representative action.</p>

            <h2 id="changes">15. Changes to These Terms</h2>
            <p>We may revise these Terms from time to time. Material changes will be communicated by email or by a prominent notice on the Service at least 14 days before they take effect. Your continued use of the Service after the effective date constitutes acceptance of the revised Terms.</p>

            <h2 id="contact">16. Contact Us</h2>
            <p>If you have any questions about these Terms, please contact us:</p>
            <ul>
                <li>Email: <a href="mailto:legal@jobsinusa.com">legal@jobsinusa.com</a></li>
                <li>Contact form: <a href="{{ url('/contact-us') }}">Contact Us page</a></li>
            </ul>
        </div>
    </div>
</section>

@endsection
