module.exports = {
    content: [
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/js/components/**/*.vue",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Inter var",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFon",
                    "Segoe UI",
                    "Roboto",
                    "Helvetica Neue",
                    "Arial",
                ],
            },
            screens: {
                qsortxs: { max: "575px" }, // Mobile (iPhone 3 - iPhone XS Max).
                qsortsm: {
                    min: "576px",
                    max: "897px",
                }, // Mobile (matches max: iPhone 11 Pro Max landscape @ 896px).
                qsortmd: {
                    min: "898px",
                    max: "1199px",
                }, // Tablet (matches max: iPad Pro @ 1112px).
                qsortlg: { min: "1200px" }, // Desktop smallest.
                qsortxl: { min: "1159px" }, // Desktop wide.
                qsortxxl: { min: "1359px" }, // Desktop widescreen.
            },
            gridTemplateRows: {
                9: "repeat(9, minmax(0, 1fr))",
                10: "repeat(10, minmax(0, 1fr))",
                11: "repeat(11, minmax(0, 1fr))",
            },
        },
    },
    variants: {},
    plugins: [
        require("postcss-import"),
        require("tailwindcss"),
        require("postcss-nested"), // or require('postcss-nesting')
        require("autoprefixer"),
        require("@tailwindcss/forms"),
    ],
};
