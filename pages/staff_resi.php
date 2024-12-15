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

<br><hr>

<h4>List of Staff</h4>

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
