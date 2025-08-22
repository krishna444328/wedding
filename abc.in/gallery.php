<?php include "db.php"; ?>

<?php
$page = $_GET['page'] ?? 'home';
$stmt = $pdo->prepare("SELECT * FROM images WHERE page_slug=? AND is_visible=1 ORDER BY year DESC, month DESC, created_at DESC");
$stmt->execute([$page]);
$images = $stmt->fetchAll();

$grouped = [];
foreach ($images as $img) {
    $grouped[$img['year']][$img['month']][] = $img;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gallery - <?php echo htmlspecialchars($page); ?></title>
    <style>
        .grid { display:flex; flex-wrap:wrap; gap:15px; }
        .card { width:200px; border:1px solid #ddd; border-radius:8px; overflow:hidden; text-align:center; }
        .card img { width:100%; height:150px; object-fit:cover; }
    </style>
</head>
<body>
    <h1>Gallery: <?php echo htmlspecialchars($page); ?></h1>

    <?php foreach ($grouped as $year => $months): ?>
        <h2><?php echo $year; ?></h2>
        <?php foreach ($months as $month => $imgs): ?>
            <h3><?php echo date("F", mktime(0,0,0,$month,10)); ?></h3>
            <div class="grid">
                <?php foreach ($imgs as $img): ?>
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($img['filename']); ?>" alt="">
                        <p><?php echo htmlspecialchars($img['title'] ?? ""); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</body>
</html>
