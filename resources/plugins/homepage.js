import 'vuetify/styles'
import { createVuetify } from 'vuetify'

const lightTheme = {
    dark: false,
    colors: {
        primary: '#7C4DFF',      // vibrant purple (accent style)
        secondary: '#00BFA6',    // bright teal
        accent: '#FF5252',       // coral red accent
        background: '#F9F9F9',   // soft warm background
        surface: '#FFFFFF',      // clean white cards
        text: '#263238',         // almost black, good contrast
        success: '#00C853',      // strong green
        error: '#D32F2F',        // solid error red
        header: '#FFFFFF',
        footer: '#F0F0F0'
    },
}


const darkTheme = {
    dark: true,
    colors: {
        primary: '#B388FF',      // soft glowing purple
        secondary: '#1DE9B6',    // bright aqua-teal
        accent: '#FF4081',       // pink accent
        background: '#121212',   // deep black background
        surface: '#1E1E1E',      // dark card background
        text: '#ECEFF1',         // almost-white text
        success: '#69F0AE',      // neon mint
        error: '#FF6E6E',        // bright error red
        header: '#1E1E1E',
        footer: '#B388FF'
    },
}


export default createVuetify({
    theme: {
        defaultTheme: 'light',
        variations: {
            colors: ['primary', 'secondary', 'accent', 'background', 'surface', 'text', 'success', 'error'],
            lighten: 4,
            darken: 4,
        },
        themes: {
            light: lightTheme, // Use your custom theme here
            dark: darkTheme, // Use your custom theme here
        },

    },

})
