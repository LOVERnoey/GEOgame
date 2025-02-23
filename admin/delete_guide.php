<?php
session_start();
include("../connection.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบว่าเป็น `POST` และมี `guide_id`
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guide_id'])) {
    $guide_id = $_POST['guide_id'];

    // ลบไกด์
    $delete_query = "DELETE FROM guide WHERE guide_id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $guide_id);

    if ($stmt->execute()) {
        header("Location: admindashboard.php?msg=ลบไกด์สำเร็จ");
    } else {
        echo "เกิดข้อผิดพลาดในการลบไกด์";
    }

    $stmt->close();
} else {
    header("Location: admindashboard.php");
    exit();
}
?>
