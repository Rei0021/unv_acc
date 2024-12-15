<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/db_connect.php';
include '../includes/header.php';

$error_message = ''; // Initialize error message variable
$success_message = ''; // Initialize success message variable
?>

<h3 class="h3-studentlist">Student List</h3>
<table>
    <tr>
        <th>Banner Number</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    // Define the SQL query
    $sql = "SELECT student_id, banner_number, first_name, last_name, email, current_status FROM students";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result && $result->num_rows > 0) {
        // Loop through each row and display the student details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['banner_number']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['current_status']}</td>
                <td>
                    <a href='student_details.php?student_id={$row['student_id']}'>See More</a>
                </td>
            </tr>
            <tr id='details-{$row['student_id']}' style='display:none;'>
                <td colspan='6' id='details-content-{$row['student_id']}'></td>
            </tr>";
        }
    } else {
        // Display a message if no students are found
        echo "<tr><td colspan='6'>No students found</td></tr>";
    }
    ?>
</table>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Accommodation Management System - Manage Students</title>
    <style>
        .h2-header {
            text-align: center;
        }

        .form-section {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            width: 50%;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        button {
            background-color: #f1485b;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: white;
            color: #f1485b;
            border: 1px solid #f1485b;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .success-message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            margin: 20px auto;
            width: 80%;
            text-align: center;
            border-radius: 5px;
        }

        .error-message {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            margin: 20px auto;
            width: 80%;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>
<body>

</body>
</html>
