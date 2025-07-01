<!-- Footer -->
    <footer class="main-footer">
        <!-- Footer Content -->
        <div class="footer-content">
            <div class="container">
                <div class="row g-4">
                    <!-- Company Info -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <h4 class="footer-title">
                                <i class="fas fa-building me-2"></i>
                                Emlak & Müteahhit
                            </h4>
                            <p class="footer-description">
                                Premium gayrimenkul çözümleri ile hayalinizdeki evi bulmak artık çok kolay. 
                                Uzman ekibimizle güvenilir hizmet sunuyoruz.
                            </p>
                            <div class="company-stats">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-number">500+</div>
                                            <div class="stat-label">Satılan Ev</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-number">50+</div>
                                            <div class="stat-label">Proje</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-number">1000+</div>
                                            <div class="stat-label">Müşteri</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opportunity Showcase -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <h4 class="footer-title">
                                <i class="fas fa-star me-2"></i>
                                Fırsat Vitrini
                            </h4>
                            <div class="opportunity-list">
                                <?php
                                // veritabanı yapılandırma dosyasını dahil et
                                include 'config/vtabani.php';
                                
                                try {
                                    // kayıt listeleme sorgusu
                                    $sorgu = 'SELECT id, urunadi, fiyat, resim FROM urunler WHERE onay="1" ORDER BY fiyat LIMIT 0,3';
                                    $stmt = $con->prepare($sorgu);
                                    $stmt->execute();
                                    $veri = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach ($veri as $kayit) { ?>
                                        <div class="opportunity-item">
                                            <div class="opportunity-image">
                                                <?php 
                                                if($kayit['resim']) {
                                                    echo "<img src='/content/images/" . $kayit['resim'] . "' alt='" . htmlspecialchars($kayit['urunadi']) . "' />";
                                                } else {
                                                    echo "<img src='/content/images/gorsel-yok.jpg' alt='Görsel Yok' />";
                                                }
                                                ?>
                                            </div>
                                            <div class="opportunity-content">
                                                <a href="urundetay.php?id=<?php echo $kayit['id'] ?>" class="opportunity-title">
                                                    <?php echo htmlspecialchars(substr($kayit['urunadi'], 0, 50)) . (strlen($kayit['urunadi']) > 50 ? '...' : ''); ?>
                                                </a>
                                                <div class="opportunity-price">
                                                    <i class="fas fa-tag me-1"></i>
                                                    <?php echo number_format($kayit['fiyat'], 0, ',', '.'); ?> ₺
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } catch(Exception $e) {
                                    echo '<p class="text-muted">Fırsat ilanları yüklenemiyor.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-12">
                        <div class="footer-section">
                            <h4 class="footer-title">
                                <i class="fas fa-phone me-2"></i>
                                İletişim
                            </h4>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h6>7/24 Müşteri Hizmetleri</h6>
                                        <p>Haftanın her günü hizmetinizdeyiz</p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h6>Telefon</h6>
                                        <a href="tel:+908508500000">0850 850 00 00</a>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h6>E-posta</h6>
                                        <a href="mailto:yardim@muteahitemlak.com">yardim@muteahitemlak.com</a>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h6>Adres</h6>
                                        <p>Merkez Mah. Emlak Cad. No:123<br>Sivas/Türkiye</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="copyright">
                            <p>&copy; 2025 Emlak & Müteahhit. Tüm hakları saklıdır.</p>
                            <p class="small-text">
                                Bu proje <strong>Mehmet DOĞAN</strong> tarafından Aksaray Üniversitesi 
                                Yazılım Mühendisliği bölümü için geliştirilmiştir.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="footer-social">
                            <h6>Bizi Takip Edin</h6>
                            <div class="social-links">
                                <a href="#" class="social-link facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-link instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="social-link youtube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- CSS Styles -->
    <style>
        /* Footer Styles */
        .main-footer {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            color: #e2e8f0;
            margin-top: 50px;
        }

        .footer-content {
            padding: 60px 0 40px;
        }

        .footer-section {
            height: 100%;
        }

        .footer-title {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-radius: 2px;
        }

        .footer-description {
            color: #cbd5e1;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* Company Stats */
        .company-stats {
            background: rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Opportunity List */
        .opportunity-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .opportunity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .opportunity-item:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateX(5px);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .opportunity-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .opportunity-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .opportunity-item:hover .opportunity-image img {
            transform: scale(1.1);
        }

        .opportunity-content {
            flex-grow: 1;
        }

        .opportunity-title {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }

        .opportunity-title:hover {
            color: #3b82f6;
        }

        .opportunity-price {
            color: #fbbf24;
            font-weight: 600;
            font-size: 13px;
        }

        /* Contact Info */
        .contact-info {
            space-y: 20px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .contact-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .contact-icon i {
            color: white;
            font-size: 16px;
        }

        .contact-content h6 {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .contact-content p,
        .contact-content a {
            color: #cbd5e1;
            font-size: 14px;
            margin: 0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-content a:hover {
            color: #3b82f6;
        }

        /* Footer Bottom */
        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 30px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .copyright p {
            margin: 0;
            color: #cbd5e1;
        }

        .small-text {
            font-size: 12px !important;
            color: #94a3b8 !important;
            margin-top: 8px !important;
        }

        .footer-social h6 {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: right;
        }

        .social-links {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .social-link:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .social-link.facebook:hover {
            background: #1877f2;
            border-color: #1877f2;
        }

        .social-link.twitter:hover {
            background: #1da1f2;
            border-color: #1da1f2;
        }

        .social-link.instagram:hover {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
            border-color: #e6683c;
        }

        .social-link.linkedin:hover {
            background: #0a66c2;
            border-color: #0a66c2;
        }

        .social-link.youtube:hover {
            background: #ff0000;
            border-color: #ff0000;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            border: none;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .back-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .back-to-top.show {
            display: flex;
        }

        /* Scrollbar Styling */
        .opportunity-list::-webkit-scrollbar {
            width: 6px;
        }

        .opportunity-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .opportunity-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-radius: 3px;
        }

        .opportunity-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-content {
                padding: 40px 0 30px;
            }

            .footer-social h6 {
                text-align: left;
            }

            .social-links {
                justify-content: flex-start;
            }

            .company-stats {
                margin-top: 20px;
            }

            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }

        @media (max-width: 576px) {
            .contact-item {
                flex-direction: column;
                text-align: center;
            }

            .contact-icon {
                margin: 0 auto 15px;
            }

            .opportunity-item {
                flex-direction: column;
                text-align: center;
            }

            .opportunity-image {
                margin: 0 auto 15px;
            }
        }
    </style>

    <!-- JavaScript -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Back to Top Button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#backToTop').addClass('show');
                } else {
                    $('#backToTop').removeClass('show');
                }
            });

            $('#backToTop').click(function() {
                $('html, body').animate({scrollTop: 0}, 600);
                return false;
            });

            // Favori ekleme işlemi
            $('.favori-ekle').on('click', function() {
                var id = $(this).attr('id');
                var sayi = parseInt(document.getElementById('urun-sayisi').innerHTML);
                var adet = document.getElementById('urun_' + id) ? document.getElementById('urun_' + id).value : 1;

                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: 'favorile.php',
                    data: {
                        id: id,
                        adet: adet
                    },
                    success: function(sonuc) {
                        if (sonuc == "true") {
                            swal("Ürün favorilendi!", {
                                icon: "success",
                                buttons: false,
                                timer: 1500,
                            });
                            $("#urun-sayisi").text(sayi + 1);
                        } else {
                            swal("Ürün daha önce favorilenmiş!", {
                                icon: "warning",
                                buttons: false,
                                timer: 1500,
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal("Bir hata oluştu!", {
                            icon: "error",
                            buttons: false,
                            timer: 1500,
                        });
                    }
                });
                return false;
            });

            // Favori güncelleme işlemi
            $('.urun-guncelle').on('click', function() {
                var id = $(this).attr('id');
                var adet = document.getElementById('urun_' + id).value;

                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: 'favori_guncelle.php',
                    data: {
                        id: id,
                        adet: adet
                    },
                    success: function() {
                        swal("Ürün adedi güncellendi!", {
                            icon: "success",
                            buttons: false,
                            timer: 1500,
                        }).then(function() {
                            window.location.href = "/my-favorites/";
                        });
                    },
                    error: function() {
                        swal("Bir hata oluştu!", {
                            icon: "error",
                            buttons: false,
                            timer: 1500,
                        });
                    }
                });
                return false;
            });

            // Favori silme işlemi
            $('.urun-sil').on('click', function() {
                var id = $(this).attr('id');

                swal({
                    title: "Emin misiniz?",
                    text: "Bu ürün favorilerden kaldırılacak!",
                    icon: "warning",
                    buttons: ["İptal", "Evet, Sil"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            cache: false,
                            type: 'POST',
                            url: 'favori_guncelle.php',
                            data: {
                                id: id
                            },
                            success: function() {
                                swal("Ürün favorilerden çıkarıldı!", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1500,
                                }).then(function() {
                                    window.location.href = "/my-favorites/";
                                });
                            },
                            error: function() {
                                swal("Bir hata oluştu!", {
                                    icon: "error",
                                    buttons: false,
                                    timer: 1500,
                                });
                            }
                        });
                    }
                });
                return false;
            });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });

            // Animate elements on scroll
            function animateOnScroll() {
                $('.footer-section').each(function() {
                    var elementTop = $(this).offset().top;
                    var elementBottom = elementTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();

                    if (elementBottom > viewportTop && elementTop < viewportBottom) {
                        $(this).addClass('animate-in');
                    }
                });
            }

            $(window).on('scroll', animateOnScroll);
            animateOnScroll(); // Initial check
        });
    </script>

</body>
</html>