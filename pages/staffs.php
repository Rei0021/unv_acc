<?php
    include '../includes/db_connect.php';
    include '../includes/header.php';
?>

<h2>Manage Staffs</h2>

<!-- Tabs or mini nav-->
<nav>
    <a href="?type=resi">Residence Staff</a> |
    <a href="?type=advsr">Advisers</a>
 </nav>

<?php
    $type = $_GET['type'] ?? 'resi'; // Default to residence staff
    if ($type === 'resi') {
        include 'staff_resi.php';
    } else {
        include 'staff_advsr.php';
    }
?>

 <?php include '../includes/footer.php'; ?>