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


CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department VARCHAR(30) NOT NULL,
    room_floor INT NOT NULL,
    capacity INT NOT NULL,
    equipments TEXT NOT NULL,
    available_start TIME NOT NULL DEFAULT '08:00:00',
    available_end TIME NOT NULL DEFAULT '21:00:00',
    room_number VARCHAR(30) NOT NULL UNIQUE,
    room_status VARCHAR(30) NOT NULL
);

-- IS Department Rooms
INSERT INTO rooms (capacity, equipments, available_start, available_end, room_number, department, room_floor, room_status)
VALUES
(25, "Projector, Whiteboard", "08:00:00", "21:00:00", "S40-2008", "IS", 2, "Available"),
(28, "Projector", "08:00:00", "21:00:00", "S40-1006", "IS", 1, "Occupied"),
(22, "Projector, Laptop", "08:00:00", "21:00:00", "S40-021", "IS", 0, "Available"),
(27, "Projector, Monitors", "08:00:00", "21:00:00", "S40-2009", "IS", 2, "Occupied"),
(26, "Whiteboard", "08:00:00", "21:00:00", "S40-1007", "IS", 1, "Available"),
(23, "Projector, Laptop", "08:00:00", "21:00:00", "S40-022", "IS", 0, "Occupied"),
(30, "Projector, Whiteboard", "08:00:00", "21:00:00", "S40-2010", "IS", 2, "Available"),
(24, "Projector, Monitors", "08:00:00", "21:00:00", "S40-1008", "IS", 1, "Occupied");

-- CS Department Rooms
INSERT INTO rooms (capacity, equipments, available_start, available_end, room_number, department, room_floor, room_status)
VALUES
(29, "Whiteboard, Laptop, Microphone", "08:00:00", "21:00:00", "S40-2045", "CS", 2, "Available"),
(27, "Whiteboard, Speaker", "08:00:00", "21:00:00", "S40-1048", "CS", 1, "Occupied"),
(24, "Whiteboard, Projector", "08:00:00", "21:00:00", "S40-060", "CS", 0, "Available"),
(26, "Whiteboard, Laptop", "08:00:00", "21:00:00", "S40-2046", "CS", 2, "Occupied"),
(28, "Whiteboard, Microphone", "08:00:00", "21:00:00", "S40-1049", "CS", 1, "Available"),
(25, "Whiteboard, Speaker", "08:00:00", "21:00:00", "S40-061", "CS", 0, "Occupied"),
(30, "Whiteboard, Laptop", "08:00:00", "21:00:00", "S40-2047", "CS", 2, "Available"),
(22, "Whiteboard, Projector", "08:00:00", "21:00:00", "S40-1050", "CS", 1, "Occupied");

-- NE Department Rooms
INSERT INTO rooms (capacity, equipments, available_start, available_end, room_number, department, room_floor, room_status)
VALUES
(20, "Laptop, Projector, Speaker", "08:00:00", "21:00:00", "S40-2086", "NE", 2, "Available"),
(23, "Laptop, Microphone", "08:00:00", "21:00:00", "S40-1086", "NE", 1, "Occupied"),
(21, "Laptop, Whiteboard", "08:00:00", "21:00:00", "S40-088", "NE", 0, "Available"),
(25, "Laptop, Speaker", "08:00:00", "21:00:00", "S40-2087", "NE", 2, "Occupied"),
(22, "Laptop, Microphone", "08:00:00", "21:00:00", "S40-1087", "NE", 1, "Available"),
(24, "Laptop, Whiteboard", "08:00:00", "21:00:00", "S40-089", "NE", 0, "Occupied"),
(26, "Laptop, Projector", "08:00:00", "21:00:00", "S40-2088", "NE", 2, "Available"),
(29, "Laptop, Speaker", "08:00:00", "21:00:00", "S40-1088", "NE", 1, "Occupied");