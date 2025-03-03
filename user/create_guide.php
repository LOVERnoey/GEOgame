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
} else {
    // หากไม่พบข้อมูลผู้ใช้
    echo "User data not found.";
    // หรือทำการส่งผู้ใช้ไปที่หน้าอื่น เช่น login
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guidename = $_POST['guidename'];
    $game_id = $_POST['game_id'];
    $guidedescription = $_POST['guidedescription'];

    $guideprofile = "default_guide.png";
    $guideimage = "default_guide_img.png";

    // Allowed file extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Function to display Bootstrap alert
    function show_alert($message, $alert_type = 'danger') {
        echo "<div class='alert alert-$alert_type' role='alert'>$message</div>";
    }

    // Handle guide profile image upload
    if (!empty($_FILES['guideprofile']['name'])) {
        $file = $_FILES['guideprofile'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_extensions)) {
            $guideprofile = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], '../uploads/' . $guideprofile);
        } else {
            show_alert("Error: Invalid file type for guide profile image. Only JPG, JPEG, PNG, and GIF are allowed.");
            exit();
        }
    }

    // Handle guide image upload
    if (!empty($_FILES['guideimage']['name'])) {
        $file = $_FILES['guideimage'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_extensions)) {
            $guideimage = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], '../uploads/' . $guideimage);
        } else {
            show_alert("Error: Invalid file type for guide image. Only JPG, JPEG, PNG, and GIF are allowed.");
            exit();
        }
    }

    $sql = "INSERT INTO guide (guidename, guideprofile, guideimage, guidedescription, user_id, game_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssii", $guidename, $guideprofile, $guideimage, $guidedescription, $id, $game_id);
    
    if ($stmt->execute()) {
        $_SESSION['guide_created'] = true; // ตั้ง session เพื่อแสดง alert
        header("Location: dashboard.php");
        exit();
    } else {
        show_alert("Error: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Guide</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Create Guide</h2>
        <form action="create_guide.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <label style="color: #578E7E; font-weight: bold;">Guide Name:</label>
            <input type="text" name="guidename" class="form-control" style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;" required>

            <label style="color: #578E7E; font-weight: bold;">Guide Profile Image:</label>
            <input type="file" name="guideprofile" class="form-control" style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;">

            <label style="color: #578E7E; font-weight: bold;">Guide Image:</label>
            <input type="file" name="guideimage" class="form-control" style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;">

            <label style="color: #578E7E; font-weight: bold;">Guide Description:</label>
            <textarea name="guidedescription" class="form-control" style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;"></textarea>

            <label style="color: #578E7E; font-weight: bold;">Game ID:</label>
            <input type="number" name="game_id" class="form-control" style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;" required>

            <button type="submit" class="btn" style="background-color: #578E7E; color: white; border-radius: 5px; padding: 10px;">
                Create Guide
            </button>
        </form>
    </div>
</body>
</html>