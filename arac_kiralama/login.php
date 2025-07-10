<?php
session_start();
require_once 'config.php';

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $hata = "Kullanıcı adı ve şifre boş olamaz.";
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // İlan verme sayfasına yönlendir (varsa)
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirectPage = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirectPage");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $hata = "Şifre yanlış.";
            }
        } else {
            $hata = "Kullanıcı bulunamadı.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Giriş Yap - Araç Kiralama</title>
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
        color: #cc0000;
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
        <a href="register.php">Kayıt Ol</a>
    </div>
</div>

<h2>Giriş Formu</h2>

<?php if ($hata): ?>
    <div class="message"><?php echo $hata; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <label>Kullanıcı Adı:</label>
    <input type="text" name="username" required>

    <label>Şifre:</label>
    <input type="password" name="password" required>

    <input type="submit" value="Giriş Yap">
</form>

<p class="link-center"><a href="register.php">Hesabınız yok mu? Kayıt olun</a></p>

</body>
</html>
