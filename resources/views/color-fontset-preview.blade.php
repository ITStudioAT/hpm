<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Typography & Colors Preview</title>

    @php
    // Slugs aus Query (wenn vorhanden)
    $colorsetSlug = request('colorset');
    $fontsetSlug = request('fontset');
    $v = request('v') ?? time();
    @endphp

    <!-- Fonts immer global laden -->
    <link rel="stylesheet" href="{{ url('/fonts/fonts.css') }}?v={{ $v }}">
    <!-- Wenn Slugs vorhanden: echte CSS-Dateien laden -->
    @if($colorsetSlug)
    <link rel="stylesheet" href="{{ url('/api/css/colors/'.rawurlencode($colorsetSlug).'.css') }}?v={{ $v }}">
    @endif
    @if($fontsetSlug)
    <link rel="stylesheet" href="{{ url('/api/css/fontset/'.rawurlencode($fontsetSlug).'.css') }}?v={{ $v }}">
    @endif

    @php
    // --- Farb-Fallback (neue Keys) ---
    $fallback = [
    "backgroundBackground" => "#FAF3E0",
    "backgroundText" => "#4B2E23",
    "mainBackground" => "#F5E6C8",
    "mainText" => "#4B2E23",
    "heroTitleText" => "#5E3023",
    "heroLeadText" => "#E6C68E",
    "titleText" => "#5E3023",
    "subtitleText" => "#8B5E3C",
    "contentText" => "#4B2E23",
    "subcontentText" => "#7A5C46",
    "buttonBackground" => "#C89F5D",
    "buttonText" => "#FFFFFF",
    "firstBackground" => "#A97142",
    "firstText" => "#FFFFFF",
    "secondBackground" => "#E6C68E",
    "secondText" => "#4B2E23",
    "thirdBackground" => "#D9B48F",
    "thirdText" => "#4B2E23",
    "strokeColor" => "#5E3023",
    ];

    // --- $colors aus Controller/Query robust mergen ---
    $fromController = [];
    if (isset($colors)) {
    if (is_string($colors)) $colors = json_decode($colors, true) ?: [];
    if (is_array($colors)) $fromController = $colors;
    }
    $fromQuery = [];
    if ($qp = request('colors')) {
    $decoded = json_decode($qp, true);
    if (is_array($decoded)) $fromQuery = $decoded;
    }
    $colors = array_merge($fallback, $fromController, $fromQuery);

    // --- Sichere CSS-Variablen erzeugen (als fertige :root-Regel) ---
    $decls = [];
    foreach ($colors as $k => $v) {
    $safeK = preg_replace('/[^a-zA-Z0-9_-]/', '', $k);
    $decls[] = "--{$safeK}: {$v}";
    }
    $rootCss = ':root{'.implode(';', $decls).';}';

    // --- Realistische Texte je Klasse (für alle Varianten gleich) ---
    $textSamples = [
    'text-heroTitle' => 'Digitale Lösungen, die begeistern.',
    'text-heroLead' => 'Wir planen, gestalten und entwickeln Websites und Apps für Bildung und Unternehmen – schnell, barrierearm und wartungsfreundlich.',
    'text-title' => 'Unsere Leistungen',
    'text-subtitle' => 'Webdesign, Branding & Entwicklung',
    'text-content' => 'Von der ersten Idee bis zum Go-Live begleiten wir Ihr Projekt. Wir kombinieren klares Design mit stabiler Technik und achten auf Performance sowie SEO-Basics.',
    'text-subcontent' => 'Hinweis: Wir arbeiten agil mit kurzen Feedback-Schleifen und transparenten Angeboten.',
    ];
    $textOrder = ['text-heroTitle','text-heroLead','text-title','text-subtitle','text-content','text-subcontent'];

    // --- Varianten ---
    $variants = [
    ['key' => 'standard', 'label' => 'Standard', 'class' => 'variant--standard', 'bgVar' => 'mainBackground', 'textVar' => 'mainText'],
    ['key' => 'first', 'label' => 'First', 'class' => 'variant--first', 'bgVar' => 'firstBackground', 'textVar' => 'firstText'],
    ['key' => 'second', 'label' => 'Second', 'class' => 'variant--second', 'bgVar' => 'secondBackground', 'textVar' => 'secondText'],
    ['key' => 'third', 'label' => 'Third', 'class' => 'variant--third', 'bgVar' => 'thirdBackground', 'textVar' => 'thirdText'],
    ];
    @endphp

    <!-- 1) Nur die Farb-Variablen als fertiges CSS (kein Blade im CSS) -->
    @if(!$colorsetSlug)
    <style id="color-vars">
        <?= $rootCss ?>
    </style>
    @endif

    <!-- 2) Statische Styles (valide CSS) -->
    <style>
        /* Aliase (alt -> neu) in eigenem :root-Block */
        :root {
            --background: var(--backgroundBackground);
            --main: var(--mainBackground);
            --heroTitleColor: var(--heroTitleText);
            --heroLeadColor: var(--heroLeadText);
            --titleColor: var(--titleText);
            --subtitleColor: var(--subtitleText);
            --contentColor: var(--contentText);
            --subcontentColor: var(--subcontentText);
            --button: var(--buttonBackground);
            --highlight: var(--firstBackground);
            --highlightText: var(--firstText);
            --secondary: var(--secondBackground);
            --secondaryText: var(--secondText);
            --tertiary: var(--thirdBackground);
            --tertiaryText: var(--thirdText);
            --illustrationStroke: var(--strokeColor);
        }

        /* Utilities (neu) – Farben anwenden */
        .background {
            background-color: var(--backgroundBackground);
            color: var(--backgroundText);
        }

        .main {
            background-color: var(--mainBackground);
            color: var(--mainText);
        }

        .text-heroTitle {
            color: var(--heroTitleText);
        }

        .text-heroLead {
            color: var(--heroLeadText);
        }

        .text-title {
            color: var(--titleText);
        }

        .text-subtitle {
            color: var(--subtitleText);
        }

        .text-content {
            color: var(--contentText);
        }

        .text-subcontent {
            color: var(--subcontentText);
        }

        .button {
            color: var(--buttonText);
            background-color: var(--buttonBackground);
            display: inline-block;
            padding: .6rem .9rem;
            border: 2px solid var(--strokeColor);
            text-decoration: none;
            border-radius: .6rem;
        }

        .text-button {
            color: var(--buttonText);
        }

        .bg-button {
            background-color: var(--buttonBackground);
        }

        .first {
            color: var(--firstText);
            background-color: var(--firstBackground);
        }

        .second {
            color: var(--secondText);
            background-color: var(--secondBackground);
        }

        .third {
            color: var(--thirdText);
            background-color: var(--thirdBackground);
        }

        .text-first {
            color: var(--firstText) !important;
        }

        .text-second {
            color: var(--secondText) !important;
        }

        .text-third {
            color: var(--thirdText) !important;
        }

        .bg-first {
            background-color: var(--firstBackground);
        }

        .bg-second {
            background-color: var(--secondBackground);
        }

        .bg-third {
            background-color: var(--thirdBackground);
        }

        .stroke {
            border-color: var(--strokeColor);
        }

        /* Layout */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans",
                "Apple Color Emoji", "Segoe UI Emoji";
        }

        .wrap {
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        .hero {
            padding: 3.5rem 1.25rem;
            border-bottom: 4px solid var(--strokeColor);
            display: grid;
            gap: 1.25rem;
            place-items: center;
            text-align: center;
            background: var(--mainBackground);
            color: var(--mainText);
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(2rem, 3vw + 1rem, 3rem);
        }

        .hero p {
            margin: 0;
            max-width: 70ch;
            font-size: clamp(1rem, 1.2vw + .6rem, 1.35rem);
        }

        .hero .img {
            width: min(100%, 980px);
            aspect-ratio: 21/9;
            object-fit: cover;
            border: 3px solid var(--strokeColor);
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .09);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.25rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .panel {
            border: 2px solid var(--strokeColor);
            border-radius: 18px;
            overflow: hidden;
            background: rgba(255, 255, 255, .08);
        }

        .panel__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .9rem 1rem;
            background: rgba(255, 255, 255, .12);
            border-bottom: 2px solid var(--strokeColor);
        }

        .panel__body {
            display: grid;
            gap: .75rem;
            padding: 1rem;
        }

        .img-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .thumb {
            width: min(100%, 520px);
            aspect-ratio: 16/9;
            object-fit: cover;
            border: 2px solid var(--strokeColor);
            border-radius: 12px;
        }

        .stack {
            display: grid;
            gap: .35rem;
        }

        .badge {
            font-size: .85rem;
            padding: .25rem .55rem;
            border-radius: 999px;
            border: 2px solid var(--strokeColor);
            opacity: .85;
        }

        /* Varianten via lokale Variablen */
        .variant {
            background: var(--_variantBg);
            color: var(--_variantText);
        }

        .variant--standard {
            --_variantBg: var(--mainBackground);
            --_variantText: var(--mainText);
        }

        .variant--first {
            --_variantBg: var(--firstBackground);
            --_variantText: var(--firstText);
        }

        .variant--second {
            --_variantBg: var(--secondBackground);
            --_variantText: var(--secondText);
        }

        .variant--third {
            --_variantBg: var(--thirdBackground);
            --_variantText: var(--thirdText);
        }

        /* Erzwinge Textfarben je Variante */
        .variant .text-heroTitle,
        .variant .text-heroLead,
        .variant .text-title,
        .variant .text-subtitle,
        .variant .text-content,
        .variant .text-subcontent {
            color: var(--_variantText) !important;
        }

        /* --- Kanten eckig (Overrides) --- */
        .button,
        .thumb,
        .panel,
        .panel__head,
        .hero .img,
        .badge {
            border-radius: 0 !important;
        }
    </style>

    @php
    // --- Fontset-Fallback (JSON-Struktur) ---
    $fontFallback = [
    "heroTitle" => ["fontFamily"=>"Roboto, sans-serif","fontWeight"=>700,"fontSize"=>"clamp(32px, 6vw, 64px)","lineHeight"=>"1.1","letterSpacing"=>"-0.02em","marginBottom"=>"16px"],
    "heroLead" => ["fontFamily"=>"Roboto, sans-serif","fontWeight"=>400,"fontSize"=>"clamp(18px, 2.5vw, 22px)","lineHeight"=>"1.5","marginBottom"=>"24px"],
    "title" => ["fontFamily"=>"Roboto, sans-serif","fontWeight"=>500,"fontSize"=>"clamp(28px, 4vw, 32px)","lineHeight"=>"clamp(36px, 5vw, 40px)","fontStyle"=>"normal","marginBottom"=>"16px"],
    "subtitle" => ["fontFamily"=>"Roboto, sans-serif","fontWeight"=>500,"fontSize"=>"clamp(22px, 3vw, 24px)","lineHeight"=>"clamp(28px, 4vw, 30px)","fontStyle"=>"normal","marginBottom"=>"8px"],
    "content" => ["fontFamily"=>"Roboto, sans-serif","fontWeight"=>400,"fontSize"=>"clamp(14px, 1.4vw, 16px)","lineHeight"=>"clamp(18px, 2.2vw, 20px)","fontStyle"=>"normal"],
    "subcontent"=> ["fontFamily"=>"Roboto, sans-serif","fontWeight"=>300,"fontSize"=>"clamp(13px, 1.2vw, 14px)","lineHeight"=>"clamp(16px, 2vw, 18px)","fontStyle"=>"normal"],
    ];

    // --- Fontset aus Controller/Query mergen (optional) ---
    $fontFromCtrl = [];
    if (isset($fontset)) {
    if (is_string($fontset)) $fontset = json_decode($fontset, true) ?: [];
    if (is_array($fontset)) $fontFromCtrl = $fontset;
    }
    $fontFromQuery = [];
    if ($fp = request('fontset')) {
    $decoded = json_decode($fp, true);
    if (is_array($decoded)) $fontFromQuery = $decoded;
    }
    $fontSpec = array_replace_recursive($fontFallback, $fontFromCtrl, $fontFromQuery);

    // --- Erlaubte Properties → CSS-Namen ---
    $allowedProps = ['fontFamily','fontWeight','fontSize','lineHeight','letterSpacing','fontStyle','marginBottom'];
    $toKebab = function(string $p){ return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $p)); };

    // --- Mapping Fontset-Key → CSS-Selector ---
    $selectorMap = [
    'heroTitle' => '.text-heroTitle',
    'heroLead' => '.text-heroLead',
    'title' => '.text-title',
    'subtitle' => '.text-subtitle',
    'content' => '.text-content',
    'subcontent'=> '.text-subcontent',
    ];

    // --- CSS-Regeln für das Fontset generieren ---
    $fontCss = '';
    foreach ($selectorMap as $key => $selector) {
    if (!isset($fontSpec[$key]) || !is_array($fontSpec[$key])) continue;
    $rules = [];
    foreach ($allowedProps as $prop) {
    if (!array_key_exists($prop, $fontSpec[$key])) continue;
    $val = $fontSpec[$key][$prop];
    if (is_bool($val)) { $val = $val ? 'true' : 'false'; }
    elseif (is_numeric($val)) { $val = (string)$val; }
    elseif (!is_string($val)) { continue; }
    $rules[] = $toKebab($prop) . ':' . $val;
    }
    if ($rules) $fontCss .= $selector . '{' . implode(';', $rules) . ';}';
    }
    @endphp

    <!-- 3) Fontset-Regeln nur ausgeben, wenn kein Slug geladen wurde -->
    @if(!$fontsetSlug)
    <style id="fontset-rules">
        <?= $fontCss ?>
    </style>
    @endif
</head>

<body class="background">
    <div class="wrap">
        <!-- HERO -->
        <header class="hero">
            <h1 class="text-heroTitle">{{ $textSamples['text-heroTitle'] }}</h1>
            <p class="text-heroLead">{{ $textSamples['text-heroLead'] }}</p>
            <img class="img" src="/storage/images/motiv.jpg" alt="Stimmungsbild" loading="lazy">
        </header>

        <main class="container">
            <div class="grid">
                @foreach($variants as $v)
                <section class="panel variant {{ $v['class'] }} stroke">
                    <div class="panel__head">
                        <strong class="text-title">{{ $v['label'] }}</strong>
                        <span class="badge">{{ $v['bgVar'] }} / {{ $v['textVar'] }}</span>
                    </div>
                    <div class="panel__body">
                        <div class="img-row">
                            <img class="thumb" src="/storage/images/motiv.jpg" alt="Motiv {{ $v['label'] }}" loading="lazy">
                            <div class="stack">
                                @foreach($textOrder as $cls)
                                <div class="{{ $cls }}">{{ $textSamples[$cls] }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                @endforeach
            </div>
        </main>

        <footer class="text-subcontent">
            Vorschau • Colorset: {{ $colorsetSlug ?? 'fallback' }}, Fontset: {{ $fontsetSlug ?? 'fallback' }} • {{ now() }}
        </footer>
    </div>
</body>

</html>