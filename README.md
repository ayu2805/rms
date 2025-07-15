# Resource Management System

A web-based Resource Management System with Admin, Employee, and Trainee roles.


## Stack

- **Frontend:** HTML, Bootstrap, JavaScript
- **Backend:** PHP
- **Database:** MariaDB

## Features

- Department & Post management
- Employee & Trainee management
- Attendance, Leave modules
- Inventory (Raw Items, Finished Products)
- Role-based dashboards (Admin/Employee/Trainee)

## Requirements

- PHP with `mysqli` extension
- MariaDB/MySQL
- Web server (Apache recommended)

## Setup Instructions

1. **Clone or Extract the Source Code**
   ```
   git clone https://github.com/ayu2805/rms
   cd rms/
   ```
2. **Import Schema and Sample Data:**

   ```
   mysql -u root -p < schema.sql
   mysql -u root -p < sampledata.sql
   ```

3. **Insert an admin user (run in MariaDB console)**:

    ```sql
    INSERT INTO Admin (username, password) VALUES ('admin', '{PASSWORD_HASH}');
    ```

    To generate `{PASSWORD_HASH}` in PHP shell(run: ```php -a```):
    ```php
    echo password_hash('youpassword', PASSWORD_DEFAULT);
    ```
3. **Configure Database Connection:**

   Edit `config.php` with your DB credentials:
   - Set ```DB_PASSWORD``` environment value with your MariaDB(root) password as value.
   - Example:
      + Set environment variable in bash:
         ```bash
         echo 'export DB_PASSWORD="yourpassword"' >> ~/.bashrc
         source ~/.bashrc
         ```
      + Set environment variable in fish:
         ```fish
         echo 'set -x DB_PASSWORD "yourpassword"' >> ~/.config/fish/config.fish
         ```
         or
         ```fish
         set -Ux DB_PASSWORD "yourpassword"
         ```

4. **Run the Application:**

   Copy all files to your web server's document root and go to you desired location(e.g http://localhost/).

**Directory structure:**

```
/ (root)
  |-- index.php
  |-- config.php
  |-- login.php
  |-- logout.php
  |-- schema.sql
  |-- /includes/
  |-- /admin/
  |-- /employee/
  |-- /trainee/
```