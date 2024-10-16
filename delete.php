<?php
session_start();
require 'db_con.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['phone_id']) && is_numeric($_GET['phone_id'])) {
    $phone_id = $_GET['phone_id'];

    $stmt = $db_con->prepare('SELECT image_path FROM phones WHERE phone_id = ?');
    $stmt->bind_param('i', $phone_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $phone = $result->fetch_assoc();
    $stmt->close();

    if ($phone) {
        if (file_exists($phone['image_path'])) {
            unlink($phone['image_path']);
        }

        $stmt = $db_con->prepare('DELETE FROM phones WHERE phone_id = ?');
        $stmt->bind_param('i', $phone_id);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Error deleting phone: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Phone not found!";
    }
} else {
    echo "No phone ID specified!";
}
?>
