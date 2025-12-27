<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | WebProofing</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-bg: #0f0f0f;
            --color-accent: #ee314f;
            --color-text: #ffffff;
            --color-muted: rgba(255, 255, 255, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Abstract Background Elements */
        .bg-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(238, 49, 79, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
            filter: blur(60px);
            animation: moveGlow 20s ease-in-out infinite;
        }

        .bg-glow-1 {
            top: -200px;
            left: -200px;
        }

        .bg-glow-2 {
            bottom: -200px;
            right: -200px;
            animation-delay: -10s;
        }

        @keyframes moveGlow {

            0%,
            100% {
                transform: translate(0, 0);
            }

            33% {
                transform: translate(100px, 50px);
            }

            66% {
                transform: translate(-50px, 100px);
            }
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .error-code {
            font-size: 10rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.2) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            position: relative;
        }

        .error-code::after {
            content: "404";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -45%);
            width: 100%;
            z-index: -1;
            color: var(--color-accent);
            opacity: 0.1;
            filter: blur(20px);
        }

        h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        p {
            font-size: 1.125rem;
            color: var(--color-muted);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--color-accent);
            color: #fff;
            box-shadow: 0 8px 24px rgba(238, 49, 79, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(238, 49, 79, 0.5);
            background-color: #ff3e5c;
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Glitch Effect */
        .glitch {
            position: relative;
        }

        @keyframes noise-anim {
            0% {
                clip: rect(44px, 9999px, 56px, 0);
            }

            5% {
                clip: rect(62px, 9999px, 12px, 0);
            }

            /* ... abbreviated for readability ... */
            100% {
                clip: rect(12px, 9999px, 98px, 0);
            }
        }

        .scanner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--color-accent);
            opacity: 0.5;
            box-shadow: 0 0 10px var(--color-accent);
            animation: scan 3s linear infinite;
        }

        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>

    <div class="content">
        <div class="error-code">404</div>
        <h1>Lost in the Cloud?</h1>
        <p>The page you are looking for has either been moved, deleted, or never existed. Let's get you back on track.
        </p>

        <div class="actions">
            <a href="/" class="btn btn-primary">Go Home</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <!-- Minimalistic subtle scanline effect -->
    <div
        style="position:fixed; top:0; left:0; width:100%; height:100%; pointer-events:none; background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.1) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03)); z-index: 10; background-size: 100% 2px, 3px 100%;">
    </div>
</body>

</html>