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