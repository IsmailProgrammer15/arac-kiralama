<?php
session_start();
require_once 'config.php';

// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Kullanıcının ilanlarını çek
$sql = "SELECT * FROM cars WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<title>Profilim - Araç Kiralama</title>
<link rel="stylesheet" href="css/style.css" />
<style>
.ilan-karti {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    display: flex;
    gap: 15px;
    background: #fff;
}
.ilan-resim {
    width: 150px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}
.ilan-detay {
    flex-grow: 1;
}
.ilan-actions a {
    margin-right: 15px;
    text-decoration: none;
    color: #2a9df4;
    font-weight: 600;
}
.ilan-actions a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="navbar">
    <div>
        <a href="index.php">Ana Sayfa</a>
        <a href="ilan-ver.php">Araç İlanı Ver</a>
        <a href="ilanlar.php">İlanlar</a>
        <a href="profile.php">Profilim</a>
    </div>
    <div>
        <a href="logout.php">Çıkış Yap (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    </div>
</div>

<h2>Profilim - Verdiğiniz İlanlar</h2>

<?php
if ($result->num_rows == 0) {
    echo "<p>Henüz ilanınız yok. <a href='ilan-ver.php'>Yeni ilan verin</a>.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        $images = json_decode($row['images'], true);
        $firstImage = isset($images[0]) ? $images[0] : "uploads/varsayilan.jpg";
        ?>
        <div class="ilan-karti">
            <img class="ilan-resim" src="<?php echo htmlspecialchars($firstImage); ?>" alt="Araç Resmi">
            <div class="ilan-detay">
                <strong><?php echo htmlspecialchars($row['brand'] . " " . $row['model']); ?></strong>
                <p>Vites: <?php echo htmlspecialchars($row['transmission']); ?></p>
                <p>Yakıt: <?php echo htmlspecialchars($row['fuel_type']); ?></p>
                <p>Kilometre: <?php echo number_format($row['kilometers']); ?></p>
                <p>Fiyat: <?php echo number_format($row['price'], 2); ?> TL</p>
                <p><em><?php echo nl2br(htmlspecialchars($row['description'])); ?></em></p>
                <div class="ilan-actions">
                    <a href="ilan-duzenle.php?id=<?php echo $row['id']; ?>">Düzenle</a>
                    <a href="ilan-sil.php?id=<?php echo $row['id']; ?>" onclick="return confirm('İlanı silmek istediğinize emin misiniz?');">Sil</a>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

</body>
</html>
