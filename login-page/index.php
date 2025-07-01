<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// oturum sonlandırma kontrolü
if ($_GET) {
    $cikis = $_GET["cikis"];
    if ($cikis == 1) {
        //session_destroy(); // TÜM SESSIONları - oturumu sonlandır
        unset($_SESSION["kullanici_loginkey"]); // oturum değişkenini sıfırla
        header("Location: /home-page/"); // anasayfaya yönlendir
    }
}

// kullanıcı kontrolü
$alert_message = "";
$alert_type = "";

if ($_POST){
    $eposta = $_POST["eposta"];
    $ksifre = $_POST["ksifre"];
    
    if (isset($eposta) && isset($ksifre)) {
        include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';

        try {
            $sorgu = "SELECT adsoyad,eposta,sifre,onay FROM kullanicilar WHERE eposta=:eposta AND sifre=:ksifre";
            $stmt = $con->prepare($sorgu);

            // parametreleri bağla
            $stmt->bindParam(":eposta", $eposta);
            $stmt->bindParam(":ksifre", $ksifre);
            // sorguyu çalıştır
            $stmt->execute();
            // gelen kaydı bir değişkende sakla
            $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

            $sayi = $stmt->rowCount();

            if(@$kayit['onay']==0 && $sayi>0 ){
                $alert_message = "Özür dileriz <strong>".$kayit['adsoyad']."</strong> henüz üyeliğiniz onaylanmamış.";
                $alert_type = "warning";
            } else if (@$kayit['onay']!=0 && $sayi>0) {
                $_SESSION["kullanici_loginkey"] = $eposta; // oturum değişkenini oluştur
                $alert_message = "Giriş başarılı! Yönlendiriliyorsunuz...";
                $alert_type = "success";
                echo "<script>setTimeout(function(){ window.location.href = '/home-page/'; }, 2000);</script>";
            } else if($eposta == ""){
                $alert_message = "E-posta adresinizi yazmadınız.";
                $alert_type = "danger";
            } else if($ksifre == ""){
                $alert_message = "Şifrenizi yazmadınız.";
                $alert_type = "danger";
            } else {
                $alert_message = "E-posta adresiniz veya şifreniz yanlış. <a href='/login-page/forgot-password/' class='alert-link'>Şifrenizi unuttuysanız buraya tıklayın.</a>";
                $alert_type = "danger";
            }
        }
        // hatayı göster
        catch(PDOException $exception){
            $alert_message = "Sistem hatası: " . $exception->getMessage();
            $alert_type = "danger";
        }
    }
}

$_SESSION["kullanici_loginkey"] = isset($_SESSION["kullanici_loginkey"]) ? $_SESSION["kullanici_loginkey"] : "";

// favori oluşturulmamışsa oluştur
$_SESSION['favori']=isset($_SESSION['favori']) ? $_SESSION['favori'] : array();
?>

<!-- CSS Styles -->
<style>
    /* Login Page Styles */
    .login-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 80px 0;
    }

    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
    }

    .login-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .login-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: white;
        border-radius: 30px 30px 0 0;
    }

    .login-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        backdrop-filter: blur(10px);
    }

    .login-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .login-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .login-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .login-body {
        padding: 50px 40px 40px;
    }

    .form-group {
        margin-bottom: 25px;
        position: relative;
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px 20px 15px 50px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
        outline: none;
    }

    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        z-index: 2;
        margin-top: 12px;
    }

    .btn-login {
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        border: none;
        border-radius: 12px;
        padding: 15px 40px;
        font-weight: 600;
        font-size: 16px;
        color: white;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .forgot-password {
        color: #3b82f6;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #1e40af;
        text-decoration: underline;
    }

    .login-links {
        background: #f8fafc;
        padding: 30px 40px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }

    .login-links p {
        margin-bottom: 15px;
        color: #64748b;
    }

    .btn-register {
        background: transparent;
        border: 2px solid #3b82f6;
        color: #3b82f6;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-register:hover {
        background: #3b82f6;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    /* Custom Alert Styles */
    .custom-alert {
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.5s ease;
    }

    .custom-alert.alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .custom-alert.alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .custom-alert.alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .alert-icon {
        margin-right: 10px;
        font-size: 18px;
    }

    /* Side Image */
    .login-image {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.9), rgba(59, 130, 246, 0.8)), url('content/images/login-bg.jpg');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        padding: 40px;
        position: relative;
    }

    .login-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(30, 64, 175, 0.8), rgba(59, 130, 246, 0.6));
    }

    .login-image-content {
        position: relative;
        z-index: 2;
    }

    .login-image h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .login-image p {
        font-size: 1.2rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: none;
        margin-right: 10px;
    }

    .btn-login.loading .loading-spinner {
        display: inline-block;
    }

    /* Animations */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .login-icon {
        animation: pulse 2s infinite;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-container {
            padding: 40px 0;
        }

        .login-body {
            padding: 30px 20px;
        }

        .login-header {
            padding: 30px 20px;
        }

        .login-links {
            padding: 20px;
        }

        .login-title {
            font-size: 1.5rem;
        }

        .login-image {
            padding: 20px;
            min-height: 200px;
        }

        .login-image h3 {
            font-size: 1.8rem;
        }

        .login-image p {
            font-size: 1rem;
        }
    }
</style>

<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="login-card">
                    <div class="row g-0">
                        <!-- Left Side - Image -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="login-image">
                                <div class="login-image-content">
                                    <h3>Hoş Geldiniz!</h3>
                                    <p>Gayrimenkul dünyasının kapılarını açmak için giriş yapın. Hayalinizdeki eve giden yolculuk burada başlıyor.</p>
                                    <div class="mt-4">
                                        <i class="fas fa-home fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class="col-lg-6">
                            <div class="login-header">
                                <div class="login-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h2 class="login-title">Üye Girişi</h2>
                                <p class="login-subtitle">Hesabınıza güvenli giriş yapın</p>
                            </div>
                            
                            <div class="login-body">
                                <?php if ($alert_message): ?>
                                    <div class="custom-alert alert-<?php echo $alert_type; ?>">
                                        <?php if ($alert_type == 'success'): ?>
                                            <i class="fas fa-check-circle alert-icon"></i>
                                        <?php elseif ($alert_type == 'danger'): ?>
                                            <i class="fas fa-exclamation-circle alert-icon"></i>
                                        <?php elseif ($alert_type == 'warning'): ?>
                                            <i class="fas fa-exclamation-triangle alert-icon"></i>
                                        <?php endif; ?>
                                        <?php echo $alert_message; ?>
                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="giris" id="loginForm">
                                    <div class="form-group">
                                        <label for="eposta" class="form-label">E-Posta Adresi</label>
                                        <div class="position-relative">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="eposta" 
                                                   name="eposta" 
                                                   placeholder="ornek@email.com"
                                                   value="<?php echo isset($_POST['eposta']) ? htmlspecialchars($_POST['eposta']) : ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="ksifre" class="form-label">Şifre</label>
                                        <div class="position-relative">
                                            <i class="fas fa-lock input-icon"></i>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="ksifre" 
                                                   name="ksifre" 
                                                   placeholder="Şifrenizi girin"
                                                   required>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <a href="/login-page/forgot-password/" class="forgot-password">
                                                <i class="fas fa-key me-1"></i>Şifremi Unuttum
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn-login" id="loginBtn">
                                        <span class="loading-spinner">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Giriş Yap
                                    </button>
                                </form>
                            </div>
                            
                            <div class="login-links">
                                <p>Henüz üye değil misiniz?</p>
                                <a href="/register/" class="btn-register">
                                    <i class="fas fa-user-plus me-2"></i>Hemen Kayıt Olun
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    // Form submission with loading state
    loginForm.addEventListener('submit', function(e) {
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
        
        // Reset after 5 seconds if no redirect happens
        setTimeout(function() {
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
        }, 5000);
    });
    
    // Input validation and styling
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
        
        // Real-time validation
        input.addEventListener('input', function() {
            if (this.validity.valid) {
                this.style.borderColor = '#10b981';
            } else {
                this.style.borderColor = '#ef4444';
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.custom-alert');
    alerts.forEach(alert => {
        if (!alert.classList.contains('alert-success')) {
            setTimeout(function() {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        }
    });
    
    // Password visibility toggle (optional enhancement)
    const passwordInput = document.getElementById('ksifre');
    const togglePassword = document.createElement('button');
    togglePassword.type = 'button';
    togglePassword.className = 'btn btn-link position-absolute';
    togglePassword.style.cssText = 'right: 15px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; margin-top: 12px;';
    togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
    
    passwordInput.parentElement.appendChild(togglePassword);
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>