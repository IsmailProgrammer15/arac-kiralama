<?php
session_start();
require_once 'config.php';

// id parametresi yoksa veya sayısal değilse ilanlar sayfasına yönlendir
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ilanlar.php");
    exit;
}

$car_id = intval($_GET['id']);

// İlan bilgilerini veritabanından çek
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Araç bulunamadı.";
    exit;
}

$car = $result->fetch_assoc();
$images = json_decode($car['images'], true);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?> - Detaylar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            background: #f9f9f9;
            color: #333;
            padding: 20px;
            border-radius: 10px;
        }
        h1 {
            margin-bottom: 15px;
        }
        .images {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .images img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .images img:hover {
            transform: scale(1.05);
        }
        .main-image {
            max-width: 100%;
            max-height: 400px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .details p {
            font-size: 1.1rem;
            margin: 8px 0;
        }
        a.back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 18px;
            background-color: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        a.back-link:hover {
            background-color: #2071b9;
        }
    </style>
    <script>
        // Küçük resimlere tıklayınca büyük resim değişsin
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
</head>
<body>

<h1><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h1>

<?php if (!empty($images)): ?>
    <img id="mainImage" src="<?php echo htmlspecialchars($images[0]); ?>" alt="Araç Resmi" class="main-image">
    <div class="images">
        <?php foreach ($images as $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Araç Resmi" onclick="changeMainImage('<?php echo htmlspecialchars($img); ?>')">
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <img src="uploads/varsayilan.jpg" alt="Varsayılan Resim" class="main-image">
<?php endif; ?>

<div class="details">
    <p><strong>Vites:</strong> <?php echo htmlspecialchars($car['transmission']); ?></p>
    <p><strong>Yakıt Tipi:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
    <p><strong>Kilometre:</strong> <?php echo number_format($car['kilometers']); ?> km</p>
    <p><strong>Fiyat:</strong> <?php echo number_format($car['price'], 2); ?> TL</p>
    <p><strong>Açıklama:</strong><br><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
</div>

<a href="ilanlar.php" class="back-link">← İlanlara Geri Dön</a>

</body>
</html>
