<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Fontset Preview</title>

    @php
    $slug = request('fontset','default');
    $v = request('t') ?? time();
    $debug = request('debug');
    @endphp

    <!-- Load your generated fontset CSS (this defines heroTitle, heroLead, title, subtitle, content, subcontent) -->
    <link rel="stylesheet" href="/fonts/fonts.css">
    <link rel="stylesheet" href="/api/css/fontset/{{ rawurlencode($slug) }}.css?v={{ $v }}">

    <style>
        :root {
            --ink: #1c1633;
            --muted: #6b6a76;
            --surface: #ffffff;
            --card: #f6f5fb;
            --stroke: #2f2a4a;
            --accent: #e05a5a;
            --accent-2: #6b89ff;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: var(--surface);
            color: var(--ink);
        }

        .wrap {
            padding: 28px;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* Utility */
        .stack>*+* {
            margin-top: 14px
        }

        .muted {
            color: var(--muted)
        }

        .pill {
            display: inline-block;
            padding: .35rem .6rem;
            border: 2px solid var(--stroke);
            border-radius: 999px;
            font-weight: 700;
            box-shadow: 0 4px 0 #e7e5f6;
            background: #fff
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px
        }

        @media (min-width: 980px) {
            .grid {
                grid-template-columns: 1.2fr .8fr
            }
        }

        .card {
            background: var(--card);
            border: 2px solid var(--stroke);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 0 #d9d6f0
        }

        .illus {
            height: 240px;
            border: 2px solid var(--stroke);
            border-radius: 14px;
            background:
                linear-gradient(135deg, #ff7d7d 0 40%, #ffdcdc 40% 50%, #fff 50% 100%)
        }

        .illus::after {
            content: "";
            display: block;
            height: 100%;
            background:
                radial-gradient(120px 60px at 25% 30%, rgba(255, 255, 255, .7), transparent 70%),
                radial-gradient(80px 40px at 65% 65%, rgba(255, 255, 255, .55), transparent 70%);
            mix-blend-mode: screen;
        }

        /* Specimen table */
        .spec {
            background: #fff;
            border: 2px solid var(--stroke);
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 10px 0 #e9e7f8
        }

        .spec__row {
            display: grid;
            grid-template-columns: 180px 1fr;
            border-top: 1px solid #d8d5ea
        }

        .spec__row:first-child {
            border-top: 0
        }

        .spec__cell {
            padding: 16px
        }

        .spec__label {
            font-weight: 700
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .5ch;
            padding: .25rem .5rem;
            border: 2px solid var(--stroke);
            border-radius: 9999px;
            margin-left: .5rem
        }

        /* Buttons just for vibe */
        .btn {
            border: 2px solid var(--stroke);
            border-radius: 10px;
            font-weight: 700;
            padding: .6rem .9rem;
            background: #fff;
            box-shadow: 0 6px 0 #e05a5a;
            cursor: pointer
        }

        .btn.alt {
            box-shadow: 0 6px 0 #9aa8ff;
            border-color: #263256
        }
    </style>
</head>

<body>
    <div class="wrap">

        @if($debug)
        <div class="pill">Debug: slug={{ $slug }}, v={{ $v }}</div>
        @endif

        <!-- HERO: uses heroTitle / heroLead -->
        <section class="card stack">
            <div class="heroTitle">Curated fonts, shown in real UI.</div>
            <div class="heroLead muted">
                Not sure which fontset to pick? This page previews how your selection styles common UI text blocks.
            </div>
            <div class="stack" style="display:flex;gap:12px;flex-wrap:wrap">

                <span class="content">Fontset: {{ $slug }}</span>
            </div>
        </section>

        <div style="height:24px"></div>

        <!-- Split layout: text card + illustration -->
        <section class="grid">
            <div class="card">
                <div class="title">Grundlagen der absoluten Netzwerksicherheit</div>
                <div class="subtitle">Diese Informationen sind ganz besonders wichtig</div>
                <div class="content">
                    Die Netzwerksicherheit umfasst technische und organisatorische Maßnahmen, um Daten und Systeme vor
                    unbefugtem Zugriff und Angriffen zu schützen. Dazu gehören Firewalls, Verschlüsselung und
                    Intrusion‑Detection‑Systeme. Im Unterricht analysieren wir typische Angriffe, konfigurieren
                    Firewall‑Regeln und lernen, den Datenverkehr zu kontrollieren.
                </div>
                <div class="subcontent muted">*) Hinweistext – nur eine Demo.</div>
            </div>
            <div class="card" aria-hidden="true" style="padding:0;overflow:hidden;align-self:start">
                <img
                    src="/storage/images/motiv.jpg"
                    alt="Motivbild"
                    style="display:block;width:100%;height:240px;object-fit:cover;border-radius:14px;">
            </div>


        </section>

        <div style="height:24px"></div>

        <!-- Specimen: show every class in isolation -->
        <section class="spec">
            <div class="d-flex flex-column gap-12">
                <div class="spec__cell spec__label">heroTitle</div>
                <div class="spec__cell">
                    <div class="heroTitle">The quick brown fox jumps over the lazy dog 0123456789</div>
                </div>
            </div>
            <div class="d-flex flex-column gap-12">
                <div class="spec__cell spec__label">heroLead</div>
                <div class="spec__cell">
                    <div class="heroLead">A concise paragraph for hero sections and landing intros.</div>
                </div>
            </div>
            <div class="d-flex flex-column gap-12">
                <div class="spec__cell spec__label">title</div>
                <div class="spec__cell">
                    <div class="title">Section headline: dense and functional</div>
                </div>
            </div>
            <div class="d-flex flex-column gap-12">
                <div class="spec__cell spec__label">subtitle</div>
                <div class="spec__cell">
                    <div class="subtitle">Secondary headline with supportive tone</div>
                </div>
            </div>
            <div class="d-flex flex-column gap-12">
                <div class="spec__cell spec__label">content</div>
                <div class="spec__cell">
                    <div class="content">Body text for articles and longer explanations. Supports punctuation, Umlauts (ÄÖÜäöü) und ß, sowie Ziffern 0123456789.</div>
                </div>
            </div>
            <div class="d-flex flex-column gap-12">
                <div class="spec__cell spec__label">subcontent</div>
                <div class="spec__cell">
                    <div class="subcontent">Tiny helper notes, disclaimers, captions.</div>
                </div>
            </div>
        </section>

        <div style="height:28px"></div>

        <!-- Footer-ish 
        <section class="stack" style="text-align:center;">
            <span class="muted">Tip: append <code>?debug=1</code> to see the current fontset slug and cache‑buster.</span>
        </section>
        -->
    </div>
</body>

</html>