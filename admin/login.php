<?php
session_start();
if ($_GET && $_GET["cikis"] == 1) {
    unset($_SESSION["loginkey"]);
    header("Location: login.php");
}

if ($_POST) {
    $kadi = $_POST["kadi"];
    $ksifre = $_POST["ksifre"];
    if ($kadi && $ksifre) {
        include "../config/vtabani.php";
        try {
            $sorgu = "SELECT kadi,sifre,onay FROM kullanicilar WHERE kadi=:kadi AND sifre=:ksifre AND onay='1'";
            $stmt = $con->prepare($sorgu);
            $stmt->bindParam(":kadi", $kadi);
            $stmt->bindParam(":ksifre", $ksifre);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $_SESSION["loginkey"] = $kadi;
                header("Location: index.php");
            } else {
                $hata = "Kullanıcı adı veya şifre yanlış.";
            }
        } catch (PDOException $e) {
            die("Hata: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>✨ Giriş Paneli ✨</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome & Google Font -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Quicksand', sans-serif;
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 40px;
            width: 350px;
            color: #fff;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 30px;
            font-size: 26px;
            color: #fff;
        }

        .login-box .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .login-box input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: none;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
            outline: none;
        }

        .login-box .form-group i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #fff;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-box button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(174, 83, 233, 0.6);
        }

        .error {
            background-color: #e74c3c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        ::placeholder {
            color: #eee;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <form class="login-box" method="post" action="">
            <h2><i class="fas fa-lock"></i> Admin Girişi</h2>
            <?php if (!empty($hata)): ?>
                <div class="error"><?php echo $hata; ?></div>
            <?php endif; ?>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="kadi" placeholder="Kullanıcı Adı" required>
            </div>
            <div class="form-group">
                <i class="fas fa-key"></i>
                <input type="password" name="ksifre" placeholder="Şifre" required>
            </div>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
