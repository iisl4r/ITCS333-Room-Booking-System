
const addRoomBtn = document.getElementById('addRoomBtn');
const popupForm = document.getElementById('popupForm');
const cancelBtn = document.getElementById('cancelBtn');

// Show the popup when "Add Room" button is clicked
addRoomBtn.addEventListener('click', function (event) {
    event.preventDefault(); // Prevent the default action of the link
    popupForm.style.display = 'flex';
});

// Close the popup when "Cancel" button is clicked
cancelBtn.addEventListener('click', function () {
    popupForm.style.display = 'none';
});

// Close the popup when clicking outside of the popup
window.addEventListener('click', function (event) {
    if (event.target === popupForm) {
        popupForm.style.display = 'none';
    }
});

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

document.addEventListener("DOMContentLoaded", () => {
    // Handle click on more-options icons
    document.querySelectorAll(".more-options").forEach(icon => {
        icon.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent click from propagating to the document
            closeAllMenus(); // Close other open menus

            // Get the associated context menu
            const contextMenu = this.nextElementSibling;

            // Position and show the menu
            const rect = this.getBoundingClientRect();
            contextMenu.style.top = `${rect.bottom + window.scrollY}px`;
            contextMenu.style.left = `${rect.left + window.scrollX}px`;
            contextMenu.style.display = "block";
        });
    });

    // Close menus if clicking outside
    document.addEventListener("click", () => {
        closeAllMenus();
    });

    // Close all menus
    function closeAllMenus() {
        document.querySelectorAll(".context-menu").forEach(menu => {
            menu.style.display = "none";
        });
    }
});
// Select all "Edit" buttons
const editRoomForm = document.getElementById('editRoomForm');
const editCancelBtn = document.getElementById('editCancelBtn');
const editForm = document.getElementById('editForm');

// Function to handle the "Edit" button click
function handleEdit(event) {
    event.preventDefault();

    // Get the clicked row
    const row = event.target.closest('tr');

    // Extract the room details from the row
    const roomData = {
        roomNumber: row.children[0].textContent,
        capacity: row.children[1].textContent,
        equipment: row.children[2].textContent,
        floor: row.children[3].textContent,
        time: row.children[5].textContent,
        status: row.children[6].textContent.trim()
    };

    // Populate the edit form fields with the room data
    document.getElementById('editRoomNumber').value = roomData.roomNumber;
    document.getElementById('editRoomCapacity').value = roomData.capacity;
    document.getElementById('editEquipment').value = roomData.equipment;
    document.getElementById('editTime').value = roomData.time;
    document.getElementById('editRoomFloor').value = roomData.floor;
    document.getElementById('editRoomStatus').value = roomData.status;

    // Show the edit form
    editRoomForm.style.display = 'flex';

    // Handle form submission to save changes
    editForm.onsubmit = (e) => {
        e.preventDefault();

        // Update the row with new data
        row.children[1].textContent = document.getElementById('editRoomCapacity').value;
        row.children[2].textContent = document.getElementById('editEquipment').value;
        row.children[3].textContent = document.getElementById('editRoomFloor').value;
        row.children[5].textContent = document.getElementById('editTime').value;
        row.children[6].textContent = document.getElementById('editRoomStatus').value;

        // Hide the edit form
        editRoomForm.style.display = 'none';
    };
}

// Attach Edit functionality to existing rows
document.querySelectorAll('.edit').forEach((button) => {
    button.addEventListener('click', handleEdit);
});

// Close Edit Room form by clicking the Cancel button
editCancelBtn.addEventListener('click', () => {
    editRoomForm.style.display = 'none';
});

// Close the Edit Room form if user clicks outside the form (on the overlay)
editRoomForm.addEventListener('click', function (event) {
    if (event.target === editRoomForm) { // Check if the click is on the overlay, not inside the form
        editRoomForm.style.display = 'none'; // Hide the form
    }
});
// side bar show - hide

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









