<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Form işleme
$alert_message = "";
$alert_type = "";

if($_POST){
    // veritabanı yapılandırma dosyasını dahil et
    include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php'; 
    
    if($_POST['adsoyad']=="" || $_POST['kadi']=="" || $_POST['sifre']=="" || $_POST['eposta']=="" || $_POST['tel_no']==""){
        $alert_message = "Tüm alanları doldurmadınız.";
        $alert_type = "danger";
    }
    else{
        try{
            // Önce aynı e-posta veya kullanıcı adı var mı kontrol et
            $kontrol_sorgu = "SELECT COUNT(*) FROM kullanicilar WHERE eposta=:eposta OR kadi=:kadi";
            $kontrol_stmt = $con->prepare($kontrol_sorgu);
            $kontrol_stmt->bindParam(':eposta', $_POST['eposta']);
            $kontrol_stmt->bindParam(':kadi', $_POST['kadi']);
            $kontrol_stmt->execute();
            $var_mi = $kontrol_stmt->fetchColumn();
            
            if($var_mi > 0){
                $alert_message = "Bu e-posta adresi veya kullanıcı adı zaten kullanılıyor.";
                $alert_type = "warning";
            }
            else{
                // kayıt ekleme sorgusu
                $sorgu = "INSERT INTO kullanicilar SET adsoyad=:adsoyad, kadi=:kadi, sifre=:sifre, eposta=:eposta, tel_no=:tel_no";
                // sorguyu hazırla
                $stmt = $con->prepare($sorgu);
                // post edilen değerler
                $adsoyad=htmlspecialchars(strip_tags($_POST['adsoyad']));
                $kadi=htmlspecialchars(strip_tags($_POST['kadi']));
                $sifre=htmlspecialchars(strip_tags($_POST['sifre']));
                $eposta=htmlspecialchars(strip_tags($_POST['eposta']));
                $tel_no=htmlspecialchars(strip_tags($_POST['tel_no']));
                // parametreleri bağla
                $stmt->bindParam(':adsoyad', $adsoyad);
                $stmt->bindParam(':kadi', $kadi);
                $stmt->bindParam(':sifre', $sifre);
                $stmt->bindParam(':eposta', $eposta);
                $stmt->bindParam(':tel_no', $tel_no);
                // sorguyu çalıştır
                if($stmt->execute()){
                    $alert_message = "Kaydınız başarıyla oluşturuldu! Onay işlemine gönderildi.";
                    $alert_type = "success";
                }else{
                    $alert_message = "Kayıt ekleme başarısız. Lütfen tekrar deneyin.";
                    $alert_type = "danger";
                }
            }
        }
        // hatayı göster
        catch(PDOException $exception){
            $alert_message = "Sistem hatası: " . $exception->getMessage();
            $alert_type = "danger";
        }
    }
}

// URL parametrelerinden gelen mesajlar
$islem = isset($_GET['islem']) ? $_GET['islem'] : "";

if($islem=='basarili'){
    $alert_message = "Kaydınız onay işlemine gönderildi.";
    $alert_type = "success";
}
else if($islem=='basarisiz'){
    $alert_message = "Kayıt ekleme başarısız.";
    $alert_type = "danger";
}
else if($islem=='girisYokilanver'){
    $alert_message = "Ücretsiz İlan vermek için öncelikle kayıt olmanız gerekir.<br/>Eğer üye iseniz <a href='/login-page/' class='alert-link'>giriş</a> yapmanız gerekir.";
    $alert_type = "info";
}
else if($islem=='mesaj_gonderemez'){
    $alert_message = "İlan sahibine mesaj gönderebilmek için öncelikle kayıt olmanız gerekir.<br/>Eğer üye iseniz <a href='/login-page/' class='alert-link'>giriş</a> yapmanız gerekir.";
    $alert_type = "info";
}
else if($islem=='bosluk'){
    $alert_message = "Tüm alanları doldurmadınız.";
    $alert_type = "danger";
}
?>

<!-- CSS Styles -->
<style>
    /* Register Page Styles */
    .register-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 80px 0;
    }

    .register-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
    }

    .register-header {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .register-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: white;
        border-radius: 30px 30px 0 0;
    }

    .register-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        backdrop-filter: blur(10px);
        animation: bounce 2s infinite;
    }

    .register-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .register-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .register-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .register-body {
        padding: 50px 40px 40px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 25px;
        position: relative;
        flex: 1;
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
        width: 100%;
    }

    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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

    .btn-register {
        background: linear-gradient(135deg, #059669, #10b981);
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

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(5, 150, 105, 0.3);
        background: linear-gradient(135deg, #047857, #059669);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .register-links {
        background: #f8fafc;
        padding: 30px 40px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }

    .register-links p {
        margin-bottom: 15px;
        color: #64748b;
    }

    .btn-login {
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

    .btn-login:hover {
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

    .custom-alert.alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    .alert-icon {
        margin-right: 10px;
        font-size: 18px;
    }

    /* Side Image */
    .register-image {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.9), rgba(16, 185, 129, 0.8)), url('content/images/register-bg.jpg');
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

    .register-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(5, 150, 105, 0.8), rgba(16, 185, 129, 0.6));
    }

    .register-image-content {
        position: relative;
        z-index: 2;
    }

    .register-image h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .register-image p {
        font-size: 1.2rem;
        opacity: 0.9;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        text-align: left;
    }

    .feature-list li {
        padding: 10px 0;
        display: flex;
        align-items: center;
    }

    .feature-list i {
        margin-right: 15px;
        color: rgba(255, 255, 255, 0.8);
        width: 20px;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: none;
        margin-right: 10px;
    }

    .btn-register.loading .loading-spinner {
        display: inline-block;
    }

    /* Password Strength Indicator */
    .password-strength {
        margin-top: 8px;
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        transition: all 0.3s ease;
        width: 0%;
    }

    .strength-weak { background: #ef4444; width: 33%; }
    .strength-medium { background: #f59e0b; width: 66%; }
    .strength-strong { background: #10b981; width: 100%; }

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

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .register-container {
            padding: 40px 0;
        }

        .register-body {
            padding: 30px 20px;
        }

        .register-header {
            padding: 30px 20px;
        }

        .register-links {
            padding: 20px;
        }

        .register-title {
            font-size: 1.5rem;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .register-image {
            padding: 20px;
            min-height: 200px;
        }

        .register-image h3 {
            font-size: 1.8rem;
        }

        .register-image p {
            font-size: 1rem;
        }
    }
</style>

<div class="register-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="register-card">
                    <div class="row g-0">
                        <!-- Left Side - Image -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="register-image">
                                <div class="register-image-content">
                                    <h3>Aramıza Katılın!</h3>
                                    <p>Gayrimenkul dünyasında yeni bir başlangıç yapın. Ücretsiz üyelikle avantajları keşfedin.</p>
                                    
                                    <ul class="feature-list">
                                        <li><i class="fas fa-check-circle"></i> Ücretsiz ilan verme</li>
                                        <li><i class="fas fa-check-circle"></i> Gelişmiş arama filtreleri</li>
                                        <li><i class="fas fa-check-circle"></i> Favori listesi oluşturma</li>
                                        <li><i class="fas fa-check-circle"></i> Anlık bildirimler</li>
                                        <li><i class="fas fa-check-circle"></i> Profesyonel destek</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Register Form -->
                        <div class="col-lg-6">
                            <div class="register-header">
                                <div class="register-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h2 class="register-title">Üye Kayıt</h2>
                                <p class="register-subtitle">Hemen ücretsiz hesap oluşturun</p>
                            </div>
                            
                            <div class="register-body">
                                <?php if ($alert_message): ?>
                                    <div class="custom-alert alert-<?php echo $alert_type; ?>">
                                        <?php if ($alert_type == 'success'): ?>
                                            <i class="fas fa-check-circle alert-icon"></i>
                                        <?php elseif ($alert_type == 'danger'): ?>
                                            <i class="fas fa-exclamation-circle alert-icon"></i>
                                        <?php elseif ($alert_type == 'warning'): ?>
                                            <i class="fas fa-exclamation-triangle alert-icon"></i>
                                        <?php elseif ($alert_type == 'info'): ?>
                                            <i class="fas fa-info-circle alert-icon"></i>
                                        <?php endif; ?>
                                        <?php echo $alert_message; ?>
                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="kayit" id="registerForm">
                                    <div class="form-group">
                                        <label for="adsoyad" class="form-label">Ad - Soyad</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user input-icon"></i>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="adsoyad" 
                                                   name="adsoyad" 
                                                   placeholder="Adınızı ve soyadınızı girin"
                                                   value="<?php echo isset($_POST['adsoyad']) ? htmlspecialchars($_POST['adsoyad']) : ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="kadi" class="form-label">Kullanıcı Adı</label>
                                            <div class="position-relative">
                                                <i class="fas fa-at input-icon"></i>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="kadi" 
                                                       name="kadi" 
                                                       placeholder="Kullanıcı adınızı girin"
                                                       value="<?php echo isset($_POST['kadi']) ? htmlspecialchars($_POST['kadi']) : ''; ?>"
                                                       required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tel_no" class="form-label">Telefon</label>
                                            <div class="position-relative">
                                                <i class="fas fa-phone input-icon"></i>
                                                <input type="tel" 
                                                       class="form-control" 
                                                       id="tel_no" 
                                                       name="tel_no" 
                                                       placeholder="5XX XXX XX XX"
                                                       value="<?php echo isset($_POST['tel_no']) ? htmlspecialchars($_POST['tel_no']) : ''; ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
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
                                        <label for="sifre" class="form-label">Şifre</label>
                                        <div class="position-relative">
                                            <i class="fas fa-lock input-icon"></i>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="sifre" 
                                                   name="sifre" 
                                                   placeholder="Güçlü bir şifre oluşturun"
                                                   required>
                                            <div class="password-strength">
                                                <div class="password-strength-bar" id="strengthBar"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn-register" id="registerBtn">
                                        <span class="loading-spinner">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                        <i class="fas fa-user-plus me-2"></i>
                                        Kayıt Ol
                                    </button>
                                </form>
                            </div>
                            
                            <div class="register-links">
                                <p>Zaten hesabınız var mı?</p>
                                <a href="/login-page/" class="btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
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
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const passwordInput = document.getElementById('sifre');
    const strengthBar = document.getElementById('strengthBar');
    const phoneInput = document.getElementById('tel_no');
    
    // Form submission with loading state
    registerForm.addEventListener('submit', function(e) {
        registerBtn.classList.add('loading');
        registerBtn.disabled = true;
        
        // Reset after 5 seconds if no redirect happens
        setTimeout(function() {
            registerBtn.classList.remove('loading');
            registerBtn.disabled = false;
        }, 5000);
    });
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        strengthBar.className = 'password-strength-bar';
        
        if (strength <= 1) {
            strengthBar.classList.add('strength-weak');
        } else if (strength <= 2) {
            strengthBar.classList.add('strength-medium');
        } else {
            strengthBar.classList.add('strength-strong');
        }
    });
    
    // Phone number formatting
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                value = value;
            } else if (value.length <= 6) {
                value = value.slice(0,3) + ' ' + value.slice(3);
            } else if (value.length <= 8) {
                value = value.slice(0,3) + ' ' + value.slice(3,6) + ' ' + value.slice(6);
            } else {
                value = value.slice(0,3) + ' ' + value.slice(3,6) + ' ' + value.slice(6,8) + ' ' + value.slice(8,10);
            }
        }
        this.value = value;
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
            if (this.validity.valid && this.value.length > 0) {
                this.style.borderColor = '#10b981';
                this.nextElementSibling?.classList.remove('text-danger');
            } else if (this.value.length > 0) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#e2e8f0';
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
    
    // Username availability check (optional enhancement)
    const usernameInput = document.getElementById('kadi');
    let usernameTimeout;
    
    usernameInput.addEventListener('input', function() {
        clearTimeout(usernameTimeout);
        const username = this.value;
        
        if (username.length >= 3) {
            usernameTimeout = setTimeout(function() {
                // Here you could add AJAX call to check username availability
                console.log('Checking username availability for:', username);
            }, 1000);
        }
    });
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>