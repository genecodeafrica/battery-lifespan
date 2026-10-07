<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">


        <title>BatteryLife AI</title>

        {{-- BatteryLife AI Favicon --}}
        <link
            rel="icon"
            type="image/jpeg"
            href="{{ asset('image/icon.jpg') }}"
        >

        <link
            rel="shortcut icon"
            type="image/jpeg"
            href="{{ asset('image/icon.jpg') }}"
        >

        <link
            rel="apple-touch-icon"
            href="{{ asset('image/icon.jpg') }}"
        >

        @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        :root {
            --navy: #0E2A47;
            --dark-navy: #082039;
            --green: #1F8A4C;
            --dark-green: #0F3D25;
            --light-green: #E8F5ED;
            --background: #F5F7F6;
            --white: #FFFFFF;
            --text: #1F2933;
            --muted: #68747D;
            --amber: #B5762D;
            --red: #B23B2E;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            color: var(--text);
            background: var(--background);
            line-height: 1.5;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input {
            font: inherit;
        }

        /* =========================
           NAVIGATION
        ========================== */

        .navbar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 20;

            padding: 17px 5%;
        }

        .nav-container {
            max-width: 1280px;
            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;

            color: var(--white);
            font-size: 19px;
            font-weight: 800;
        }

        .brand-icon {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: var(--green);
            color: white;

            font-size: 15px;

            box-shadow: 0 6px 20px rgba(31, 138, 76, .3);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links a {
            color: rgba(255, 255, 255, .82);
            font-size: 13px;
            font-weight: 500;

            transition: .2s ease;
        }

        .nav-links a:hover {
            color: white;
        }

        .login-btn {
            padding: 8px 17px;

            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 7px;

            background: rgba(255, 255, 255, .07);

            color: white !important;

            cursor: pointer;

            backdrop-filter: blur(8px);
        }

        .login-btn:hover {
            background: var(--green);
            border-color: var(--green);
        }


        /* =========================
           HERO
        ========================== */

        .hero {
            position: relative;

            min-height: 625px;

            overflow: hidden;

            background-image:
                linear-gradient(
                    90deg,
                    rgba(5, 22, 38, .94) 0%,
                    rgba(8, 32, 57, .84) 38%,
                    rgba(8, 32, 57, .66) 68%,
                    rgba(15, 61, 37, .56) 100%
                ),
                url('{{ asset('image/background.jpg') }}');

            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }

        .hero-container {
            position: relative;
            z-index: 2;

            max-width: 1280px;
            min-height: 625px;

            margin: auto;

            padding: 105px 5% 55px;

            display: grid;
            grid-template-columns: 1.05fr .95fr;

            align-items: center;

            gap: 50px;
        }

        .hero-content {
            max-width: 650px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            margin-bottom: 15px;

            padding: 6px 11px;

            border: 1px solid rgba(255,255,255,.17);
            border-radius: 50px;

            background: rgba(255,255,255,.07);

            color: #DCEFE4;

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: 1.2px;

            backdrop-filter: blur(8px);
        }

        .eyebrow-dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: #56C982;

            box-shadow: 0 0 10px rgba(86,201,130,.8);
        }

        .hero h1 {
            margin-bottom: 16px;

            color: white;

            font-size: clamp(40px, 4.2vw, 60px);

            line-height: 1.03;

            font-weight: 800;

            letter-spacing: -2.5px;
        }

        .hero h1 span {
            color: #63D28A;
        }

        .hero-description {
            max-width: 590px;

            margin-bottom: 19px;

            color: rgba(255,255,255,.76);

            font-size: 14px;

            line-height: 1.65;
        }

        .developer {
            display: flex;
            flex-direction: column;

            margin-bottom: 20px;

            color: rgba(255,255,255,.58);

            font-size: 11px;
        }

        .developer strong {
            color: white;

            font-size: 13px;
            font-weight: 700;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;

            margin-bottom: 23px;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 11px 18px;

            border-radius: 7px;

            font-size: 12px;
            font-weight: 700;

            cursor: pointer;

            transition: .2s ease;
        }

        .btn-primary {
            border: 1px solid var(--green);

            background: var(--green);
            color: white;

            box-shadow: 0 8px 20px rgba(31,138,76,.22);
        }

        .btn-primary:hover {
            transform: translateY(-1px);

            background: #269F59;
        }

        .btn-secondary {
            border: 1px solid rgba(255,255,255,.23);

            background: rgba(255,255,255,.07);
            color: white;

            backdrop-filter: blur(7px);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,.13);
        }

        .hero-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 27px;
        }

        .stat {
            display: flex;
            flex-direction: column;
        }

        .stat strong {
            color: white;

            font-size: 19px;
            line-height: 1.2;
        }

        .stat span {
            margin-top: 2px;

            color: rgba(255,255,255,.53);

            font-size: 10px;
        }


        /* =========================
           DASHBOARD PREVIEW
        ========================== */

        .dashboard-preview {
            width: 100%;
            max-width: 455px;

            margin-left: auto;

            padding: 18px;

            border: 1px solid rgba(255,255,255,.14);
            border-radius: 17px;

            background: rgba(8,32,57,.72);

            box-shadow:
                0 25px 55px rgba(0,0,0,.32),
                inset 0 1px 0 rgba(255,255,255,.07);

            backdrop-filter: blur(16px);
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 14px;
        }

        .preview-title {
            color: white;

            font-size: 12px;
            font-weight: 700;
        }

        .live-status {
            display: flex;
            align-items: center;
            gap: 5px;

            color: #83DDA2;

            font-size: 9px;
            font-weight: 600;
        }

        .live-dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: #53C67C;

            box-shadow: 0 0 8px rgba(83,198,124,.8);
        }

        .battery-panel {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 14px;

            margin-bottom: 12px;

            border: 1px solid rgba(255,255,255,.07);
            border-radius: 11px;

            background: rgba(255,255,255,.045);
        }

        .battery-info small {
            display: block;

            margin-bottom: 2px;

            color: rgba(255,255,255,.46);

            font-size: 9px;
        }

        .battery-info strong {
            color: white;

            font-size: 22px;
        }

        .battery-health {
            color: #68D78F;

            font-size: 9px;
            font-weight: 700;
        }

        .battery-shape {
            position: relative;

            width: 85px;
            height: 40px;

            padding: 4px;

            border: 2px solid rgba(255,255,255,.5);
            border-radius: 6px;
        }

        .battery-shape::after {
            content: "";

            position: absolute;

            top: 11px;
            right: -7px;

            width: 4px;
            height: 14px;

            border-radius: 0 2px 2px 0;

            background: rgba(255,255,255,.5);
        }

        .battery-fill {
            width: 78%;
            height: 100%;

            border-radius: 3px;

            background: linear-gradient(
                90deg,
                #1F8A4C,
                #63D28A
            );
        }

        .chart {
            height: 112px;

            padding: 13px;

            border: 1px solid rgba(255,255,255,.06);
            border-radius: 10px;

            background: rgba(255,255,255,.035);
        }

        .chart-title {
            margin-bottom: 5px;

            color: rgba(255,255,255,.5);

            font-size: 8px;
        }

        .chart svg {
            width: 100%;
            height: 75px;
        }

        .preview-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);

            gap: 7px;

            margin-top: 8px;
        }

        .metric {
            padding: 9px;

            border: 1px solid rgba(255,255,255,.06);
            border-radius: 7px;

            background: rgba(255,255,255,.035);
        }

        .metric small {
            display: block;

            color: rgba(255,255,255,.42);

            font-size: 7px;
        }

        .metric strong {
            color: white;

            font-size: 11px;
        }


        /* =========================
           GENERAL SECTIONS
        ========================== */

        .section {
            padding: 70px 5%;
        }

        .section-container {
            max-width: 1200px;
            margin: auto;
        }

        .section-heading {
            max-width: 620px;

            margin-bottom: 35px;
        }

        .section-label {
            margin-bottom: 7px;

            color: var(--green);

            font-size: 10px;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: 1.3px;
        }

        .section-heading h2 {
            margin-bottom: 10px;

            color: var(--navy);

            font-size: clamp(28px, 3vw, 38px);

            line-height: 1.15;

            letter-spacing: -1px;
        }

        .section-heading p {
            color: var(--muted);

            font-size: 13px;

            line-height: 1.7;
        }


        /* =========================
           FEATURES
        ========================== */

        .features {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 15px;
        }

        .feature-card {
            padding: 22px;

            border: 1px solid #E4E9E7;
            border-radius: 12px;

            background: white;

            transition: .2s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);

            border-color: rgba(31,138,76,.3);

            box-shadow: 0 14px 30px rgba(8,32,57,.07);
        }

        .feature-icon {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 14px;

            border-radius: 9px;

            background: var(--light-green);
            color: var(--green);

            font-size: 15px;
            font-weight: 800;
        }

        .feature-card h3 {
            margin-bottom: 6px;

            color: var(--navy);

            font-size: 14px;
        }

        .feature-card p {
            color: var(--muted);

            font-size: 11px;

            line-height: 1.65;
        }


        /* =========================
           HOW IT WORKS
        ========================== */

        .process-section {
            background: white;
        }

        .process {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 14px;
        }

        .process-step {
            padding: 22px;

            border-radius: 11px;

            background: var(--background);
        }

        .process-number {
            width: 31px;
            height: 31px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 14px;

            border-radius: 50%;

            background: var(--navy);
            color: white;

            font-size: 9px;
            font-weight: 800;
        }

        .process-step h3 {
            margin-bottom: 6px;

            color: var(--navy);

            font-size: 14px;
        }

        .process-step p {
            color: var(--muted);

            font-size: 11px;

            line-height: 1.6;
        }


        /* =========================
           ABOUT
        ========================== */

        .about {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 55px;

            align-items: center;
        }

        .about-text h2 {
            margin-bottom: 13px;

            color: var(--navy);

            font-size: 36px;

            line-height: 1.15;

            letter-spacing: -1px;
        }

        .about-text p {
            margin-bottom: 10px;

            color: var(--muted);

            font-size: 12px;

            line-height: 1.75;
        }

        .about-box {
            padding: 28px;

            border-radius: 14px;

            background:
                linear-gradient(
                    135deg,
                    var(--navy),
                    var(--dark-green)
                );

            color: white;

            box-shadow: 0 18px 40px rgba(8,32,57,.13);
        }

        .about-box h3 {
            margin-bottom: 10px;

            font-size: 18px;
        }

        .about-box p {
            color: rgba(255,255,255,.7);

            font-size: 12px;

            line-height: 1.7;
        }


        /* =========================
           CTA
        ========================== */

        .cta-section {
            padding: 55px 5%;
        }

        .cta {
            max-width: 1150px;

            margin: auto;

            padding: 45px;

            border-radius: 17px;

            background:
                linear-gradient(
                    135deg,
                    var(--dark-navy),
                    var(--navy),
                    var(--dark-green)
                );

            text-align: center;

            box-shadow: 0 20px 45px rgba(8,32,57,.14);
        }

        .cta h2 {
            margin-bottom: 10px;

            color: white;

            font-size: clamp(27px, 3vw, 38px);

            line-height: 1.15;
        }

        .cta p {
            max-width: 600px;

            margin: 0 auto 20px;

            color: rgba(255,255,255,.66);

            font-size: 12px;
        }


        /* =========================
           FOOTER
        ========================== */

        footer {
            padding: 22px 5%;

            border-top: 1px solid #E4E9E7;

            background: white;
        }

        .footer-container {
            max-width: 1200px;

            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .footer-brand {
            color: var(--navy);

            font-size: 13px;
            font-weight: 800;
        }

        .footer-text {
            color: var(--muted);

            font-size: 10px;
        }


        /* =========================
           LOGIN MODAL
        ========================== */

        .modal-overlay {
            position: fixed;

            inset: 0;

            z-index: 100;

            display: none;

            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(3,15,25,.78);

            backdrop-filter: blur(7px);
        }

        .modal-overlay.active {
            display: flex;
        }

        .login-modal {
            position: relative;

            width: 100%;
            max-width: 400px;

            padding: 32px;

            border-radius: 15px;

            background: white;

            box-shadow: 0 25px 60px rgba(0,0,0,.3);

            animation: modalIn .2s ease;
        }

        @keyframes modalIn {

            from {
                opacity: 0;
                transform: translateY(12px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

        }

        .modal-close {
            position: absolute;

            top: 13px;
            right: 15px;

            width: 30px;
            height: 30px;

            border: none;
            border-radius: 50%;

            background: #F0F3F2;

            color: var(--muted);

            cursor: pointer;

            font-size: 18px;
        }

        .modal-icon {
            width: 44px;
            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 14px;

            border-radius: 11px;

            background: var(--light-green);
            color: var(--green);

            font-size: 18px;
        }

        .login-modal h2 {
            margin-bottom: 5px;

            color: var(--navy);

            font-size: 22px;
        }

        .modal-subtitle {
            margin-bottom: 20px;

            color: var(--muted);

            font-size: 11px;
        }

        .form-group {
            margin-bottom: 13px;
        }

        .form-group label {
            display: block;

            margin-bottom: 5px;

            color: var(--text);

            font-size: 11px;
            font-weight: 700;
        }

        .form-group input {
            width: 100%;

            padding: 10px 12px;

            border: 1px solid #D8E0DD;
            border-radius: 7px;

            outline: none;

            color: var(--text);
            background: #FAFCFB;

            font-size: 12px;
        }

        .form-group input:focus {
            border-color: var(--green);

            box-shadow: 0 0 0 3px rgba(31,138,76,.1);
        }

        .modal-submit {
            width: 100%;

            margin-top: 3px;

            padding: 11px;

            border: none;
            border-radius: 7px;

            background: var(--green);
            color: white;

            font-size: 12px;
            font-weight: 700;

            cursor: pointer;
        }

        .modal-submit:hover {
            background: #269F59;
        }

        .login-error {
            margin-bottom: 13px;

            padding: 9px 11px;

            border-radius: 7px;

            background: #FDEDEC;
            color: var(--red);

            font-size: 10px;
        }


        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 1000px) {

            .hero-container {
                grid-template-columns: 1fr;

                padding-top: 120px;
                padding-bottom: 55px;

                gap: 35px;
            }

            .hero {
                min-height: auto;
            }

            .hero-container {
                min-height: auto;
            }

            .dashboard-preview {
                margin-left: 0;
            }

            .features {
                grid-template-columns: repeat(2, 1fr);
            }

            .process {
                grid-template-columns: repeat(2, 1fr);
            }

            .about {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 700px) {

            .navbar {
                padding: 15px 5%;
            }

            .nav-links a:not(.login-btn) {
                display: none;
            }

            .hero h1 {
                font-size: 42px;

                letter-spacing: -1.8px;
            }

            .features,
            .process {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 55px 5%;
            }

            .about-text h2 {
                font-size: 31px;
            }

            .cta {
                padding: 35px 22px;
            }

            .footer-container {
                flex-direction: column;

                text-align: center;
            }
        }


        @media (max-width: 480px) {

            .brand {
                font-size: 16px;
            }

            .hero-container {
                padding-top: 105px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }

            .hero-stats {
                gap: 20px;
            }

            .dashboard-preview {
                padding: 14px;
            }

            .preview-metrics {
                grid-template-columns: 1fr;
            }

            .login-modal {
                padding: 27px 21px;
            }
        }
    </style>
</head>

<body>


    <!-- =========================
         NAVIGATION
    ========================== -->

    <nav class="navbar">

        <div class="nav-container">

            <a href="{{ url('/') }}" class="brand">

                <div class="brand-icon">
                    ⚡
                </div>

                <span>
                    BatteryLife AI
                </span>

            </a>


            <div class="nav-links">

                <a href="#features">
                    Features
                </a>

                <a href="#how-it-works">
                    How It Works
                </a>

                <a href="#about">
                    About
                </a>


                @auth

                    <a href="{{ url('/dashboard') }}"
                       class="login-btn">

                        Dashboard

                    </a>

                @else

                    <a href="javascript:void(0)"
                       class="login-btn"
                       onclick="openLoginModal()">

                        Login

                    </a>

                @endauth

            </div>

        </div>

    </nav>


    <!-- =========================
         HERO
    ========================== -->

    <section class="hero">

        <div class="hero-container">


            <div class="hero-content">


                <div class="eyebrow">

                    <span class="eyebrow-dot"></span>

                    AI-Powered Battery Intelligence

                </div>


                <h1>

                    Smarter Battery
                    <span>Health Monitoring.</span>

                </h1>


                <p class="hero-description">

                    BatteryLife AI uses intelligent data analysis
                    and machine learning to monitor battery
                    performance, estimate remaining useful life,
                    and provide actionable insights for better
                    battery management.

                </p>


                <div class="developer">

                    <span>
                        Developer
                    </span>

                    <strong>
                        Gene Kiliyobas Chipau
                    </strong>

                </div>


                <div class="hero-buttons">


                    @auth

                        <a href="{{ url('/dashboard') }}"
                           class="btn-primary">

                            Open Dashboard

                        </a>

                    @else

                        <button
                            type="button"
                            class="btn-primary"
                            onclick="openLoginModal()">

                            Login to BatteryLife AI

                        </button>

                    @endauth


                    <a href="#how-it-works"
                       class="btn-secondary">

                        Explore Platform

                    </a>

                </div>


                <div class="hero-stats">

                    <div class="stat">

                        <strong>
                            AI
                        </strong>

                        <span>
                            Intelligent Analysis
                        </span>

                    </div>


                    <div class="stat">

                        <strong>
                            ML
                        </strong>

                        <span>
                            Predictive Models
                        </span>

                    </div>


                    <div class="stat">

                        <strong>
                            RUL
                        </strong>

                        <span>
                            Remaining Useful Life
                        </span>

                    </div>

                </div>

            </div>


            <!-- =========================
                 DASHBOARD PREVIEW
            ========================== -->

            <div class="dashboard-preview">


                <div class="preview-header">

                    <div class="preview-title">
                        Battery Health Dashboard
                    </div>

                    <div class="live-status">

                        <span class="live-dot"></span>

                        Monitoring

                    </div>

                </div>


                <div class="battery-panel">

                    <div class="battery-info">

                        <small>
                            Current Battery Health
                        </small>

                        <strong>
                            78%
                        </strong>

                        <div class="battery-health">
                            Good Condition
                        </div>

                    </div>


                    <div class="battery-shape">

                        <div class="battery-fill"></div>

                    </div>

                </div>


                <div class="chart">

                    <div class="chart-title">
                        Battery Capacity Trend
                    </div>


                    <svg
                        viewBox="0 0 500 100"
                        preserveAspectRatio="none">

                        <defs>

                            <linearGradient
                                id="chartGradient"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1">

                                <stop
                                    offset="0%"
                                    stop-color="#1F8A4C"
                                    stop-opacity=".35"/>

                                <stop
                                    offset="100%"
                                    stop-color="#1F8A4C"
                                    stop-opacity="0"/>

                            </linearGradient>

                        </defs>


                        <path
                            d="M0,20
                               C40,22 55,27 85,29
                               S130,35 160,38
                               S210,36 235,43
                               S285,50 310,54
                               S350,57 375,61
                               S420,68 450,72
                               S480,76 500,81
                               L500,100
                               L0,100 Z"
                            fill="url(#chartGradient)"
                        />


                        <path
                            d="M0,20
                               C40,22 55,27 85,29
                               S130,35 160,38
                               S210,36 235,43
                               S285,50 310,54
                               S350,57 375,61
                               S420,68 450,72
                               S480,76 500,81"
                            fill="none"
                            stroke="#63D28A"
                            stroke-width="3"
                        />

                    </svg>

                </div>


                <div class="preview-metrics">


                    <div class="metric">

                        <small>
                            Voltage
                        </small>

                        <strong>
                            3.72 V
                        </strong>

                    </div>


                    <div class="metric">

                        <small>
                            Temperature
                        </small>

                        <strong>
                            28.4°C
                        </strong>

                    </div>


                    <div class="metric">

                        <small>
                            RUL
                        </small>

                        <strong>
                            186 Cycles
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================
         FEATURES
    ========================== -->

    <section class="section" id="features">

        <div class="section-container">

            <div class="section-heading">

                <div class="section-label">
                    Platform Features
                </div>

                <h2>
                    Everything you need to understand battery health.
                </h2>

                <p>
                    BatteryLife AI combines battery monitoring,
                    data analysis, predictive analytics and
                    machine learning into one intelligent platform.
                </p>

            </div>


            <div class="features">


                <div class="feature-card">

                    <div class="feature-icon">
                        ⚡
                    </div>

                    <h3>
                        Battery Monitoring
                    </h3>

                    <p>
                        Monitor important battery measurements
                        and performance indicators from a
                        centralized dashboard.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        AI
                    </div>

                    <h3>
                        AI Analysis
                    </h3>

                    <p>
                        Apply intelligent analysis techniques
                        to understand battery behaviour and
                        performance.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        ML
                    </div>

                    <h3>
                        Machine Learning
                    </h3>

                    <p>
                        Use predictive models to identify
                        battery degradation patterns and
                        future performance.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        R
                    </div>

                    <h3>
                        Remaining Useful Life
                    </h3>

                    <p>
                        Estimate the remaining useful life
                        of a battery using historical
                        performance data.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        ↗
                    </div>

                    <h3>
                        Reports & Analytics
                    </h3>

                    <p>
                        Generate useful reports and visual
                        analytics for battery performance
                        evaluation.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <h3>
                        Data-Driven Decisions
                    </h3>

                    <p>
                        Turn battery data into meaningful
                        information that supports better
                        maintenance decisions.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================
         HOW IT WORKS
    ========================== -->

    <section
        class="section process-section"
        id="how-it-works">

        <div class="section-container">


            <div class="section-heading">

                <div class="section-label">
                    How It Works
                </div>

                <h2>
                    From battery data to intelligent insight.
                </h2>

                <p>
                    BatteryLife AI follows a simple data-driven
                    process to transform battery measurements
                    into useful predictions.
                </p>

            </div>


            <div class="process">


                <div class="process-step">

                    <div class="process-number">
                        01
                    </div>

                    <h3>
                        Collect Data
                    </h3>

                    <p>
                        Capture battery measurements including
                        voltage, temperature, capacity and
                        cycle information.
                    </p>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        02
                    </div>

                    <h3>
                        Analyze Data
                    </h3>

                    <p>
                        Process and analyze battery data to
                        identify important performance and
                        degradation patterns.
                    </p>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        03
                    </div>

                    <h3>
                        Predict
                    </h3>

                    <p>
                        Machine learning models use historical
                        data to estimate future battery behaviour.
                    </p>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        04
                    </div>

                    <h3>
                        Take Action
                    </h3>

                    <p>
                        Use the resulting insights and reports
                        to make better battery management
                        decisions.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================
         ABOUT
    ========================== -->

    <section class="section" id="about">

        <div class="section-container">

            <div class="about">


                <div class="about-text">

                    <div class="section-label">
                        About BatteryLife AI
                    </div>

                    <h2>
                        Turning battery data into useful intelligence.
                    </h2>

                    <p>
                        BatteryLife AI is an intelligent battery
                        monitoring and prediction platform designed
                        to help users understand battery performance,
                        degradation and remaining useful life.
                    </p>

                    <p>
                        By combining modern web technologies with
                        data analytics and machine learning, the
                        platform provides a centralized environment
                        for monitoring battery health and generating
                        meaningful insights.
                    </p>

                </div>


                <div class="about-box">

                    <h3>
                        Built for intelligent battery management.
                    </h3>

                    <p>
                        The platform provides a foundation for
                        collecting battery measurements, analyzing
                        historical behaviour, running predictive
                        models and presenting results through a
                        modern dashboard.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================
         CTA
    ========================== -->

    <section class="cta-section">

        <div class="cta">

            <h2>
                Start monitoring your battery intelligence.
            </h2>

            <p>
                Access the BatteryLife AI platform and explore
                battery health monitoring, analytics and
                predictive capabilities.
            </p>


            @auth

                <a href="{{ url('/dashboard') }}"
                   class="btn-primary">

                    Open Dashboard

                </a>

            @else

                <button
                    type="button"
                    class="btn-primary"
                    onclick="openLoginModal()">

                    Login to BatteryLife AI

                </button>

            @endauth

        </div>

    </section>


    <!-- =========================
         FOOTER
    ========================== -->

    <footer>

        <div class="footer-container">

            <div class="footer-brand">
                BatteryLife AI
            </div>

            <div class="footer-text">
                © {{ date('Y') }} BatteryLife AI. All rights reserved.
            </div>

            <div class="footer-text">
                Developed by Gene Kiliyobas Chipau
            </div>

        </div>

    </footer>


    <!-- =========================
         LOGIN MODAL
    ========================== -->

    <div
        class="modal-overlay"
        id="loginModal"
        onclick="handleModalBackdrop(event)"
    >

        <div class="login-modal">


            <button
                type="button"
                class="modal-close"
                onclick="closeLoginModal()"
                aria-label="Close">

                ×

            </button>


            <div class="modal-icon">
                ⚡
            </div>


            <h2>
                Welcome Back
            </h2>


            <p class="modal-subtitle">
                Sign in to access your BatteryLife AI dashboard.
            </p>


            @if ($errors->any())

                <div class="login-error">

                    @foreach ($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            @endif


            <form
                method="POST"
                action="{{ url('/login') }}">

                @csrf


                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        required
                        autocomplete="email">

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password">

                </div>


                <button
                    type="submit"
                    class="modal-submit">

                    Sign In

                </button>

            </form>

        </div>

    </div>


    <!-- =========================
         JAVASCRIPT
    ========================== -->

    <script>

        function openLoginModal() {

            const modal =
                document.getElementById('loginModal');

            if (!modal) {
                return;
            }

            modal.classList.add('active');

            document.body.style.overflow = 'hidden';

            setTimeout(function () {

                const email =
                    document.getElementById('email');

                if (email) {
                    email.focus();
                }

            }, 100);
        }


        function closeLoginModal() {

            const modal =
                document.getElementById('loginModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('active');

            document.body.style.overflow = '';
        }


        function handleModalBackdrop(event) {

            if (
                event.target ===
                document.getElementById('loginModal')
            ) {

                closeLoginModal();

            }
        }


        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {

                    closeLoginModal();

                }

            }
        );


        @if ($errors->any())

            document.addEventListener(
                'DOMContentLoaded',
                function() {

                    openLoginModal();

                }
            );

        @endif

    </script>

</body>
</html>