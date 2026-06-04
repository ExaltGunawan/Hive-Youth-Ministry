<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hive Youth Ministry | GKI</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #070a13;
            --bg-card: rgba(15, 23, 42, 0.6);
            --accent-gold: #f59e0b;
            --accent-gold-rgb: 245, 158, 11;
            --accent-gold-hover: #fbbf24;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-glass: rgba(245, 158, 11, 0.08);
            --border-hover: rgba(245, 158, 11, 0.3);
            --font-display: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --font-sans: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: var(--font-sans);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            line-height: 1.6;
        }

        /* Ambient glowing backgrounds */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(var(--accent-gold-rgb), 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            z-index: -1;
            pointer-events: none;
        }
        
        .glow-1 {
            top: -10%;
            right: -10%;
        }
        
        .glow-2 {
            bottom: 10%;
            left: -10%;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #090d1a;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(var(--accent-gold-rgb), 0.2);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(var(--accent-gold-rgb), 0.4);
        }

        /* Parallax Background and Honeycomb Overlay */
        .hero-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: url('{{ asset("assets/BG.png") }}');
            background-size: cover;
            background-position: center;
            opacity: 0.12;
            filter: grayscale(40%) contrast(110%);
            z-index: -2;
            pointer-events: none;
        }

        .honeycomb-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: radial-gradient(rgba(245, 158, 11, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
            z-index: -2;
            pointer-events: none;
        }

        /* Header Navigation */
        header {
            width: 100%;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(7, 10, 19, 0.8);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
        }

        .logo-img {
            height: 48px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 8px rgba(var(--accent-gold-rgb), 0.2));
            transition: transform 0.4s ease;
        }

        .logo-separator {
            width: 1px;
            height: 28px;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .logo-container:hover .logo-img {
            transform: scale(1.05);
        }

        .brand-text {
            display: flex;
            flex-col: column;
            line-height: 1.2;
        }

        .brand-name {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--text-light);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .brand-sub {
            font-size: 0.75rem;
            color: var(--accent-gold);
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .nav-btn {
            background: linear-gradient(135deg, rgba(var(--accent-gold-rgb), 0.1) 0%, rgba(var(--accent-gold-rgb), 0.2) 100%);
            border: 1px solid var(--border-glass);
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            color: var(--text-light);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: var(--font-display);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .nav-btn:hover {
            border-color: var(--accent-gold);
            background: linear-gradient(135deg, rgba(var(--accent-gold-rgb), 0.2) 0%, rgba(var(--accent-gold-rgb), 0.3) 100%);
            box-shadow: 0 0 15px rgba(var(--accent-gold-rgb), 0.3);
            transform: translateY(-2px);
        }

        .nav-btn svg {
            width: 16px;
            height: 16px;
            fill: var(--accent-gold);
            transition: transform 0.3s ease;
        }

        .nav-btn:hover svg {
            transform: translateX(3px);
        }

        /* Container styling */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        /* Section Titles */
        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
            position: relative;
        }

        .section-tag {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-title {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-light);
            letter-spacing: -0.5px;
        }

        .section-desc {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0.5rem auto 0 auto;
        }

        /* Hero / Dynamic Welcome Section */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 3rem 1rem 6rem 1rem;
            position: relative;
        }

        .hero-badge {
            background: linear-gradient(135deg, rgba(var(--accent-gold-rgb), 0.15) 0%, rgba(var(--accent-gold-rgb), 0.03) 100%);
            border: 1px solid rgba(var(--accent-gold-rgb), 0.35);
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            color: var(--accent-gold);
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-transform: uppercase;
            box-shadow: 0 4px 20px rgba(var(--accent-gold-rgb), 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: pulse-glow 3s infinite alternate;
        }

        .hero-badge:hover {
            transform: translateY(-2px);
            border-color: rgba(var(--accent-gold-rgb), 0.6);
            box-shadow: 0 6px 25px rgba(var(--accent-gold-rgb), 0.25);
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 8px rgba(var(--accent-gold-rgb), 0.1), 0 4px 15px rgba(0, 0, 0, 0.15); }
            100% { box-shadow: 0 0 22px rgba(var(--accent-gold-rgb), 0.35), 0 4px 15px rgba(0, 0, 0, 0.15); }
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.25;
            letter-spacing: -1px;
            margin-bottom: 1.5rem;
            max-width: 950px;
            padding-top: 5px;
            padding-bottom: 12px;
            background: linear-gradient(135deg, #ffffff 40%, rgba(255, 255, 255, 0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 650px;
            margin-bottom: 3rem;
            line-height: 1.7;
        }

        /* Service Schedule Glassmorphic Card */
        .schedule-card {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 3rem;
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 2.5rem;
            text-align: left;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .schedule-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(225deg, rgba(var(--accent-gold-rgb), 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .schedule-card:hover {
            transform: translateY(-5px);
            border-color: var(--border-hover);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(var(--accent-gold-rgb), 0.15);
        }

        .schedule-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .schedule-tag {
            color: var(--accent-gold);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.8rem;
        }

        .schedule-heading {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .schedule-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .schedule-right {
            border-left: 1px solid rgba(255, 255, 255, 0.06);
            padding-left: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1.5rem;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(var(--accent-gold-rgb), 0.08);
            border: 1px solid rgba(var(--accent-gold-rgb), 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            shrink-0: 1;
            color: var(--accent-gold);
        }

        .info-icon svg {
            width: 22px;
            height: 22px;
            stroke-width: 2;
        }

        .info-content {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-family: var(--font-display);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-light);
            margin-top: 0.1rem;
        }

        /* Instagram Section */
        .instagram-section {
            background: linear-gradient(180deg, transparent 0%, rgba(var(--accent-gold-rgb), 0.01) 50%, transparent 100%);
            border-y: 1px solid rgba(255, 255, 255, 0.02);
        }

        .instagram-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .ig-card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 18px;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
        }

        /* Generate beautiful fallback abstract visual backgrounds for Instagram items */
        .ig-bg-1 { 
            background-image: url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=600&q=80');
            background-size: cover;
            background-position: center;
        }
        .ig-bg-2 { 
            background-image: url('https://img.youtube.com/vi/5ly-VesN-xg/0.jpg');
            background-size: cover;
            background-position: center;
        }
        .ig-bg-3 { 
            background-image: url('https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&w=600&q=80');
            background-size: cover;
            background-position: center;
        }
        .ig-bg-4 { 
            background-image: url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=600&q=80');
            background-size: cover;
            background-position: center;
        }

        .ig-card:hover .play-btn-circle {
            background: var(--accent-gold) !important;
            box-shadow: 0 0 35px rgba(var(--accent-gold-rgb), 0.8) !important;
            transform: translate(-50%, -50%) scale(1.15) !important;
        }

        .ig-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.85) 90%);
            z-index: 1;
            transition: opacity 0.3s ease;
        }

        .ig-card:hover {
            transform: translateY(-8px);
            border-color: var(--border-hover);
            box-shadow: 0 15px 35px rgba(var(--accent-gold-rgb), 0.15);
        }

        .ig-content {
            position: relative;
            z-index: 2;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .ig-tag {
            align-self: flex-start;
            background: rgba(var(--accent-gold-rgb), 0.15);
            border: 1px solid var(--accent-gold);
            color: var(--accent-gold);
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ig-caption {
            font-size: 0.85rem;
            color: var(--text-light);
            font-weight: 500;
            line-clamp: 2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .ig-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 0.6rem;
        }

        .ig-stats {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ig-stats svg {
            width: 14px;
            height: 14px;
        }

        .ig-icon-floating {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }

        .social-btn-container {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .social-btn {
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: var(--font-display);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .social-btn-ig-1 {
            background: linear-gradient(135deg, #c13584 0%, #e1306c 100%);
            box-shadow: 0 8px 20px rgba(225, 48, 108, 0.25);
        }
        
        .social-btn-ig-2 {
            background: linear-gradient(135deg, #f56040 0%, #f77737 100%);
            box-shadow: 0 8px 20px rgba(245, 96, 64, 0.25);
        }

        .social-btn-yt {
            background: linear-gradient(135deg, #e52d27 0%, #b31217 100%);
            box-shadow: 0 8px 20px rgba(229, 45, 39, 0.25);
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(255, 255, 255, 0.1), 0 5px 15px rgba(0, 0, 0, 0.3);
            filter: brightness(1.1);
        }

        /* Board Directory / Kontak Pengurus Section */
        .pengurus-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .pengurus-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .pengurus-card:hover {
            transform: translateY(-8px);
            border-color: var(--border-hover);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 20px rgba(var(--accent-gold-rgb), 0.1);
        }

        .avatar-container {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            position: relative;
            margin-bottom: 1.2rem;
            padding: 3px;
            background: linear-gradient(135deg, rgba(var(--accent-gold-rgb), 0.2) 0%, rgba(var(--accent-gold-rgb), 0.6) 100%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0b0f19;
            background-color: #1e293b;
        }

        .avatar-fallback {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid #0b0f19;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--accent-gold);
        }

        .pengurus-name {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 0.25rem;
            line-clamp: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        .pengurus-role {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 0.8rem;
            background: rgba(var(--accent-gold-rgb), 0.08);
            padding: 0.2rem 0.8rem;
            border-radius: 50px;
            border: 1px solid rgba(var(--accent-gold-rgb), 0.15);
            display: inline-block;
        }

        .pengurus-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 0.8rem;
            width: 100%;
        }

        .pengurus-meta-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .pengurus-meta-item svg {
            width: 14px;
            height: 14px;
            color: var(--accent-gold);
        }

        .pengurus-contact-btn {
            width: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.08) 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 0.6rem;
            color: var(--text-light);
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .pengurus-contact-btn:hover {
            border-color: var(--accent-gold);
            background: linear-gradient(135deg, rgba(var(--accent-gold-rgb), 0.15) 0%, rgba(var(--accent-gold-rgb), 0.05) 100%);
            color: var(--accent-gold);
            transform: translateY(-2px);
        }

        .pengurus-contact-btn svg {
            width: 14px;
            height: 14px;
        }

        /* Footer styling */
        footer {
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding: 3rem 2rem;
            text-align: center;
            background: rgba(5, 7, 13, 0.95);
            position: relative;
            z-index: 10;
        }

        .footer-logo-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .footer-logo-img {
            height: 36px;
            width: auto;
            opacity: 0.6;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .footer-copyright {
            color: rgba(255, 255, 255, 0.25);
            font-size: 0.75rem;
            margin-top: 1rem;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .instagram-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .pengurus-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 1rem 1.5rem;
            }
            
            .brand-name {
                font-size: 1.1rem;
            }
            
            .brand-sub {
                font-size: 0.65rem;
                letter-spacing: 1px;
            }
            
            .hero-title {
                font-size: 2.8rem;
            }
            
            .schedule-card {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 2rem;
            }
            
            .schedule-right {
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.06);
                padding-left: 0;
                padding-top: 2rem;
            }
            
            .container {
                padding: 2.5rem 1.5rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .instagram-grid {
                grid-template-columns: 1fr;
            }
            
            .pengurus-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient glowing lights behind elements -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    
    <!-- Background layers -->
    <div class="hero-bg"></div>
    <div class="honeycomb-pattern"></div>

    <!-- Navigation Header -->
    <header>
        <a href="/" class="logo-container">
            <img src="{{ asset('assets/gki.png') }}" alt="Logo GKI" class="logo-img">
            <div class="logo-separator"></div>
            <img src="{{ asset('assets/Logo.png') }}" alt="Logo Hive Youth" class="logo-img">
            <div class="brand-text">
                <span class="brand-name">Hive Youth</span>
                <span class="brand-sub">Ministry</span>
            </div>
        </a>
        {{-- 
        <a href="{{ url('/admin/login') }}" class="nav-btn">
            <span>Portal Pengurus</span>
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </a>
        --}}
    </header>

    <!-- Main Content -->
    <main>
        
        <!-- Hero Section featuring weekly service schedule -->
        <section class="hero container">
            <div class="hero-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color: var(--accent-gold);"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span>SELAMAT DATANG DI HIVE YOUTH MINISTRY</span>
            </div>
            <h1 class="hero-title">Connecting Youths, Growing in Faith</h1>
            <p class="hero-subtitle">Membangun komunitas anak muda yang solid, bersukacita, dan bertumbuh bersama di dalam iman Kristen. Mari bergabung bersama kami di persekutuan mingguan!</p>
            
            <!-- Service Schedule Card -->
            <div class="schedule-card">
                <div class="schedule-left">
                    <span class="schedule-tag">JADWAL PERSEKUTUAN</span>
                    <h2 class="schedule-heading">Ibadah Pemuda Mingguan</h2>
                    <p class="schedule-description">Persekutuan pemuda dirancang khusus untuk membawa pesan yang relevan, pujian penyembahan yang bersemangat, serta ruang untuk saling bertumbuh dan berbagi dalam kelompok kecil.</p>
                </div>
                <div class="schedule-right">
                    <!-- Day Indicator -->
                    <div class="info-row">
                        <div class="info-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Hari</span>
                            <span class="info-value">Setiap Hari Minggu</span>
                        </div>
                    </div>
                    <!-- Time Indicator -->
                    <div class="info-row">
                        <div class="info-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Waktu</span>
                            <span class="info-value">Pukul 07:00 WIB</span>
                        </div>
                    </div>
                    <!-- Location Indicator -->
                    <div class="info-row">
                        <div class="info-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Tempat</span>
                            <span class="info-value">GKI Cimahi, Ruang Kebaktian 2</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Media & Komunitas Section -->
        <section class="instagram-section container">
            <div class="section-header">
                <span class="section-tag">Media & Komunitas</span>
                <h2 class="section-title">Koneksi Sosial Media Hub</h2>
                <p class="section-desc">Ikuti kabar terbaru, keseruan persekutuan, dokumentasi ibadah, dan renungan menarik melalui kanal sosial media resmi kami.</p>
            </div>
            
            <!-- Media Grid -->
            <div class="instagram-grid">
                <!-- Card 1: Instagram Post 1 (Foto Bersama) -->
                <a href="https://www.instagram.com/p/BjWewf8BTk7/?igsh=MXFnMjVjajM5bWtidA==" target="_blank" class="ig-card ig-bg-1">
                    <div class="ig-icon-floating">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </div>
                    <div class="ig-overlay"></div>
                    <div class="ig-content">
                        <span class="ig-tag">Foto Bersama</span>
                        <p class="ig-caption">Kebersamaan & sukacita persekutuan remaja pemuda GKI Cimahi dalam satu iman yang hangat.</p>
                        <div class="ig-meta">
                            <span>@kp_gkicimahi</span>
                            <div class="ig-stats">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                <span>Lihat Post</span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Card 2: YouTube Video (Video Teaser Worship Night) -->
                <a href="https://youtube.com/shorts/5ly-VesN-xg?si=MBT6vnejYsVA9Iv3" target="_blank" class="ig-card ig-bg-2">
                    <!-- Glowing Play Button Overlay -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 3; background: rgba(229, 45, 39, 0.9); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 25px rgba(229, 45, 39, 0.6); transition: all 0.3s ease;" class="play-btn-circle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <div class="ig-overlay"></div>
                    <div class="ig-content">
                        <span class="ig-tag" style="background: rgba(229, 45, 39, 0.2); border-color: #e52d27; color: #e52d27;">Teaser Worship Night</span>
                        <p class="ig-caption">Saksikan teaser Online Worship Night: Pujian penyembahan yang mempersatukan hati kita di hadirat-Nya.</p>
                        <div class="ig-meta">
                            <span>@GKICimahi</span>
                            <div class="ig-stats">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                <span>Tonton Video</span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Card 3: Instagram Reel (Recap Camp Youth) -->
                <a href="https://www.instagram.com/reel/DKw1IQkRFAV/?igsh=OG9lNmV5cGZrMGhn" target="_blank" class="ig-card ig-bg-3">
                    <div class="ig-icon-floating">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </div>
                    <div class="ig-overlay"></div>
                    <div class="ig-content">
                        <span class="ig-tag">Recap Camp Youth</span>
                        <p class="ig-caption">Momen-momen sukacita, tawa, ibadah, dan perjumpaan pribadi dengan Tuhan yang memulihkan hidup selama Camp Youth.</p>
                        <div class="ig-meta">
                            <span>@koreci_gki</span>
                            <div class="ig-stats">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 1 15.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                <span>Tonton Reel</span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Card 4: Instagram Post 2 (Acara Community) -->
                <a href="https://www.instagram.com/p/DNF-g-ZPVGm/?igsh=cHVuMXBicnduMzZt" target="_blank" class="ig-card ig-bg-4">
                    <div class="ig-icon-floating">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </div>
                    <div class="ig-overlay"></div>
                    <div class="ig-content">
                        <span class="ig-tag">Acara Community</span>
                        <p class="ig-caption">Keseruan kegiatan kebersamaan, games, dan aktivitas seru komunitas pemuda dalam merajut tali persaudaraan.</p>
                        <div class="ig-meta">
                            <span>@kp_gkicimahi</span>
                            <div class="ig-stats">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 1 15.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                <span>Lihat Post</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Connection Buttons -->
            <div class="social-btn-container font-semibold">
                <a href="https://instagram.com/kp_gkicimahi" target="_blank" class="social-btn social-btn-ig-1">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    <span>@kp_gkicimahi</span>
                </a>
                <a href="https://instagram.com/koreci_gki" target="_blank" class="social-btn social-btn-ig-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    <span>@koreci_gki</span>
                </a>
                <a href="https://youtube.com/@GKICimahi" target="_blank" class="social-btn social-btn-yt">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.163c-.272-1.022-1.074-1.826-2.099-2.099C19.548 3.5 12 3.5 12 3.5s-7.548 0-9.399.564c-1.025.273-1.827 1.077-2.1 2.1C0 8.015 0 12 0 12s0 3.985.501 5.837c.273 1.022 1.075 1.826 2.1 2.1C4.452 20.5 12 20.5 12 20.5s7.548 0 9.399-.563c1.025-.274 1.827-1.078 2.1-2.1.501-1.852.501-5.837.501-5.837s0-3.985-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    <span>GKICimahi</span>
                </a>
            </div>
        </section>

        <!-- Board Directory / Kontak Pengurus Section -->
        @php
            // Extract dynamic pengurus from DB (filter by Ketua/Wakil keywords and ignore default admin unless empty)
            $displayList = collect();
            
            if (isset($pengurus) && $pengurus->isNotEmpty()) {
                foreach ($pengurus as $p) {
                    // Check if the role/jabatan is Ketua or Wakil
                    $isKetuaOrWakil = false;
                    $jabatanLower = strtolower($p['jabatan']);
                    if (str_contains($jabatanLower, 'ketua') || str_contains($jabatanLower, 'wakil') || str_contains($jabatanLower, 'chair') || str_contains($jabatanLower, 'president')) {
                        $isKetuaOrWakil = true;
                    }
                    
                    if ($isKetuaOrWakil && ($p['nama'] !== 'Admin Hive' || $pengurus->count() === 1)) {
                        $displayList->push($p);
                    }
                }
            }
            
            // Take only the first 2 items to prevent showing more than Ketua & Wakil
            $displayList = $displayList->take(2);
        @endphp

        @if($displayList->isNotEmpty())
        <section class="container">
            <div class="section-header">
                <span class="section-tag">BOARD & DIRECTORY</span>
                <h2 class="section-title">Kontak Pengurus</h2>
                <p class="section-desc">Punya pertanyaan seputar pelayanan pemuda atau butuh teman berbagi? Silakan hubungi jajaran pengurus kami secara langsung.</p>
            </div>
            
            <div class="pengurus-grid">

                @foreach($displayList as $p)
                    <div class="pengurus-card">
                        <div class="avatar-container">
                            @if(isset($p['photo']) && $p['photo'])
                                <img src="{{ $p['photo'] }}" alt="{{ $p['nama'] }}" class="avatar-img">
                            @else
                                <div class="avatar-fallback">
                                    {{ strtoupper(substr($p['nama'], 0, 1)) }}{{ count(explode(' ', $p['nama'])) > 1 ? strtoupper(substr(explode(' ', $p['nama'])[1], 0, 1)) : '' }}
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="pengurus-name">{{ $p['nama'] }}</h3>
                        <span class="pengurus-role">{{ $p['jabatan'] }}</span>
                        
                        <div class="pengurus-meta">
                            <div class="pengurus-meta-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                                </svg>
                                <span>&commat;{{ ltrim($p['instagram'], '@') }}</span>
                            </div>
                            <div class="pengurus-meta-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ $p['kontak'] }}</span>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p['kontak']) }}" target="_blank" class="pengurus-contact-btn">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 1.887.521 3.652 1.428 5.166l-1.01 3.693c-.092.336.216.63.533.518l3.666-1.3a9.92 9.92 0 004.823 1.246c5.522 0 10-4.484 10-10.017C22 6.484 17.522 2 12 2zm4.184 14.073c-.237.669-1.38 1.282-1.92 1.348-.485.06-1.127.08-3.242-.796-2.705-1.12-4.437-3.873-4.572-4.053-.135-.18-1.1-1.464-1.1-2.793 0-1.33.697-1.983.945-2.247.247-.263.541-.33.72-.33h.518c.158 0 .373-.06.586.452.225.541.765 1.866.832 2.001.068.136.113.294.023.475-.09.18-.135.294-.27.452-.135.158-.285.353-.406.474-.135.136-.277.285-.12.556.157.271.7 1.152 1.503 1.866.697.62 1.285.811 1.57.947.285.136.45.113.62-.083.168-.195.72-.834.912-1.12.19-.285.382-.237.64-.143.26.094 1.643.774 1.925.917.282.143.472.21.54.327.067.118.067.683-.17 1.353z" clip-rule="evenodd" />
                            </svg>
                            <span>Hubungi Pengurus</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-logo-row">
            <img src="{{ asset('assets/gki.png') }}" alt="Logo GKI" class="footer-logo-img">
            <img src="{{ asset('assets/Logo.png') }}" alt="Logo Hive Youth" class="footer-logo-img">
        </div>
        <p class="footer-text"><strong>Hive Youth Ministry</strong> | Komunitas Pemuda Gereja Kristen Indonesia</p>
        <p class="footer-text" style="font-size: 0.8rem; opacity: 0.7;">Persekutuan Setiap Hari Minggu Pukul 07.00 WIB di GKI Cimahi, Ruang Kebaktian 2</p>
        <p class="footer-copyright">&copy; {{ date('Y') }} Hive Youth Ministry. All Rights Reserved. Built for faith and community.</p>
    </footer>

</body>
</html>
