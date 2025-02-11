<?php
session_start();
include("../connection.php");
include("../functions.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}  

// ดึง user_id จาก session
$user_id = $_SESSION['user_id'];

// ใช้ Prepared Statement เพื่อดึง user_name ตาม user_id
$query = "SELECT user_name FROM users WHERE user_id = ? LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['user_name'];
} else {
    $user_name = "ไม่พบข้อมูล";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>หลับไอเหี้ย <?php echo htmlspecialchars($user_name); ?></h1>
</body>
</html>
