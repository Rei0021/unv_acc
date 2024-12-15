<h3>Manage Residence Staff</h3>

<!-- Add Staff Form -->
<form action="" method="post">
    <h4>Add Staff</h4>
    <input type="text" name="fname" placeholder="firstName" required>
    <input type="text" name="lname" placeholder="lastName" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="homeAddress" placeholder="Home Address" required>
    <input type="date" name="dateBirth" required>
    <input type="text" name="gender" placeholder="Male/Female/Other" required>
    <input type="text" name="position" placeholder="Position" required>
    <input type="text" name="location" placeholder="Location" required>
    <button type="submit">Add Staff</button>
</form>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            echo "Staff added successfully.";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
     }
?>

<br><hr>

<h4>List</h4>

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
                            <a href='?type=resi&edit={$row['staff_id']}'>Edit</a>
                            <a href='?type=resi&delete={$row['staff_id']}'>Delete</a>
                        </td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='9'>No staffs found</td></tr>";
        }
    ?>
</table>

<?php
    // Edit Staff Logic
    if (isset($_GET['edit'])) {
        $staff_id = $_GET['edit'];
    
        $sql = "UPDATE residence_staff SET first_name = '$firstName', last_name = '$lastName',
        email = '$email', home_address = '$homeAddress', date_of_birth = '$date',
        gender = '$gender', position ='$position', location = '$location' WHERE flat_id = $flat_id";

        if ($conn->query($sql) === TRUE) {
            echo "Flat deleted successfully";
            exit();
        } else {
            echo "Error deleting record" . $conn->error;
        }
    }
?>

<?php
    // Delete Staff Logic
    if (isset($_GET['delete'])) {
        $staff_id = $_GET['delete'];
        $sql = "DELETE FROM residence_staff WHERE staff_id = $staff_id";

        if ($conn->query($sql) === TRUE) {
            echo "Staff deleted successfully";
            exit();
        } else {
            echo "Error deleting record" . $conn->error;
        }
    }
?>