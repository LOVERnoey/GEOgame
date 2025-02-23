<?php
session_start();
include("../connection.php");
include("../functions.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบว่ามีการระบุ ID เกมหรือไม่
if (!isset($_GET['game_id'])) {
    header('Location: dashboard.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? ''; // role ของผู้ใช้

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}



$game_id = $_GET['game_id'];
$query = "SELECT user_id, user_name, user_email, role, image FROM users";
$result = $con->query($query);
// ดึงข้อมูลเกมจากฐานข้อมูล
$query = "SELECT * FROM gamecommu WHERE game_id = ? LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่ามีข้อมูลเกมหรือไม่
if ($result && $result->num_rows > 0) {
    $game_data = $result->fetch_assoc();
} else {
    echo "ไม่พบข้อมูลเกม";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game_data['gamename']); ?> - Game Info</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* พื้นหลังเบลอ */
        .background-container {
            position: absolute;
            width: 100%;
            height: 50vh;
            background: url('../uploads/<?php echo htmlspecialchars($game_data['gamebg']); ?>') no-repeat center center;
            background-size: cover;
            filter: blur(10px);
            z-index: -1;
        }

        /* กล่องโปรไฟล์เกม */
        .game-profile {
            position: relative;
            text-align: center;
            margin-top: -80px;
        }

        .game-profile img {
            width: 250px;
            height: 330px;
            border-radius: 20px;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-top: 300px;
        }

        /* ชื่อเกม */
        .game-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }

        /* ระบบแท็บ */
        .tab-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .tab-container button {
            background: none;
            border: none;
            font-size: 18px;
            padding: 10px 20px;
            cursor: pointer;
            color: gray;
        }

        .tab-container button.active {
            color: black;
            border-bottom: 2px solid black;
        }

        .tab-content {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
        }

        .btn-back {
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <a href="admindashboard.php" class="btn">← Back to Dashboard</a>
    <!-- ภาพพื้นหลังที่ถูกเบลอ -->
    <div class="background-container"></div>

    <div class="container">
        <!-- รูปเกม -->
        <div class="game-profile">
            <img src="../uploads/<?php echo htmlspecialchars($game_data['gameprofile']); ?>" alt="Game Profile">
        </div>

        <!-- ชื่อเกม -->
        <div class="game-title"><?php echo htmlspecialchars($game_data['gamename']); ?></div>

        <!-- ระบบแท็บ -->
        <div class="tab-container">
            <button class="tab-button active" onclick="showTab('overview')">Overview</button>
            <button class="tab-button" onclick="showTab('place')">Place for sale</button>
            <button class="tab-button" onclick="showTab('guide')">Guide</button>
        </div>

        <!-- เนื้อหาแท็บ -->
        <div id="overview" class="tab-content">
            <p><?php echo nl2br(htmlspecialchars($game_data['gameavgdata'])); ?></p>
            <form action="delete_game.php" method="POST" onsubmit="return confirm('Are you sure to delete this game community?');">
                <input type="hidden" name="game_id" value="<?= htmlspecialchars($game_id) ?>">
                <button type="submit" class="btn btn-danger">ลบเกม</button>
            </form>
        </div>
        <div id="place" class="tab-content" style="display:none;">
            <p><?php echo nl2br(htmlspecialchars($game_data['gameplaceforsale'])); ?></p>
            <form action="delete_game.php" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบเกมนี้?');">
                <input type="hidden" name="game_id" value="<?= htmlspecialchars($game_id) ?>">
                <button type="submit" class="btn btn-danger">ลบเกม</button>
            </form>
        </div>
        <div id="guide" class="tab-content" style="display:none;">
            <div class="guide-section">
                <h3>Guides for <?php echo htmlspecialchars($game_data['gamename']); ?></h3>

                <?php
                $guide_query = "SELECT g.guide_id, g.guidename, g.guideprofile, u.user_name FROM guide g 
                            JOIN users u ON g.user_id = u.id 
                            WHERE g.game_id = ?";
                $stmt = $con->prepare($guide_query);
                $stmt->bind_param("i", $game_id);
                $stmt->execute();
                $guide_result = $stmt->get_result();

                if ($guide_result && $guide_result->num_rows > 0) {
                    echo '<div class="row">';
                    while ($guide_row = $guide_result->fetch_assoc()) {
                        echo '<div class="col-md-6">';
                        echo '<div class="card" style="background-color: #578E7E; color: white; padding: 10px; border-radius: 5px;">';
                        echo '<img src="../uploads/' . htmlspecialchars($guide_row['guideprofile']) . '" alt="' . htmlspecialchars($guide_row['guidename']) . '" class="card-img-top" style="border-radius: 5px; width: 100px; height: 100px;">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($guide_row['guidename']) . '</h5>';
                        echo '<p class="card-text">by ' . htmlspecialchars($guide_row['user_name']) . '</p>';
                        echo '<a href="guide_adminpage.php?guide_id=' . urlencode($guide_row['guide_id']) . '" class="btn btn-light">View Details</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>No guides found for this game.</p>';
                }

                $stmt->close();
                ?>
                <form action="delete_game.php" method="POST"
                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบเกมนี้?');">
                    <input type="hidden" name="game_id" value="<?= htmlspecialchars($game_id) ?>">
                    <button type="submit" class="btn btn-danger">ลบเกม</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            let tabs = document.getElementsByClassName("tab-content");
            let buttons = document.getElementsByClassName("tab-button");

            for (let i = 0; i < tabs.length; i++) {
                tabs[i].style.display = "none";
                buttons[i].classList.remove("active");
            }

            document.getElementById(tabName).style.display = "block";
            event.currentTarget.classList.add("active");
        }
    </script>

</body>

</html>