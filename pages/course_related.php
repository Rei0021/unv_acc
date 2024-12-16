<?php
    include '../includes/db_connect.php';
    include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
body {
        margin: 0;
        padding: 0;
        display: wrap;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Full viewport height */
        background-color: #f9f9f9;
        font-family: Arial, sans-serif;
    }

    .mg-navbar {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f9f9f9;
        margin-bottom: 20px;
    }

    .mg-navbar nav {
        background-color: #f1485b;
        padding: 10px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }

    .mg-navbar nav a {
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
    }

    .mg-navbar nav a:hover {
        text-decoration: none;
        color: #212f3c;
    }

    .mc-cont {
        text-align: center;
    }
</style>
    
</body>
</html>

<h2 class="mc-cont">Manage Courses</h2>

<!-- Tabs or mini nav-->
 <div class="mg-navbar">
 <nav>
    <a href="?type=crs">Courses</a> |
    <a href="?type=nstrctr">Instructor</a>
 </nav>   
 </div>
<?php
    $type = $_GET['type'] ?? 'crs'; // Default to courses
    if ($type === 'crs') {
        include 'courses.php';
    } else {
        include 'instructors.php';
    }
?>

<?php include '../includes/footer.php'; ?>