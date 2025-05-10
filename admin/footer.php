<footer class="navbar navbar-default navbar-fixed-bottom">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 footer-copyright">
                <p>&copy; 2024 Emlak & Müteahit Projesi / Admin Panel</p>
            </div>
            <div class="col-md-4 footer-social text-center">
                <ul class="list-inline social-icons">
                    <li><a href="#" target="_blank"><i class="fab fa-youtube"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                </ul>
            </div>
            <div class="col-md-4 footer-support text-right">
                <p><a href="mailto:destek@emlakmuteahit.com"><i class="fas fa-envelope"></i> destek@emlakmuteahit.com</a></p>
            </div>
        </div>
    </div>
</footer>
<!-- Footer sonu -->




<!-- Footer stil ayarları -->
<style>
footer.navbar-fixed-bottom {
    background-color: #2c3e50;
    color: #ecf0f1;
    border: none;
    margin-bottom: 0;
    min-height: 10px; /* Tek satır yüksekliği */
    padding: 10px 0;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

footer p {
    font-size: 13px;
    margin: 5px 0;
    line-height: 30px; /* Dikey ortalama için */
}

footer a {
    color: #ecf0f1;
    transition: color 0.3s;
    text-decoration: none;
}

footer a:hover {
    color: #3498db;
    text-decoration: none;
}

.footer-social .list-inline {
    margin: 0;
    padding: 0;
    line-height: 30px; /* Dikey ortalama için */
}

.footer-social .list-inline li {
    display: inline-block;
    padding: 0;
    margin: 0 10px;
}

.social-icons a {
    display: inline-block;
    font-size: 20px;
    width: 20px;
    height: 20px;
    line-height: 20px;
    text-align: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.social-icons a:hover {
    transform: translateY(-3px);
}

.social-icons .fa-youtube:hover { color: #FF0000; }
.social-icons .fa-facebook:hover { color: #3b5998; }
.social-icons .fa-instagram:hover { color: #e1306c; }
.social-icons .fa-linkedin:hover { color: #0077b5; }
.social-icons .fa-twitter:hover { color: #1da1f2; }

/* Sabit footer ile sayfa içeriğinin çakışmaması için body padding */
body.admin {
    padding-bottom: 50px; /* Footer yüksekliği kadar padding */
}

/* Mobil görünüm için footer düzenlemeleri */
@media (max-width: 767px) {
    .footer-copyright, .footer-support {
        text-align: center;
    }
    
    .footer-copyright {
        margin-bottom: 5px;
    }
    
    .footer-support {
        margin-top: 5px;
    }
    
    footer.navbar-fixed-bottom {
        position: fixed; /* Mobilde bile sabit kalması için */
    }
    
    /* Mobilde tek satır görünümü korumak için */
    .row {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
    }
    
    .footer-social .list-inline li {
        margin: 0 5px;
    }
}
</style>
</body>
</html>