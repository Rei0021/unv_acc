<h3>Manage Halls of Residence</h3>

<!-- Add Hall Form -->
<form action="" method="post">
    <h4>Add Hall</h4>
    <input type="text" name="name" placeholder="Hall Name" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="phone_no" placeholder="Telephone" required> <!-- Ensure this name matches -->
    <input type="text" name="staff_id" placeholder="Manager Name" required> <!-- Ensure this name matches -->
    <button type="submit" name="add_hall">Add Hall</button>
</form>


<?php
// Add Hall Logic
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_hall'])) {
        $name = $_POST['name'] ?? null;
        $address = $_POST['address'] ?? null;
        $phone_no = $_POST['phone_no'] ?? null;
        $staff_id = $_POST['staff_id'] ?? null;

        if ($name && $address && $phone_no && $staff_id) {
            $sql = "INSERT INTO halls_of_residence (name, address, phone_no, staff_id) 
                    VALUES ('$name', '$address', '$phone_no', '$staff_id')";

            if ($conn->query($sql) === TRUE) {
                echo "Hall added successfully.";
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Please fill in all required fields.";
        }
    }

?>

<br><hr>

<h4>List</h4>

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
            echo "Flat deleted successfully";
            exit();
        } else {
            echo "Error deleting record" . $conn->error;
        }
    }
?>