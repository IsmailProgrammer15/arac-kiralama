<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Ana Sayfa - Araç Kiralama</title>
<style>
    /* Reset ve temel ayarlar */
    * {
        margin: 0; padding: 0; box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: #f5f7fa;
        color: #333;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar */
    .navbar {
        background-color: #2a9df4;
        color: white;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
    }

    .navbar-left a,
    .navbar-right a {
        color: white;
        text-decoration: none;
        margin-right: 25px;
        font-weight: 600;
        transition: opacity 0.3s ease;
    }

    .navbar-left a:last-child,
    .navbar-right a:last-child {
        margin-right: 0;
    }

    .navbar a:hover {
        opacity: 0.8;
    }

    /* Kullanıcı karşılama mesajı */
    .welcome-message {
        text-align: center;
        margin: 40px 0;
        font-size: 1.8rem;
        color: #222;
    }

    /* Hero Section */
    .hero {
        background: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1470&q=80') no-repeat center center/cover;
        height: 450px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-shadow: 1px 1px 8px rgba(0,0,0,0.8);
        padding: 0 20px;
        text-align: center;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .hero p {
        font-size: 1.2rem;
        max-width: 600px;
        margin-bottom: 30px;
    }

    /* Arama Formu */
    .search-form {
        display: flex;
        max-width: 700px;
        width: 100%;
        background: rgba(255,255,255,0.95);
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgb(0 0 0 / 0.15);
    }

    .search-form input,
    .search-form select {
        border: none;
        padding: 15px 20px;
        flex: 1;
        font-size: 1rem;
        outline: none;
    }

    .search-form select {
        max-width: 180px;
    }

    .search-form button {
        background-color: #0078d7;
        color: white;
        border: none;
        padding: 0 35px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-form button:hover {
        background-color: #005ea0;
    }

    /* Popüler Araçlar Bölümü */
    .popular-section {
        padding: 60px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .popular-section h2 {
        font-size: 2rem;
        margin-bottom: 40px;
        text-align: center;
        color: #222;
    }

    .card-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 6px 18px rgb(0 0 0 / 0.1);
        width: 280px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgb(0 0 0 / 0.15);
    }

    .card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .card-body {
        padding: 20px;
    }

    .card-body h3 {
        margin-bottom: 10px;
        font-size: 1.3rem;
        color: #0078d7;
    }

    .card-body p {
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .card-body .price {
        font-weight: 700;
        font-size: 1.1rem;
        color: #222;
    }

    /* Footer */
    footer {
        margin-top: auto;
        background: #222;
        color: white;
        text-align: center;
        padding: 20px 10px;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .card-container {
            flex-direction: column;
            align-items: center;
        }

        .search-form {
            flex-direction: column;
        }

        .search-form input,
        .search-form select,
        .search-form button {
            max-width: 100%;
            margin: 5px 0;
            border-radius: 30px;
        }
    }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-left">
        <a href="index.php">Ana Sayfa</a>
        <a href="ilan-ver.php">Araç İlanı Ver</a>
        <a href="ilanlar.php">İlanlar</a>
        <a href="#">Ek Hizmetler</a>
    </div>
    <div class="navbar-right">
        <a href="#">Konum Seçin</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Çıkış Yap (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="register.php">Kayıt Ol</a>
            <a href="login.php">Giriş Yap</a>
        <?php endif; ?>
    </div>
</div>

<!-- Kullanıcı karşılama mesajı -->
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="welcome-message">
        Hoşgeldin, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
    </div>
<?php else: ?>
    <div class="welcome-message">
        Hoşgeldiniz! Lütfen giriş yapın veya kayıt olun.
    </div>
<?php endif; ?>

<!-- Hero Bölümü -->
<div class="hero">
    <h1>Hayalinizdeki Aracı Kiralayın</h1>
    <p>Geniş araç seçeneklerimizle size en uygun aracı hemen bulun ve kiralayın!</p>
    
    <form class="search-form" action="ilanlar.php" method="GET">
        <input type="text" name="brand" placeholder="Marka ara..." />
        <select name="transmission">
            <option value="">Vites Türü</option>
            <option value="Otomatik">Otomatik</option>
            <option value="Manuel">Manuel</option>
            <option value="Yarı Otomatik">Yarı Otomatik</option>
            <option value="CVT">CVT</option>
            <option value="Düz">Düz</option>
        </select>
        <button type="submit">Ara</button>
    </form>
</div>

<!-- Popüler Araçlar -->
 
<div class="popular-section">
    <h2>Popüler Araçlar</h2>
    <div class="card-container">
        <div class="card">
            <img src="https://wp.oggusto.com/wp-content/uploads/2024/09/bmw-320i-sedan-hakkinda-bilmeniz-gerekenler.webp" alt="Araç 1" />
            <div class="card-body">
                <h3>BMW 320i</h3>
                <p>Otomatik, Benzinli, 50,000 KM</p>
                <div class="price">3500 TL / Günlük</div>
            </div>
        </div>
        
        <div class="card">
            <img src="https://media.ed.edmunds-media.com/audi/a4/2022/oem/2022_audi_a4_sedan_prestige-s-line_fq_oem_1_815.jpg" alt="Araç 2" />
            <div class="card-body">
                <h3>Audi A4</h3>
                <p>Manuel, Dizel, 70,000 KM</p>
                <div class="price">3000 TL / Günlük</div>
            </div>
        </div>
    
        <div class="card">
            <img src="https://cylindersi.pl/wp-content/uploads/2022/05/Mercedes-AMG-G63-sylwetka.jpg" alt="Araç 2" />
            <div class="card-body">
                <h3>Mercedes G63 AMG</h3>
                <p>Otomatik, Dizel, 20,000 KM</p>
                <div class="price">10000 TL / Günlük</div>
            </div>
        </div>
        <div class="card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2020-porsche-911-carrera-4s-101-1577743864.jpg?crop=0.566xw:0.567xh;0.230xw,0.298xh&resize=1200:*" alt="Araç 2" />
            <div class="card-body">
                <h3>Porsche Carrera 911 4S</h3>
                <p>Otomatik, Dizel, 15,000 KM</p>
                <div class="price">7000 TL / Günlük</div>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/0_488_GTB.jpg" alt="Araç 2" />
            <div class="card-body">
                <h3>Ferrari 458 Italia</h3>
                <p>Otomatik, Benzinli, 5,000 KM</p>
                <div class="price">12000 TL / Günlük</div>
            </div>
        </div>


        <div class="card">
            <img src="https://friendscarrental.com/frontend/image/range-rover-vogue-2019-1713166235104.webp" alt="Araç 2" />
            <div class="card-body">
                <h3>Range Rover Vogue</h3>
                <p>Manuel, Dizel, 70,000 KM</p>
                <div class="price">3000 TL / Günlük</div>
            </div>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 Araç Kiralama | Tüm hakları saklıdır.
</footer>

</body>
</html>
