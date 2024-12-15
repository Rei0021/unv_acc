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

<h3>Manage Instructor</h3>

<h4>Add Instructor</h4>

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

<br><hr>

<h4>List of Instructors</h4>

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