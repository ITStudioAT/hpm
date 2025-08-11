<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>App-Vorschau</title>

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

        /* Toolbar + Ausrichtung (zentriert oben) */
        .toolbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: #f2f2f2;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .toolbar-left {
            justify-self: start;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .toolbar-center {
            justify-self: center;
        }

        .toolbar-right {
            justify-self: end;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        label {
            font-size: .95rem;
        }

        select {
            padding: .4rem .6rem;
        }

        /* Picker in Toolbar */
        .picker {
            display: flex;
            flex-direction: row;
            gap: 12px;
            align-items: center;
        }

        .picker .trigger {
            border: 2px solid var(--illustrationStroke);
            background: #fff;
            padding: .45rem .7rem;
            cursor: pointer;
        }

        .current-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
        }

        .current-chip .dot {
            width: 14px;
            height: 14px;
            border: 1px solid rgba(0, 0, 0, .2);
        }

        .current-chip .dots {
            display: flex;
            gap: 4px;
        }

        .dots {
            display: flex;
            gap: 4px;
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, .2);
        }

        .popover {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .popover-panel {
            position: absolute;
            z-index: 30;
            top: 110%;
            left: 0;
            background: #fff;
            border: 2px solid var(--illustrationStroke);
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(3, minmax(180px, 1fr));
            gap: 12px;
            max-height: 60vh;
            overflow: auto;
            min-width: 560px;
            box-shadow: 0 10px 0 var(--accent, transparent);
        }

        .popover-panel[hidden] {
            display: none !important;
        }

        @media (max-width:800px) {
            .toolbar {
                grid-template-columns: 1fr;
                grid-auto-rows: auto;
            }

            .toolbar-center {
                order: 1;
                justify-self: center;
            }

            .toolbar-left,
            .toolbar-right {
                order: 2;
                justify-self: stretch;
            }
        }

        @media (max-width:680px) {
            .popover-panel {
                grid-template-columns: 1fr;
                min-width: 320px;
            }
        }

        .opt-card {
            display: flex;
            flex-direction: column;
            gap: 8px;
            border: 2px solid var(--illustrationStroke);
            padding: 10px;
            background: #fafafa;
            cursor: pointer;
        }

        .opt-card:hover {
            outline: 3px solid var(--accent, #ddd);
        }

        .opt-name {
            font-size: .9rem;
            opacity: .9;
        }

        .swatch-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 6px;
        }

        .sw {
            height: 22px;
            border: 1px solid rgba(0, 0, 0, .15);
        }

        .font-demo {
            display: grid;
            gap: 6px;
        }

        .font-Aa {
            font-size: 28px;
            line-height: 1;
        }

        .font-sample {
            font-size: 14px;
            opacity: .9;
        }

        /* Layout / Demo-Seite */
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

        .button {
            display: inline-block;
            padding: .6rem .9rem;
            background: var(--button, #fff);
            color: var(--buttonText, #000);
            border: 2px solid var(--illustrationStroke);
            text-decoration: none;
            box-shadow: 0 6px 0 var(--accent, transparent);
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

        .spacer24 {
            height: 24px;
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <div class="toolbar-left">
            <!-- optional: alte Selects/Labels -->
        </div>

        <div class="toolbar-center">
            <div class="picker">
                <!-- Farbschema -->
                <div class="popover" id="color-pop">
                    <button class="trigger" type="button" aria-haspopup="listbox" aria-expanded="false">🎨 Farbschema wählen</button>
                    <div class="current-chip" id="color-chip" aria-hidden="true" title="Aktuelles Farbschema">
                        <div class="dots">
                            <div class="dot" id="dot-bg"></div>
                            <div class="dot" id="dot-main"></div>
                            <div class="dot" id="dot-sec"></div>
                            <div class="dot" id="dot-ter"></div>
                            <div class="dot" id="dot-hi"></div>
                            <div class="dot" id="dot-btn"></div>
                        </div>
                        <span id="color-name">{{ $color }}</span>
                    </div>
                    <div class="popover-panel" hidden id="color-panel" role="listbox" aria-label="Farbschema"></div>
                </div>

                <!-- Schriftbild -->
                <div class="popover" id="font-pop">
                    <button class="trigger" type="button" aria-haspopup="listbox" aria-expanded="false">🔤 Schriftbild wählen</button>
                    <div class="current-chip" id="font-chip" aria-hidden="true" title="Aktuelles Schriftbild">
                        <div class="font-sample" style="margin:0;">Aa</div>
                        <span id="font-name">{{ $font }}</span>
                    </div>
                    <div class="popover-panel" hidden id="font-panel" role="listbox" aria-label="Schriftbild"></div>
                </div>
            </div>
        </div>

        <div class="toolbar-right">
            <!-- optional -->
        </div>
    </div>

    <!-- Hero -->
    <div class="header">
        <div class="wrap">
            <div class="heroTitle" style="margin-bottom:8px;">Willkommen!</div>
            <div class="heroLead">So sieht Ihre Anwendung mit diesem Stil aus.</div>
            <div class="subcontent note">(highlight / highlightText)</div>
        </div>
    </div>

    <!-- Inhalt -->
    <div class="wrap">
        <section class="section-main">
            <div class="title" style="margin-top:0;">Aktuelles</div>
            <div class="content">Alles Wichtige auf einen Blick – klar strukturiert und gut lesbar.</div>
            <div class="subcontent note">(main, Textfarben: titleColor / contentColor / subcontentColor)</div>

            <div class="spacer24"></div>

            <div class="grid">
                <div class="card">
                    <div class="subtitle" style="margin-top:0;">Neuigkeiten</div>
                    <div class="content">Kurze Hinweise, Ankündigungen und Termine erscheinen hier.</div>
                    <div class="subcontent" style="opacity:.8; margin-top:8px;">Stand: heute</div>
                    <div class="subcontent note">(tertiary / tertiaryText, Rahmen: illustrationStroke)</div>
                </div>

                <div class="card secondary">
                    <div class="subtitle" style="margin-top:0;">Schnellzugriff</div>
                    <div class="content">Direkt zu den häufig genutzten Bereichen wechseln.</div>
                    <div class="row content" style="margin-top:12px; flex-wrap:wrap;">
                        <a class="button" href="#">Übersicht</a>
                        <a class="button" href="#">Dokumente</a>
                        <a class="button" href="#">Kontakt</a>
                    </div>
                    <div class="subcontent note" style="margin-top:8px;">
                        (secondary / secondaryText, Buttons: button / buttonText, Rahmen: illustrationStroke)
                    </div>
                </div>
            </div>
        </section>

        <div class="spacer24"></div>

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
        // Robust starten – egal ob DOM schon ready ist
        function startAll() {
            initFancyPickers().catch(console.error);
            init().catch(console.error);
        }
        if (document.readyState === 'loading') {
            window.addEventListener('DOMContentLoaded', startAll);
        } else {
            startAll();
        }

        // ---------- Helpers ----------
        const $ = sel => document.querySelector(sel);
        const $$ = sel => Array.from(document.querySelectorAll(sel));

        // Colorset-Variablen aus CSS lesen (pro Option) – kein Shadow-Import
        const colorCssCache = new Map();
        async function getColorVars(name) {
            if (colorCssCache.has(name)) return colorCssCache.get(name);
            const res = await fetch(`/api/css/colors/${encodeURIComponent(name)}.css`, {
                headers: {
                    'Accept': 'text/css'
                }
            });
            const css = await res.text();
            const vars = {};
            const re = /--([\w-]+)\s*:\s*([^;]+);/g;
            let m;
            while ((m = re.exec(css)) !== null) vars[m[1]] = m[2].trim();
            colorCssCache.set(name, vars);
            return vars;
        }

        // Normalize backend response to an array of strings
        function normalizeList(payload) {
            const arr = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
            return arr.map(it =>
                (typeof it === 'string') ? it :
                (it.value ?? it.slug ?? it.id ?? it.code ?? it.name ?? it.title)
            ).filter(Boolean);
        }

        // Tell parent (Vue) about the current selection
        function broadcastState() {
            try {
                const u = new URL(location.href);
                const colorset = u.searchParams.get('colorset') || 'default';
                const fontset = u.searchParams.get('fontset') || 'default';
                window.parent?.postMessage({
                    type: 'color-fontset-selection',
                    colorset,
                    fontset
                }, '*');
            } catch {}
        }

        function applyColorPreviewToCard(root, vars) {
            const pick = (key, fallback = '#ccc') => (vars[key] || fallback);
            const map = [
                ['--background', 'background'],
                ['--main', 'main'],
                ['--secondary', 'secondary'],
                ['--tertiary', 'tertiary'],
                ['--highlight', 'highlight'],
                ['--button', 'button'],
            ];
            root.querySelectorAll('.dot').forEach((el, i) => {
                const [varName] = map[i];
                const val = pick(varName.replace(/^--/, ''), '#ccc');
                el.style.background = val;
                el.title = `${varName}: ${val}`;
            });
            const bars = root.querySelectorAll('.sw');
            bars.forEach((bar, i) => {
                const [varName] = map[i];
                const val = pick(varName.replace(/^--/, ''), '#ccc');
                bar.style.background = val;
                bar.title = `${varName}: ${val}`;
            });
            const txt = root.querySelector('.font-sample');
            if (txt) txt.style.color = pick('contentColor', '#333');
        }

        function closePanel(popSel, panelSel) {
            const pop = $(popSel);
            const panel = $(panelSel);
            panel.hidden = true;
            pop.querySelector('.trigger')?.setAttribute('aria-expanded', 'false');
        }

        function closeAllPanels() {
            $$('.popover-panel').forEach(p => p.hidden = true);
            $$('.popover .trigger').forEach(b => b.setAttribute('aria-expanded', 'false'));
        }

        function openPanel(popSel, panelSel) {
            closeAllPanels();
            const pop = $(popSel);
            const panel = $(panelSel);
            panel.hidden = false;
            pop.querySelector('.trigger')?.setAttribute('aria-expanded', 'true');
        }

        function togglePanel(popSel, panelSel) {
            const panel = $(panelSel);
            if (panel.hidden) openPanel(popSel, panelSel);
            else closePanel(popSel, panelSel);
            if (!panel.hidden) {
                const off = (e) => {
                    if (!$(popSel).contains(e.target)) {
                        closePanel(popSel, panelSel);
                        document.removeEventListener('click', off);
                    }
                };
                setTimeout(() => document.addEventListener('click', off), 0);
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeAllPanels();
        });

        function makeOptionA11y(card, onSelect) {
            card.setAttribute('tabindex', '0');
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    onSelect();
                }
            });
        }

        // --------- Karten ----------
        function createColorCard(name) {
            const card = document.createElement('button');
            card.type = 'button';
            card.className = 'opt-card';
            card.setAttribute('role', 'option');
            card.setAttribute('aria-label', name);

            const host = document.createElement('div');
            card.appendChild(host);
            const root = host.attachShadow({
                mode: 'open'
            });

            const style = document.createElement('style');
            style.textContent = `
        .dots{ display:flex; gap:4px; align-items:center; }
        .dot{ width:14px; height:14px; border:1px solid rgba(0,0,0,.2); border-radius:50%; }
        .swatch-row{ display:grid; grid-template-columns:repeat(6,1fr); gap:6px; margin-top:6px; }
        .sw{ height:22px; border:1px solid rgba(0,0,0,.15); }
        .font-sample{ font-size:14px; opacity:.9; margin-top:6px; }
      `;
            root.appendChild(style);

            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
        <div class="dots">
          <div class="dot"></div><div class="dot"></div><div class="dot"></div>
          <div class="dot"></div><div class="dot"></div><div class="dot"></div>
        </div>
        <div class="swatch-row" aria-hidden="true">
          <div class="sw"></div><div class="sw"></div><div class="sw"></div>
          <div class="sw"></div><div class="sw"></div><div class="sw"></div>
        </div>
        <div class="font-sample">Beispieltext</div>
      `;
            root.appendChild(wrapper);

            const label = document.createElement('div');
            label.className = 'opt-name';
            label.textContent = name;
            card.appendChild(label);

            // Farben sofort laden/anzeigen
            getColorVars(name).then(vars => applyColorPreviewToCard(root, vars)).catch(() => {});

            const select = () => {
                swapCss('colorset-css', `/api/css/colors/${encodeURIComponent(name)}.css`)
                    .then(() => updateCurrentColorDots()); // nach Lade-Event
                syncQuery({
                    colorset: name
                });
                document.querySelector('#color-name').textContent = name;
                closeAllPanels();
                document.querySelector('#color-pop .trigger')?.focus();
                broadcastState(); // NEW: inform parent after click selection
            };
            card.addEventListener('click', (e) => {
                e.stopPropagation();
                select();
            });
            makeOptionA11y(card, select);
            return card;
        }

        function createFontCard(name) {
            const card = document.createElement('button');
            card.type = 'button';
            card.className = 'opt-card';
            card.setAttribute('role', 'option');
            card.setAttribute('aria-label', name);

            const host = document.createElement('div');
            card.appendChild(host);
            const root = host.attachShadow({
                mode: 'open'
            });

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `/api/css/fontset/${encodeURIComponent(name)}.css`;
            root.appendChild(link);

            const demo = document.createElement('div');
            demo.className = 'font-demo';
            demo.innerHTML = `
        <div class="font-Aa heroTitle">Aa</div>
        <div class="font-sample content">Grumpy wizards make toxic brew.</div>
        <div class="font-sample subcontent">1234567890 — !?@€%&</div>
      `;
            root.appendChild(demo);

            const label = document.createElement('div');
            label.className = 'opt-name';
            label.textContent = name;
            card.appendChild(label);

            const select = () => {
                swapCss('fontset-css', `/api/css/fontset/${encodeURIComponent(name)}.css`);
                syncQuery({
                    fontset: name
                });
                $('#font-name').textContent = name;
                closeAllPanels();
                $('#font-pop .trigger')?.focus();
                broadcastState(); // NEW: inform parent after click selection
            };
            card.addEventListener('click', (e) => {
                e.stopPropagation();
                select();
            });
            makeOptionA11y(card, select);
            return card;
        }

        // ---------- Status / Init ----------
        function updateCurrentColorDots() {
            const styles = getComputedStyle(document.documentElement);
            const map = {
                '#dot-bg': '--background',
                '#dot-main': '--main',
                '#dot-sec': '--secondary',
                '#dot-ter': '--tertiary',
                '#dot-hi': '--highlight',
                '#dot-btn': '--button'
            };
            Object.entries(map).forEach(([sel, varName]) => {
                const v = styles.getPropertyValue(varName).trim();
                const el = $(sel);
                if (el) el.style.background = v || '#ccc';
            });
        }

        async function initFancyPickers() {
            const [colorsRaw, fontsRaw] = await Promise.all([getJson('/api/colorsets'), getJson('/api/fontsets')]);
            const colors = normalizeList(colorsRaw);
            const fonts = normalizeList(fontsRaw);

            const cPanel = $('#color-panel');
            colors.forEach(n => cPanel.appendChild(createColorCard(n)));
            const fPanel = $('#font-panel');
            fonts.forEach(n => fPanel.appendChild(createFontCard(n)));

            $('#color-pop .trigger').addEventListener('click', () => togglePanel('#color-pop', '#color-panel'));
            $('#font-pop  .trigger').addEventListener('click', () => togglePanel('#font-pop', '#font-panel'));

            closeAllPanels();
            updateCurrentColorDots();
            broadcastState(); // announce initial state to parent
        }

        // ---- Utilities / bestehend ----
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

        // Warten bis CSS-Link geladen ist (für zuverlässige Dots)
        function swapCss(id, href) {
            return new Promise((resolve) => {
                const link = document.getElementById(id);
                const newHref = cacheBust(href);
                if (link.getAttribute('href') === newHref) {
                    resolve();
                    return;
                }
                const done = () => {
                    link.removeEventListener('load', done);
                    link.removeEventListener('error', done);
                    resolve();
                };
                link.addEventListener('load', done, {
                    once: true
                });
                link.addEventListener('error', done, {
                    once: true
                });
                link.setAttribute('href', newHref);
            });
        }

        function syncQuery(p) {
            const u = new URL(location.href);
            Object.entries(p).forEach(([k, v]) => u.searchParams.set(k, v));
            history.replaceState(null, '', u);
        }

        async function fillSelect(id, items, current) {
            const el = document.getElementById(id);
            if (!el) return;
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
            const [colorsRaw, fontsRaw] = await Promise.all([getJson('/api/colorsets'), getJson('/api/fontsets')]);
            const colors = normalizeList(colorsRaw);
            const fonts = normalizeList(fontsRaw);

            const colorNow = colors.includes(qs('colorset', '{{ $color }}')) ? qs('colorset', '{{ $color }}') : (colors[0] ?? 'default');
            const fontNow = fonts.includes(qs('fontset', '{{ $font }}')) ? qs('fontset', '{{ $font }}') : (fonts[0] ?? 'default');

            const palette = await fillSelect('palette', colors, colorNow);
            const fontset = await fillSelect('fontset', fonts, fontNow);

            syncQuery({
                colorset: colorNow,
                fontset: fontNow
            });
            broadcastState(); // also after syncing URL

            palette?.addEventListener('change', () => {
                swapCss('colorset-css', `/api/css/colors/${encodeURIComponent(palette.value)}.css`)
                    .then(() => updateCurrentColorDots());
                syncQuery({
                    colorset: palette.value
                });
                broadcastState(); // inform parent
            });
            fontset?.addEventListener('change', () => {
                swapCss('fontset-css', `/api/css/fontset/${encodeURIComponent(fontset.value)}.css`);
                syncQuery({
                    fontset: fontset.value
                });
                broadcastState(); // inform parent
            });
        }
    </script>
</body>

</html>