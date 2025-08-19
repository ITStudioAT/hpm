// src/composables/useThemePreview.js

// Keine Vue-Abhängigkeit – plain JS mit internen Caches.
// Nutzt dieselbe Mess-Strategie wie deine App.vue: echte Klassen messen.

const SYS_FALLBACK = `system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans", sans-serif`;

const _colorsCache = new Map();   // slug -> { first, second, third, ... }
const _fontsCache = new Map();   // slug -> { title, subtitle, content, button }
const _loadedHref = new Map();   // id -> href (ohne Cachebust)

export function useThemePreview() {
    /** Utils **/
    function debounce(fn, ms = 150) {
        let t = null;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), ms);
        };
    }

    async function ensureStylesheet(id, href, { bust = false } = {}) {
        const cleanHref = href; // wir lassen Browser-Caching zu
        if (!bust && _loadedHref.get(id) === cleanHref) return document.getElementById(id);
        let link = document.getElementById(id);
        if (!link) {
            link = document.createElement("link");
            link.id = id;
            link.rel = "stylesheet";
            document.head.appendChild(link);
        }
        const finalHref = bust ? `${cleanHref}${cleanHref.includes("?") ? "&" : "?"}v=${Date.now()}` : cleanHref;
        if (link.getAttribute("href") === finalHref) return link;
        await new Promise((res) => {
            link.addEventListener("load", res, { once: true });
            link.addEventListener("error", res, { once: true });
            link.setAttribute("href", finalHref);
        });
        _loadedHref.set(id, cleanHref);
        return link;
    }

    async function fetchCssText(url) {
        try {
            const res = await fetch(url, { headers: { Accept: "text/css,*/*;q=0.1" } });
            return res.ok ? await res.text() : "";
        } catch {
            return "";
        }
    }

    function pickVar(cssText, names) {
        for (const n of names) {
            const m = cssText.match(new RegExp(`--${n}\\s*:\\s*([^;]+);`, "i"));
            if (m) return m[1].trim();
        }
        return "";
    }

    function stripQuotes(s) {
        return (s || "").replace(/^['"]+|['"]+$/g, "");
    }

    /** Colors – CSS-Datei parsen (scope-agnostisch) **/
    async function getColorsetPreview(slug) {
        if (_colorsCache.has(slug)) return _colorsCache.get(slug);
        const css = await fetchCssText(`/api/css/colors/${encodeURIComponent(slug)}.css`);
        const first = pickVar(css, ["firstBackground", "firstBG", "first", "firstColor"]) || "#eee";
        const second = pickVar(css, ["secondBackground", "secondBG", "second", "secondColor"]) || "#ddd";
        const third = pickVar(css, ["thirdBackground", "thirdBG", "third", "thirdColor"]) || "#ccc";
        const buttonBg = pickVar(css, ["buttonBackground", "button", "accent", "primary"]) || "#ccc";
        const buttonFg = pickVar(css, ["buttonText", "onButton", "button-contrast"]) || "#000";
        const text = pickVar(css, ["mainText", "contentColor", "backgroundText", "text"]) || "#333";
        const stroke = pickVar(css, ["strokeColor", "illustrationStroke", "stroke", "borderColor"]) || "rgba(0,0,0,.12)";
        const val = { first, second, third, buttonBg, buttonFg, text, stroke };
        _colorsCache.set(slug, val);
        return val;
    }

    /** Fonts – Fontset-CSS einhängen + Sandbox messen (wie App.vue) **/
    async function ensureFontsetCss(slug) {
        return ensureStylesheet("fontset-css-admin", `/api/css/fontset/${encodeURIComponent(slug)}.css`);
    }

    async function measureFontsetFromSandbox(slug) {
        const box = document.createElement("div");
        box.style.cssText = "position:fixed;left:-99999px;top:-99999px;visibility:hidden;pointer-events:none;";
        // gängige Scopes abdecken
        box.classList.add("fontset", `fontset-${slug}`, slug);
        box.setAttribute("data-fontset", slug);
        box.setAttribute("aria-hidden", "true");

        box.innerHTML = `
      <div class="title">T</div>
      <div class="heroTitle">T</div>
      <div class="subtitle">S</div>
      <div class="heroLead">S</div>
      <div class="content">C</div>
      <button class="button">B</button>
    `;
        document.body.appendChild(box);
        await new Promise((r) => requestAnimationFrame(r));

        const firstStyled = (selectors) => {
            for (const sel of selectors) {
                const el = box.querySelector(sel);
                if (!el) continue;
                const cs = getComputedStyle(el);
                const fam = cs.fontFamily?.trim();
                if (fam && fam.length) {
                    return {
                        fontFamily: fam,
                        fontWeight: cs.fontWeight || "400",
                        fontStyle: cs.fontStyle || "normal",
                    };
                }
            }
            return { fontFamily: SYS_FALLBACK, fontWeight: "400", fontStyle: "normal" };
        };

        const title = firstStyled([".title", ".heroTitle"]);
        const subtitle = firstStyled([".subtitle", ".heroLead"]);
        const content = firstStyled([".content"]);
        const button = firstStyled([".button"]);

        box.remove();
        return { title, subtitle, content, button };
    }

    async function preloadFonts(preview) {
        if (!("fonts" in document)) return;
        const list = [preview.title, preview.subtitle, preview.content, preview.button];
        const jobs = list.map(({ fontFamily, fontWeight = "400", fontStyle = "normal" }) => {
            const primary = (fontFamily || "").split(",")[0].replace(/['"]/g, "").trim() || "sans-serif";
            return document.fonts.load(`${fontStyle} ${fontWeight} 16px "${primary}"`);
        });
        try {
            await Promise.allSettled(jobs);
            await document.fonts.ready;
        } catch { }
    }

    async function getFontsetPreview(slug) {
        if (_fontsCache.has(slug)) return _fontsCache.get(slug);
        await ensureFontsetCss(slug);
        const preview = await measureFontsetFromSandbox(slug);
        await preloadFonts(preview);
        _fontsCache.set(slug, preview);
        return preview;
    }

    /** Optional: Core-Fonts (woff2) + ggf. Color-Bindings einmalig laden */
    async function ensureCore({ fontsCoreHref = "/fonts/fonts.css", colorBindingsHref = null } = {}) {
        await ensureStylesheet("fonts-core", fontsCoreHref);
        if (colorBindingsHref) {
            await ensureStylesheet("color-bindings", colorBindingsHref);
        }
    }

    return {
        debounce,
        ensureStylesheet,
        ensureCore,
        ensureFontsetCss,
        getColorsetPreview,
        getFontsetPreview,
    };
}
