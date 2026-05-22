<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                margin: 0;
                padding: 0;
                background-color: #0c0f17;
                background-image: 
                    radial-gradient(circle at 50% -20%, #1e293b 0%, transparent 70%),
                    radial-gradient(circle at 0% 100%, #0f172a 0%, transparent 50%),
                    radial-gradient(circle at 100% 100%, #0f172a 0%, transparent 50%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #e2e8f0;
            }
            .login-card {
                background: #111827;
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 2rem;
                box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.6);
                width: 100%;
                max-width: 420px;
                padding: 2.5rem;
                margin-top: 1rem;
            }
            .premium-input {
                background: #0f172a !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                color: white !important;
                border-radius: 0.85rem !important;
                padding: 0.85rem 1.15rem !important;
                font-size: 0.9rem !important;
                transition: all 0.2s ease !important;
            }
            .premium-input:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
                background: #0f172a !important;
            }
            .premium-input::placeholder {
                color: #4b5563 !important;
                opacity: 0.7;
            }
            .premium-btn {
                background: #2563eb;
                border: none;
                color: white;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-radius: 1rem;
                padding: 1.15rem;
                cursor: pointer;
                transition: all 0.2s ease-out;
                box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
                font-size: 1rem;
            }
            .premium-btn:hover {
                background: #3b82f6;
                transform: translateY(-1.5px);
                box-shadow: 0 10px 25px rgba(37, 99, 235, 0.5);
            }
            .label-premium {
                font-size: 0.7rem;
                font-weight: 800;
                color: #60a5fa;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin-bottom: 0.5rem;
                display: block;
            }
            .link-premium {
                color: #60a5fa;
                text-decoration: none;
                font-size: 0.8rem;
                font-weight: 800;
                text-transform: uppercase;
                transition: color 0.2s ease;
            }
            .link-premium:hover {
                color: #93c5fd;
            }
            .text-muted-premium {
                color: #94a3b8;
                font-size: 0.8rem;
                font-weight: 600;
            }
            .copyright {
                margin-top: 3rem;
                color: #4b5563;
                font-size: 0.65rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.2em;
                text-align: center;
            }
            .logo-glow {
                position: absolute;
                inset: -20px;
                background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
                filter: blur(15px);
                z-index: -1;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="flex flex-col items-center w-full max-w-xl px-6 py-8">
            <!-- Brand Header -->
            <div class="mb-8 text-center">
                <div class="relative inline-block mb-4">
                    <div class="logo-glow"></div>
                    <img src="{{ asset('images/logo.png') }}" 
                         style="width: 100px; height: 100px; object-fit: cover;"
                         class="rounded-2xl shadow-2xl" 
                         alt="Logo">
                </div>
                <h1 class="text-3xl font-black tracking-tighter text-white italic">AKAZASTORE</h1>
                <p class="text-blue-500 text-[10px] font-bold tracking-[0.3em] uppercase mt-1 opacity-90">Premium Top-up & Services</p>
            </div>

            <!-- Login Card -->
            <div class="login-card">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="copyright">
                &copy; {{ date('Y') }} AkazaStore. All Rights Reserved.
            </div>
        </div>
    </body>
</html>
