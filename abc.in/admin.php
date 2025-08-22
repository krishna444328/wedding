<?php
// db connect
include 'db.php';

$notice = '';

// ✅ Image Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Get filename from DB
    $stmt = $pdo->prepare("SELECT filename FROM gallery_images WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $img = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($img) {
        $filePath = __DIR__ . "/uploads/" . $img['filename'];
        if (file_exists($filePath)) {
            unlink($filePath); // remove from uploads folder
        }

        // Remove record from DB
        $pdo->prepare("DELETE FROM gallery_images WHERE id = :id")->execute(['id' => $id]);

        $notice = "🗑️ Image deleted successfully!";
    }
}

// ✅ Upload Image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $page  = $_POST['page'];
    $year  = $_POST['year'];
    $month = $_POST['month'];
    $title = $_POST['title'];

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName   = time() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $sql = "INSERT INTO gallery_images (page, year, month, filename, title) 
                VALUES (:page, :year, :month, :filename, :title)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'page'     => $page,
            'year'     => $year,
            'month'    => $month,
            'filename' => $fileName,
            'title'    => $title
        ]);

        $notice = "✅ Image uploaded successfully!";
    } else {
        $notice = "❌ Upload failed!";
    }
}

// ✅ Fetch all images for sidebar
$images = $pdo->query("SELECT * FROM gallery_images ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Upload</title>
<style>
body {
    display: flex;
    font-family: Arial, sans-serif;
}
.sidebar {
    width: 40%;
    padding: 15px;
    border-left: 2px solid #ccc;
    background: #f9f9f9;
    overflow-y: auto;
    height: 100vh;
}
.grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}
.card {
    border: 1px solid #ddd;
    padding: 5px;
    border-radius: 6px;
    text-align: center;
    background: #fff;
}
.card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}
.delete-btn {
    margin-top: 5px;
    display: inline-block;
    padding: 4px 8px;
    font-size: 12px;
    background: #dc2626;
    color: white;
    border-radius: 4px;
    text-decoration: none;
}
.delete-btn:hover {
    background: #b91c1c;
}
.main {
    width: 60%;
    padding: 20px;
}
.notice {
    padding:10px;
    background:#d1fae5;
    color:#065f46;
    border-radius:8px;
    margin-bottom:10px
}
</style>
</head>
<body>

<div class="main">
    <?php if($notice): ?>
        <div class="notice"><?= htmlspecialchars($notice) ?></div>
    <?php endif; ?>

    <!-- Upload Form -->
    <h2>Upload New Image</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Page:</label>
        <input type="text" name="page" value="about" required><br><br>

        <label>Year:</label>
        <input type="number" name="year" value="<?= date('Y') ?>" required><br><br>

        <label>Month:</label>
        <select name="month" required>
            <?php 
            $months = [
                "January","February","March","April","May","June",
                "July","August","September","October","November","December"
            ];
            foreach ($months as $m) {
                echo "<option value='$m' " . ($m==date('F')?'selected':'') . ">$m</option>";
            }
            ?>
        </select><br><br>

        <label>Title:</label>
        <input type="text" name="title"><br><br>

        <label>Choose Image:</label>
        <input type="file" name="image" required><br><br>

        <button type="submit">Upload</button>
    </form>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Gallery Images</h2>
    <div class="grid">
        <?php foreach ($images as $img): ?>
            <div class="card">
                <img src="uploads/<?= htmlspecialchars($img['filename']) ?>" alt="">
                <div><?= htmlspecialchars($img['title'] ?: 'No Title') ?></div>
                <a href="admin.php?delete=<?= $img['id'] ?>" class="delete-btn"
                   onclick="return confirm('Delete this image?')">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
