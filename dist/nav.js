const initApp = () => {
    const menuBtn = document.getElementById('nav-btn');
    const menuList = document.getElementById('nav-menu');

    const toggleMenu = () => {
        menuList.classList.toggle("flex");
        menuList.classList.toggle("hidden");
        menuBtn.classList.toggle("toggle-btn");
    }

    menuBtn.addEventListener('click', toggleMenu);
    menuList.addEventListener('click', toggleMenu);
}

document.addEventListener('DOMContentLoaded', initApp);