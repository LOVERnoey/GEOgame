<?php
session_start();
include("../connection.php");
include("../functions.php");

if (!isset($_GET['guide_id'])) {
    die("Invalid guide ID");
}
$guide_id = intval($_GET['guide_id']);

$guide_query = "SELECT g.guidename, g.guideprofile, g.guidedescription, g.guideimage, g.guiderating, u.user_name FROM guide g JOIN users u ON g.user_id = u.id WHERE g.guide_id = ?";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f0f5f7; padding: 30px; }
        .container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); }
        h1 { font-size: 40px; font-weight: 800; color: #222; }
        p { font-size: 20px; color: #555; }
        .guide-image { width: 100%; height: auto; border-radius: 12px; margin: 20px 0; }
        .btn-primary { background-color: #00cc44; color: white; padding: 12px 30px; font-size: 22px; font-weight: bold; border-radius: 50px; cursor: pointer; border: none; }
        .btn-primary:hover { background-color: #00b33c; }
        .like-section { display: flex; align-items: center; font-size: 22px; font-weight: bold; color: #444; }
        .like-section span { margin-left: 10px; }
    </style>
</head>
<body>
<button onclick="history.back()" class="btn btn-secondary">Back</button>
    <div class="container">

    
        <?php if (!empty($guide['guideprofile'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($guide['guideprofile']); ?>" alt="Guide Profile Image" class="guide-image" style="width: 150px; height: 150px; border-radius: 10%;">
        <?php endif; ?>
        <h1><?php echo htmlspecialchars($guide['guidename']); ?></h1>
        <p><strong>by <?php echo htmlspecialchars($guide['user_name']); ?></strong></p>
        <p><?php echo nl2br(htmlspecialchars($guide['guidedescription'])); ?></p>
        <?php if (!empty($guide['guideimage'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($guide['guideimage']); ?>" alt="Guide Image" class="guide-image">
        <?php endif; ?>
        <div class="like-section"></div>
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
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById("like-count").innerText = response.newRating;
                    } else {
                        alert("Error liking guide");
                    }
                }
            };
            xhr.send("guide_id=" + guideId);
        }
    </script>
</body>
</html>
