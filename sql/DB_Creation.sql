CREATE DATABASE Room_System;

USE Room_System;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL ,
    class_id INT NOT NULL,
    booking_date date NOT NULL,
    start_time TIME NOT NULL,
    duration INT NOT NULL,
    end_time TIME NOT NULL,
    booking_status VARCHAR(10) NOT NULL
);

INSERT INTO booking (user_id, class_id, booking_date, start_time, duration, end_time, booking_status) VALUES(2,2,'2024-11-30','8:00:00',60,'9:00:00','active');
INSERT INTO booking (user_id, class_id, booking_date, start_time, duration, end_time, booking_status) VALUES(3,2,'2024-12-12','13:00:00',60,'14:00:00','canceled');