<template>
    <div class="preview-wrap">
        <div class="device-grid">
            <!-- Desktop -->
            <div class="device-col">
                <div class="device-head">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="label">Desktop</div>
                </div>
                <iframe ref="desktop" class="device-frame"
                    :style="{ width: desktopWidth + 'px', height: desktopHeight + 'px' }"
                    title="Desktop Preview"></iframe>
            </div>

            <!-- Handy -->
            <div class="device-col">
                <div class="device-head">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="label">Handy</div>
                </div>
                <iframe ref="mobile" class="device-frame phone"
                    :style="{ width: mobileWidth + 'px', height: mobileHeight + 'px' }" title="Mobile Preview"></iframe>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ThemeLivePreview",
    props: {
        colorset: { type: String, required: true },
        fontset: { type: String, required: true },
    },
    data() {
        return {
            desktopWidth: 1200,
            mobileWidth: 390, // iPhone 12/13/14-ish
            desktopHeight: 760,
            mobileHeight: 760,
            _debounce: null,
        };
    },
    watch: {
        colorset() { this.debouncedRender(); },
        fontset() { this.debouncedRender(); },
    },
    mounted() {
        this.renderBoth();
        window.addEventListener("resize", this.autoScale);
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.autoScale);
    },
    methods: {
        debouncedRender() {
            clearTimeout(this._debounce);
            this._debounce = setTimeout(this.renderBoth, 120);
        },

        async renderBoth() {
            await Promise.all([this.renderDesktop(), this.renderMobile()]);
            this.autoScale();
        },

        autoScale() {
            // passt Höhe an den Inhalt an
            const fit = (iframe, setHeight) => {
                try {
                    const doc = iframe.contentDocument;
                    const h = Math.max(
                        doc.body.scrollHeight,
                        doc.documentElement.scrollHeight,
                        doc.body.offsetHeight,
                        doc.documentElement.offsetHeight
                    );
                    setHeight(Math.min(Math.max(h, 560), 1200)); // clamp
                } catch { /* ignore */ }
            };
            if (this.$refs.desktop) fit(this.$refs.desktop, (h) => (this.desktopHeight = h));
            if (this.$refs.mobile) fit(this.$refs.mobile, (h) => (this.mobileHeight = h));
        },

        renderDesktop() {
            return this.renderIntoIframe(this.$refs.desktop, { mode: "desktop" });
        },
        renderMobile() {
            return this.renderIntoIframe(this.$refs.mobile, { mode: "mobile" });
        },

        buildHtml({ mode }) {
            const colorsHref = `/api/css/colors/${encodeURIComponent(this.colorset)}.css`;
            const fontsetHref = `/api/css/fontset/${encodeURIComponent(this.fontset)}.css`;
            const fontsCore = `/fonts/fonts.css`;
            const bindings = `/css/colors.css`; // deine Bindings (background/main/first/..., heroTitle/content/...)

            const isMobile = mode === "mobile";

            // Demo-Inhalt: Hero, Bild, Cards, Farbbereiche, Buttons
            // Klassen: heroTitle, heroLead, title, subtitle, content, subcontent, button
            const html = `
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Live Preview</title>
<link rel="stylesheet" href="${fontsCore}">
<link rel="stylesheet" href="${fontsetHref}">
<link rel="stylesheet" href="${colorsHref}">
<link rel="stylesheet" href="${bindings}">
<style>
  :root { --radius: 20px; --gap: 16px; }

  /* Grundlayout */
  body.background { margin: 0; min-height: 100vh; }
  .shell { max-width: ${isMobile ? 360 : 1120}px; margin: 0 auto; padding: ${isMobile ? 12 : 24}px; display: grid; gap: var(--gap); }
  .card { background: var(--mainBackground, #fff); color: var(--mainText, #111); border: 1px solid var(--strokeColor, rgba(0,0,0,.1)); border-radius: var(--radius); padding: ${isMobile ? 14 : 20}px; box-shadow: 0 10px 30px rgba(0,0,0,.05); }

  /* Hero */
  .hero { position: relative; overflow: hidden; border-radius: var(--radius); display: grid; }
  .hero .media { width: 100%; height: ${isMobile ? 180 : 320}px; object-fit: cover; display: block; filter: saturate(1.05); }
  .hero .overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.15), rgba(0,0,0,0.45)); }
  .hero .inner { position: absolute; inset: 0; display: grid; align-content: end; padding: ${isMobile ? 14 : 24}px; gap: 8px; }
  .badge { display:inline-block; padding: 6px 10px; border-radius: 999px; background: var(--firstBackground, #eee); color: var(--firstText, #222); font-weight: 700; border: 1px solid var(--strokeColor, rgba(0,0,0,.12)); }

  /* Grid */
  .grid { display: grid; gap: var(--gap); grid-template-columns: ${isMobile ? "1fr" : "1fr 1fr"}; }
  .pill-row { display:flex; gap: 10px; flex-wrap: wrap; }
  .chip { border:1px solid var(--strokeColor, rgba(0,0,0,.12)); border-radius:999px; padding:6px 10px; font-size: .875rem; background: var(--thirdBackground, #f2f2f2); color: var(--thirdText, #222); }

  /* Button: nutzt deine token */
  .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:10px 16px; border-radius: 999px; text-decoration:none; cursor:pointer; border:2px solid var(--strokeColor, rgba(0,0,0,.15)); }
  .btn.primary { background: var(--buttonBackground, #000); color: var(--buttonText, #fff); box-shadow: 0 8px 0 rgba(0,0,0,.07); }
  .btn.ghost { background: transparent; color: var(--mainText, #111); }

  /* Farbbereiche Showcase */
  .swatch { border-radius: var(--radius); padding: 14px; border:1px solid var(--strokeColor, rgba(0,0,0,.12)); display:grid; gap:6px; }
  .first  .swatch { background: var(--firstBackground, #e9f); color: var(--firstText, #111); }
  .second .swatch { background: var(--secondBackground, #eef); color: var(--secondText, #111); }
  .third  .swatch { background: var(--thirdBackground, #efe); color: var(--thirdText, #111); }

  /* Mini Nav (Fake) */
  .navbar { display:flex; gap:10px; align-items:center; padding: ${isMobile ? 10 : 14}px ${isMobile ? 10 : 16}px; background: var(--mainBackground, #fff); border:1px solid var(--strokeColor, rgba(0,0,0,.1)); border-radius: 999px; }
  .avatar { width:28px; height:28px; border-radius:999px; background: var(--secondBackground, #ddd); border:1px solid var(--strokeColor, rgba(0,0,0,.12)); }

  /* Typo-Bindings kommen aus colors.css & fontset; wir sorgen nur für Abstände */
  .heroTitle { margin: 0; }
  .heroLead { margin: 0; opacity:.95; }
  .title { margin: 0 0 6px 0; }
  .subtitle { margin: 0 0 10px 0; opacity:.8; }
  .content { margin: 0 0 10px 0; }
  .subcontent { margin: 0; opacity:.7; }

  /* Footer */
  .footer { display:flex; justify-content:space-between; align-items:center; margin-top: 6px; padding-top: 10px; border-top:1px dashed var(--strokeColor, rgba(0,0,0,.15)); }
</style>
</head>
<body class="background">
  <div class="shell">

    <div class="navbar card">
      <div class="avatar"></div>
      <div class="subtitle">Deine App-Vorschau</div>
      <div style="flex:1"></div>
      <a class="btn ghost button" href="#">Login</a>
      <a class="btn primary button" href="#">Jetzt starten</a>
    </div>

    <section class="hero background">
      <img class="media" src="/storage/images/motiv.jpg" alt="Motiv" />
      <div class="overlay"></div>
      <div class="inner">
        <span class="badge">Live Preview</span>
        <h1 class="heroTitle">Willkommen in unserer App</h1>
        <p class="heroLead">Hier siehst du, wie <strong>Schrift</strong> & <strong>Farben</strong> im echten Layout wirken.</p>
      </div>
    </section>

    <section class="grid">
      <article class="card">
        <h2 class="title">Elegante Typografie out-of-the-box</h2>
        <div class="subtitle">Titel, Lead, Fließtext & Buttons – alles aus deinem Fontset</div>
        <p class="content">
          Die Netzwerksicherheit umfasst technische und organisatorische Maßnahmen, um Daten und Systeme
          vor unbefugtem Zugriff zu schützen. Dazu gehören Firewalls, Verschlüsselung und IDS.
        </p>
        <p class="subcontent">Hinweis: Diese Vorschau lädt deine echten CSS-Dateien.</p>
        <div class="pill-row">
          <span class="chip">Responsive</span>
          <span class="chip">Zugänglich</span>
          <span class="chip">Performant</span>
        </div>
        <div style="display:flex; gap:10px; margin-top:12px;">
          <a class="btn primary button" href="#">Aktion</a>
          <a class="btn ghost button" href="#">Mehr erfahren</a>
        </div>
      </article>

      <aside class="card">
        <h3 class="title">Farbharmonie</h3>
        <p class="content">Die Sektionen unten zeigen <code>.first</code>, <code>.second</code>, <code>.third</code>.</p>
        <div class="first">
          <div class="swatch">
            <div class="subtitle">First Bereich</div>
            <p class="content">Kontraste und Lesbarkeit checken.</p>
          </div>
        </div>
        <div class="second" style="margin-top:10px;">
          <div class="swatch">
            <div class="subtitle">Second Bereich</div>
            <p class="content">Buttons & Chips im Kontext.</p>
          </div>
        </div>
        <div class="third" style="margin-top:10px;">
          <div class="swatch">
            <div class="subtitle">Third Bereich</div>
            <p class="content">Alternative Flächenfarbe.</p>
          </div>
        </div>
        <div class="footer">
          <span class="subcontent">© Deine Marke</span>
          <a class="btn ghost button" href="#">Impressum</a>
        </div>
      </aside>
    </section>

  </div>
</body>
</html>`;
            return html;
        },

        async renderIntoIframe(iframe, { mode }) {
            if (!iframe) return;
            const html = this.buildHtml({ mode });
            const doc = iframe.contentDocument;
            doc.open();
            doc.write(html);
            doc.close();

            // kleinen Delay, dann Höhe anpassen
            requestAnimationFrame(() => this.autoScale());
        },
    },
};
</script>

<style scoped>
.preview-wrap {
    margin-top: 16px;
}

.device-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

@media (min-width: 1200px) {
    .device-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.device-col {
    display: grid;
    gap: 8px;
}

.device-head {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    opacity: .8;
}

.device-head .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #e44;
    box-shadow: 16px 0 0 #eb3, 32px 0 0 #4c6;
}

.device-head .label {
    margin-left: 48px;
    letter-spacing: .02em;
}

.device-frame {
    width: 100%;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 30px 70px rgba(0, 0, 0, .07), 0 2px 8px rgba(0, 0, 0, .06);
}

.device-frame.phone {
    border-radius: 30px;
}
</style>
