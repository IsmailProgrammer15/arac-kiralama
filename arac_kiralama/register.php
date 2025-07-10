<?php
session_start();
require_once 'config.php';

$hata = "";
$basarili = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($email) || empty($password)) {
        $hata = "Tüm alanları doldurmanız gerekiyor.";
    } else {
        // Kullanıcı adı veya e-posta zaten var mı kontrolü
        $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bind_param("ss", $username, $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        if ($stmtCheck->num_rows > 0) {
            $hata = "Bu kullanıcı adı veya e-posta zaten kayıtlı.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $basarili = "Kayıt başarılı! Giriş yapabilirsiniz.";
            } else {
                $hata = "Kayıt sırasında hata oluştu: " . $stmt->error;
            }
            $stmt->close();
        }
        $stmtCheck->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kayıt Ol - Araç Kiralama</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f7fa;
        padding: 40px 20px;
        color: #333;
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
        margin-bottom: 30px;
        color: #222;
    }
    form {
        max-width: 400px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgb(0 0 0 / 0.1);
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        outline: none;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #2a9df4;
    }
    input[type="submit"] {
        background-color: #2a9df4;
        color: white;
        border: none;
        padding: 15px 30px;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 50px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
        background-color: #1b6bcf;
    }
    .message {
        max-width: 400px;
        margin: 10px auto 30px;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
    }
    .message.error {
        color: #cc0000;
    }
    .message.success {
        color: #0b8a00;
    }
    p.link-center {
        text-align: center;
        margin-top: 30px;
    }
    p.link-center a {
        color: #2a9df4;
        text-decoration: none;
        font-weight: 600;
    }
    p.link-center a:hover {
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
    </div>
    <div>
        <a href="login.php">Giriş Yap</a>
    </div>
</div>

<h2>Kayıt Formu</h2>

<?php if ($hata): ?>
    <div class="message error"><?php echo $hata; ?></div>
<?php endif; ?>
<?php if ($basarili): ?>
    <div class="message success"><?php echo $basarili; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <label>Kullanıcı Adı:</label>
    <input type="text" name="username" required>

    <label>E-posta:</label>
    <input type="email" name="email" required>

    <label>Şifre:</label>
    <input type="password" name="password" required>

    <input type="submit" value="Kayıt Ol">
</form>

<p class="link-center"><a href="login.php">Zaten hesabınız var mı? Giriş yap</a></p>

</body>
</html>
