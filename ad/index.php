<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/header.php";

// Oturum kontrolü
if (!isset($_SESSION["kullanici_loginkey"]) || $_SESSION["kullanici_loginkey"] == "") {
    // oturum açılmamışsa login.php sayfasına git
    header("Location: /login-page/?islem=girisYokilanver");
    exit();
}

// Kullanıcı bilgilerini al
try {
    include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';
    $sorgu = "SELECT adsoyad, onay FROM kullanicilar WHERE eposta=:eposta";
    $stmt = $con->prepare($sorgu);
    $stmt->bindParam(":eposta", $_SESSION["kullanici_loginkey"]);
    $stmt->execute();
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($kullanici && $kullanici['onay'] == 0) {
        $onay_bekliyor = true;
    } else {
        $onay_bekliyor = false;
    }
} catch(PDOException $exception) {
    $onay_bekliyor = false;
}
?>

<!-- CSS Styles -->
<style>
    /* Ad Type Selection Page Styles */
    .ad-selection-container {
        min-height: 80vh;
        padding: 80px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .page-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 2px;
    }

    .page-subtitle {
        font-size: 1.3rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* User Welcome Section */
    .user-welcome {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 40px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #10b981;
    }

    .user-welcome h5 {
        color: #1e293b;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .user-welcome p {
        color: #64748b;
        margin: 0;
    }

    /* Approval Warning */
    .approval-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1px solid #f59e0b;
        color: #92400e;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 40px;
        text-align: center;
        animation: pulse 2s infinite;
    }

    .approval-warning i {
        font-size: 2rem;
        margin-bottom: 15px;
        display: block;
    }

    /* Ad Type Cards */
    .ad-type-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        max-width: 800px;
        margin: 0 auto;
    }

    .ad-type-card {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 3px solid transparent;
    }

    .ad-type-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .ad-type-card:hover::before {
        opacity: 1;
    }

    .ad-type-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }

    .ad-type-card.house-ad {
        border-color: #ef4444;
    }

    .ad-type-card.house-ad:hover {
        border-color: #ef4444;
        box-shadow: 0 20px 60px rgba(239, 68, 68, 0.2);
    }

    .ad-type-card.land-ad {
        border-color: #06b6d4;
    }

    .ad-type-card.land-ad:hover {
        border-color: #06b6d4;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2);
    }

    /* Icon Styles */
    .ad-type-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 3rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .house-ad .ad-type-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .land-ad .ad-type-icon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .ad-type-card:hover .ad-type-icon {
        transform: scale(1.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Floating Animation */
    .ad-type-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        animation: float 3s ease-in-out infinite;
    }

    /* Text Styles */
    .ad-type-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #1e293b;
    }

    .ad-type-description {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .ad-type-features {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }

    .ad-type-features li {
        padding: 8px 0;
        color: #059669;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ad-type-features i {
        margin-right: 8px;
        color: #10b981;
    }

    /* Call to Action */
    .ad-type-cta {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s ease;
    }

    .house-ad:hover .ad-type-cta {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        color: #991b1b;
    }

    .land-ad:hover .ad-type-cta {
        background: linear-gradient(135deg, #ecfeff, #cffafe);
        color: #164e63;
    }

    /* Benefits Section */
    .benefits-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-top: 60px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    }

    .benefits-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 30px;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .benefit-item {
        text-align: center;
        padding: 20px;
    }

    .benefit-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 1.5rem;
    }

    .benefit-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .benefit-text {
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
    }

    /* Animations */
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(45deg);
        }
        50% {
            transform: translateY(-10px) rotate(45deg);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .ad-selection-container {
            padding: 40px 0;
        }

        .page-title {
            font-size: 2rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
        }

        .ad-type-container {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 0 15px;
        }

        .ad-type-card {
            padding: 30px 20px;
        }

        .ad-type-icon {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }

        .ad-type-title {
            font-size: 1.5rem;
        }

        .benefits-section {
            padding: 30px 20px;
            margin-top: 40px;
        }

        .benefits-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Disabled State */
    .ad-type-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }

    .ad-type-card.disabled:hover {
        transform: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="ad-selection-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header fade-in-up">
            <h1 class="page-title">Ücretsiz İlan Ver</h1>
            <p class="page-subtitle">Gayrimenkul ilanınızı hemen yayınlayın</p>
        </div>

        <!-- User Welcome -->
        <?php if (isset($kullanici['adsoyad'])): ?>
        <div class="user-welcome fade-in-up">
            <h5><i class="fas fa-user-circle me-2"></i>Hoş geldiniz, <?php echo htmlspecialchars($kullanici['adsoyad']); ?>!</h5>
            <p>İlan verme işlemine başlamak için aşağıdan ilan türünüzü seçin.</p>
        </div>
        <?php endif; ?>

        <!-- Approval Warning -->
        <?php if ($onay_bekliyor): ?>
        <div class="approval-warning fade-in-up">
            <i class="fas fa-hourglass-half"></i>
            <h5>Hesap Onayı Bekleniyor</h5>
            <p>Hesabınız henüz onaylanmamış. İlan verebilmek için hesap onayınızın tamamlanmasını beklemeniz gerekmektedir.</p>
        </div>
        <?php endif; ?>

        <!-- Ad Type Selection -->
        <div class="ad-type-container">
            <!-- House Ad -->
            <a href="/ad/house-ad/" class="ad-type-card house-ad fade-in-up <?php echo $onay_bekliyor ? 'disabled' : ''; ?>">
                <div class="ad-type-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="ad-type-title">Ev İlanı</h3>
                <p class="ad-type-description">Satılık veya kiralık ev, daire, villa ilanlarınızı buradan verebilirsiniz.</p>
                
                <ul class="ad-type-features">
                    <li><i class="fas fa-check"></i> Detaylı oda bilgileri</li>
                    <li><i class="fas fa-check"></i> Fotoğraf galerisi</li>
                    <li><i class="fas fa-check"></i> Konum haritası</li>
                    <li><i class="fas fa-check"></i> Özellik filtreleri</li>
                </ul>
                
                <div class="ad-type-cta">
                    <i class="fas fa-plus-circle me-2"></i>Ev İlanı Ver
                </div>
            </a>

            <!-- Land Ad -->
            <a href="/ad/land-ad/" class="ad-type-card land-ad fade-in-up <?php echo $onay_bekliyor ? 'disabled' : ''; ?>" style="animation-delay: 0.2s;">
                <div class="ad-type-icon">
                    <i class="fas fa-map"></i>
                </div>
                <h3 class="ad-type-title">Arsa İlanı</h3>
                <p class="ad-type-description">Satılık arsa, tarla, bahçe ilanlarınızı buradan kolayca verebilirsiniz.</p>
                
                <ul class="ad-type-features">
                    <li><i class="fas fa-check"></i> Metrekare bilgileri</li>
                    <li><i class="fas fa-check"></i> İmar durumu</li>
                    <li><i class="fas fa-check"></i> Konum detayları</li>
                    <li><i class="fas fa-check"></i> Tapu bilgileri</li>
                </ul>
                
                <div class="ad-type-cta">
                    <i class="fas fa-plus-circle me-2"></i>Arsa İlanı Ver
                </div>
            </a>
        </div>

        <!-- Benefits Section -->
        <div class="benefits-section fade-in-up" style="animation-delay: 0.4s;">
            <h2 class="benefits-title">Neden Bizimle İlan Vermelisiniz?</h2>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h6 class="benefit-title">Hızlı Yayın</h6>
                    <p class="benefit-text">İlanınız onay sonrası anında yayına alınır ve binlerce kişiye ulaşır.</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h6 class="benefit-title">Kolay Bulunabilirlik</h6>
                    <p class="benefit-text">Gelişmiş arama filtreleri ile doğru alıcılar ilanınızı kolayca bulur.</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h6 class="benefit-title">Güvenli Platform</h6>
                    <p class="benefit-text">Tüm ilanlar moderasyon sürecinden geçer ve güvenli alışveriş sağlanır.</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h6 class="benefit-title">İstatistik Takibi</h6>
                    <p class="benefit-text">İlanınızın görüntülenme sayısını ve ilgi durumunu takip edebilirsiniz.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);

    // Observe all elements with fade-in-up class
    document.querySelectorAll('.fade-in-up').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });

    // Add click tracking for analytics (optional)
    document.querySelectorAll('.ad-type-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (this.classList.contains('disabled')) {
                e.preventDefault();
                
                // Show info message for disabled state
                const message = document.createElement('div');
                message.className = 'alert alert-warning position-fixed';
                message.style.cssText = 'top: 100px; right: 20px; z-index: 9999; animation: slideInRight 0.3s ease;';
                message.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Hesap onayınız bekleniyor. İlan verebilmek için onay sürecinin tamamlanmasını bekleyin.
                `;
                
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => message.remove(), 300);
                }, 4000);
                
                return false;
            }
            
            // Track which ad type was selected
            const adType = this.classList.contains('house-ad') ? 'house' : 'land';
            console.log('Ad type selected:', adType);
            
            // Add loading state
            this.style.opacity = '0.7';
            this.style.pointerEvents = 'none';
            
            // Optional: You can add a loading spinner here
            const loadingIcon = document.createElement('div');
            loadingIcon.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Yönlendiriliyor...';
            loadingIcon.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.9); padding: 10px 20px; border-radius: 10px; font-weight: 600;';
            this.appendChild(loadingIcon);
        });
    });

    // Add hover effects for better UX
    document.querySelectorAll('.ad-type-card:not(.disabled)').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Add CSS for slide animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/footer.php"; ?>