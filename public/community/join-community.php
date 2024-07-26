<?php
session_start();
include('../../config/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $community_id = $data['community_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO community_members (community_id, user_id) VALUES (:community_id, :user_id)");
        $stmt->bindValue(':community_id', $community_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Success: Joined community!";
        } else {
            echo "Error: " . implode(" ", $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
