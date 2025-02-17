<?php
include("../connection.php");
include("../functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gamename = $_POST['gamename'];
    $gameavgdata = $_POST['gameavgdata'];
    $gameplaceforsale = $_POST['gameplaceforsale'];
    $user_id = 1; // ดึง ID ของผู้ใช้ (ควรใช้ session)

    $gameprofile = "default_game.png";
    $gamebg = "default_bg.png";

    // Handle profile image upload
    if (!empty($_FILES['gameprofile']['name'])) {
        $file = $_FILES['gameprofile'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $gameprofile = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], '../uploads/' . $gameprofile);
        }
    }

    // Handle background image upload
    if (!empty($_FILES['gamebg']['name'])) {
        $file = $_FILES['gamebg'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $gamebg = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], '../uploads/' . $gamebg);
        }
    }

    $sql = "INSERT INTO gamecommu (gamename, gameprofile, gamebg, user_id, gameavgdata, gameplaceforsale)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssiss", $gamename, $gameprofile, $gamebg, $user_id, $gameavgdata, $gameplaceforsale);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit(); // ใช้ exit() เพื่อหยุดการทำงานของสคริปต์หลัง redirect
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
