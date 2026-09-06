PHP OUTPUT #3 & #4 - PERSON REGISTRATION (DATABASE CONNECTED)
================================================================

WHAT THIS IS
------------
A registration form (Name, Age, Gender, Email, Address, Contact Number)
that is validated, connected to a MySQL database, saves submitted
records directly to the database, and displays a live list of all
registered persons below the form with Edit and Delete actions.

FILES
-----
index.php            -> Home page: registration form + list of records
edit.php             -> Edit an existing record
delete.php           -> Delete a record
includes/db.php      -> Database connection settings
assets/style.css     -> Styling
registration_db.sql  -> Database schema (import this first)

SETUP (XAMPP / WAMP / LAMP)
----------------------------
1. Start Apache and MySQL (e.g. via XAMPP Control Panel).
2. Open phpMyAdmin (http://localhost/phpmyadmin) and import
   "registration_db.sql". This creates the database
   "registration_db" and the "persons" table.
3. Copy the "php-output-3-4" folder into your server's web root
   (e.g. C:\xampp\htdocs\php-output-3-4).
4. If your MySQL username/password are different from the defaults,
   edit includes/db.php:
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "registration_db";
5. Visit http://localhost/php-output-3-4/index.php in your browser.

HOW IT WORKS
------------
- index.php validates all fields server-side (required fields,
  age range, email format, contact number format) before inserting
  into the "persons" table using a prepared statement.
- After a successful insert, the list below the form (also on
  index.php) refreshes automatically since it queries the database
  on every page load.
- Each row in the list has "Edit" and "Delete" buttons:
    - Edit opens edit.php?id=X, pre-fills the form with that
      record's data, validates, and updates the row on submit.
    - Delete removes the row after a confirmation prompt.

NOTES FOR SUBMISSION
---------------------
Remember the deliverables required by the instructor:
1. Screen recording during development
2. Screenshots of the output
3. Project folder (this whole "php-output-3-4" folder)
4. Exported database file (.sql) -> registration_db.sql is included,
   but re-export from phpMyAdmin after you've added sample records
   so it includes actual data if required.
