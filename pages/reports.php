
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
}

.rep-font {
    text-align: center;
}

.h3 {
    color: black;
}

.report-container {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 40px auto;
    width: 80%;
    max-width: 600px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.report-container nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: center;
}

.report-container nav ul li {
    margin: 10px 0;
}

.report-container nav ul li a {
    text-decoration: none;
    color: #f1485b;
    font-size: 1em;
    padding: 10px 20px;
    border: 1px solid #f1485b;
    border-radius: 4px;
    display: inline-block;
    transition: all 0.3s ease;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.report-container nav ul li a:hover {
    background-color: #f1485b;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    transform: translateY(-3px);
}

/* Additional Section Styles */
.report-section {
    margin-top: 30px;
    padding: 15px;
    border-radius: 8px;
    background-color: #f1f1f1;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.report-section h3 {
    text-align: center;
    color: #333;
}

.report-section table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.report-section table, .report-section th, .report-section td {
    border: 1px solid #ddd;
}

.report-section th, .report-section td {
    padding: 10px;
    text-align: left;
}

.report-section th {
    background-color: #f1485b;
    color: white;
}

.report-section tr:nth-child(even) {
    background-color: #f9f9f9;
}

.report-section tr:hover {
    background-color: #f1f1f1;
}

.report-section p {
    text-align: center;
    color: #777;
    font-style: italic;
}

/* Additional Table Section Styles */
.table-section {
    margin-top: 30px;
    padding: 15px;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.table-section h4 {
    text-align: center;
    color: #333;
    margin-bottom: 15px;
}

.table-section table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table-section table, .table-section th, .table-section td {
    border: 1px solid #ddd;
}

.table-section th, .table-section td {
    padding: 10px;
    text-align: left;
}

.table-section th {
    background-color: #f1485b;
    color: white;
}

.table-section tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table-section tr:hover {
    background-color: #f1f1f1;
}

.table-section p {
    text-align: center;
    color: #777;
    font-style: italic;
}


</style>



<body>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php

include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2 class="rep-font">Reports</h2>

<!-- Navigation for Reports -->
 <div class="report-container">
    <nav>
        <ul>
            <li><a href="?report=waiting_list">Students on Waiting List</a></li>
            <li><a href="?report=available_rooms">Available Rooms</a></li>
            <li><a href="?report=unpaid_invoices">Unpaid Invoices</a></li>
            <li><a href="?report=rent_summary">Rent Summary</a></li>
        </ul>
    </nav>
 </div>


<hr>

<?php
// Determine which report to display
$report = $_GET['report'] ?? 'waiting_list';

if ($report === 'waiting_list') {
    echo "<div class='report-section'>";
    echo "<h3>Students on Waiting List</h3>";
    $result = $conn->query("SELECT banner_number, first_name, last_name, email, current_status 
                            FROM students WHERE current_status = 'Waiting'");
    if ($result->num_rows > 0) {
        echo "<table>
                <tr><th>Banner Number</th><th>Name</th><th>Email</th><th>Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['banner_number']}</td>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['current_status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No students on the waiting list.</p>";
    }
    echo "</div>";
}


if ($report === 'available_rooms') {
    echo "<div class='report-section'>";
    echo "<h3>Available Rooms</h3>";
    $hall_rooms = $conn->query("SELECT hall_rooms.room_number, halls_of_residence.name AS hall_name 
                                FROM hall_rooms 
                                JOIN halls_of_residence ON hall_rooms.hall_id = halls_of_residence.hall_id 
                                WHERE hall_rooms.room_id NOT IN (SELECT room_id FROM leases)");
    $flat_rooms = $conn->query("SELECT flat_rooms.room_number, student_flats.apartment_number 
                                FROM flat_rooms 
                                JOIN student_flats ON flat_rooms.flat_id = student_flats.flat_id 
                                WHERE flat_rooms.room_id NOT IN (SELECT room_id FROM leases)");
    
    echo "<h4>Halls</h4>";
    if ($hall_rooms->num_rows > 0) {
        echo "<table>
                <tr><th>Room Number</th><th>Hall Name</th></tr>";
        while ($row = $hall_rooms->fetch_assoc()) {
            echo "<tr>
                    <td>Room {$row['room_number']}</td>
                    <td>{$row['hall_name']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No available rooms in halls.</p>";
    }

    echo "<h4>Flats</h4>";
    if ($flat_rooms->num_rows > 0) {
        echo "<table>
                <tr><th>Room Number</th><th>Apartment Number</th></tr>";
        while ($row = $flat_rooms->fetch_assoc()) {
            echo "<tr>
                    <td>Room {$row['room_number']}</td>
                    <td>{$row['apartment_number']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No available rooms in flats.</p>";
    }
    echo "</div>";
}

if ($report === 'unpaid_invoices') {
    echo "<div class='report-section'>";
    echo "<h3>Unpaid Invoices</h3>";
    $sql = "SELECT invoices.invoice_number, students.first_name, students.last_name, invoices.payment_due
            FROM invoices
            JOIN students ON invoices.student_id = students.student_id
            WHERE invoices.payment_date IS NULL";

    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        echo "<table>
                <tr><th>Invoice Number</th><th>Student Name</th><th>Amount Due</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['invoice_number']}</td>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['payment_due']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No unpaid invoices.</p>";
    }
    echo "</div>";
}

if ($report === 'rent_summary') {
    echo "<div class='report-section'>";
    echo "<h3>Rent Summary</h3>";

    // Halls Section
    echo "<h4>Halls</h4>";
    echo "<table>
            <tr>
                <th>Place Number</th>
                <th>Room Number</th>
                <th>Monthly Rent</th>
                <th>Room Type</th>
                <th>Hall Name</th>
            </tr>";

    $sql = "SELECT r.place_number, r.room_number, r.monthly_rent,
                r.room_type, hr.name AS hall_id
            FROM rooms r
            JOIN halls_of_residence hr ON r.hall_id = hr.hall_id
            WHERE r.room_type = 'Hall'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>{$row['place_number']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$row['monthly_rent']}</td>
                    <td>{$row['room_type']}</td>
                    <td>{$row['hall_id']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No rooms found</td></tr>";
    }
    echo "</table>";

    // Flats Section
    echo "<h4>Flats</h4>";
    echo "<table>
            <tr>
                <th>Place Number</th>
                <th>Room Number</th>
                <th>Monthly Rent</th>
                <th>Apartment Number</th>
            </tr>";

    $sql = "SELECT r.place_number, r.room_number, r.monthly_rent,
                r.room_type, sf.apartment_number AS flat_id
            FROM rooms r
            JOIN student_flats sf ON r.flat_id = sf.flat_id
            WHERE r.room_type = 'Flat'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>{$row['place_number']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$row['monthly_rent']}</td>
                    <td>{$row['flat_id']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No rooms found</td></tr>";
    }
    echo "</table>";

    // Total Rent Section
    echo "<h4>Total Rent Value</h4>";
    echo "<table>
            <tr><th>Total Rent Value</th></tr>";

    $sql = "SELECT SUM(monthly_rent) AS total_rent FROM rooms";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo "<tr><td>{$row['total_rent']}</td></tr>";
        }
    } else {
        echo "<tr><td colspan='1'>No total amount found</td></tr>";
    }
    echo "</table>";

    echo "</div>";
}


?>

