<?php
session_start();
include("../connection.php");
include("../functions.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
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

    // Handle profile image upload
    if (!empty($_FILES['image']['name'])) {
        $image_file = $_FILES['image'];
        $image_extension = pathinfo($image_file['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            $image_name = uniqid() . '.' . $image_extension;
            $upload_path = '../uploads/' . $image_name;

            if (move_uploaded_file($image_file['tmp_name'], $upload_path)) {
                $updated_fields['image'] = $image_name;
            }
        }
    }

    // Handle background image upload (ทำให้เหมือนกับการอัปโหลดโปรไฟล์)
    if (!empty($_FILES['background_image']['name'])) {
        $bg_file = $_FILES['background_image'];
        $bg_extension = pathinfo($bg_file['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

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
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');

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
            position: fixed;
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

        .profile-banner {
            background-size: cover;
            background-position: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 150px;
            position: relative;
        }

        .profile-image {
            width: 70px;
            /* Smaller size */
            height: 70px;
            border-radius: 50%;
            position: absolute;
            bottom: -35px;
            left: 90px;
            transform: translateX(-50%);
            border: 3px solid white;
        }

        .profile-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .profile-cardanlter {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-card h5 {
            margin-top: 40px;
        }

        .profile-card p {
            color: #666;
        }

        .form-control {
            border-radius: 10px;
        }

        /* Button Style */
        .add-game-btn {
            width: 200px;
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 2px solid #79A89D;
            border-radius: 15px;
            cursor: pointer;
            background-color: white;
        }

        .add-game-btn .plus-icon {
            font-size: 40px;
            font-weight: bold;
            color: black;
            border: 2px solid black;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .add-game-btn p {
            margin-top: 10px;
            font-size: 14px;
            color: black;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        input,
        textarea,
        button {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 8px;
        }

        /* Game Profile Styles */
        .game-profiles {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .game-profile {
            width: 200px;
            text-align: center;
            position: relative;
        }

        .game-profile-image {
            width: 100%;
            height: 280px;
            border-radius: 10px;
            margin-left: 70px;
        }

        .game-profile-name {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(87, 142, 126, 0.8);
            color: white;
            padding: 20px 0;
            font-size: 18px;
            font-weight: bold;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            margin-left: 70px;
        }

        /* Latest News Styles */
        .latest-news {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin: 20px 100px;
        }

        .latest-news img {
            width: 210px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .news-description {
            flex: 1;
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
                style="position: ; top: 390px; left: 16px; justify-content: center; margin-top: 280px;">
                <img src="../uploads/<?php echo !empty($user_image) ? $user_image : $default_image; ?>" alt="Profile"
                    class="rounded-circle" style="width: 60px; height: 60px;">
            </button>
            <li class="nav-item">
                <form action="../logout.php" method="post">
                    <button type="submit" class="btn btn-danger" style="color: white; font-size: 16px;">Logout</button>
                </form>
            </li>
        </div>
    </div>
    <div class="container mt-4">
        <!-- Tab Content -->
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"
                style="">
                <h2></h2>
                <!-- Fetch and display game profiles -->
                <div class="game-profiles">
                    <?php
                    $game_query = "SELECT game_id, gamename, gameprofile FROM gamecommu";
                    $game_result = $con->query($game_query);

                    if ($game_result && $game_result->num_rows > 0) {
                        while ($game_row = $game_result->fetch_assoc()) {
                            $game_id = $game_row['game_id']; // ดึง id ของเกม
                            $game_name = $game_row['gamename'];
                            $game_profile = $game_row['gameprofile'];

                            echo '<div class="game-profile" style="margin-top: 20px; margin-bottom: 20px; margin-left: 20px;">';
                            echo '<a href="game_page.php?game_id=' . urlencode($game_id) . '">'; // ใช้ game_id แทน gamename
                            echo '<img src="../uploads/' . htmlspecialchars($game_profile) . '" alt="' . htmlspecialchars($game_name) . '" class="game-profile-image">';
                            echo '<h3 class="game-profile-name">' . htmlspecialchars($game_name) . '</h3>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No games found.</p>';
                    }
                    ?>
                </div>
                <img src="../image/homeheader.png" alt="game header"
                    style="width: 100%; height: 100%; margin-top: 20px;">
                <h1 style="margin-left: 100px; margin-top: 60px;">Latest News</h1>
                <hr style="margin-left: 100px; margin-right: 100px;">
                <div class="latest-news">
                    <img src="../image/new1.png" alt="News Image">
                    <div class="news-description">
                        <h3>The Sims Legacy Collection is Having Major Crashing Problems</h3>
                        <p>The Sims Legacy Collections are having significant crashing problems. Numerous fans of The
                            Sims
                            and The Sims 2 are becoming increasingly angry with the current state of the games.

                            Sims fans expressed excitement when EA announced that The Sims and The Sims 2 would be
                            released
                            in Legacy Collections that would bring back the base games and the vast majority of DLC
                            content
                            to current systems. However, it seems that response has been short-lived, as many players
                            have
                            shared their frustration with the state of the titles.</p>
                    </div>
                </div>
                <div class="latest-news">
                    <img src="../image/new2.png" alt="News Image" style="margin-top: 20px;">
                    <div class="news-description" style="margin-top: 20px;">
                        <h3>Rainbow Six Siege Teases ‘Major Evolution’ for the Game</h3>
                        <p>Rainbow Six Siege has announced a showcase that will take place on March 13 to talk about
                            Rainbow
                            Six Siege X. Ubisoft has been supporting Rainbow Six Siege with updates since its release in
                            2015, bringing content and features that have made the shooter one of the company's most
                            successful games.</p>
                    </div>
                </div>
                <div class="latest-news">
                    <img src="../image/new3.png" alt="News Image" style="margin-top: 20px;">
                    <div class="news-description" style="margin-top: 20px;">
                        <h3>GTA 6 PC Port Coming Sooner Than Expected, Corsair Believes</h3>
                        <p>The Grand Theft Auto 6 PC port might arrive in early 2026, according to a senior Corsair
                            Gaming
                            official. This prediction suggests GTA 6 might reach PC much sooner than many fans are
                            expecting.

                            GTA 6 was originally announced for the PS5 and Xbox Series X/S in early December 2023.
                            Take-Two
                            interactive later revealed that the game was targeting a fall 2025 launch. While the
                            publisher
                            has already reiterated this release window on several occasions, it has yet to mention
                            anything
                            official about the game's potential PC port, which many industry watchers agree is only a
                            question of time.</p>
                    </div>
                </div>
                <div class="latest-news">
                    <img src="../image/new4.png" alt="News Image" style="margin-top: 20px;">
                    <div class="news-description" style="margin-top: 20px;">
                        <h3>Zenless Zone Zero Leak Teases Possible New Character</h3>
                        <p>A Zenless Zone Zero leak is teasing a possible new character named Jufufu. Known for its
                            unique
                            fighting game mechanics, Zenless Zone Zero's character roster has diverse abilities that
                            serve
                            different purposes, from buffing allies to dealing significant damage to enemies.

                            As Zenless Zone Zero's story continues to progress, siblings Belle and Wise, also known as
                            Phaethon, also work with new Agents in various Hollow-related missions. Usually, the
                            character
                            banners give players an idea of the factions that will be featured in the update. For
                            example,
                            the release of the upcoming Version 1.6 character Trigger, who's an Obol Squad member, could
                            be
                            a sign that Zenless Zone Zero might finally</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-game" role="tabpanel" aria-labelledby="v-pills-game-tab" style="">
                <h2></h2>
                <img src="../image/gameheader.png" alt="game header" style="width: 100%; height: 100%;">
                <div class="add-game-btn" id="openModal"
                    style="margin-top: 20px; margin-bottom: 20px; margin-left: 90px;">
                    <div class="plus-icon">+</div>
                    <p>add a game</p>
                </div>
                <h1 style="margin-left: 90px;">Game Community</h1>
                <!-- Fetch and display game profiles -->
                <div class="game-profiles">
                    <?php
                    $game_query = "SELECT game_id, gamename, gameprofile FROM gamecommu";
                    $game_result = $con->query($game_query);

                    if ($game_result && $game_result->num_rows > 0) {
                        while ($game_row = $game_result->fetch_assoc()) {
                            $game_id = $game_row['game_id']; // ดึง id ของเกม
                            $game_name = $game_row['gamename'];
                            $game_profile = $game_row['gameprofile'];

                            echo '<div class="game-profile" style="margin-top: 20px; margin-bottom: 20px; margin-left: 20px;">';
                            echo '<a href="game_page.php?game_id=' . urlencode($game_id) . '">'; // ใช้ game_id แทน gamename
                            echo '<img src="../uploads/' . htmlspecialchars($game_profile) . '" alt="' . htmlspecialchars($game_name) . '" class="game-profile-image">';
                            echo '<h3 class="game-profile-name">' . htmlspecialchars($game_name) . '</h3>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No games found.</p>';
                    }
                    ?>
                </div>
                <!-- Modal -->
                <div class="modal" id="gameModal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <form action="create_game.php" method="POST" enctype="multipart/form-data"
                            style="display: flex; flex-direction: column; gap: 15px;">

                            <label style="color: #578E7E; font-weight: bold;">Game Name:</label>
                            <input type="text" name="gamename" class="form-control"
                                style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;" required>

                            <label style="color: #578E7E; font-weight: bold;">Game Profile Image:</label>
                            <input type="file" name="gameprofile" class="form-control"
                                style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;">

                            <label style="color: #578E7E; font-weight: bold;">Game Background Image:</label>
                            <input type="file" name="gamebg" class="form-control"
                                style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;">

                            <label style="color: #578E7E; font-weight: bold;">Average Game Data:</label>
                            <textarea name="gameavgdata" class="form-control"
                                style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;"></textarea>

                            <label style="color: #578E7E; font-weight: bold;">Place Available for Sale:</label>
                            <textarea name="gameplaceforsale" class="form-control"
                                style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;"></textarea>

                            <button type="submit" class="btn"
                                style="background-color: #578E7E; color: white; border-radius: 5px; padding: 10px;">
                                Create Game
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                    // Open and close modal
                    const openModal = document.getElementById("openModal");
                    const closeModal = document.getElementById("closeModal");
                    const modal = document.getElementById("gameModal");

                    openModal.addEventListener("click", () => {
                        modal.style.display = "flex";
                    });

                    closeModal.addEventListener("click", () => {
                        modal.style.display = "none";
                    });

                    window.addEventListener("click", (event) => {
                        if (event.target === modal) {
                            modal.style.display = "none";
                        }
                    });
                </script>

            </div>
            <div class="tab-pane fade" id="v-pills-guide" role="tabpanel" aria-labelledby="v-pills-guide-tab" style="">
                <h2></h2>
                <img src="../image/guideheader.png" alt="guide header" style="width: 100%; height: 100%;">
                <div class="add-game-btn" id="openGuideModal"
                    style="margin-top: 20px; margin-bottom: 20px; margin-left: 90px;">
                    <div class="plus-icon">+</div>
                    <p>add a guide</p>
                </div>
                <h1 style="margin-left: 90px;">Guide</h1>
                <!-- Fetch and display guide profiles -->
                <div class="guide-profiles" style="margin-left: 90px;">
                    <?php
                    $guide_query = "SELECT g.guide_id, g.guidename, g.guideprofile, u.user_name FROM guide g JOIN users u ON g.user_id = u.id";
                    $guide_result = $con->query($guide_query);

                    if ($guide_result && $guide_result->num_rows > 0) {
                        $count = 0;
                        while ($guide_row = $guide_result->fetch_assoc()) {
                            $guide_id = $guide_row['guide_id'];
                            $guide_name = $guide_row['guidename'];
                            $guide_profile = $guide_row['guideprofile'];
                            $user_name = $guide_row['user_name'];

                            if ($count % 2 == 0) {
                                echo '<div class="row" style="margin-bottom: 20px;">';
                            }

                            echo '<div class="col-md-6">';
                            echo '<div class="card" style="background-color: #578E7E; color: white; padding: 10px; border-radius: 5px;">';
                            echo '<img src="../uploads/' . $guide_profile . '" alt="' . $guide_name . '" class="card-img-top" style="border-radius: 5px; width: 100px; height: 100px;">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $guide_name . '</h5>';
                            echo '<p class="card-text">by ' . $user_name . '</p>';
                            echo '<a href="guide_page.php?guide_id=' . urlencode($guide_id) . '" class="btn btn-light">View Details</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                            if ($count % 2 == 1) {
                                echo '</div>';
                            }

                            $count++;
                        }

                        if ($count % 2 != 0) {
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No guides found.</p>';
                    }
                    ?>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal" id="guideModal">
                <div class="modal-content">
                    <span class="close-btn" id="closeGuideModal">&times;</span>
                    <form action="create_guide.php" method="POST" enctype="multipart/form-data"
                        style="display: flex; flex-direction: column; gap: 15px;">
                        <label style="color: #578E7E; font-weight: bold;">Select Game:</label>
                        <select name="game_id">
                            <?php
                            $result = $con->query("SELECT game_id, gamename FROM gamecommu");
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['game_id']}'>{$row['gamename']}</option>";
                            }
                            ?>
                        </select>

                        <label for="guidename" style="color: #578E7E; font-weight: bold;">Guide Name:</label>
                        <input type="text" name="guidename" id="guidename" class="form-control"
                            style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;" required>

                        <label for="guideprofile" style="color: #578E7E; font-weight: bold;">Guide Profile
                            Image:</label>
                        <input type="file" name="guideprofile" id="guideprofile" class="form-control"
                            style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;">

                        <label for="guideimage" style="color: #578E7E; font-weight: bold;">Guide Image:</label>
                        <input type="file" name="guideimage" id="guideimage" class="form-control"
                            style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;">

                        <label for="guidedescription" style="color: #578E7E; font-weight: bold;">Guide
                            Description:</label>
                        <textarea name="guidedescription" id="guidedescription" class="form-control"
                            style="border: 1px solid #578E7E; border-radius: 5px; padding: 10px;"></textarea>

                        <button type="submit" class="btn"
                            style="background-color: #578E7E; color: white; border-radius: 5px; padding: 10px;">Create
                            Guide</button>
                    </form>
                </div>
            </div>

            <script>
                // Open and close modal
                const openGuideModal = document.getElementById("openGuideModal");
                const closeGuideModal = document.getElementById("closeGuideModal");
                const guideModal = document.getElementById("guideModal");

                openGuideModal.addEventListener("click", () => {
                    guideModal.style.display = "flex";
                });

                closeGuideModal.addEventListener("click", () => {
                    guideModal.style.display = "none";
                });

                window.addEventListener("click", (event) => {
                    if (event.target === guideModal) {
                        guideModal.style.display = "none";
                    }
                });
            </script>

        </div>
        <div class="tab-pane fade" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
            <h2>Search</h2>
            <form method="GET" action="#v-pills-search">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search_query" placeholder="Search games or guides..."
                        value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <?php
            if (isset($_GET['search_query']) && !empty($_GET['search_query'])):
                $search_query = $_GET['search_query'];
                $search_query = $con->real_escape_string($search_query);
                ?>
                <h5>Search Results:</h5>

                <!-- Search in Games -->
                <?php
                $search_game_sql = "
        SELECT game_id, gamename, gameprofile
        FROM gamecommu
        WHERE gamename LIKE '%$search_query%'
        ";
                $search_game_res = $con->query($search_game_sql);

                if ($search_game_res && $search_game_res->num_rows > 0):
                    echo '<h6>Games Found:</h6>';
                    echo '<div class="row">';
                    while ($row_game = $search_game_res->fetch_assoc()):
                        $found_game_id = $row_game['game_id'];
                        $found_game_name = $row_game['gamename'];
                        $found_game_profile = $row_game['gameprofile'];
                        ?>
                        <div class="col-md-4" style="margin-top: 20px;">
                            <div class="game-profile">
                                <a href="game_page.php?game_id=<?php echo urlencode($found_game_id); ?>">
                                    <img src="../uploads/<?php echo htmlspecialchars($found_game_profile); ?>"
                                        alt="<?php echo htmlspecialchars($found_game_name); ?>" class="game-profile-image">
                                    <h3 class="game-profile-name"><?php echo htmlspecialchars($found_game_name); ?></h3>
                                </a>
                            </div>
                        </div>
                        <?php
                        // Fetch and display the guides for each game
                        $search_guide_sql = "
                SELECT g.guide_id, g.guidename, g.guideprofile, u.user_name
                FROM guide g
                JOIN users u ON g.user_id = u.id
                WHERE g.game_id = $found_game_id
                ";
                        $search_guide_res = $con->query($search_guide_sql);

                        if ($search_guide_res && $search_guide_res->num_rows > 0):
                            echo '<div class="guide-profiles row" style="margin-top: 20px;">';
                            while ($row_guide = $search_guide_res->fetch_assoc()):
                                $found_guide_id = $row_guide['guide_id'];
                                $found_guide_name = $row_guide['guidename'];
                                $found_guide_profile = $row_guide['guideprofile'];
                                $creator_name = $row_guide['user_name'];
                                ?>
                                <div class="col-md-6">
                                    <div class="card"
                                        style="background-color: #578E7E; color: white; padding: 10px; border-radius: 5px; margin: 10px;">
                                        <img src="../uploads/<?php echo htmlspecialchars($found_guide_profile); ?>"
                                            alt="<?php echo htmlspecialchars($found_guide_name); ?>" class="card-img-top"
                                            style="border-radius: 5px; width: 100px; height: 100px;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($found_guide_name); ?></h5>
                                            <p class="card-text">by <?php echo htmlspecialchars($creator_name); ?></p>
                                            <a href="guide_page.php?guide_id=<?php echo urlencode($found_guide_id); ?>"
                                                class="btn btn-light">View Details</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            echo '</div>';
                        else:
                            echo '<p>No guides found for this game.</p>';
                        endif;
                    endwhile;
                    echo '</div>';
                else:
                    echo '<h6>Guides Found:</h6>';
                    echo '<div class="guide-profiles row" style="margin-left: 20px;">';
                    // Search in Guides if no games found
                    $search_guide_sql = "
            SELECT g.guide_id, g.guidename, g.guideprofile, u.user_name
            FROM guide g
            JOIN users u ON g.user_id = u.id
            WHERE g.guidename LIKE '%$search_query%'
            ";
                    $search_guide_res = $con->query($search_guide_sql);

                    if ($search_guide_res && $search_guide_res->num_rows > 0):
                        while ($row_guide = $search_guide_res->fetch_assoc()):
                            $found_guide_id = $row_guide['guide_id'];
                            $found_guide_name = $row_guide['guidename'];
                            $found_guide_profile = $row_guide['guideprofile'];
                            $creator_name = $row_guide['user_name'];
                            ?>
                            <div class="col-md-6">
                                <div class="card"
                                    style="background-color: #578E7E; color: white; padding: 10px; border-radius: 5px; margin: 10px;">
                                    <img src="../uploads/<?php echo htmlspecialchars($found_guide_profile); ?>"
                                        alt="<?php echo htmlspecialchars($found_guide_name); ?>" class="card-img-top"
                                        style="border-radius: 5px; width: 100px; height: 100px;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($found_guide_name); ?></h5>
                                        <p class="card-text">by <?php echo htmlspecialchars($creator_name); ?></p>
                                        <a href="guide_page.php?guide_id=<?php echo urlencode($found_guide_id); ?>"
                                            class="btn btn-light">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                    else:
                        echo '<p>No guides found matching your query.</p>';
                    endif;
                    echo '</div>';
                endif;
                ?>
                <?php
            else:
                echo '<p>Please enter a search query to see matching games or guides.</p>';
            endif;
            ?>
        </div>

        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
            <div class="container mt-4">
                <h2>Edit User Profile</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Profile Card -->
                        <div class="col-md-6">
                            <div class="profile-card">
                                <!-- ภาพพื้นหลัง -->
                                <div class="profile-banner"
                                    style="background-image: url('../uploads/<?php echo !empty($background_image) ? $background_image : 'default_bg.png'; ?>');">
                                    <!-- รูปโปรไฟล์ -->
                                    <img src="../uploads/<?php echo !empty($user_image) ? $user_image : 'default_image.png'; ?>"
                                        class="rounded-circle profile-image">

                                </div>

                                <div class="text-center">
                                    <!-- ข้อมูลโปรไฟล์ -->
                                    <h5 class="mt-2" style="margin-top: 70px;">
                                        <?php echo $user_name; ?>
                                    </h5>
                                    <h5 class="mt-2" style="margin-top: 70px;">
                                        <?php echo $_SESSION['user_id'];  // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่ ?>
                                    </h5>
                                    <p><?php echo htmlspecialchars($bio); ?></p>

                                    <!-- ปุ่มกด -->

                                    <button type="submit" class="btn btn-success"
                                        style="margin-bottom: 10px;">Save</button>
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="col-md-6">
                            <div class="profile-cardanlter" style="margin-top: 50px;">
                                <h5>Bio</h5>
                                <textarea class="form-control" name="bio" rows="13"><?php echo $bio; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6 mt-3">
                            <div class="profile-cardanlter" style="margin-top: 10px;">
                                <h5>Personal Information</h5>
                                <label>Full Name</label>
                                <input type="text" class="form-control mb-2" name="user_name"
                                    value="<?php echo $user_name; ?>">
                                <label>Email</label>
                                <input type="email" class="form-control" name="user_email"
                                    value="<?php echo htmlspecialchars($user_email); ?>">
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="col-md-6 mt-1">
                            <div class="profile-cardanlter">
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
                                    <input type="text" class="form-control" name="youtube"
                                        value="<?php echo $youtube; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Upload Images -->
                        <div class="col-md-12 mt-3">
                            <div class="profile-cardanlter">
                                <h5>Update Images</h5>
                                <label>Profile Picture</label>
                                <input type="file" class="form-control mb-2" name="image">
                                <label>Background Image</label>
                                <input type="file" class="form-control" name="background_image">
                            </div>
                        </div>
                    </div>
                </form>
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