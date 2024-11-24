<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

</head>

<body>
    <div class="container">
        <div class="top">
            <button id="menu-btn">
                <span class="material-symbols-sharp">menu</span>
            </button>
        </div>
        <aside>
            <div class="close" id="close-btn">
                <span class="material-symbols-sharp">close</span>
            </div>

            <div class="sidebar">
                <a href="#" class="menu-item  active" data-section="dashboard-section">
                    <span class="material-symbols-sharp">
                        grid_view
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="#" class="menu-item" data-section="rooms-section">
                    <span class="material-symbols-sharp">
                        table_rows
                    </span>
                    <h3>Rooms Management</h3>
                </a>
                <a href="#" class="menu-item" data-section="schedules-section">
                    <span class="material-symbols-sharp">
                        event_note
                    </span>
                    <h3>Schedules</h3>
                </a>

                <a href="#" class="menu-item" data-section="feedback-section">
                    <span class="material-symbols-sharp">
                        reviews
                    </span>
                    <h3>Feedback</h3>
                </a>
                <a href="#">
                    <span class="material-symbols-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

</body>

</html>