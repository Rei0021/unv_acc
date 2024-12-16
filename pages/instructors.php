<?php
include '../includes/db_connect.php';

// Add Instructor Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_instructor'])) {
    $name = $_POST['name'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $instructor_room = $_POST['instructor_room'];

    $sql = "INSERT INTO instructors (name, phone_no, email, instructor_room)
            VALUES ('$name', '$phone_no', '$email', '$instructor_room')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect after adding the instructor
        header("Location: course_related.php?type=nstrctr");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Edit Instructor Logic
if (isset($_GET['edit'])) {
    $instructor_id = $_GET['edit'];

    // Fetch the instructor data to populate the form for editing
    $sql = "SELECT * FROM instructors WHERE instructor_id = '$instructor_id'";
    $result = $conn->query($sql);
    $instructor = $result->fetch_assoc();
}

// Update Instructor Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_instructor'])) {
    $instructor_id = $_POST['instructor_id'];
    $name = $_POST['name'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $instructor_room = $_POST['instructor_room'];

    $sql = "UPDATE instructors
            SET name = '$name', phone_no = '$phone_no', email = '$email', instructor_room = '$instructor_room'
            WHERE instructor_id = '$instructor_id'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect after updating the instructor
        header("Location: course_related.php?type=nstrctr");
        exit();
    } else {
        echo "Error updating instructor: " . $conn->error;
    }
}

// Delete Instructor Logic
if (isset($_GET['delete'])) {
    $instructor_id = $_GET['delete'];
    $sql = "DELETE FROM instructors WHERE instructor_id = '$instructor_id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect after deleting the instructor
        header("Location: course_related.php?type=nstrctr");
        exit();
    } else {
        echo "Error deleting instructor: " . $conn->error;
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


<div class="form-container">
<h3>Manage Instructor</h3>

<h4 class="h4-header">Add Instructor</h4>

<form action="" method="post">
    <input type="text" name="name" placeholder="Name" value="<?php echo isset($instructor) ? $instructor['name'] : ''; ?>" required>
    <input type="text" name="phone_no" placeholder="Phone Number" value="<?php echo isset($instructor) ? $instructor['phone_no'] : ''; ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?php echo isset($instructor) ? $instructor['email'] : ''; ?>" required>
    <input type="text" name="instructor_room" placeholder="Instructor Room" value="<?php echo isset($instructor) ? $instructor['instructor_room'] : ''; ?>" required>

    <?php if (isset($instructor)): ?>
        <input type="hidden" name="instructor_id" value="<?php echo $instructor['instructor_id']; ?>">
        <button type="submit" name="edit_instructor">Update Instructor</button>
    <?php else: ?>
        <button type="submit" name="add_instructor">Add Instructor</button>
    <?php endif; ?>
</form>
    </div>

<br><hr>

<h4 class="h4-header">List of Instructors</h4>

<table border="1">
    <tr>
        <th>Instructor ID</th>
        <th>Name</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Instructor's Room</th>
        <th>Actions</th>
    </tr>

    <?php
    // Fetch Instructor Data
    $sql = "SELECT * FROM instructors";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['instructor_id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['phone_no'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['instructor_room'] . "</td>
                    <td>
                        <a href='?type=nstrctr&edit={$row['instructor_id']}'>Edit</a>
                        <a href='?type=nstrctr&delete={$row['instructor_id']}'>Delete</a>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='6'>No instructors found</td></tr>";
    }
    ?>
</table>
