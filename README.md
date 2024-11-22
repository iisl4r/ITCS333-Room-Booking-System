# ITCS 333 Room Booking System

## Project Overview
The **ITCS 333 Room Booking System** is a responsive web-based application developed for the IT College. Its primary goal is to enhance the overall operational efficiency of room booking and management within the college. The system provides various features that enable effective management of room availability and bookings, ensuring a user-friendly experience for all users. The application is designed to be fully responsive, allowing seamless access across all devices.

## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Setup Instructions](#setup-instructions)
- [Usage](#usage)

## Features
- **User Registration and Login**: Users can register using their UoB email and log in to access their profiles.
- **Profile Management**: Users can manage their profiles, including uploading profile pictures.
- **Room Browsing**: Users can browse available rooms and view details such as capacity, equipment, and available timeslots.
- **Room Booking**: Users can book rooms and receive conflict checks to avoid double bookings.
- **Admin Panel**: Admins can manage room information, view bookings, and oversee user activities.
- **Reporting and Analytics**: Users can view statistics about room usage and popularity.
- **Comment System**: Users can leave feedback about rooms, and admins can respond to comments.

## Technologies Used
- **Frontend**: HTML, CSS (Bootstrap), JavaScript
- **Backend**: PHP
- **Database**: MySQL (using PDO for database access)
- **Web Server**: Apache

## Setup Instructions
Follow these steps to set up the project locally:

```bash
1. Clone the repository:
   git clone https://github.com/iisl4r/ITCS333-Room-Booking-System.git
   cd ITCS333-Room-Booking-System

2. Install a local server:
   Make sure you have a local server set up (like XAMPP or WAMP).

3. Set up the database:
   - Open your MySQL management tool (like phpMyAdmin).
   - Import the `sql/DB_Creation.sql` file to create the database tables.

4. Configure database connection:
   - Open `php/db.php` and set your database connection details.

5. Start your local server and navigate to `http://localhost/ITCS333-Room-Booking-System` in your web browser.

```

## Usage
To use the ITCS 333 Room Booking System, follow these steps:

### 1. User Registration
- **Visit the Registration Page**: Navigate to the registration page in your web browser.
- **Create an Account**: Enter your University of Birmingham (UoB) email address and complete the registration form.

### 2. Login
- **Access the Login Page**: After registering, go to the login page.
- **Enter Credentials**: Input your registered email and password to log in.
- **Access Your Profile**: Upon successful login, you will be directed to your user profile page.

### 3. Room Browsing
- **Browse Available Rooms**: Click on the room browsing link to view all available rooms.
- **Room Details**: Select a room to see its details, including:
  - **Capacity**: The number of people the room can accommodate.
  - **Equipment**: Information about available equipment (e.g., projectors, whiteboards).
  - **Available Timeslots**: A calendar or list showing when the room is available for booking.

### 4. Room Booking
- **Select a Room**: From the room details page, choose your preferred room.
- **Choose a Timeslot**: Pick an available timeslot for your booking.
- **Complete the Booking Process**: Confirm your booking. You may receive a confirmation message or email.

### 5. Booking Management
- **View Upcoming Bookings**: Check your user dashboard for a list of your upcoming bookings.
- **Cancel a Booking**: If needed, select a booking to cancel and follow the prompts to confirm your cancellation.

### 6. Admin Panel (for Administrators Only)
- **Login to the Admin Panel**: Use admin credentials to access the admin dashboard.
- **Manage Room Details**: Add, edit, or delete room information as needed.
- **View All Bookings**: Monitor all bookings made by users.
- **Respond to User Comments**: Check feedback from users and respond appropriately.
