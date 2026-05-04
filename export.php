<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit;
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="transform-u-enrollments.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Course', 'Registration Date']);

$res = $conn->query("SELECT * FROM enrollments ORDER BY id DESC");
while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['email'],
        $row['phone'],
        $row['course'],
        date('d M Y', strtotime($row['reg_date']))
    ]);
}
fclose($output);
exit;
?>
