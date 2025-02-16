<?php
session_start();
include("../connection.php");
include("../functions.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
$user_data = check_login($con);
$user_id = $user_data['user_id'];
$user_name = $user_data['user_name'];
$password = $user_data['password'];
$user_image = $user_data['image'];
$date = $user_data['date'];
$user_email = $user_data['user_email'];
$bio = $user_data['bio'];
$x = $user_data['x'];
$facebook = $user_data['facebook'];
$instagram = $user_data['instagram'];
$youtube = $user_data['youtube'];

$default_image = "../image/dp.png";

if (empty($user_image)) {
    $user_image = $default_image;
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $updated_fields = [];

    if (!empty($_POST['user_name']) && !is_numeric($_POST['user_name'])) {
        $updated_fields['user_name'] = $_POST['user_name'];
    }

    if (!empty($_POST['password'])) {
        $updated_fields['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    if (!empty($_POST['bio'])) {
        $updated_fields['bio'] = $_POST['bio'];
    }

    if (!empty($_POST['user_email'])) {
        $updated_fields['user_email'] = $_POST['user_email'];
    }

    if (!empty($_POST['x'])) {
        $updated_fields['x'] = $_POST['x'];
    }
    if (!empty($_POST['facebook'])) {
        $updated_fields['facebook'] = $_POST['facebook'];
    }
    if (!empty($_POST['instagram'])) {
        $updated_fields['instagram'] = $_POST['instagram'];
    }
    if (!empty($_POST['youtube'])) {
        $updated_fields['youtube'] = $_POST['youtube'];
    }

    // Handle profile picture
    if (!empty($_FILES['image']['name'])) {
        $image_file = $_FILES['image'];
        $image_extension = pathinfo($image_file['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png'];

        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            $image_name = uniqid() . '.' . $image_extension;
            $upload_path = '../uploads/' . $image_name;

            if (move_uploaded_file($image_file['tmp_name'], $upload_path)) {
                $updated_fields['image'] = $image_name;
            }
        }
    }

    // Handle background image
    if (!empty($_FILES['background_image']['name'])) {
        $bg_file = $_FILES['background_image'];
        $bg_extension = pathinfo($bg_file['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($bg_extension), $allowed_extensions)) {
            $bg_name = uniqid() . '.' . $bg_extension;
            $bg_upload_path = '../uploads/' . $bg_name;

            if (move_uploaded_file($bg_file['tmp_name'], $bg_upload_path)) {
                $updated_fields['background_image'] = $bg_name;
            }
        }
    }

    if (!empty($updated_fields)) {
        $set_clauses = [];
        $params = [];
        $types = '';

        foreach ($updated_fields as $field => $value) {
            $set_clauses[] = "$field = ?";
            $params[] = $value;
            $types .= 's';
        }

        $params[] = $user_id;
        $types .= 'i';

        $sql = "UPDATE users SET " . implode(", ", $set_clauses) . " WHERE user_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    }
}
$user_name = $user_data['user_name'];
$user_image = $user_data['image'];

$default_image = "../image/dp.png";
$default_bg = "../image/bg.png";

// ตรวจสอบว่ามีรูปโปรไฟล์หรือไม่
if (empty($user_image)) {
    $user_image = $default_image;
}

// ตรวจสอบว่ามีรูปพื้นหลังหรือไม่
if (empty($background_image)) {
    $background_image = $default_bg;
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
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: ;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: start;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงาตกด้านขวา */
        }

        .sidebar img {
            width: 150px;
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
            padding: 10px;
            color: #333;
        }

        .sidebar .nav-link.active {
            background: #F5F5F5;
            color: black;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover {
            background: #d4e1d6;
            border-radius: 5px;
        }

        .tab-content {
            flex-grow: 1;
            padding: 20px;
        }

        .profile-container {
            display: flex;
            justify-content: center;
            /* จัดให้อยู่กึ่งกลางแนวนอน */
            align-items: center;
            /* จัดให้อยู่กึ่งกลางแนวตั้ง */
            height: 100px;
            /* ปรับขนาดความสูงให้เหมาะสม */
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border: 3px solid white;
            margin-top: -40px;
        }

        .profile-card {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-banner {
            background: url('../uploads/<?php echo $background_image; ?>') center/cover no-repeat;
            height: 150px;
            border-radius: 15px 15px 0 0;
        }

        .form-control {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="../image/logo.png" alt="GEOgame Logo" style="margin-bottom: 60px;"> <!-- โลโก้เป็นรูปภาพ -->
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home"
                type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" style="margin-bottom: 15px;">
                <i data-lucide="home"></i> Home
            </button>
            <button class="nav-link" id="v-pills-game-tab" data-bs-toggle="pill" data-bs-target="#v-pills-game"
                type="button" role="tab" aria-controls="v-pills-game" aria-selected="false"
                style="margin-bottom: 15px;">
                <i data-lucide="gamepad-2"></i> Game
            </button>
            <button class="nav-link" id="v-pills-guide-tab" data-bs-toggle="pill" data-bs-target="#v-pills-guide"
                type="button" role="tab" aria-controls="v-pills-guide" aria-selected="false"
                style="margin-bottom: 15px;">
                <i data-lucide="book"></i> Guide
            </button>
            <button class="nav-link" id="v-pills-search-tab" data-bs-toggle="pill" data-bs-target="#v-pills-search"
                type="button" role="tab" aria-controls="v-pills-search" aria-selected="false"
                style="margin-bottom: 15px;">
                <i data-lucide="search"></i> Search
            </button>
            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile"
                type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"
                style="position: relative; top: 390px; left: 16px; justify-content: center;">
                <img src="../uploads/<?php echo !empty($user_image) ? $user_image : $default_image; ?>" alt="Profile"
                    class="rounded-circle" style="width: 60px; height: 60px;">
            </button>
            <p><?php echo htmlspecialchars($user_name); ?></p>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
            <h2>Home</h2>
            <p>Welcome to GEOgame! Your hub for gaming guides and news.</p>
        </div>
        <div class="tab-pane fade" id="v-pills-game" role="tabpanel" aria-labelledby="v-pills-game-tab">
            <h2>Game</h2>
            <p>Discover the latest games and reviews.</p>
        </div>
        <div class="tab-pane fade" id="v-pills-guide" role="tabpanel" aria-labelledby="v-pills-guide-tab">
            <h2>Guide</h2>
            <p>Find the best game guides and walkthroughs.</p>
        </div>
        <div class="tab-pane fade" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
            <h2>Search</h2>
            <p>Search for your favorite games and guides.</p>
        </div>
        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
            <div class="container mt-4">
                <h2>Edit User Profile</h2>
                <div class="row">
                    <!-- Profile Card -->
                    <div class="col-md-6">
                        <div class="profile-card">
                            <!-- ภาพพื้นหลัง -->
                            <div class="profile-banner"
                                style="background-image: url('../uploads/<?php echo !empty($background_image) ? $background_image : 'default_bg.png'; ?>');">
                            </div>

                            <div class="text-center">
                                <!-- รูปโปรไฟล์ -->
                                <img src="../uploads/<?php echo !empty($user_image) ? $user_image : 'default_image.png'; ?>"
                                    class="rounded-circle profile-image">

                                <!-- ข้อมูลโปรไฟล์ -->
                                <h5 class="mt-2">Your Profile</h5>
                                <p>Bio</p>

                                <!-- ปุ่มกด -->
                                <button class="btn btn-secondary">Cancel</button>
                                <button class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="col-md-6">
                        <div class="profile-card">
                            <h5>Bio</h5>
                            <textarea class="form-control" name="bio" rows="3"><?php echo $bio; ?></textarea>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="col-md-6 mt-3">
                        <div class="profile-card">
                            <h5>Personal Information</h5>
                            <label>Full Name</label>
                            <input type="text" class="form-control mb-2" name="user_name"
                                value="<?php echo $user_name; ?>">
                            <label>Email</label>
                            <input type="email" class="form-control" name="user_email"
                                value="<?php echo $user_email; ?>">
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="col-md-6 mt-3">
                        <div class="profile-card">
                            <h5>Social Media</h5>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i data-lucide="twitter"></i></span>
                                <input type="text" class="form-control" name="x" value="<?php echo $x; ?>">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i data-lucide="facebook"></i></span>
                                <input type="text" class="form-control" name="facebook"
                                    value="<?php echo $facebook; ?>">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i data-lucide="instagram"></i></span>
                                <input type="text" class="form-control" name="instagram"
                                    value="<?php echo $instagram; ?>">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i data-lucide="youtube"></i></span>
                                <input type="text" class="form-control" name="youtube" value="<?php echo $youtube; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Upload Images -->
                    <div class="col-md-12 mt-3">
                        <div class="profile-card">
                            <h5>Update Images</h5>
                            <label>Profile Picture</label>
                            <input type="file" class="form-control mb-2" name="image">
                            <label>Background Image</label>
                            <input type="file" class="form-control" name="background_image">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        lucide.createIcons(); // แสดงผลไอคอน Lucide
    </script>
</body>

</html>