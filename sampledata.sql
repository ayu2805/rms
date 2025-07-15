-- Sample Data
USE rms;

-- Departments and Posts
INSERT INTO Posts_and_Departments (department_name, post)
VALUES 
('Human Resources', 'Manager'),
('Human Resources', 'Assistant'),
('IT', 'Developer'),
('IT', 'Support'),
('Production', 'Operator'),
('Production', 'Supervisor'),
('System', 'Trainee');

-- Employees
INSERT INTO Employees (pd_id, first_name, last_name, gender, age, contact_address, email, password, hire_date)
VALUES
(1, 'John', 'Doe', 'Male', 35, '123 Main St', 'johndoe@example.com', '$2y$12$d7Vc3odV9UvF9ulCtL3nCukspAkObfIhlLEalykZGZBd/LS3N0Kti', '2022-01-15'), -- pw: johndoe123
(3, 'Jane', 'Smith', 'Female', 28, '456 West Lane', 'janesmith@example.com', '$2y$12$wL5NRgGj3iqEzIZ0veLA/erEYdBwj/n.qmeLiPAWSoZNbjvQNpgfW', '2023-03-10'); -- pw: janesmith123

-- Trainees (with "Trainee" post)
INSERT INTO Trainees (pd_id, first_name, last_name, gender, age, contact_address, email, password, batch_id, start_date, end_date, trainer_employee_id, completion_status)
VALUES
(7, 'Alice', 'Wonder', 'Female', 22, '789 East Rd', 'alicewonder@example.com', '$2y$12$uET6D2PBnaaYosrhhpBBWOFiPYkRihpcZtaCMglh3Y1HZM5ujhluu', 'B2024', '2025-06-01', '2025-09-30', 1, 'Enrolled'); -- pw: trainee1

-- Suppliers
INSERT INTO Suppliers (supplier_name, contact_person, contact_email, contact_phone, address)
VALUES 
('Mega Supply Co', 'Tom Vendor', 'tom@megasupply.com', '555-1234', '22 Industrial Ave'),
('WidgetMart', 'Sara Widgets', 'sara@widgetmart.com', '555-5678', '77 Commerce Blvd');

-- Raw_Items
INSERT INTO Raw_Items (ri_name, quantity)
VALUES
('Steel', 1000),
('Plastic', 500);

-- Finishes_Products
INSERT INTO Finishes_Products (fp_name, quantity)
VALUES
('Widget', 200),
('Gadget', 150);