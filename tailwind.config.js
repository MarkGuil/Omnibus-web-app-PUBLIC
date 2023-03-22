/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./dist/*.{php,js}"],
  theme: {
    extend: {
      screens: {
        '3xl': '1920px',
        '3k': '2130px',
        '4k': '2560px',
      },
      colors: {
        'dark-blue': '#0205a1',
        'dark-bluer': '#010488',
      },
      backgroundImage: {
        'home-small': "linear-gradient(rgba(2, 5, 161, 0.9), rgba(2, 5, 161, 0.9)), url('images/bgsmall.jpg')",
        'home-large': "linear-gradient(rgba(2, 5, 161, 0.9), rgba(2, 5, 161, 0.9)), url('images/bglarge.jpg')",
      },
      animation: {
        'zoom-in': 'zoom .8s ease-in',
        'slow-bounce': 'slowbounce 3s ease-in-out infinite alternate',
        'nav-bounce': 'navbounce .5s ease-in-out',
        'wave1': 'wave1 10s linear -2s infinite',
        'wave2': 'wave2 8s linear -2s infinite',
        'wave3': 'wave2 6s linear -2s infinite',
      },
      keyframes: {
        zoom: {
          '0%': { 
            opacity: 0,
            transform: 'scale(1.2)' 
          },
          '100%': { 
            opacity: 1,
            transform: 'scale(1)' 
          },
        },
        slowbounce: {
          '0%': {
            transform: 'translateY(-5%)'
          },
          '100%': {
            transform: 'translateY(5)'
          },
        },
        navbounce: {
          '0%': {
            transform: 'scaleY(0)'
          },
          '80%': {
            transform: 'scaleY(1.1)'
          },
          '100%': {
            transform: 'scaleY(1)'
          },
        },
        wave1: {
          '0%': {
            transform: 'translate(85px, 0%)'
          },
          '100%': {
            transform: 'translate(-90px, 0%)'
          },
        },
        wave2: {
          '0%': {
            transform: 'translate(-90px, 0%)'
          },
          '100%': {
            transform: 'translate(85px, 0%)'
          },
        },
      },
    },
    maxWidth: {
      'max-w-8xl': '1320px',
    },
  },
  variants: {
    color: ['hover', 'active'],
    visibility: ['hover', 'active']
  },
  plugins: [],
}
