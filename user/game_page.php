<?php
session_start();
include("../connection.php");
include("../functions.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบว่ามีการระบุชื่อเกมหรือไม่
if (!isset($_GET['game'])) {
    header('Location: dashboard.php');
    exit();
}

$game_name = $_GET['game'];

// ดึงข้อมูลเกมจากฐานข้อมูล
$query = "SELECT * FROM gamecommu WHERE gamename = ? LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $game_name);
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
    <title>Game Info - <?php echo htmlspecialchars($game_data['gamename']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .game-info-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .game-info-header {
            display: flex;
            align-items: center;
        }

        .game-info-header img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            margin-right: 20px;
        }

        .game-info-content {
            margin-top: 20px;
        }

        .game-info-content p {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="game-info-container">
        <div class="game-info-header">
            <img src="../uploads/<?php echo htmlspecialchars($game_data['gameprofile']); ?>" alt="<?php echo htmlspecialchars($game_data['gamename']); ?>">
            <h2><?php echo htmlspecialchars($game_data['gamename']); ?></h2>
        </div>
        <div class="game-info-content">
            <p><strong>Average Game Data:</strong> <?php echo nl2br(htmlspecialchars($game_data['gameavgdata'])); ?></p>
            <p><strong>Place Available for Sale:</strong> <?php echo nl2br(htmlspecialchars($game_data['gameplaceforsale'])); ?></p>
        </div>
    </div>
</body>

</html>