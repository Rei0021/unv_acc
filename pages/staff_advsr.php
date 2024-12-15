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

<h3>Manage Advisers</h3>

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

<h4>List of Advisers</h4>

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
