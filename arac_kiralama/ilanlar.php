<?php
session_start();
require_once 'config.php';

$sql = "SELECT * FROM cars ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Araç İlanları - Araç Kiralama</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f7fa;
        color: #333;
        padding: 40px 20px;
    }
    .navbar {
        background-color: #2a9df4;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
        margin-bottom: 40px;
    }
    .navbar a {
        color: white;
        text-decoration: none;
        margin-right: 25px;
        font-weight: 600;
    }
    .navbar a:last-child {
        margin-right: 0;
    }
    .navbar a:hover {
        opacity: 0.8;
    }
    h2 {
        text-align: center;
        margin-bottom: 40px;
        color: #222;
    }
    .ilan-container {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        justify-content: center;
    }
    .ilan-karti {
        background: white;
        width: 280px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgb(0 0 0 / 0.1);
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .ilan-karti:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgb(0 0 0 / 0.15);
    }
    .ilan-resim {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .ilan-detay {
        padding: 15px 20px;
    }
    .ilan-detay strong {
        color: #2a9df4;
        font-size: 1.2rem;
        display: block;
        margin-bottom: 8px;
    }
    .ilan-detay em {
        font-size: 0.9rem;
        color: #555;
        white-space: pre-line;
    }
    .ilan-detay p {
        margin: 5px 0;
        font-size: 0.95rem;
        color: #333;
    }
    .price {
        font-weight: 700;
        font-size: 1.1rem;
        color: #222;
        margin-top: 10px;
    }
    .links {
        text-align: center;
        margin-top: 40px;
    }
    .links a {
        text-decoration: none;
        color: #2a9df4;
        font-weight: 600;
        margin: 0 10px;
    }
    .links a:hover {
        text-decoration: underline;
    }
    @media (max-width: 800px) {
        .ilan-container {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
</head>
<body>

<div class="navbar">
    <div>
        <a href="index.php">Ana Sayfa</a>
        <a href="ilan-ver.php">Araç İlanı Ver</a>
        <a href="ilanlar.php">İlanlar</a>
    </div>
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Çıkış Yap (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="register.php">Kayıt Ol</a>
            <a href="login.php">Giriş Yap</a>
        <?php endif; ?>
    </div>
</div>

<h2>Araç İlanları</h2>

<div class="ilan-container">
<?php
if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $images = json_decode($row['images'], true);
        $firstImage = isset($images[0]) ? $images[0] : "uploads/varsayilan.jpg";
?>
    <div class="ilan-karti">
        <a href="arac-detay.php?id=<?php echo $row['id']; ?>">
            <img class="ilan-resim" src="<?php echo htmlspecialchars($firstImage); ?>" alt="Araç Resmi">
        </a>

        <div class="ilan-detay">
            <strong><?php echo htmlspecialchars($row['brand'] . " " . $row['model']); ?></strong><br>
            Vites: <?php echo htmlspecialchars($row['transmission']); ?><br>
            Yakıt: <?php echo htmlspecialchars($row['fuel_type']); ?><br>
            KM: <?php echo number_format($row['kilometers']); ?><br>
            Fiyat: <?php echo number_format($row['price'], 2); ?> TL<br><br>
            <em><?php echo nl2br(htmlspecialchars($row['description'])); ?></em>
        </div>
    </div>
<?php
    endwhile;
else:
    echo "<p style='text-align:center;'>Henüz ilan eklenmemiş.</p>";
endif;
?>
</div>

<div class="links">
    <a href="index.php">Ana Sayfa</a> | <a href="ilan-ver.php">Yeni İlan Ver</a>
</div>

</body>
</html>
