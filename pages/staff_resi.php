<?php
    // Ensure no output before header() is called

    // Handle Staff Add
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_GET['edit'])) {
        $firstName = $_POST['fname'];
        $lastName = $_POST['lname'];
        $email = $_POST['email'];
        $homeAddress = $_POST['homeAddress'];
        $date = $_POST['dateBirth'];
        $gender = $_POST['gender'];
        $position = $_POST['position'];
        $location = $_POST['location'];

        $sql = "INSERT INTO residence_staff (first_name, last_name, email,
                home_address, date_of_birth, gender, position, location) VALUES
                ('$firstName', '$lastName', '$email', '$homeAddress', '$date', '$gender',
                '$position', '$location')";
        
        if ($conn->query($sql) === TRUE) {
            // Redirect to prevent re-submission
            header("Location: staffs.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Edit Staff Logic
    if (isset($_GET['edit'])) {
        $staff_id = $_GET['edit'];
        
        // Fetch current staff data for editing
        $sql = "SELECT * FROM residence_staff WHERE staff_id = $staff_id";
        $result = $conn->query($sql);
        $staff = $result->fetch_assoc();

        // Check if form is submitted for update
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get updated staff data from the form
            $firstName = $_POST['fname'];
            $lastName = $_POST['lname'];
            $email = $_POST['email'];
            $homeAddress = $_POST['homeAddress'];
            $date = $_POST['dateBirth'];
            $gender = $_POST['gender'];
            $position = $_POST['position'];
            $location = $_POST['location'];

            // Update query
            $updateSql = "UPDATE residence_staff SET first_name = '$firstName', last_name = '$lastName',
            email = '$email', home_address = '$homeAddress', date_of_birth = '$date',
            gender = '$gender', position = '$position', location = '$location' WHERE staff_id = $staff_id";

            if ($conn->query($updateSql) === TRUE) {
                // Redirect to prevent re-submission
                header("Location: staffs.php");
                exit();
            } else {
                echo "Error updating staff: " . $conn->error;
            }
        }
    }

    // Delete Staff Logic
    if (isset($_GET['delete'])) {
        $staff_id = $_GET['delete'];
        $sql = "DELETE FROM residence_staff WHERE staff_id = $staff_id";

        if ($conn->query($sql) === TRUE) {
            // Redirect to prevent re-submission
            header("Location: staffs.php");
            exit();
        } else {
            echo "Error deleting staff: " . $conn->error;
        }
    }
?>

<h3>Manage Residence Staff</h3>

<div class="form-container">
<!-- Add Staff Form -->
<form action="" method="post">
    <h4>Add Staff</h4>
    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="lname" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="homeAddress" placeholder="Home Address" required>
    <input type="date" name="dateBirth" required>
    <input type="text" name="gender" placeholder="Male/Female/Other" required>
    <input type="text" name="position" placeholder="Position" required>
    <input type="text" name="location" placeholder="Location" required>
    <button type="submit">Add Staff</button>
</form>
</div>

 
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


<br><hr>

<h4 class="h4-header">List of Staff</h4>

<table border="1">
    <tr>
        <th>Staff ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Home Address</th>
        <th>Date of Birth</th>
        <th>Gender</th>
        <th>Position</th>
        <th>Location</th>
        <th>Actions</th>
    </tr>

    <?php
        // Fetch Staff Data
        $sql = "SELECT * FROM residence_staff";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>" . $row['staff_id'] . "</td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['home_address'] . "</td>
                        <td>" . $row['date_of_birth'] . "</td>
                        <td>" . $row['gender'] . "</td>
                        <td>" . $row['position'] . "</td>
                        <td>" . $row['location'] . "</td>
                        <td>
                            <a href='?edit={$row['staff_id']}'>Edit</a>
                            <a href='?delete={$row['staff_id']}'>Delete</a>
                        </td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='10'>No staff found</td></tr>";
        }
    ?>
</table>

<div class="form-container">
<?php

    // If editing, show the form with pre-filled data
    if (isset($_GET['edit'])) {
        echo "
        <h4>Edit Staff</h4>
        <form action='' method='post'>
            <input type='text' name='fname' value='{$staff['first_name']}' required>
            <input type='text' name='lname' value='{$staff['last_name']}' required>
            <input type='email' name='email' value='{$staff['email']}' required>
            <input type='text' name='homeAddress' value='{$staff['home_address']}' required>
            <input type='date' name='dateBirth' value='{$staff['date_of_birth']}' required>
            <input type='text' name='gender' value='{$staff['gender']}' required>
            <input type='text' name='position' value='{$staff['position']}' required>
            <input type='text' name='location' value='{$staff['location']}' required>
            <button type='submit'>Update Staff</button>
        </form>
        ";
    }
 
?>   
</div>
