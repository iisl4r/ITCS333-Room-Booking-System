const menuItems = document.querySelectorAll('.menu-item');
const contentSections = document.querySelectorAll('.content-section');

menuItems.forEach(item => {
    item.addEventListener('click', event => {
        event.preventDefault();

        menuItems.forEach(i => i.classList.remove('active'));
        contentSections.forEach(section => section.classList.remove('active'));


        item.classList.add('active');


        const sectionId = item.getAttribute('data-section');


        if (sectionId) {
            document.getElementById(sectionId).classList.add('active');
        }
    });
});


const sideMenu = document.querySelector("aside");
const menuBtn = document.querySelector("#menu-btn");
const closeBtn = document.querySelector("#close-btn");
const themeToggler = document.querySelector(".theme-toggler");

// Show Sidebar
menuBtn.addEventListener("click", () => {
    sideMenu.style.display = "block";
});

// Hide Sidebar
closeBtn.addEventListener("click", () => {
    sideMenu.style.display = "none";
});

// Change Theme
themeToggler.addEventListener("click", () => {
    document.body.classList.toggle("dark-theme-variables");

    themeToggler.querySelector("span:nth-child(1)").classList.toggle("active");
    themeToggler.querySelector("span:nth-child(2)").classList.toggle("active");
});