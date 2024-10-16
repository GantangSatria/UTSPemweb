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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $release_year = $_POST['release_year'];
    $description = $_POST['description'];
    $image_path = $phone['image_path'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File yang diunggah bukan gambar.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $stmt = $db_con->prepare('UPDATE phones SET brand = ?, name = ?, price = ?, release_year = ?, description = ?, image_path = ? WHERE phone_id = ?');
    $stmt->bind_param('ssdsssi', $brand, $name, $price, $release_year, $description, $image_path, $phone_id);
    
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating phone: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Phone</title>
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

    </style>
</head>
<body>
    <div class="container">
        <h2>Update Phone</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="brand">Merk:</label>
            <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($phone['brand']); ?>" required>

            <label for="name">Tipe:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($phone['name']); ?>" required>

            <label for="price">Harga:</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($phone['price']); ?>" required step="0.01" min="0">

            <label for="release_year">Tahun Rilis:</label>
            <input type="number" id="release_year" name="release_year" value="<?= htmlspecialchars($phone['release_year']); ?>" required>

            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($phone['description']); ?></textarea>

            <label for="image">Gambar:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <br>
            <button type="submit" class="btn">Update</button>
        </form>
        <a href="index.php" class="btn">Kembali</a>
    </div>

</body>
</html>
