<?php
    include '../includes/db_connect.php';
?>

<h3>Manage Advisers</h3>

<!-- Add Adviser Form -->
<form action="" method="post">
    <h4>Add Adviser</h4>
    <input type="text" name="fullName" placeholder="Full Name" required>
    <input type="text" name="position" placeholder="Position" required>
    <input type="text" name="deptName" placeholder="Dept. Name" required>
    <input type="text" name="internal_phone_no" placeholder="Phone number" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="roomNum" placeholder="Room Number" required>
    <button type="submit">Add Adviser</button>
</form>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullName = $_POST['fullName'];
        $position = $_POST['position'];
        $deptName = $_POST['deptName'];
        $internal_phone_no = $_POST['internal_phone_no'];
        $email = $_POST['email'];
        $roomNum = $_POST['roomNum'];

        $sql = "INSERT INTO advisers (full_name, position, department_name,
        internal_phone_no, email, room_number) VALUES ('$fullName', '$position',
        '$deptName', '$internal_phone_no', '$email', '$roomNum')";

        if ($conn->query($sql) === TRUE) {
            echo "Adviser added successfully.";
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
                        <td>
                            <a href='?type=advsr&edit={$row['staff_id']}'>Edit</a>
                            <a href='?type=advsr&delete={$row['staff_id']}'>Delete</a>
                        </td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='8'>No advisers found</td></tr>";
        }
    ?>
</table>