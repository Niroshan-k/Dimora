module.exports = {
    content: [
        "./app/views/**/*.php",
        ".public/index.php"
    ],
    theme: {
        extend: {
            colors:{
                yellow : {
                    50 : '#EFEFE9'
                },
                green : {
                    50 : '#959D90',
                    100 : '#223030'
                },
                brown : {
                    50  : '#E8D9CD',
                    100 : '#BBA58F',
                    150 : '#523D35'
                },
                red : {
                    100 : '#FF4600'
                }
            },
            fontFamily: {
                serif: ['DM Serif Display', 'serif'],
                inter: ['Inter', 'sans-serif'],    
            },
        },
    },
    plugins: [],
};