# Create a text file with the setup guide for the PHP project
guide_content = """\
Guide to Clone and Set Up PHP Project

Prerequisites:
1. Install XAMPP: Download and install XAMPP from https://www.apachefriends.org/index.html.
2. Install Composer: Download and install Composer from https://getcomposer.org/download/.
3. Install Git: Ensure you have Git installed on your machine from https://git-scm.com/downloads.

Steps to Clone and Set Up the Project:

1. Open Command Prompt/Terminal:
   - On Windows: Search for `cmd` and open Command Prompt.
   - On Mac/Linux: Open the Terminal.

2. Navigate to XAMPP's `htdocs` Directory:
   cd C:\\xampp\\htdocs

3. Clone the Repository:
   - Replace `your-repo-url.git` with the actual URL of your Git repository.
   git clone your-repo-url.git

4. Navigate to the Project Directory:
   cd your-project-directory

5. Install Composer Dependencies:
   - Ensure you are in the project root directory (where `composer.json` is located).
   composer install

6. Create a Database:
   - Open your web browser and go to http://localhost/phpmyadmin.
   - Click on "Databases" and create a new database (e.g., `fms`).
   - Import the SQL file if you have one (usually named `database.sql` in your project folder).

7. Update Database Configuration:
   - Locate your database configuration file (usually `config.php` or similar in your project).
   - Update the database connection settings to match the new database you've created:
     - `$dbHost`= 'localhost';
     - `$dbUser` = 'root';
     - `$dbPass` = '';
     - `$dbName` = 'fms';

8. Start XAMPP:
   - Open the XAMPP Control Panel and start the Apache and MySQL services.

9. Access the Project:
   - Open your web browser and go to:
     `http://localhost/fms`
