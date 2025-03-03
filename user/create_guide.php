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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guidename = $_POST['guidename'];
    $game_id = $_POST['game_id'];
    $guidedescription = $_POST['guidedescription'];

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
    $stmt->bind_param("ssssii", $guidename, $guideprofile, $guideimage, $guidedescription, $id, $game_id);
    
    if ($stmt->execute()) {
        $_SESSION['guide_created'] = true; // ตั้ง session เพื่อแสดง alert
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!-- ในส่วน HTML ของ dashboard.php -->
<?php if (isset($_SESSION['guide_created']) && $_SESSION['guide_created'] == true): ?>
    <script>
        alert('Guide created successfully');
    </script>
    <?php unset($_SESSION['guide_created']); ?> <!-- ลบ session หลังจากแสดงผล -->
<?php endif; ?>
