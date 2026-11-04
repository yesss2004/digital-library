<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Discover Books</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            :root {
                --bg-soft: #e7e1d5;
                --panel: #f5f1ea;
                --panel-strong: #f1ece5;
                --sidebar: rgba(255,255,255,0.38);
                --white: #ffffff;
                --text: #1d1d1c;
                --muted: #686b6a;
                --line: rgba(33, 33, 30, 0.12);
                --green: #1a5f53;
                --green-2: #0c4d45;
                --green-soft: #dfeae7;
                --dot: #b7b1a8;
                --shadow: 0 14px 30px rgba(33, 36, 31, 0.08);
                --pink: #e5d3e8;
                --lavender: #f0e8fa;
                --beige: #efe5d7;
                --cream: #f4f0eb;
                --yellow: #f7d53f;
                --mustard: #f5c726;
                --orange: #ef7b3d;
                --red: #b7342d;
                --purple: #7d4dd7;
            }

            * { box-sizing: border-box; }
            html, body { margin: 0; min-height: 100%; font-family: 'Inter', sans-serif; background: var(--bg-soft); color: var(--text); }
            body {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .app-shell {
                width: min(1440px, 100%);
                min-height: 920px;
                background: rgba(255,255,255,0.08);
                display: grid;
                grid-template-columns: 280px 1fr;
                gap: 0;
                border-radius: 30px;
                overflow: hidden;
            }

            .sidebar {
                background: rgba(255,255,255,0.12);
                padding: 24px 18px 18px 18px;
                border-right: 1px solid rgba(27, 28, 28, 0.08);
                display: flex;
                flex-direction: column;
            }

            .logo {
                font-weight: 900;
                font-size: 1.05rem;
                letter-spacing: 0.06em;
                color: #171918;
                margin: 2px 0 26px 12px;
                text-transform: uppercase;
            }

            .nav {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-bottom: 18px;
            }

            .nav-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 12px;
                border-radius: 12px;
                font-size: 0.98rem;
                color: #333b3a;
                text-decoration: none;
                transition: 0.2s ease;
            }

            .nav-item:hover {
                background: rgba(255,255,255,0.38);
            }

            .nav-item.active {
                background: rgba(255,255,255,0.55);
                box-shadow: inset 0 0 0 1px rgba(26,95,83,0.08);
            }

            .nav-icon {
                width: 22px;
                height: 22px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                background: rgba(0,0,0,0.03);
                color: #3a3b39;
                font-size: 0.8rem;
                font-weight: 700;
            }

            .nav-item.active .nav-icon {
                background: linear-gradient(135deg, #ef7b3d, #df5a2e);
                color: white;
            }

            .sidebar-separator {
                height: 1px;
                background: rgba(23, 25, 24, 0.1);
                margin: 10px 0 16px;
            }

            .book-library {
                margin-top: auto;
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 18px 10px 6px;
                border-top: 1px solid rgba(23,25,24,0.08);
            }

            .mini-cover {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                position: relative;
                background: linear-gradient(135deg, #f5d5d4, #f2ebff);
                box-shadow: inset 0 0 0 2px rgba(0,0,0,0.04);
            }

            .mini-cover::before,
            .mini-cover::after {
                content: "";
                position: absolute;
                border-radius: 50%;
                background: rgba(16, 16, 16, 0.82);
            }

            .mini-cover::before {
                width: 22px;
                height: 22px;
                left: 8px;
                top: 6px;
            }

            .mini-cover::after {
                width: 18px;
                height: 18px;
                right: 7px;
                bottom: 8px;
            }

            .book-library strong {
                display: block;
                font-size: 0.9rem;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: #1d1d1c;
            }

            .content {
                background: var(--panel);
                padding: 20px 28px 24px 28px;
                display: flex;
                flex-direction: column;
            }

            .topbar {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                margin-bottom: 14px;
            }

            .user-box {
                display: flex;
                align-items: center;
                gap: 12px;
                background: rgba(255,255,255,0.2);
                padding: 8px 12px 8px 10px;
                border-radius: 999px;
                border: 1px solid rgba(26,95,83,0.08);
            }

            .avatar {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: linear-gradient(135deg, #826b60, #e7caa9);
                position: relative;
                box-shadow: inset 0 0 0 2px rgba(255,255,255,0.4);
            }

            .avatar::before {
                content: "";
                position: absolute;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: rgba(255,255,255,0.8);
                left: 8px;
                top: 7px;
            }

            .user-name {
                font-weight: 500;
                color: #1f2222;
                font-size: 0.95rem;
            }

            .bell {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #2d312f;
                font-size: 1rem;
                position: relative;
            }

            .bell::after {
                content: "";
                position: absolute;
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: #1d1d1c;
                right: 4px;
                top: 4px;
                box-shadow: 0 0 0 2px rgba(255,255,255,0.85);
            }

            .discover-wrap {
                background: rgba(255,255,255,0.18);
                border-radius: 30px;
                padding: 8px 22px 8px;
                min-height: 760px;
            }

            .discover-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 16px 0;
            }

            .discover-title {
                font-size: clamp(2.3rem, 3vw, 4rem);
                font-weight: 800;
                letter-spacing: -0.06em;
                margin: 0;
            }

            .search-area {
                max-width: 760px;
                margin: 22px auto 10px;
                display: grid;
                grid-template-columns: 200px 1fr 150px;
                gap: 0;
                border: 1px solid rgba(20,22,20,0.12);
                background: rgba(255,255,255,0.52);
                border-radius: 18px;
                overflow: hidden;
                box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
            }

            .search-select,
            .search-input,
            .search-button {
                border: none;
                background: transparent;
                height: 70px;
                font: inherit;
            }

            .search-select {
                padding: 0 20px;
                font-size: 1.1rem;
                border-right: 1px solid rgba(21,24,22,0.1);
                color: #2f3233;
                appearance: none;
                background-image: linear-gradient(45deg, transparent 50%, #3a3d3d 50%), linear-gradient(135deg, #3a3d3d 50%, transparent 50%);
                background-position: calc(100% - 20px) calc(50% - 3px), calc(100% - 15px) calc(50% - 3px);
                background-size: 6px 6px, 6px 6px;
                background-repeat: no-repeat;
            }

            .search-input {
                padding: 0 18px;
                font-size: 1.05rem;
                color: #313637;
                outline: none;
            }

            .search-input::placeholder {
                color: #7a7f7f;
            }

            .search-button {
                background: linear-gradient(180deg, #234f49, #123d37);
                color: white;
                font-weight: 700;
                font-size: 1.02rem;
                cursor: pointer;
            }

            .section-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 26px;
                padding: 0 14px;
            }

            .section-title {
                margin: 0;
                font-size: clamp(1.6rem, 2vw, 3rem);
                letter-spacing: -0.05em;
            }

            .view-all {
                padding: 12px 22px;
                border: 1px solid rgba(27,29,28,0.16);
                border-radius: 999px;
                background: rgba(255,255,255,0.2);
                font-weight: 600;
                color: #1d2020;
            }

            .recommendations {
                display: grid;
                grid-template-columns: repeat(5, minmax(180px, 1fr));
                gap: 20px;
                margin-top: 20px;
                padding: 0 14px;
            }

            .book-card {
                min-height: 320px;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                border-radius: 20px;
                position: relative;
                padding: 0 0 0 0;
                overflow: hidden;
                box-shadow: 0 10px 18px rgba(33, 36, 31, 0.12);
            }

            .book-cover {
                position: relative;
                width: 100%;
                height: 320px;
                display: flex;
                align-items: center;
                justify-content: center;
                perspective: 1200px;
                overflow: hidden;
                border-radius: 16px;
            }

            .book-cover::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(255,255,255,0.12), rgba(0,0,0,0.08));
                z-index: 2;
            }

            .cover-inner {
                position: relative;
                z-index: 3;
                width: 78%;
                height: 88%;
                border-radius: 6px 6px 0 12px;
                box-shadow: -8px 0 12px rgba(0,0,0,0.12), 0 8px 16px rgba(0,0,0,0.08);
                transform: rotate(-1deg);
                padding: 16px 16px 12px 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                font-weight: 800;
                letter-spacing: -0.04em;
                line-height: 0.95;
            }

            .cover-inner .title {
                font-family: Georgia, serif;
                font-style: italic;
                font-size: clamp(1.7rem, 2vw, 2.7rem);
                color: #2a2c2d;
                display: block;
                word-break: break-word;
            }

            .cover-inner .small {
                font-size: 0.86rem;
                letter-spacing: 0.02em;
                text-transform: uppercase;
                font-style: normal;
                font-family: 'Inter', sans-serif;
                margin-top: 10px;
                display: block;
                color: #3b3c3b;
            }

            .book-card:nth-child(1) .cover-inner {
                background: linear-gradient(180deg, #eef8f5, #d9f0e7);
            }

            .book-card:nth-child(1) .cover-inner .title { color: #1f2c10; }

            .book-card:nth-child(2) .cover-inner {
                background: linear-gradient(180deg, #f5f4ef, #ffffff);
            }

            .book-card:nth-child(3) .cover-inner {
                background: linear-gradient(180deg, #fdf4b8, #f3d53a);
            }

            .book-card:nth-child(4) .cover-inner {
                background: linear-gradient(180deg, #f7f5e9, #f0ebde);
                color: #392922;
            }

            .book-card:nth-child(5) .cover-inner {
                background: linear-gradient(180deg, #f5f1f0, #f2e2df);
            }

            .book-card:nth-child(5) .cover-inner .title {
                font-family: Georgia, serif;
                font-style: italic;
                color: #2d1918;
            }

            .category-block {
                margin-top: 38px;
                padding: 0 14px;
            }

            .category-grid {
                margin-top: 20px;
                display: grid;
                grid-template-columns: repeat(4, minmax(180px, 1fr));
                gap: 18px;
            }

            .category-item {
                background: rgba(255,255,255,0.18);
                border: 1px solid rgba(28,30,28,0.08);
                border-radius: 18px;
                padding: 12px 12px 18px;
                display: flex;
                flex-direction: column;
            }

            .category-cover {
                width: 100%;
                height: 200px;
                border-radius: 12px;
                background: rgba(255,255,255,0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: Georgia, serif;
                font-style: italic;
                font-weight: 700;
                font-size: 2.1rem;
                color: #1d1d1d;
                position: relative;
                overflow: hidden;
            }

            .category-cover::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(255,255,255,0.15), rgba(0,0,0,0.05));
            }

            .category-cover .book-word {
                position: relative;
                z-index: 1;
                text-shadow: 0 1px 0 rgba(255,255,255,0.4);
            }

            .cat-1 { background: linear-gradient(135deg, #f3f4ee, #dfe5dc); }
            .cat-2 { background: linear-gradient(135deg, #f3f0ef, #dfe4dc); }
            .cat-3 { background: linear-gradient(135deg, #f7e4c1, #efe4a7); }
            .cat-4 { background: linear-gradient(135deg, #f7e5e0, #f4d8d2); }
            .cat-5 { background: linear-gradient(135deg, #faf3cf, #fbecb2); }

            .category-name {
                margin-top: 14px;
                font-size: clamp(1.2rem, 1.4vw, 2rem);
                font-weight: 700;
                letter-spacing: -0.05em;
                text-align: center;
                color: #1c1f1f;
            }

            .layout-toggle {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: rgba(255,255,255,0.28);
                border: 1px solid rgba(21,24,22,0.1);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 4px;
                padding: 8px 10px;
            }

            .layout-toggle span {
                display: block;
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background: #27302d;
                box-shadow: 0 8px 0 #27302d, 0 -8px 0 #27302d;
            }

            @media (max-width: 1200px) {
                .recommendations { grid-template-columns: repeat(3, minmax(180px, 1fr)); }
                .category-grid { grid-template-columns: repeat(2, minmax(180px, 1fr)); }
            }

            @media (max-width: 900px) {
                .app-shell { grid-template-columns: 1fr; }
                .sidebar { border-right: none; border-bottom: 1px solid rgba(27, 28, 28, 0.08); }
                .discover-wrap { min-height: auto; }
            }

            @media (max-width: 700px) {
                body { padding: 10px; }
                .content { padding: 18px 16px 22px; }
                .search-area { grid-template-columns: 1fr; }
                .search-select { border-right: none; border-bottom: 1px solid rgba(21,24,22,0.1); }
                .recommendations, .category-grid { grid-template-columns: 1fr; }
                .discover-head { flex-direction: column; align-items: flex-start; gap: 14px; }
            }
        </style>
    </head>
    <body>
        <div class="app-shell">
            <aside class="sidebar">
                <div class="logo">The Books</div>

                <nav class="nav" aria-label="Navigation">
                    <a class="nav-item active" href="#">
                        <span class="nav-icon">⌂</span>
                        <span>Discover</span>
                    </a>
                    <a class="nav-item" href="#">
                        <span class="nav-icon">▦</span>
                        <span>Category</span>
                    </a>
                    <a class="nav-item" href="#">
                        <span class="nav-icon">▣</span>
                        <span>My Library</span>
                    </a>
                    <a class="nav-item" href="#">
                        <span class="nav-icon">↓</span>
                        <span>Download</span>
                    </a>
                    <a class="nav-item" href="#">
                        <span class="nav-icon">♥</span>
                        <span>Favorite</span>
                    </a>
                </nav>

                <div class="sidebar-separator"></div>

                <nav class="nav" aria-label="Other navigation">
                    <a class="nav-item" href="#">
                        <span class="nav-icon">⚙</span>
                        <span>Setting</span>
                    </a>
                    <a class="nav-item" href="#">
                        <span class="nav-icon">?</span>
                        <span>Help</span>
                    </a>
                    <a class="nav-item" href="#">
                        <span class="nav-icon">↩</span>
                        <span>Log out</span>
                    </a>
                </nav>

                <div class="book-library">
                    <div class="mini-cover"></div>
                    <strong>Book Library</strong>
                </div>
            </aside>

            <main class="content">
                <header class="topbar">
                    <div class="user-box" aria-label="User info">
                        <div class="avatar" aria-hidden="true"></div>
                        <span class="user-name">Davis Workman</span>
                        <span class="bell" aria-hidden="true">◔</span>
                    </div>
                </header>

                <div class="discover-wrap">
                    <div class="discover-head">
                        <h1 class="discover-title">Discover</h1>
                    </div>

                    <div class="search-area" role="search">
                        <select class="search-select" aria-label="Kategori">
                            <option>All Categories</option>
                            <option>Money</option>
                            <option>Design</option>
                            <option>Business</option>
                            <option>Self Improvement</option>
                        </select>
                        <input class="search-input" type="text" placeholder="Find the book you like..." aria-label="Find the book you like" />
                        <button class="search-button" type="button">Search</button>
                    </div>

                    <div class="section-head">
                        <h2 class="section-title">Book Recommendation</h2>
                    </div>

                    <section class="recommendations" aria-label="Book recommendation">
                        <article class="book-card">
                            <div class="book-cover">
                                <div class="cover-inner" style="background: linear-gradient(180deg, #eaf7f2, #d5efe3);">
                                    <div>
                                        <span class="title" style="font-size:2.1rem; line-height:0.95;">Psychology<br>of<br>Money</span>
                                        <span class="small">Morgan Housel</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="book-card">
                            <div class="book-cover">
                                <div class="cover-inner" style="background: linear-gradient(180deg, #f2f1ee, #e9e7e1);">
                                    <div>
                                        <span class="title" style="font-size:2.2rem; letter-spacing:-0.08em;">Company<br>of<br>One</span>
                                        <span class="small">Paul Jarvis</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="book-card">
                            <div class="book-cover">
                                <div class="cover-inner" style="background: linear-gradient(180deg, #f7dc52, #efd733);">
                                    <div>
                                        <span class="title" style="font-size:2.2rem;">MATT<br>RIDLEY</span>
                                        <span class="small">How innovation works</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="book-card">
                            <div class="book-cover">
                                <div class="cover-inner" style="background: linear-gradient(180deg, #f4efe6, #e9e1d0);">
                                    <div>
                                        <span class="title" style="font-size:2.2rem; line-height:1.05;">The Picture of Dorian Gray</span>
                                        <span class="small" style="font-size:0.7rem;">Oscar Wilde</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="book-card">
                            <div class="book-cover">
                                <div class="cover-inner" style="background: linear-gradient(180deg, #eadfdf, #ebdad9);">
                                    <div>
                                        <span class="title" style="font-size:2.1rem;">The<br>Art<br>of<br>War</span>
                                        <span class="small">Sun Tzu</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>

                    <div class="section-head category-block" style="margin-top: 36px;">
                        <h2 class="section-title">Book Category</h2>
                        <button class="view-all" type="button">View all</button>
                    </div>

                    <section class="category-grid" aria-label="Book categories">
                        <article class="category-item">
                            <div class="category-cover cat-1"><span class="book-word">The Two Towers</span></div>
                            <div class="category-name">Money/Investing</div>
                        </article>

                        <article class="category-item">
                            <div class="category-cover cat-2"><span class="book-word">Objectif</span></div>
                            <div class="category-name">Design</div>
                        </article>

                        <article class="category-item">
                            <div class="category-cover cat-3"><span class="book-word">Human</span></div>
                            <div class="category-name">Business</div>
                        </article>

                        <article class="category-item">
                            <div class="category-cover cat-4"><span class="book-word">The Subtle Art</span></div>
                            <div class="category-name">Self Improvement</div>
                        </article>
                    </section>
                </div>
            </main>
        </div>
    </body>
</html>
