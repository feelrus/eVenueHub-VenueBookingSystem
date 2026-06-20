1-Install xampp;

2-Install visual studio code;

3-Run Apache and MySQL;

4-Place evenuehub folder in directory:\xampp\htdocs\ folder

5-Open localhost/phpmyadmin in any web browser;

6-Open the SQL menu;

7-“Create DATABASE evenuehubdb;” in SQL;

8-Click on the newly create evenuehubdb;

9-Click on the SQL menu and insert the tables below. Be careful of whitespace if got error;
USE evenuehubdb;

-- Users Table
CREATE TABLE users (
    userid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Locations Table
CREATE TABLE locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    location_name VARCHAR(100) NOT NULL UNIQUE
);

-- Events Table
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL UNIQUE
);

-- Venues Table
CREATE TABLE venues (
    venueid INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    event_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    location_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    pax INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id)
);

-- Bookings Table
CREATE TABLE bookings (
    bookingid INT AUTO_INCREMENT PRIMARY KEY,
    venue_id INT NOT NULL,
    user_id INT NOT NULL,
    special_request VARCHAR(255) NULL,
    remarks TEXT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    FOREIGN KEY (venue_id) REFERENCES venues(venueid),
    FOREIGN KEY (user_id) REFERENCES users(userid)
);

-- Payments Table
CREATE TABLE payments (
    paymentid INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    total_cost DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES bookings(bookingid)
);

10- Click on the users table, click on the SQL menu and insert the values below. Be careful of whitespace if got error:
USE evenuehubdb;

INSERT INTO users (username, email, password, role, created_at, updated_at)
VALUES ('admin', 'admin@admin.com', '$2y$10$NeIlM5/vAH51SM.pIVs2keAOhi908IWp.xAQ3QbUFiqbuC2HgrP0O', 0, NOW(), NOW());

11- Click on the events table, click on the SQL menu and insert the values below. Be careful of whitespace if got error:
USE evenuehubdb;

-- Insert Sample Events
INSERT INTO events (event_name) VALUES ('Conference');
INSERT INTO events (event_name) VALUES ('Meeting');
INSERT INTO events (event_name) VALUES ('Party');
INSERT INTO events (event_name) VALUES ('Wedding');

12- Click on the location table, click on the SQL menu and insert the values below. Be careful of whitespace if got error:
USE evenuehubdb;

-- Insert Sample Locations
INSERT INTO locations (location_name) VALUES ('Cyberjaya');
INSERT INTO locations (location_name) VALUES ('Petaling Jaya');
INSERT INTO locations (location_name) VALUES ('Shah Alam');
INSERT INTO locations (location_name) VALUES ('Subang Jaya');

13- Click on the venues table, click on the SQL menu and insert the values below. Be careful of whitespace if got error:
USE evenuehubdb;

-- Insert example venues
INSERT INTO venues (name, description, event_id, image, location_id, address, price, pax)
VALUES 
('Grand Conference Hall', 'A large hall perfect for conferences and meetings.', 1, 'image_1.jpg', 1, '123 Cyberjaya Road, Cyberjaya', 1500.00, 500),
('Elegant Meeting Room', 'An intimate room ideal for business meetings and small conferences.', 2, 'image_2.jpg', 2, '456 Petaling Jaya Street, Petaling Jaya', 800.00, 50),
('Majestic Wedding Venue', 'A beautiful venue with a stunning view, perfect for weddings.', 4, 'image_3.jpg', 3, '789 Shah Alam Avenue, Shah Alam', 2000.00, 300),
('Party Palace', 'A vibrant venue designed for parties and social gatherings.', 3, 'image_4.jpg', 4, '101 Subang Jaya Lane, Subang Jaya', 1200.00, 200);

14- Open the browser and enter “localhost/evenuehub/index.php”

15- Register your new account on top right of the webpage to use the features or use the admin account.

username: admin
password: admin1234

16- and you’re done.;

17- Alternatively, open localhost/phpMyAdmin on the web browser;

18-Click on SQL menu, and CREATE DATABASE evenuehubdb;

19- Click on the `evenuehubdb` database, click on the import menu, and import the evenuehubdb.sql file that provided in the folder and click `Import`.
