export const mediamanagerLightTheme = {
    dark: false,
    colors: {
        'mm-primary': '#ff0000',
        'mm-secondary': '#B0BEC5',
        'mm-accent': '#18FFFF',
        'mm-background': '#FAFAFA',
        'mm-surface': '#FFFFFF',
        'mm-text': '#424242',
        'mm-success': '#00897B',
        'mm-error': '#E53935',
    },
}

export const mediamanagerDarkTheme = {
    dark: true,
    colors: {
        'mm-primary': '#7986CB',
        'mm-secondary': '#455A64',
        'mm-accent': '#00E5FF',
        'mm-background': '#212121',
        'mm-surface': '#424242',
        'mm-text': '#E0E0E0',
        'mm-success': '#4DB6AC',
        'mm-error': '#EF5350',
    },
}

export default {
    install(app) {
        app.config.globalProperties.$mmThemes = {
            light: mediamanagerLightTheme,
            dark: mediamanagerDarkTheme,
        };
    }
};