<?php
    include '../includes/db_connect.php';
    include '../includes/header.php';
?>

<h2>Manage Rooms</h2>

<h3>Add Rooms</h3>

<!-- Add Room Form -->
<form action="" method="post">
    <input type="text" name="place_number" placeholder="Place Number" required>
    <input type="text" name="room_number" placeholder="Room Number" required>
    <input type="text" name="monthly_rent" placeholder="Monthly Rent" required>
    <input type="text" name="room_type" placeholder="Hall/Flat" required>
    <!--<input type="text" name="hall_id" placeholder="Hall ID">-->
    <select name="hall_id">
        <option value="">Select Hall</option>
        <?php
        // Fetch halls to populate dropdown
            $sql = "SELECT * FROM halls_of_residence";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['hall_id'] . "'>
                " . $row['name'] . "</option>";
            }
        ?>
    </select>
    <!--<input type="text" name="flat_id" placeholder="Flat ID">-->
    <select name="flat_id">
        <option value="">Select Flat</option>
        <?php
        // Fetch flats to populate dropdown
            $sql = "SELECT * FROM student_flats";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['flat_id'] . "'>
                " . $row['apartment_number'] . "</option>";
            }
        ?>
    </select>
    <button type="submit">Add Room</button>
</form>

<?php
// Add Room Logic
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $placeNum = $_POST['place_number'];
        $roomNum = $_POST['room_number'];
        $monthlyRent = $_POST['monthly_rent'];
        $roomType = $_POST['room_type'];
        $hall_id = $_POST['hall_id'];
        $flat_id = $_POST['flat_id'];

        $sql = "INSERT INTO rooms (place_number, room_number, monthly_rent,
        room_type, hall_id, flat_id) VALUES ('$placeNum', '$roomNum', '$monthlyRent',
        '$roomType', '$hall_id', '$flat_id')";

        if ($conn->query($sql) === TRUE){
            echo "Room added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<br><hr>

<h4>List</h4>

<table border="1">
    <tr>
        <th>Place Number</th>
        <th>Room Number</th>
        <th>Monthly Rent</th>
        <th>Room Type</th>
        <th>Hall Name</th>
        <th>Apartment Number</th>
        <th>Actions</th>
    </tr>

    <?php
        // Fetch Room Data
        $sql = "SELECT r.place_number, r.room_number, r.monthly_rent,
                r.room_type, hr.name AS hall_id, sf.apartment_number AS flat_id
                FROM ((rooms r
                JOIN halls_of_residence hr ON r.hall_id = hr.hall_id)
                JOIN student_flats sf ON r.flat_id = sf.flat_id)";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>" . $row['place_number'] . "</td>
                        <td>" . $row['room_number'] . "</td>
                        <td>" . $row['monthly_rent'] . "</td>
                        <td>" . $row['room_type'] . "</td>
                        <td>" . $row['hall_id'] . "</td>
                        <td>" . $row['flat_id'] . "</td>
                        <td>
                            <a href='?type=room&edit={$row['place_number']}'>Edit</a>
                            <a href='?type=room&delete={$row['place_number']}'>Delete</a>
                        </td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='9'>No rooms found</td></tr>";
        }
    ?>
</table>

<?php include '../includes/footer.php'; ?>