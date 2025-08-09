<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>App‑Vorschau</title>

    @php
    $color = request('colorset','default');
    $font = request('fontset','default');
    $v = request('t') ?? time();
    @endphp

    <!-- Colors & Fonts -->
    <link id="colorset-css" rel="stylesheet" href="/api/css/colors/{{ rawurlencode($color) }}.css?v={{ $v }}">
    <link rel="stylesheet" href="/fonts/fonts.css">
    <link id="fontset-css" rel="stylesheet" href="/api/css/fontset/{{ rawurlencode($font) }}.css?v={{ $v }}">

    <style>
        * {
            border-radius: 0 !important;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: var(--background);
            color: var(--contentColor);
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 10px 16px;
            background: #f2f2f2;
        }

        .toolbar .spacer {
            margin-left: auto;
        }

        .header {
            background: var(--highlight);
            color: var(--highlightText);
            border-bottom: 2px solid var(--illustrationStroke);
        }

        .header .wrap {
            padding: 24px 20px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .wrap {
            padding: 28px 20px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .grid {
            display: grid;
            gap: 24px;
            grid-template-columns: 1fr;
        }

        @media (min-width:980px) {
            .grid {
                grid-template-columns: 1.2fr .8fr;
            }
        }

        .section-main {
            background: var(--main);
            border: 2px solid var(--illustrationStroke);
            padding: 24px;
        }

        /* Force matching text colors inside secondary and tertiary cards */
        .card.secondary {
            background: var(--secondary);
            color: var(--secondaryText) !important;
            border: 2px solid var(--illustrationStroke);
            padding: 20px;
        }

        .card.secondary .subtitle,
        .card.secondary .content,
        .card.secondary .subcontent {
            color: var(--secondaryText) !important;
        }

        .card {
            background: var(--tertiary);
            color: var(--tertiaryText) !important;
            border: 2px solid var(--illustrationStroke);
            padding: 20px;
        }

        .card .subtitle,
        .card .content,
        .card .subcontent {
            color: var(--tertiaryText) !important;
        }

        /* Typography hooks from your fontset */
        .heroTitle {
            color: var(--heroTitleColor);
        }

        .heroLead {
            color: var(--heroLeadColor);
        }

        .title {
            color: var(--titleColor);
        }

        .subtitle {
            color: var(--subtitleColor);
        }

        .content {
            color: var(--contentColor);
        }

        .subcontent {
            color: var(--subcontentColor);
        }

        /* Notes */
        .note {
            margin-top: 6px;
            opacity: .85;
        }

        .row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
        }

        .row>* {
            white-space: nowrap;
        }

        /* Button uses same font as normal content */
        .button {
            display: inline-block;
            padding: .6rem .9rem;
            background: var(--button, #fff);
            color: var(--buttonText, #000);
            border: 2px solid var(--illustrationStroke);
            text-decoration: none;
            box-shadow: 0 6px 0 var(--accent, transparent);
            /* key: inherit whatever the parent uses */
            font-family: inherit !important;
            font-size: inherit;
            line-height: inherit;
            font-style: inherit;
            font-weight: inherit;
        }


        .hero-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border: 2px solid var(--illustrationStroke);
        }

        label {
            font-size: .95rem;
        }

        select {
            padding: .4rem .6rem;
        }

        .spacer24 {
            height: 24px;
        }
    </style>
</head>

<body>

    <!-- Auswahlleiste -->
    <div class="toolbar">
        <label for="palette">Farbschema:</label>
        <select id="palette"></select>

        <label for="fontset">Schriftbild:</label>
        <select id="fontset"></select>

        <div class="spacer"></div>
        <div class="row content">
            <span class="content">Colorset: {{ $color }}</span>
            <span class="content">Fontset: {{ $font }}</span>
            <!-- content class ensures content typography; .button inherits it -->
            <a class="button content" href="#">Jetzt starten</a>
        </div>
    </div>

    <!-- Hero (highlight / highlightText) -->
    <div class="header">
        <div class="wrap">
            <div class="heroTitle" style="margin-bottom:8px;">Willkommen!</div>
            <div class="heroLead">So sieht Ihre Anwendung mit diesem Stil aus.</div>
            <div class="subcontent note">(highlight / highlightText)</div>
        </div>
    </div>

    <div class="wrap">
        <!-- Hauptbereich (main) -->
        <section class="section-main">
            <div class="title" style="margin-top:0;">Aktuelles</div>
            <div class="content">Alles Wichtige auf einen Blick – klar strukturiert und gut lesbar.</div>
            <div class="subcontent note">(main, Textfarben: titleColor / contentColor / subcontentColor)</div>

            <div class="spacer24"></div>

            <div class="grid">
                <!-- Karte: tertiary / tertiaryText -->
                <div class="card">
                    <div class="subtitle" style="margin-top:0;">Neuigkeiten</div>
                    <div class="content">Kurze Hinweise, Ankündigungen und Termine erscheinen hier.</div>
                    <div class="subcontent" style="opacity:.8; margin-top:8px;">Stand: heute</div>
                    <div class="subcontent note">(tertiary / tertiaryText, Rahmen: illustrationStroke)</div>
                </div>

                <!-- Karte: secondary / secondaryText -->
                <div class="card secondary">
                    <div class="subtitle" style="margin-top:0;">Schnellzugriff</div>
                    <div class="content">Direkt zu den häufig genutzten Bereichen wechseln.</div>
                    <div class="row content" style="margin-top:12px; flex-wrap:wrap;">
                        <a class="button " href="#">Übersicht</a>
                        <a class="button " href="#">Dokumente</a>
                        <a class="button " href="#">Kontakt</a>
                    </div>
                    <div class="subcontent note" style="margin-top:8px;">
                        (secondary / secondaryText, Buttons: button / buttonText, Rahmen: illustrationStroke)
                    </div>
                </div>
            </div>
        </section>

        <div class="spacer24"></div>

        <!-- Bildkarte (illustrationStroke) -->
        <section class="grid">
            <div class="card">
                <div class="subtitle" style="margin-top:0;">Information</div>
                <div class="content">
                    Klare Überschriften, angenehme Fließtexte und dezente Hinweistexte – alles im gewählten Stil.
                </div>
                <div class="subcontent" style="opacity:.8; margin-top:8px;">Beispieltext zur Illustration.</div>
                <div class="subcontent note">(Textfarben: titleColor / contentColor / subcontentColor)</div>
            </div>

            <div class="card" aria-hidden="true" style="padding:0; overflow:hidden; align-self:start;">
                <img src="/storage/images/motiv.jpg" alt="Motivbild" class="hero-img">
                <div class="subcontent" style="padding:10px 12px;">
                    (Bildrahmen: illustrationStroke)
                </div>
            </div>
        </section>
    </div>

    <script>
        const qs = (k, d) => new URL(location.href).searchParams.get(k) ?? d;

        async function getJson(url) {
            const r = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!r.ok) throw new Error('HTTP ' + r.status + ' ' + url);
            return r.json();
        }

        function cacheBust(url) {
            const u = new URL(url, location.origin);
            u.searchParams.set('v', Date.now());
            return u.toString();
        }

        function swapCss(id, href) {
            document.getElementById(id).href = cacheBust(href);
        }

        function syncQuery(p) {
            const u = new URL(location.href);
            Object.entries(p).forEach(([k, v]) => u.searchParams.set(k, v));
            history.replaceState(null, '', u);
        }

        async function fillSelect(id, items, current) {
            const el = document.getElementById(id);
            el.innerHTML = '';
            items.forEach(n => {
                const o = document.createElement('option');
                o.value = n;
                o.textContent = n;
                if (n === current) o.selected = true;
                el.appendChild(o);
            });
            return el;
        }

        async function init() {
            const [colors, fonts] = await Promise.all([
                getJson('/api/colorsets'),
                getJson('/api/fontsets')
            ]);

            const colorNow = colors.includes(qs('colorset', '{{ $color }}')) ? qs('colorset', '{{ $color }}') : (colors[0] ?? 'default');
            const fontNow = fonts.includes(qs('fontset', '{{ $font }}')) ? qs('fontset', '{{ $font }}') : (fonts[0] ?? 'default');

            const palette = await fillSelect('palette', colors, colorNow);
            const fontset = await fillSelect('fontset', fonts, fontNow);

            syncQuery({
                colorset: colorNow,
                fontset: fontNow
            });

            palette.addEventListener('change', () => {
                swapCss('colorset-css', `/api/css/colors/${encodeURIComponent(palette.value)}.css`);
                syncQuery({
                    colorset: palette.value
                });
            });
            fontset.addEventListener('change', () => {
                swapCss('fontset-css', `/api/css/fontset/${encodeURIComponent(fontset.value)}.css`);
                syncQuery({
                    fontset: fontset.value
                });
            });
        }

        init().catch(console.error);
    </script>
</body>

</html>