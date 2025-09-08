// src/constants/uiOptions.ts

// Basis-Typ
export interface OptionItem<T = string> {
    label: string
    value: T
}

// Farben
export const COLOR_ITEMS = [
    { label: "Farbe A", value: "first" },
    { label: "Farbe B", value: "second" },
    { label: "Farbe C", value: "third" },
    { label: "Transparent", value: "transparent" },
] as const satisfies readonly OptionItem[]

// Dichte
export const DENSITY_ITEMS = [
    { label: "Standard", value: "default" },
    { label: "Prominent (hoch)", value: "prominent" },
    { label: "Komfortabel", value: "comfortable" },
    { label: "Kompakt (niedrig)", value: "compact" },
] as const satisfies readonly OptionItem[]

// Scroll-Verhalten
export const SCROLL_BEHAVIOR_ITEMS = [
    { label: "Verschwindet", value: "hide" },
    { label: "Standard", value: "default" },
    { label: "Erhöhen beim Scrollen", value: "elevate" },
] as const satisfies readonly OptionItem[]

// Ausrichtung
export const JUSTIFY_ITEMS = [
    { label: "linksbündig", value: "justify-start" },
    { label: "mittig", value: "justify-center" },
    { label: "rechtsbündig", value: "justify-end" },
] as const satisfies readonly OptionItem[]

// Textvarianten
export const TEXT_VARIANT_ITEMS = [
    { label: "Hero", value: "heroLead" },
    { label: "Titel", value: "title" },
    { label: "Untertitel", value: "subtitle" },
    { label: "Inhalt", value: "content" },
    { label: "Anmerkung", value: "subcontent" },
] as const satisfies readonly OptionItem[]
