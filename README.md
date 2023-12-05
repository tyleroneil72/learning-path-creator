# Learning Path Creator
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Linux](https://img.shields.io/badge/Linux-FCC624?style=for-the-badge&logo=linux&logoColor=black)
![Apache](https://img.shields.io/badge/Apache-404D59?style=for-the-badge)

## Description
This project was an assignment for an 'Advanced Web Programming' course. The Learning Path Creator is a web application designed to facilitate collaborative learning and knowledge sharing. This project empowers educators and learners alike to create, import, and explore tailored learning paths. Users can curate resources, share insights, and engage with a community-driven platform that enhances the educational experience. This report provides insights into the project's development, functionalities, and additional notes.

This project when deployed operates on the LAMP stack, leveraging Linux, Apache web server, MySQL, and PHP. Additionally, Bootstrap is employed for frontend development, ensuring a visually appealing and responsive website. Although it is not hosted at the moment

## Example
<img width="1424" alt="github-picture" src="https://github.com/tyleroneil72/learning-path-creator/assets/43754564/43832ebe-f4bc-4fe1-950d-7b169e811ae1">

## Configuration And Setup
```php
// db/config.php
$host = "localhost"; // Change this to your host if not "localhost"
$username = "root"; // Change this to your user if not "root"
$password = ""; // Change this to your password if you have set one
$database = "project_db"; // Change this to your database name

$conn = new mysqli($host, $username, $password, $database);
```
Also, Make sure to run [`create_db.sql`](https://github.com/tyleroneil72/learning-path-creator/blob/main/create_db.sql) in the database
## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Contact
For any inquiries or questions, you can reach me at tyleroneildev@gmail.com
or on my linkedin at https://ca.linkedin.com/in/tyler-oneil-dev
