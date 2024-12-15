<?php
include '../includes/db_connect.php';

// Add Course Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $courseNum = $_POST['course_number'];
    $courseTitle = $_POST['course_title'];
    $instructor_id = $_POST['instructor_id'];
    $dept_name = $_POST['dept_name'];

    $sql = "INSERT INTO courses (course_number, course_title, instructor_id, department_name) 
            VALUES ('$courseNum', '$courseTitle', '$instructor_id', '$dept_name')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect after adding the course
        header("Location: course_related.php?type=crs");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Edit Course Logic
if (isset($_GET['edit'])) {
    $course_number = $_GET['edit'];

    // Fetch the course data to populate the form for editing
    $sql = "SELECT * FROM courses WHERE course_number = '$course_number'";
    $result = $conn->query($sql);
    $course = $result->fetch_assoc();
}

// Update Course Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_course'])) {
    $courseNum = $_POST['course_number'];
    $courseTitle = $_POST['course_title'];
    $instructor_id = $_POST['instructor_id'];
    $dept_name = $_POST['dept_name'];

    $sql = "UPDATE courses 
            SET course_title = '$courseTitle', instructor_id = '$instructor_id', department_name = '$dept_name' 
            WHERE course_number = '$courseNum'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect after updating the course
        header("Location: course_related.php?type=crs");
        exit();
    } else {
        echo "Error updating course: " . $conn->error;
    }
}

// Delete Course Logic
if (isset($_GET['delete'])) {
    $course_number = $_GET['delete'];
    $sql = "DELETE FROM courses WHERE course_number = '$course_number'";

    if ($conn->query($sql) === TRUE) {
        // Redirect after deleting the course
        header("Location: course_related.php?type=crs");
        exit();
    } else {
        echo "Error deleting course: " . $conn->error;
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

h2{
        text-align: center;
    }

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




 <div class="form-container">

<h4 class="h4-header">Add Course</h4>

<form action="" method="post">
    <input type="text" name="course_number" placeholder="Course Number" value="<?php echo isset($course) ? $course['course_number'] : ''; ?>" required>
    <input type="text" name="course_title" placeholder="Course Title" value="<?php echo isset($course) ? $course['course_title'] : ''; ?>" required>

    <select name="instructor_id">
        <option value="">Select Instructor</option>
        <?php
        // Fetch instructors to populate dropdown
        $sql = "SELECT * FROM instructors";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $selected = (isset($course) && $course['instructor_id'] == $row['instructor_id']) ? 'selected' : '';
            echo "<option value='" . $row['instructor_id'] . "' $selected>" . $row['name'] . "</option>";
        }
        ?>
    </select>

    <input type="text" name="dept_name" placeholder="Department Name" value="<?php echo isset($course) ? $course['department_name'] : ''; ?>" required>

    <?php if (isset($course)): ?>
        <button type="submit" name="edit_course">Update Course</button>
    <?php else: ?>
        <button type="submit" name="add_course">Add Course</button>
    <?php endif; ?>
</form>
    </div>
<br><hr>

<h4 class="h4-header">List of Courses</h4>

<table border="1">
    <tr>
        <th>Course Number</th>
        <th>Course Title</th>
        <th>Instructor</th>
        <th>Department Name</th>
        <th>Actions</th>
    </tr>

    <?php
    // Fetch Course Data
    $sql = "SELECT c.course_number, c.course_title, i.name AS instructor_id, c.department_name
            FROM courses c
            JOIN instructors i ON c.instructor_id = i.instructor_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['course_number'] . "</td>
                    <td>" . $row['course_title'] . "</td>
                    <td>" . $row['instructor_id'] . "</td>
                    <td>" . $row['department_name'] . "</td>
                    <td>
                        <a href='?type=crs&edit={$row['course_number']}'>Edit</a>
                        <a href='?type=crs&delete={$row['course_number']}'>Delete</a>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='5'>No courses found</td></tr>";
    }
    ?>
</table>
</body>
</html>