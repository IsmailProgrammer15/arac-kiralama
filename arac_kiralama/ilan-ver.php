<?php
session_start();
require_once 'config.php';

// Kullanıcı giriş yapmamışsa yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=ilan-ver.php");
    exit;
}

$hata = "";
$basarili = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $kilometers = intval($_POST['kilometers'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    $uploadedFiles = [];
    $uploadDir = "uploads/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $name) {
            $tmpName = $_FILES['images']['tmp_name'][$key];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($ext, $allowed)) {
                $hata = "Sadece jpg, jpeg, png ve gif dosyaları yüklenebilir.";
                break;
            }

            $newName = uniqid() . "." . $ext;
            $destination = $uploadDir . $newName;

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedFiles[] = $destination;
            } else {
                $hata = "Dosya yüklenirken hata oluştu.";
                break;
            }
        }
    }

    if (!$hata) {
        if (empty($brand) || empty($model) || empty($transmission) || empty($fuel_type) || $kilometers <= 0 || $price <= 0) {
            $hata = "Lütfen tüm alanları eksiksiz ve doğru doldurun.";
        } else {
            $imagesJson = json_encode($uploadedFiles);
            $user_id = $_SESSION['user_id'];

            $sql = "INSERT INTO cars (brand, model, transmission, fuel_type, kilometers, price, description, images, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssiissi", $brand, $model, $transmission, $fuel_type, $kilometers, $price, $description, $imagesJson, $user_id);

            if ($stmt->execute()) {
                $basarili = "Araç ilanınız başarıyla eklendi.";
            } else {
                $hata = "Veritabanı hatası: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Araç İlanı Ver - Araç Kiralama</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Navbar CSS (eğer style.css içinde yoksa buraya koyabilirsin) */
        .navbar {
            background-color: #333;
            color: white;
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .navbar-left, .navbar-right {
            display: flex;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 25px;
            font-weight: 600;
            font-size: 15px;
        }
        .navbar a:last-child {
            margin-right: 0;
        }
        .navbar a:hover {
            text-decoration: underline;
        }

        /* Sayfa genel stili */
        body {
            background-color: #fff;  /* Beyaz arka plan */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }

        /* Form kapsayıcı */
        .form-container {
            max-width: 700px;
            background-color: #fafafa;
            margin: 40px auto;
            padding: 35px 40px;
            border-radius: 10px;
            box-shadow: 0 5px 18px rgba(207, 14, 14, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-size: 15px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 11px 14px;
            margin-bottom: 20px;
            border: 1.8px solid #bdc3c7;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            resize: vertical;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: #2980b9;
            outline: none;
        }

        input[type="file"] {
            margin-bottom: 25px;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            font-weight: 700;
            font-size: 18px;
            padding: 14px 0;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #1c5980;
        }

        /* Mesaj kutuları */
        .message {
            max-width: 700px;
            margin: 20px auto;
            padding: 15px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }

        .error {
            background-color: #fdecea;
            color: #e74c3c;
            border: 1.5px solid #e74c3c;
        }

        .success {
            background-color: #e8f8f5;
            color: #27ae60;
            border: 1.5px solid #27ae60;
        }

        /* Sayfa alt linki */
        .back-link {
            text-align: center;
            margin: 35px 0 50px 0;
        }
        .back-link a {
            color: #2980b9;
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="navbar-left">
        <a href="index.php">Ana Sayfa</a>
        <a href="ilan-ver.php">Araç İlanı Ver</a>
        <a href="ilanlar.php">İlanlar</a>
        <a href="profile.php">Profilim</a>
    </div>
    <div class="navbar-right">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Çıkış Yap (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="register.php">Kayıt Ol</a>
            <a href="login.php">Giriş Yap</a>
        <?php endif; ?>
    </div>
</div>

<div class="form-container">
    <h2>Araç İlanı Ver</h2>

    <?php if ($hata): ?>
        <div class="message error"><?php echo $hata; ?></div>
    <?php endif; ?>
    <?php if ($basarili): ?>
        <div class="message success"><?php echo $basarili; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Marka:</label>
        <input type="text" name="brand" required>

        <label>Model:</label>
        <input type="text" name="model" required>

        <label>Vites:</label>
        <select name="transmission" required>
            <option value="">Seçiniz</option>
            <option value="Otomatik">Otomatik</option>
            <option value="Manuel">Manuel</option>
            <option value="Yarı Otomatik">Yarı Otomatik</option>
            <option value="CVT">CVT</option>
            <option value="Düz">Düz</option>
        </select>

        <label>Yakıt Tipi:</label>
        <select name="fuel_type" required>
            <option value="">Seçiniz</option>
            <option value="Benzinli">Benzinli</option>
            <option value="Dizel">Dizel</option>
            <option value="LPG">LPG</option>
            <option value="Elektrikli">Elektrikli</option>
            <option value="Hibrit">Hibrit</option>
        </select>

        <label>Kilometre:</label>
        <input type="number" name="kilometers" min="1" required>

        <label>Fiyat (TL):</label>
        <input type="number" name="price" min="1" step="0.01" required>

        <label>Açıklama:</label>
        <textarea name="description" rows="5" placeholder="Aracınızla ilgili detayları yazın..."></textarea>

        <label>Araç Resimleri (birden fazla seçebilirsiniz):</label>
        <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.gif">

        <input type="submit" value="İlan Ver">
    </form>
</div>

<div class="back-link">
    <a href="index.php">&larr; Ana Sayfaya Dön</a>
</div>

</body>
</html>
