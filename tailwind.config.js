const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors');

module.exports = {
    theme: {
        extend: {
            colors: {
                'transparent': 'transparent',
                'current': 'currentColor',
                'white': '#ffffff',
                'purple': '#3f3cbb',
                'midnight': '#121063',
                'metal': '#565584',
                'tahiti': '#3ab7bf',
                'silver': '#ecebff',
                'bubble-gum': '#ff77e9',
                'bermuda': '#78dcca',
            },
            fontSize: {
                'xs': '0.75rem',
                'sm': '0.8rem',
                'base': '1rem',
                'lg': '1.125rem',
                'xl': '1.25rem',
                '2xl': '1.5rem',
                '3xl': '2rem',
                '4xl': '2.4rem',
                '5xl': '3rem',
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            inset: {
                '3px': '3px',
            },
            borderRadius: {
                'md': '12px',
                'xl': '2rem',
                '3xl': '50px'
            },
        },
        minWidth: {
            '1/4': '25%',
        }
    },
    safelist: [
        {
            pattern: /bg-(red|green|blue)-(100|200|300|400|500|600|700|800|900)/,
        },
    ],
    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
        backgroundColor: ['responsive', 'hover', 'focus', 'disabled'],
    },
    variants: {
        extend: {
            backgroundColor: ['active'],
        }
    },
    content: [
        './app/**/*.php',
        './resources/**/*.html',
        './resources/**/*.js',
        './resources/**/*.jsx',
        './resources/**/*.ts',
        './resources/**/*.tsx',
        './resources/**/*.php',
        './resources/**/*.vue',
        './resources/**/*.twig',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
    ],
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
