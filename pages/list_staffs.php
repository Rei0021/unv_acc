<?php
    include '../includes/db_connect.php';
    include '../includes/header.php';
?>

<h2>Staff Lists</h2>

<h3>Residence Staffs</h3>

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
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='9'>No flats found</td></tr>";
        }
    ?>
</table>

<br><hr>

<h3>Advisers</h3>

<table border="1">
    <tr>
        <th>Adviser ID</th>
        <th>Full Name</th>
        <th>Position</th>
        <th>Department Name</th>
        <th>Internal Phone Number</th>
        <th>Email</th>
        <th>Room Number</th>
    </tr>

    <?php
        // Fetch Adviser Data
        $sql = "SELECT * FROM advisers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>" . $row['adviser_id'] . "</td>
                        <td>" . $row['full_name'] . "</td>
                        <td>" . $row['position'] . "</td>
                        <td>" . $row['department_name'] . "</td>
                        <td>" . $row['internal_phone_no'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['room_number'] . "</td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='8'>No advisers found</td></tr>";
        }
    ?>
</table>

<?php include '../includes/footer.php'; ?>