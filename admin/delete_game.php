<?php
session_start();
include("../connection.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบว่าเป็น `POST` และมี `game_id`
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // ลบเกม
    $delete_query = "DELETE FROM gamecommu WHERE game_id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $game_id);

    if ($stmt->execute()) {
        header("Location: admindashboard.php?msg=ลบเกมสำเร็จ");
    } else {
        echo "เกิดข้อผิดพลาดในการลบเกม";
    }

    $stmt->close();
} else {
    header("Location: admindashboard.php");
    exit();
}
?>