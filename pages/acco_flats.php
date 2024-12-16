<?php
ob_start(); // Start output buffering
?>

<h3>Manage Student Flats</h3>
<div class="form-container">
<!-- Add Flat Form -->
<form action="" method="post">
    <h4>Add Flat</h4>
    <input type="text" name="apartment_number" placeholder="Apartment Number" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="number" name="total_bedrooms" placeholder="Total Bedrooms" required>
    <button type="submit" name="add_flat">Add Flat</button>
</form>
</div>

<?php
// Add Flat Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_flat'])) {
    $apartment_number = $_POST['apartment_number'];
    $address = $_POST['address'];
    $total_bedrooms = $_POST['total_bedrooms'];

    $sql = "INSERT INTO student_flats (apartment_number, address, total_bedrooms)
            VALUES ('$apartment_number', '$address', $total_bedrooms)";

    if ($conn->query($sql) === TRUE) {
        // Redirect after adding flat
        header("Location: accommodation.php?type=flat");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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

<h4 class="h4-header">List of Flats</h4>

<table border="1">
    <tr>
        <th>Flat ID</th>
        <th>Apartment Number</th>
        <th>Address</th>
        <th>Total Bedrooms</th>
        <th>Actions</th>
    </tr>

    <?php
    // Fetch Flat Data
    $sql = "SELECT * FROM student_flats";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['flat_id'] . "</td>
                    <td>" . $row['apartment_number'] . "</td>
                    <td>" . $row['address'] . "</td>
                    <td>" . $row['total_bedrooms'] . "</td>
                    <td>
                        <a href='?type=flat&edit=" . $row['flat_id'] . "'>Edit</a>
                        <a href='?type=flat&delete=" . $row['flat_id'] . "'>Delete</a>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='5'>No flats found</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php
// Edit Flat Logic
if (isset($_GET['edit'])) {
    $flat_id = $_GET['edit'];

    // Fetch current flat data for editing
    $sql = "SELECT * FROM student_flats WHERE flat_id = $flat_id";
    $result = $conn->query($sql);
    $flat = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_flat'])) {
        // Get updated flat data from the form
        $apartment_number = $_POST['apartment_number'];
        $address = $_POST['address'];
        $total_bedrooms = $_POST['total_bedrooms'];

        // Update query
        $updateSql = "UPDATE student_flats SET apartment_number = '$apartment_number', 
                      address = '$address', total_bedrooms = $total_bedrooms WHERE flat_id = $flat_id";

        if ($conn->query($updateSql) === TRUE) {
            // Redirect after updating flat
            header("Location: accommodation.php?type=flat");
            exit();
        } else {
            echo "Error updating flat: " . $conn->error;
        }
    }
    ?>

    <!-- Edit Flat Form (Pre-filled with existing data) -->
    <div class="form-container">
    <h4>Edit Flat</h4>
    <form action="" method="post">
        <input type="text" name="apartment_number" value="<?php echo $flat['apartment_number']; ?>" required>
        <input type="text" name="address" value="<?php echo $flat['address']; ?>" required>
        <input type="number" name="total_bedrooms" value="<?php echo $flat['total_bedrooms']; ?>" required>
        <button type="submit" name="edit_flat">Update Flat</button>
    </form>
</div>
<?php
}
?>

<?php
// Delete Flat Logic
if (isset($_GET['delete'])) {
    $flat_id = $_GET['delete'];
    $sql = "DELETE FROM student_flats WHERE flat_id = $flat_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect after deleting flat
        header("Location: accommodation.php?type=flat");
        exit();
    } else {
        echo "Error deleting flat: " . $conn->error;
    }
}
?>

<?php
ob_end_flush(); // End output buffering and send the output to the browser
?>