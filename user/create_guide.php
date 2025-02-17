<?php
include("../connection.php");
include("../functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guidename = $_POST['guidename'];
    $game_id = $_POST['game_id'];
    $guidedescription = $_POST['guidedescription'];
    $user_id = 1; // ดึง ID ของผู้ใช้ (ควรใช้ session)

    $guideprofile = "default_guide.png";
    $guideimage = "default_guide_img.png";

    // Handle guide profile image upload
    if (!empty($_FILES['guideprofile']['name'])) {
        $file = $_FILES['guideprofile'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $guideprofile = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], '../uploads/' . $guideprofile);
        }
    }

    // Handle guide image upload
    if (!empty($_FILES['guideimage']['name'])) {
        $file = $_FILES['guideimage'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $guideimage = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], '../uploads/' . $guideimage);
        }
    }

    $sql = "INSERT INTO guide (guidename, guideprofile, guideimage, guidedescription, user_id, game_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssii", $guidename, $guideprofile, $guideimage, $guidedescription, $user_id, $game_id);
    
    if ($stmt->execute()) {
        echo "Guide created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
