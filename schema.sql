-- Resource Management System Schema + Sample Data

DROP DATABASE IF EXISTS rms;
CREATE DATABASE rms;
USE rms;

-- Admin Table
CREATE TABLE Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Posts_and_Departments
CREATE TABLE Posts_and_Departments (
    pd_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    post VARCHAR(100) NOT NULL
);

-- Employees
CREATE TABLE Employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    pd_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male','Female','Other'),
    age INT,
    contact_address VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    hire_date DATE NOT NULL,
    FOREIGN KEY (pd_id) REFERENCES Posts_and_Departments(pd_id) ON DELETE SET NULL
);

-- Attendance
CREATE TABLE Attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    attendance_date DATE NOT NULL,
    status ENUM('Present','Absent','Half-day','Leave') NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES Employees(employee_id) ON DELETE CASCADE
);

-- Leaves
CREATE TABLE Leaves (
    leave_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending','Approved','Denied') NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (employee_id) REFERENCES Employees(employee_id) ON DELETE CASCADE,
);

-- Payroll_Records
CREATE TABLE Payroll_Records (
    payroll_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    net_pay DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES Employees(employee_id) ON DELETE CASCADE
);

-- Trainees
CREATE TABLE Trainees (
    trainee_id INT AUTO_INCREMENT PRIMARY KEY,
    pd_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male','Female','Other'),
    age INT,
    contact_address VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    batch_id VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    trainer_employee_id INT NULL,
    completion_status ENUM('Enrolled','In Progress','Completed','Dropped') NOT NULL DEFAULT 'Enrolled',
    FOREIGN KEY (pd_id) REFERENCES Posts_and_Departments(pd_id) ON DELETE SET NULL,
    FOREIGN KEY (trainer_employee_id) REFERENCES Employees(employee_id) ON DELETE SET NULL
);

-- Suppliers
CREATE TABLE Suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) UNIQUE NOT NULL,
    contact_person VARCHAR(100),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    address VARCHAR(255)
);

-- Raw_Items
CREATE TABLE Raw_Items (
    ri_id INT AUTO_INCREMENT PRIMARY KEY,
    ri_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0
);

-- Finishes_Products
CREATE TABLE Finishes_Products (
    fp_id INT AUTO_INCREMENT PRIMARY KEY,
    fp_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0
);