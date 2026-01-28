import type { Config } from 'tailwindcss'

export default {
    content: [
        "./index.html",
        "./resources/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
} satisfies Config
