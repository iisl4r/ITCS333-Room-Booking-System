const popupForm = document.getElementById('popupForm');
const cancelBtn = document.getElementById('cancelBtn');
const roomForm = document.getElementById('roomForm');

// Show the popup when "Add Room" button is clicked
document.getElementById('addRoomBtn').addEventListener('click', function (event) {
    event.preventDefault();
    popupForm.style.display = 'flex';
});

// Close the popup if cancel button is clicked
cancelBtn.addEventListener('click', function () {
    popupForm.style.display = 'none';
});

// Handle room form submission
roomForm.onsubmit = function (event) {
    event.preventDefault();

    const formData = new FormData(roomForm);

    fetch('../php/addRoom.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#roomsTable tbody');

                if (!tbody) {
                    throw new Error('Table body not found');
                }


                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td>${data.room.roomId}</td>
                <td>${data.room.department}</td>
                <td>${data.room.capacity}</td>
                <td>${data.room.equipment}</td>
                <td>${data.room.roomFloor}</td>
                <td>${data.room.startTime}</td>
                <td>${data.room.endTime}</td>
                <td>${data.room.roomNumber}</td>
                <td>${data.room.roomStatus}</td>
                <td>
                                <span class='material-symbols-sharp more-options'>more_vert</span>
                                <div class='context-menu hidden'>
                                    <a href='#' class='edit' data-room-index='${data.room.roomId}'>Edit</a>
                                    <a href='#' class='delete'>Delete</a>
                                </div>
                            </td>
            `;
                tbody.appendChild(newRow);

                // Add event listeners for the Edit and Delete buttons of the newly added row
                const editBtn = newRow.querySelector('.edit');
                const deleteBtn = newRow.querySelector('.delete');
                const moreOptionsBtn = newRow.querySelector('.more-options');
                const contextMenu = newRow.querySelector('.context-menu');

                // Show/Hide context menu when 'more-options' is clicked
                moreOptionsBtn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    contextMenu.style.display = contextMenu.style.display === 'block' ? 'none' : 'block';
                });

                // Close context menu when clicking outside
                document.addEventListener('click', () => {
                    contextMenu.style.display = 'none';
                });

                // Handle Edit button click
                editBtn.addEventListener('click', handleEdit);

                // Handle Delete button click
                deleteBtn.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Get the closest row and the room ID
                    const row = event.target.closest('tr');
                    const roomId = row.querySelector('td:first-child').textContent;

                    console.log('Room ID to delete:', roomId);
                    fetch('../php/deleteRoom.php', {
                        method: 'POST',
                        body: JSON.stringify({ roomId: roomId }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.remove();
                                alert('Room deleted successfully!');
                            } else {
                                alert('Failed to delete room: ' + data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting room:', error);
                            alert('Error deleting room. Please check the console for details.');
                        });
                });

                // Close the popup and reset the form
                popupForm.style.display = 'none';
                roomForm.reset(); // Clear the form fields
                alert('Room added successfully!');

            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error adding room:', error);
            alert('Error adding room. Please try again.');
        });
};



// Close the Add Room form
cancelBtn.addEventListener('click', () => {
    popupForm.style.display = 'none';
    roomForm.reset();
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
document.addEventListener("DOMContentLoaded", function () {
    const addScheduleBtn = document.getElementById("addScheduleBtn");
    const addScheduleForm = document.getElementById("addScheduleForm");
    const addCancelBtn = document.getElementById("addCancelBtn");
    const editScheduleBtn = document.querySelectorAll(".context-menu .edit"); // All edit buttons
    let currentScheduleId = null; // Variable to store the current schedule ID for editing

    // Show Add Schedule Form
    addScheduleBtn.addEventListener("click", function (event) {
        event.preventDefault();
        addScheduleForm.classList.remove("hidden"); // Show the form
        // Reset the form fields when opening for adding
        resetForm();
        currentScheduleId = null; // Clear the current schedule ID when adding
    });

    // Cancel the Add Schedule Form
    addCancelBtn.addEventListener("click", function () {
        addScheduleForm.classList.add("hidden"); // Hide the form
    });

    // Handle editing schedule data
    editScheduleBtn.forEach(function (editButton) {
        editButton.addEventListener("click", function (event) {
            event.preventDefault();

            // Get the schedule ID from the data attribute
            const scheduleId = editButton.getAttribute("data-schedule-id");


            const scheduleData = getScheduleDataById(scheduleId);

            // Fill the form with the fetched data
            populateEditForm(scheduleData);

            // Show the form to edit
            addScheduleForm.classList.remove("hidden");
            currentScheduleId = scheduleId; // Store the current schedule ID for saving the changes
        });
    });

    // Reset the form fields
    function resetForm() {
        document.getElementById("roomNo").value = "";
        document.getElementById("scheduleDate").value = "";
        document.getElementById("startTime").value = "";
        document.getElementById("endTime").value = "";
        document.getElementById("status").value = "";
    }

    // Populate the form with the data to edit
    function populateEditForm(data) {
        document.getElementById("roomNo").value = data.roomNo;
        document.getElementById("scheduleDate").value = data.scheduleDate;
        document.getElementById("startTime").value = data.startTime;
        document.getElementById("endTime").value = data.endTime;
        document.getElementById("status").value = data.status;
    }

    // Mock function to simulate fetching schedule data
    function getScheduleDataById(scheduleId) {
        // Replace with actual data fetching logic
        const mockData = {
            2: { roomNo: "101", scheduleDate: "2024-11-24", startTime: "09:00", endTime: "11:00", status: "Scheduled" },
            1: { roomNo: "102", scheduleDate: "2024-11-25", startTime: "10:00", endTime: "12:00", status: "Available" }
        };
        return mockData[scheduleId]; // Return data for the specific schedule
    }

    // Handle form submission (for saving the schedule)
    const addForm = document.getElementById("addForm");
    addForm.addEventListener("submit", function (event) {
        event.preventDefault();

        // Get the data from the form
        const roomNo = document.getElementById("roomNo").value;
        const scheduleDate = document.getElementById("scheduleDate").value;
        const startTime = document.getElementById("startTime").value;
        const endTime = document.getElementById("endTime").value;
        const status = document.getElementById("status").value;

        if (currentScheduleId) {
            console.log("Editing schedule", currentScheduleId, {
                roomNo, scheduleDate, startTime, endTime, status
            });
        } else {
            console.log("Adding new schedule", {
                roomNo, scheduleDate, startTime, endTime, status
            });
        }

        // Hide the form after submission
        addScheduleForm.classList.add("hidden");
        resetForm(); // Reset the form for the next use
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const addScheduleBtn = document.getElementById("addScheduleBtn");
    const addScheduleForm = document.getElementById("addScheduleForm");
    const addCancelBtn = document.getElementById("addCancelBtn");

    // Show the Add Schedule Form when the "Add Schedule" button is clicked
    addScheduleBtn.addEventListener("click", function (event) {
        event.preventDefault();
        addScheduleForm.classList.remove("hidden");  // Show the form
    });

    // Cancel the Add Schedule Form (hide the form)
    addCancelBtn.addEventListener("click", function () {
        addScheduleForm.classList.add("hidden");  // Hide the form
    });

    // Handle form submission (for saving the schedule)
    const addForm = document.getElementById("addForm");
    addForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const roomNo = document.getElementById("roomNo").value;
        const scheduleDate = document.getElementById("scheduleDate").value;
        const startTime = document.getElementById("startTime").value;
        const endTime = document.getElementById("endTime").value;
        const status = document.getElementById("status").value;

        console.log({
            roomNo: roomNo,
            scheduleDate: scheduleDate,
            startTime: startTime,
            endTime: endTime,
            status: status
        });

        addScheduleForm.classList.add("hidden");
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
        roomId: row.children[0].textContent, // The ID is the first cell in the row
        department: row.children[1].textContent,
        capacity: row.children[2].textContent,
        equipment: row.children[3].textContent,
        floor: row.children[4].textContent,
        startTime: row.children[5].textContent,
        endTime: row.children[6].textContent,
        roomNumber: row.children[7].textContent,
        status: row.children[8].textContent.trim()
    };

    // Populate the edit form fields with the room data
    document.getElementById('editRoomId').value = roomData.roomId;
    document.getElementById('editDepartment').value = roomData.department;
    document.getElementById('editRoomCapacity').value = roomData.capacity;
    document.getElementById('editEquipment').value = roomData.equipment;
    document.getElementById('editRoomFloor').value = roomData.floor;
    document.getElementById('editStartTime').value = roomData.startTime;
    document.getElementById('editEndTime').value = roomData.endTime;
    document.getElementById('editRoomNumber').value = roomData.roomNumber;
    document.getElementById('editRoomStatus').value = roomData.status;

    // Show the edit form
    editRoomForm.style.display = 'flex';

    // Handle form submission to save changes
    editForm.onsubmit = (e) => {
        e.preventDefault();

        // Get form data
        const formData = new FormData(editForm);

        // Send data using fetch to update the database
        fetch('../php/updateRoom.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the table with the new values
                    row.children[1].textContent = document.getElementById('editDepartment').value;
                    row.children[2].textContent = document.getElementById('editRoomCapacity').value;
                    row.children[3].textContent = document.getElementById('editEquipment').value;
                    row.children[4].textContent = document.getElementById('editRoomFloor').value;
                    row.children[5].textContent = document.getElementById('editStartTime').value;
                    row.children[6].textContent = document.getElementById('editEndTime').value;
                    row.children[7].textContent = document.getElementById('editRoomNumber').value;
                    row.children[8].textContent = document.getElementById('editRoomStatus').value;

                    alert('Room data updated successfully!');

                    // Hide the form
                    editRoomForm.style.display = 'none';
                } else {
                    console.error('Error updating room data:', data.error);
                }
            })
            .catch(error => {
                console.error('Error with the fetch request:', error);
            });
    };
}



document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete');

    // Check if delete buttons are found
    console.log('Delete buttons:', deleteButtons);

    deleteButtons.forEach(button => {
        console.log('Attaching event listener to button:', button);
        button.addEventListener('click', function (event) {
            event.preventDefault();

            // Get the closest row and the room ID
            const row = event.target.closest('tr');
            const roomId = row.querySelector('td:first-child').textContent;

            console.log('Room ID to delete:', roomId);

            fetch('../php/deleteRoom.php', {
                method: 'POST',
                body: JSON.stringify({ roomId: roomId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);

                    if (data.success) {
                        row.remove();
                        alert('Room deleted successfully!');
                    } else {
                        alert('Failed to delete room: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error deleting room:', error);
                    alert('Error deleting room. Please check the console for details.');
                });
        });
    });
});



// Attach Edit functionality to existing rows
document.querySelectorAll('.edit').forEach((button) => {
    button.addEventListener('click', handleEdit);
});

// Close Edit Room form by clicking the Cancel button
editCancelBtn.addEventListener('click', () => {
    editRoomForm.style.display = 'none';
});

// Close the Edit Room form if user clicks outside the form
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


