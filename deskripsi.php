<?php
session_start();
require 'db_con.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['phone_id']) && is_numeric($_GET['phone_id'])) {
    $phone_id = $_GET['phone_id'];

    $stmt = $db_con->prepare('SELECT * FROM phones WHERE phone_id = ?');
    $stmt->bind_param('i', $phone_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $phone = $result->fetch_assoc();
    $stmt->close();

    if (!$phone) {
        echo "Phone not found!";
        exit();
    }
} else {
    echo "No phone ID specified!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deskripsi <?= htmlspecialchars($phone['name']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .img-thumbnail {
            display: block;
            margin: 20px auto;
            max-width: 300px;
            max-height: 300px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?=htmlspecialchars($phone['brand']) . " " . htmlspecialchars($phone['name']); ?></h2>
 
        <?php if (!empty($phone['image_path'])): ?>
            <img src="<?= htmlspecialchars('upload/' . basename($phone['image_path'])); ?>" class="img-thumbnail" alt="Image of <?= htmlspecialchars($phone['name']); ?>">
        <?php else: ?>
            <p><strong>Gambar:</strong> Tidak ada gambar</p>
        <?php endif; ?>
        <p><strong>Merk:</strong> <?= htmlspecialchars($phone['brand']); ?></p>
        <p><strong>Tipe:</strong> <?= htmlspecialchars($phone['name']); ?></p>
        <p><strong>Harga:</strong> $ <?= htmlspecialchars($phone['price']); ?></p>
        <p><strong>Tahun Rilis:</strong> <?= htmlspecialchars($phone['release_year']); ?></p>

        <p class="description"><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($phone['description'])); ?></p>


        <br><br>
        <a href="update.php?phone_id=<?= htmlspecialchars($phone['phone_id']); ?>" class="btn-warning">Update</a>
        <a href="delete.php?phone_id=<?= htmlspecialchars($phone['phone_id']); ?>" class="btn-danger">Delete</a>
        <a href="index.php" class="btn">Kembali</a>
    </div>
</body>
</html>
