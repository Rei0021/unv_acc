<h3>Manage Halls of Residence</h3>

<!-- Add Hall Form -->
<form action="" method="post">
    <h4>Add Hall</h4>
    <input type="text" name="name" placeholder="Hall Name" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="telephone" placeholder="Telephone" required> <!-- Ensure this name matches -->
    <input type="text" name="manager_name" placeholder="Manager Name" required> <!-- Ensure this name matches -->
    <button type="submit" name="add_hall">Add Hall</button>
</form>

<?php
// Add Hall Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_hall'])) {
    $name = $_POST['name'] ?? null;
    $address = $_POST['address'] ?? null;
    $telephone = $_POST['telephone'] ?? null;
    $manager_name = $_POST['manager_name'] ?? null;

    if ($name && $address && $telephone && $manager_name) {
        $sql = "INSERT INTO halls_of_residence (name, address, telephone, manager_name) 
                VALUES ('$name', '$address', '$telephone', '$manager_name')";

        if ($conn->query($sql) === TRUE) {
            // Redirect after adding hall
            header("Location: accommodation.php?type=hall"); // Replace "accommodation.php?type=hall" with your actual page URL
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

<?php
// Edit Hall Logic
if (isset($_GET['edit'])) {
    $hall_id = $_GET['edit'];

    // Fetch current hall data for editing
    $sql = "SELECT * FROM halls_of_residence WHERE hall_id = $hall_id";
    $result = $conn->query($sql);
    $hall = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_hall'])) {
        // Get updated hall data from the form
        $name = $_POST['name'] ?? null;
        $address = $_POST['address'] ?? null;
        $telephone = $_POST['telephone'] ?? null;
        $manager_name = $_POST['manager_name'] ?? null;

        // Update query
        $updateSql = "UPDATE halls_of_residence SET name = '$name', address = '$address',
        telephone = '$telephone', manager_name = '$manager_name' WHERE hall_id = $hall_id";

        if ($conn->query($updateSql) === TRUE) {
            // Redirect after updating hall
            header("Location: accommodation.php?type=hall"); // Redirect to the halls list page
            exit();
        } else {
            echo "Error updating hall: " . $conn->error;
        }
    }
    ?>

    <!-- Edit Hall Form (Pre-filled with existing data) -->
    <h4>Edit Hall</h4>
    <form action="" method="post">
        <input type="text" name="name" value="<?php echo $hall['name']; ?>" required>
        <input type="text" name="address" value="<?php echo $hall['address']; ?>" required>
        <input type="text" name="telephone" value="<?php echo $hall['telephone']; ?>" required>
        <input type="text" name="manager_name" value="<?php echo $hall['manager_name']; ?>" required>
        <button type="submit" name="edit_hall">Update Hall</button>
    </form>

<?php
}
?>

<br><hr>

<h4>List of Halls</h4>

<table border="1">
    <tr>    
        <th>Hall ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone Number</th>
        <th>Staff ID</th>
        <th>Actions</th>
    </tr>

    <?php
    // Fetch Hall Data
    $sql = "SELECT * FROM halls_of_residence";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['hall_id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['address'] . "</td>
                    <td>" . $row['telephone'] . "</td>
                    <td>" . $row['manager_name'] . "</td>
                    <td>
                        <a href='?type=hall&edit=" . $row['hall_id'] . "'>Edit</a>
                        <a href='?type=hall&delete=" . $row['hall_id'] . "'>Delete</a>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='6'>No halls found</tr>";
    }
    ?>
</table>

<?php
// Delete Hall Logic
if (isset($_GET['delete'])) {
    $hall_id = $_GET['delete'];
    $sql = "DELETE FROM halls_of_residence WHERE hall_id = $hall_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect after deleting hall
        header("Location: accommodation.php?type=hall"); // Redirect to the halls list page
        exit();
    } else {
        echo "Error deleting hall: " . $conn->error;
    }
}
?>
