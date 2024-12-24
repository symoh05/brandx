function toggleMenu() {
    const menu = document.getElementById('menu');
    const hamMenu = document.querySelector('.ham-menu');
    menu.classList.toggle('active');
    hamMenu.classList.toggle('active');
}
