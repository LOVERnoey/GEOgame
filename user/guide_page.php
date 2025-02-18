<?php
session_start();
include("../connection.php");
include("../functions.php");

if (!isset($_GET['guide_id'])) {
    die("Invalid guide ID");
}
$guide_id = intval($_GET['guide_id']);

// ดึงข้อมูลไกด์จากฐานข้อมูล
$guide_query = "SELECT g.guidename, g.guideprofile, g.guidedescription, g.guideimage, g.guiderating, u.user_name 
                FROM guide g JOIN users u ON g.user_id = u.id 
                WHERE g.guide_id = ?";
$stmt = $con->prepare($guide_query);
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$guide_result = $stmt->get_result();
$guide = $guide_result->fetch_assoc();
if (!$guide) {
    die("Guide not found");
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($guide['guidename']); ?></title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <button onclick="history.back()" class="btn btn-secondary">Back</button>
        <h1><?php echo htmlspecialchars($guide['guidename']); ?></h1>
        <p><strong>by <?php echo htmlspecialchars($guide['user_name']); ?></strong></p>
        <p><?php echo nl2br(htmlspecialchars($guide['guidedescription'])); ?></p>
        
        <?php if (!empty($guide['guideimage'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($guide['guideimage']); ?>" alt="Guide Image" class="guide-image">
        <?php endif; ?>

        <div class="like-section">
            <button id="like-btn" class="btn btn-primary" onclick="likeGuide(<?php echo $guide_id; ?>)">👍 Like</button>
            <span id="like-count"> <?php echo (int)$guide['guiderating']; ?> </span>
        </div>
    </div>

    <script>
        function likeGuide(guideId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "like_guide.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("like-count").innerText = xhr.responseText;
                }
            };
            xhr.send("guide_id=" + guideId);
        }
    </script>
</body>
</html>