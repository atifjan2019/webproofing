<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebProofing Documentation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --color-bg: #ffffff;
            --color-bg-soft: #f8f9fa;
            --color-text: #374151;
            --color-text-dark: #111827;
            --color-accent: #EE314F;
            --color-border: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Nav */
        .nav {
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--color-border);
            margin-bottom: 3rem;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-weight: 700;
            color: var(--color-text-dark);
            text-decoration: none;
            font-size: 1.25rem;
        }

        .logo span {
            color: var(--color-accent);
        }

        .back-link {
            text-decoration: none;
            color: var(--color-text);
            font-size: 0.9rem;
        }

        .back-link:hover {
            color: var(--color-accent);
        }

        /* Content */
        h1 {
            font-size: 2.5rem;
            color: var(--color-text-dark);
            margin-bottom: 2rem;
            line-height: 1.2;
        }

        h2 {
            font-size: 1.5rem;
            color: var(--color-text-dark);
            margin-top: 3rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--color-border);
        }

        h3 {
            font-size: 1.1rem;
            color: var(--color-text-dark);
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        p {
            margin-bottom: 1rem;
        }

        ul {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        li {
            margin-bottom: 0.5rem;
        }

        .box {
            background: var(--color-bg-soft);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
            border: 1px solid var(--color-border);
        }

        .note {
            font-size: 0.9rem;
            color: #666;
            font-style: italic;
        }

        .cta-box {
            background: #fff0f2;
            padding: 2rem;
            text-align: center;
            border-radius: 1rem;
            margin-top: 4rem;
        }

        .footer {
            margin-top: 4rem;
            padding: 2rem 0;
            border-top: 1px solid var(--color-border);
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="nav">
            <div class="nav-content">
                <a href="/" class="logo">Web<span>Proofing</span> Docs</a>
                <a href="/" class="back-link">← Back to website</a>
            </div>
        </nav>

        <h1>How to use WebProofing</h1>

        <p class="note">A simple guide for freelancers, agencies, and website owners.</p>

        <div class="box">
            <h3>Quick Summary</h3>
            <p>WebProofing gives visual proof that a website was live, plus analytics data, all in one place. Unlike
                regular uptime tools, we take real pictures of your site so you can see exactly how it looked.</p>
        </div>

        <h2>1. What is WebProofing?</h2>
        <p>WebProofing is a simple monitoring tool. It visits your website automatically (just like a real person would)
            and takes a full screenshot.</p>
        <p>It also connects to your Google data to show you how many people visited your site and how it performed in
            Google Search.</p>
        <p><strong>It uses real browser screenshots, not just "pings".</strong></p>

        <h2>2. What problems does it solve?</h2>
        <ul>
            <li><strong>Clients asking "was my site live?"</strong> – Now you have photo proof.</li>
            <li><strong>Uptime tools lie</strong> – Sometimes a site says "200 OK" (online) but is actually a blank
                white page. WebProofing shows you the truth.</li>
            <li><strong>Scattered data</strong> – Stop logging into 3 different tools. See screenshots, traffic, and
                search clicks in one dashboard.</li>
        </ul>

        <h2>3. Getting Started</h2>
        <ol>
            <li><strong>Create an account</strong> – Sign up or log in.</li>
            <li><strong>Add a website</strong> – Just type in your domain name (e.g., mysite.com).</li>
            <li><strong>Connect Google Analytics</strong> – Click the "Connect" button to link your GA4 property.</li>
            <li><strong>Connect Search Console</strong> – Link your GSC property to see search rankings.</li>
            <li><strong>Done!</strong> – We start monitoring immediately.</li>
        </ol>

        <h2>4. Screenshots (Website Proof)</h2>
        <p>We monitor your site automatically. Every time we check your site:</p>
        <ul>
            <li>We open a real Chrome browser.</li>
            <li>We load your website fully.</li>
            <li>We take a high-quality screenshot.</li>
            <li>We save it in your history.</li>
        </ul>
        <p>This is your "Visual Proof". If a client claims their site was down, you can check the history and show them
            the photo.</p>

        <h2>5. Google Analytics Data</h2>
        <p>We pull key numbers from your Google Analytics 4 (GA4) account:</p>
        <ul>
            <li><strong>Visitors</strong> – How many people came to your site.</li>
            <li><strong>Pageviews</strong> – How many pages they looked at.</li>
        </ul>
        <p class="note">Note: We only READ your data. We never change or delete anything in your Google account.</p>

        <h2>6. Google Search Console Data</h2>
        <p>We show you how your site is doing in Google Search:</p>
        <ul>
            <li><strong>Clicks</strong> – How many people clicked your site in Google results.</li>
            <li><strong>Impressions</strong> – How many times your site appeared in search results.</li>
        </ul>
        <p>This helps you see if your SEO efforts are working without getting lost in complex spreadsheets.</p>

        <h2>7. History & Records</h2>
        <p>WebProofing keeps a historical record of your site. You can look back at previous days to see changes over
            time.</p>
        <p>This is great for monthly reports or audits.</p>

        <h2>8. Who should use WebProofing?</h2>
        <ul>
            <li><strong>Freelancers</strong> – Manage client sites with peace of mind.</li>
            <li><strong>Agencies</strong> – Send visual reports to clients.</li>
            <li><strong>SEO Professionals</strong> – Track rankings and visual changes together.</li>
            <li><strong>Website Owners</strong> – Know your business is actually online.</li>
        </ul>

        <h2>9. Common Questions</h2>

        <h3>Is this an uptime monitoring tool?</h3>
        <p>Yes, but better. Regular uptime monitors just "ping" a server. We visually check the site.</p>

        <h3>Does WebProofing change my website?</h3>
        <p>No. We never modify your website code.</p>

        <h3>Is Google access safe?</h3>
        <p>Yes. We use the official Google Integration. You can revoke access at any time.</p>

        <div class="cta-box">
            <h3>Early Product Notice 🚀</h3>
            <p>WebProofing is in early stages. We are actively adding features every week.</p>
            <p>If you have a suggestion or find a bug, please reach out!</p>
        </div>

        <footer class="footer">
            <p>Still confused? Reach out or send feedback — we’re actively improving WebProofing.</p>
            <p>&copy; {{ date('Y') }} WebProofing</p>
        </footer>
    </div>
</body>

</html>