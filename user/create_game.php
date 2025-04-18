<?php
session_start(); // เรียกใช้ session
include("../connection.php");
include("../functions.php");

if (!isset($_SESSION['user_id'])) {
    echo "User ID: " . $_SESSION['user_id'];  // แสดง user id ก่อน
    die("Error: คุณยังไม่ได้ล็อกอิน");
}

$user_data = check_login($con);  // ฟังก์ชัน check_login ควรคืนค่าผู้ใช้หากล็อกอินสำเร็จ

if ($user_data) {
    // ถ้ามีข้อมูลผู้ใช้
    $user_id = $user_data['user_id'];
    $id = $user_data['id'];
    $user_name = $user_data['user_name'];
    $password = $user_data['password'];
    $user_image = $user_data['image'];
    $date = $user_data['date'];
    $user_email = $user_data['user_email'];
    $bio = $user_data['bio'];
    $background_image = $user_data['background_image'];
    $x = $user_data['x'];
    $facebook = $user_data['facebook'];
    $instagram = $user_data['instagram'];
    $youtube = $user_data['youtube'];
} else {
    // หากไม่พบข้อมูลผู้ใช้
    echo "User data not found.";
    // หรือทำการส่งผู้ใช้ไปที่หน้าอื่น เช่น login
    header("Location: login.php");
    exit();
}
$gamename = $_POST['gamename'];
$gameavgdata = $_POST['gameavgdata'];
$gameplaceforsale = $_POST['gameplaceforsale'];

$gameprofile = "default_game.png";
$gamebg = "default_bg.png";

// Allowed file extensions
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Function to display Bootstrap alert
function show_alert($message, $alert_type = 'danger') {
    echo "<div class='alert alert-$alert_type' role='alert'>$message</div>";
}

// Handle profile image upload
if (!empty($_FILES['gameprofile']['name'])) {
    $file = $_FILES['gameprofile'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed_extensions)) {
        $gameprofile = uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], '../uploads/' . $gameprofile);
    } else {
        show_alert("Error: Invalid file type for profile image. Only JPG, JPEG, PNG, and GIF are allowed.");
        exit();
    }
}

// Handle background image upload
if (!empty($_FILES['gamebg']['name'])) {
    $file = $_FILES['gamebg'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed_extensions)) {
        $gamebg = uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], '../uploads/' . $gamebg);
    } else {
        show_alert("Error: Invalid file type for background image. Only JPG, JPEG, PNG, and GIF are allowed.");
        exit();
    }
}

$sql = "INSERT INTO gamecommu (gamename, gameprofile, gamebg, user_id, gameavgdata, gameplaceforsale)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssiss", $gamename, $gameprofile, $gamebg, $id, $gameavgdata, $gameplaceforsale);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    show_alert("Error: " . $stmt->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
</body>
</html>