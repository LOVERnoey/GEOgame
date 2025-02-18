<?php
session_start();
include("../connection.php");
include("../functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['guide_id'])) {
        $guide_id = intval($_POST['guide_id']);

        // Update the like count in the database
        $update_query = "UPDATE guide SET guiderating = guiderating + 1 WHERE guide_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("i", $guide_id);
        if ($stmt->execute()) {
            // Get the new like count
            $select_query = "SELECT guiderating FROM guide WHERE guide_id = ?";
            $stmt = $con->prepare($select_query);
            $stmt->bind_param("i", $guide_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $guide = $result->fetch_assoc();

            // Return the new like count
            echo json_encode(['success' => true, 'newRating' => $guide['guiderating']]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
?>