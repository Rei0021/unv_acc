<?php
include '../includes/db_connect.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $adviserId = $_GET['delete'];
    $deleteSql = "DELETE FROM advisers WHERE adviser_id = $adviserId";

    if ($conn->query($deleteSql) === TRUE) {
        echo "Adviser deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle Edit Action
if (isset($_GET['edit'])) {
    $adviserId = $_GET['edit'];
    $selectSql = "SELECT * FROM advisers WHERE adviser_id = $adviserId";
    $result = $conn->query($selectSql);
    $adviser = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update adviser data
        $fullName = $_POST['full_name'];
        $position = $_POST['position'];
        $deptName = $_POST['department_name'];
        $internalPhoneNumber = $_POST['internal_phone_number'];
        $email = $_POST['email'];
        $roomNumber = $_POST['room_number'];

        $updateSql = "UPDATE advisers SET full_name = '$fullName', position = '$position', department_name = '$deptName', 
                      internal_phone_number = '$internalPhoneNumber', email = '$email', room_number = '$roomNumber' 
                      WHERE adviser_id = $adviserId";

        if ($conn->query($updateSql) === TRUE) {
            // Redirect after update to avoid form resubmission
            header("Location: staffs.php?type=advsr"); // Change this URL to your page
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
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
    h2{
        text-align: center;
    }
    
.h4-header {
            text-align: center;
        }

table {
    margin: 0 auto; 
    border-collapse: collapse; 
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

.ml-container{
    text-align: center;

}

.form-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 15px; /* Even spacing between elements */
    width: 90%;
    max-width: 400px;
    margin: auto; /* Center horizontally */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9fc;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
}

/* Style the title */
.form-container h3 {
    margin: 0;
    color: #34495e;
    text-align: center;
}

/* Style the input, select, and textarea */
.form-container input,
.form-container select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Style the button */
.form-container button {
    width: 100%;
    padding: 10px;
    background-color: #f1485b;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

/* Button hover effect */
.form-container button:hover {
    background-color: white;
    color: #f1485b;
    border: 1px solid #f1485b;
}

</style>


<h3>Manage Advisers</h3>
<div class="form-container">
<!-- Add Adviser Form -->
<form action="" method="post">
    <h4>Add Adviser</h4>
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="text" name="position" placeholder="Position" required>
    <input type="text" name="department_name" placeholder="Dept. Name" required>
    <input type="text" name="internal_phone_number" placeholder="Phone Number" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="room_number" placeholder="Room Number" required>
    <button type="submit">Add Adviser</button>
</form>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure fields exist in the POST request
    $fullName = $_POST['full_name'] ?? null;
    $position = $_POST['position'] ?? null;
    $deptName = $_POST['department_name'] ?? null;
    $internalPhoneNumber = $_POST['internal_phone_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $roomNumber = $_POST['room_number'] ?? null;

    // Validate that all fields are filled
    if ($fullName && $position && $deptName && $internalPhoneNumber && $email && $roomNumber) {
        $sql = "INSERT INTO advisers (full_name, position, department_name, internal_phone_number, email, room_number)
                VALUES ('$fullName', '$position', '$deptName', '$internalPhoneNumber', '$email', '$roomNumber')";

        if ($conn->query($sql) === TRUE) {
            echo "Adviser added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "All fields are required.";
    }
}

?>

<br><hr>


<h4 class="h4-header">List of Advisers</h4>

<table border="1">
    <tr>
        <th>Adviser ID</th>
        <th>Full Name</th>
        <th>Position</th>
        <th>Department Name</th>
        <th>Internal Phone Number</th>
        <th>Email</th>
        <th>Room Number</th>
        <th>Actions</th>
    </tr>

    <?php
    // Fetch Adviser Data
    $sql = "SELECT * FROM advisers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['adviser_id'] . "</td>
                    <td>" . $row['full_name'] . "</td>
                    <td>" . $row['position'] . "</td>
                    <td>" . $row['department_name'] . "</td>
                    <td>" . $row['internal_phone_number'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['room_number'] . "</td>
                    <td>
                        <a href='?type=advsr&edit={$row['adviser_id']}'>Edit</a>
                        <a href='?type=advsr&delete={$row['adviser_id']}'>Delete</a>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='8'>No advisers found</td></tr>";
    }
    ?>
</table>

</body>
</html>
<div class="form-container">
<?php
// Edit Form
if (isset($adviser)) {
    echo "
    <h4>Edit Adviser</h4>
    <form action='' method='post'>
        <input type='text' name='full_name' value='{$adviser['full_name']}' required>
        <input type='text' name='position' value='{$adviser['position']}' required>
        <input type='text' name='department_name' value='{$adviser['department_name']}' required>
        <input type='text' name='internal_phone_number' value='{$adviser['internal_phone_number']}' required>
        <input type='email' name='email' value='{$adviser['email']}' required>
        <input type='text' name='room_number' value='{$adviser['room_number']}' required>
        <button type='submit'>Update Adviser</button>
    </form>
    ";
}
?>
</div>
