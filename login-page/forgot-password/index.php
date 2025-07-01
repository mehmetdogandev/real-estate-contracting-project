<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$alert_message = "";
$alert_type = "";
$found_password = "";

if($_POST){
    // veritabanı yapılandırma dosyasını dahil et
    include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';
    
    // Input validation
    $kadi = trim($_POST['kadi']);
    $tel_no = trim($_POST['tel_no']);
    
    if(empty($kadi) || empty($tel_no)){
        $alert_message = "Lütfen tüm alanları doldurun.";
        $alert_type = "warning";
    }
    else{
        try{
            // doğrulama sorgusu - güvenli prepared statement kullanımı
            $sorgu = "SELECT sifre, onay, adsoyad FROM kullanicilar WHERE kadi=:kadi AND tel_no=:tel_no";

            $stmt = $con->prepare($sorgu);
            $stmt->bindParam(':kadi', $kadi);
            $stmt->bindParam(':tel_no', $tel_no);
            // sorguyu çalıştır
            $stmt->execute();
            // gelen kaydı bir değişkende sakla
            $kayit = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($kayit && isset($kayit['sifre'])){
                $found_password = $kayit['sifre'];
                $alert_message = "İşlem doğrulandı! Şifreniz bulundu.";
                $alert_type = "success";
                
                if($kayit['onay'] == 0){
                    $alert_message .= "<br><small><strong>NOT:</strong> Kaydınız henüz onaylanmamış. Bu yüzden sisteme giriş yapamazsınız.</small>";
                }
            }
            else{
                $alert_message = "Girdiğiniz bilgilere ait bir kayıt bulunamadı. Lütfen kullanıcı adınızı ve telefon numaranızı kontrol edin.";
                $alert_type = "danger";
            }
        }
        // hatayı göster
        catch(PDOException $exception){
            $alert_message = "Sistem hatası oluştu. Lütfen daha sonra tekrar deneyin.";
            $alert_type = "danger";
            error_log('Password Recovery Error: ' . $exception->getMessage());
        }
    }
}
?>

<!-- CSS Styles -->
<style>
    /* Password Recovery Page Styles */
    .recovery-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 80px 0;
    }

    .recovery-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
    }

    .recovery-header {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .recovery-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: white;
        border-radius: 30px 30px 0 0;
    }

    .recovery-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        backdrop-filter: blur(10px);
        animation: pulse 2s infinite;
    }

    .recovery-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .recovery-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .recovery-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .recovery-body {
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
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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

    .btn-recovery {
        background: linear-gradient(135deg, #dc2626, #ef4444);
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

    .btn-recovery:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
        background: linear-gradient(135deg, #b91c1c, #dc2626);
    }

    .btn-recovery:active {
        transform: translateY(0);
    }

    .recovery-links {
        background: #f8fafc;
        padding: 30px 40px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }

    .recovery-links p {
        margin-bottom: 15px;
        color: #64748b;
    }

    .btn-back {
        background: transparent;
        border: 2px solid #6b7280;
        color: #6b7280;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        margin-right: 15px;
    }

    .btn-back:hover {
        background: #6b7280;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .btn-register {
        background: transparent;
        border: 2px solid #10b981;
        color: #10b981;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-register:hover {
        background: #10b981;
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

    /* Password Display */
    .password-display {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .password-display::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(16, 185, 129, 0.1), transparent);
        animation: shine 2s infinite;
        pointer-events: none;
    }

    .password-value {
        font-size: 2rem;
        font-weight: 700;
        color: #065f46;
        letter-spacing: 2px;
        font-family: 'Courier New', monospace;
        margin: 10px 0;
        position: relative;
        z-index: 2;
    }

    .password-label {
        color: #059669;
        font-weight: 600;
        margin-bottom: 10px;
        position: relative;
        z-index: 2;
    }

    .copy-button {
        background: #10b981;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .copy-button:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* Side Image */
    .recovery-image {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.9), rgba(239, 68, 68, 0.8)), url('content/images/recovery-bg.jpg');
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

    .recovery-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(220, 38, 38, 0.8), rgba(239, 68, 68, 0.6));
    }

    .recovery-image-content {
        position: relative;
        z-index: 2;
    }

    .recovery-image h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .recovery-image p {
        font-size: 1.2rem;
        opacity: 0.9;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .security-tips {
        list-style: none;
        padding: 0;
        text-align: left;
    }

    .security-tips li {
        padding: 10px 0;
        display: flex;
        align-items: center;
    }

    .security-tips i {
        margin-right: 15px;
        color: rgba(255, 255, 255, 0.8);
        width: 20px;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: none;
        margin-right: 10px;
    }

    .btn-recovery.loading .loading-spinner {
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

    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .recovery-container {
            padding: 40px 0;
        }

        .recovery-body {
            padding: 30px 20px;
        }

        .recovery-header {
            padding: 30px 20px;
        }

        .recovery-links {
            padding: 20px;
        }

        .recovery-title {
            font-size: 1.5rem;
        }

        .recovery-image {
            padding: 20px;
            min-height: 200px;
        }

        .recovery-image h3 {
            font-size: 1.8rem;
        }

        .recovery-image p {
            font-size: 1rem;
        }

        .password-value {
            font-size: 1.5rem;
        }

        .btn-back, .btn-register {
            display: block;
            margin: 10px 0;
        }
    }
</style>

<div class="recovery-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="recovery-card">
                    <div class="row g-0">
                        <!-- Left Side - Image -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="recovery-image">
                                <div class="recovery-image-content">
                                    <h3>Şifre Kurtarma</h3>
                                    <p>Endişelenmeyin! Şifrenizi kolayca kurtarabilirsiniz. Güvenlik önlemlerimiz sayesinde hesabınız korunmaktadır.</p>
                                    
                                    <ul class="security-tips">
                                        <li><i class="fas fa-shield-alt"></i> Güvenli doğrulama</li>
                                        <li><i class="fas fa-user-check"></i> Kimlik teyidi</li>
                                        <li><i class="fas fa-lock"></i> Şifreli bağlantı</li>
                                        <li><i class="fas fa-clock"></i> Hızlı işlem</li>
                                    </ul>
                                    
                                    <div class="mt-4">
                                        <i class="fas fa-key fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Recovery Form -->
                        <div class="col-lg-6">
                            <div class="recovery-header">
                                <div class="recovery-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <h2 class="recovery-title">Şifremi Unuttum</h2>
                                <p class="recovery-subtitle">Hesap bilgilerinizle şifrenizi kurtarın</p>
                            </div>
                            
                            <div class="recovery-body">
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
                                    
                                    <?php if ($found_password && $alert_type == 'success'): ?>
                                        <div class="password-display">
                                            <div class="password-label">
                                                <i class="fas fa-key me-2"></i>Şifreniz
                                            </div>
                                            <div class="password-value" id="passwordValue"><?php echo htmlspecialchars($found_password); ?></div>
                                            <button class="copy-button" onclick="copyPassword()">
                                                <i class="fas fa-copy me-1"></i>Kopyala
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if (!$found_password): ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="sifre_unuttum" id="recoveryForm">
                                    <div class="form-group">
                                        <label for="kadi" class="form-label">Kullanıcı Adı</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user input-icon"></i>
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
                                        <label for="tel_no" class="form-label">Telefon Numarası</label>
                                        <div class="position-relative">
                                            <i class="fas fa-phone input-icon"></i>
                                            <input type="tel" 
                                                   class="form-control" 
                                                   id="tel_no" 
                                                   name="tel_no" 
                                                   placeholder="Telefon numaranızı girin"
                                                   value="<?php echo isset($_POST['tel_no']) ? htmlspecialchars($_POST['tel_no']) : ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn-recovery" id="recoveryBtn">
                                        <span class="loading-spinner">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                        <i class="fas fa-search me-2"></i>
                                        Şifremi Bul
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                            
                            <div class="recovery-links">
                                <p>Şifrenizi hatırladınız mı?</p>
                                <a href="/login-page/" class="btn-back">
                                    <i class="fas fa-arrow-left me-2"></i>Giriş Yap
                                </a>
                                <a href="/register/" class="btn-register">
                                    <i class="fas fa-user-plus me-2"></i>Kayıt Ol
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
    const recoveryForm = document.getElementById('recoveryForm');
    const recoveryBtn = document.getElementById('recoveryBtn');
    const phoneInput = document.getElementById('tel_no');
    
    // Form submission with loading state
    if (recoveryForm) {
        recoveryForm.addEventListener('submit', function(e) {
            recoveryBtn.classList.add('loading');
            recoveryBtn.disabled = true;
            
            // Reset after 10 seconds if no response
            setTimeout(function() {
                recoveryBtn.classList.remove('loading');
                recoveryBtn.disabled = false;
            }, 10000);
        });
    }
    
    // Phone number formatting
    if (phoneInput) {
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
    }
    
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
            } else if (this.value.length > 0) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });
    });
    
    // Auto-hide alerts after 8 seconds
    const alerts = document.querySelectorAll('.custom-alert');
    alerts.forEach(alert => {
        if (!alert.classList.contains('alert-success')) {
            setTimeout(function() {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 8000);
        }
    });
});

// Copy password function
function copyPassword() {
    const passwordValue = document.getElementById('passwordValue');
    const copyButton = document.querySelector('.copy-button');
    
    if (passwordValue) {
        // Create a temporary textarea to copy text
        const textarea = document.createElement('textarea');
        textarea.value = passwordValue.textContent;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        
        // Update button text temporarily
        const originalHTML = copyButton.innerHTML;
        copyButton.innerHTML = '<i class="fas fa-check me-1"></i>Kopyalandı!';
        copyButton.style.background = '#10b981';
        
        setTimeout(function() {
            copyButton.innerHTML = originalHTML;
            copyButton.style.background = '#10b981';
        }, 2000);
        
        // Show success message
        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success mt-2';
        successMsg.innerHTML = '<i class="fas fa-check-circle me-2"></i>Şifre panoya kopyalandı!';
        passwordValue.parentElement.appendChild(successMsg);
        
        setTimeout(function() {
            successMsg.remove();
        }, 3000);
    }
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>