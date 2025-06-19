# 🏢 Emlak & Müteahhit Projesi

<div align="center">
  <img src="about-images/mehmet_dogan_svg-1.svg" alt="Emlak Projesi Banner" width="800">
  
  <p align="center">
    <b>Modern, İnteraktif ve Kullanıcı Dostu Emlak & Müteahhit Platformu</b>
  </p>
  
  <p align="center">
    <a href="#-özellikler"><strong>Özellikler</strong></a> •
    <a href="#-kurulum"><strong>Kurulum</strong></a> •
    <a href="#-admin-paneli-erişimi"><strong>Admin Paneli</strong></a> •
    <a href="#-kullanıcı-sayfası-işleyişi"><strong>Kullanıcı Arayüzü</strong></a> •
    <a href="#-e-posta-gönderme-sistemi"><strong>E-posta Sistemi</strong></a>
  </p>
</div>

## 📋 Proje Tanımı

Bu proje, emlak ve müteahhitlik sektörüne yönelik modern bir web platformu sunar. Kullanıcıların gayrimenkul arama, inceleme süreçlerini kolaylaştırmak ve müteahhitler ile potansiyel müşteriler arasında bir köprü oluşturmak için tasarlanmıştır.

### ✨ Demo & Proje Kodları Anlatımı

<div align="center">
  <a href="https://youtu.be/demo-video">
    <img src="https://img.freepik.com/free-photo/real-estate-business_53876-47038.jpg" alt="Emlak Müteahhit Demo Video" width="600">
  </a>
  <p><a href="https://youtu.be/demo-video">🎬 Demo ve Tanıtım videosunu izlemek için tıklayın</a></p>
  <p><a href="https://youtu.be/code-explanation">🎬 Proje Kodlarının detaylı anlatıldığı videoya ulaşmak için tıklayın</a></p>
</div>

## 🚀 Özellikler

<div align="center">
  <table>
    <tr>
      <td align="center" width="33%">
        <img src="https://img.icons8.com/color/48/000000/home.png" width="48px"/><br/>
        <b>Detaylı Emlak İlanları</b><br/>
        <small>Fotoğraf ve açıklamalarla tam bilgi</small>
      </td>
      <td align="center" width="33%">
        <img src="https://img.icons8.com/color/48/000000/building.png" width="48px"/><br/>
        <b>Müteahhit Projeleri</b><br/>
        <small>Proje detayları ve görsel galeri</small>
      </td>
      <td align="center" width="33%">
        <img src="https://img.icons8.com/color/48/000000/chat.png" width="48px"/><br/>
        <b>Mesajlaşma Sistemi</b><br/>
        <small>Kullanıcılar arası iletişim</small>
      </td>
    </tr>
    <tr>
      <td align="center" width="33%">
        <img src="https://img.icons8.com/color/48/000000/user-group-man-woman.png" width="48px"/><br/>
        <b>Üyelik Sistemi</b><br/>
        <small>Kayıt, giriş ve profil yönetimi</small>
      </td>
      <td align="center" width="33%">
        <img src="https://img.icons8.com/color/48/000000/dashboard.png" width="48px"/><br/>
        <b>Admin Paneli</b><br/>
        <small>Kapsamlı yönetim arayüzü</small>
      </td>
      <td align="center" width="33%">
        <img src="https://w7.pngwing.com/pngs/333/868/png-transparent-mail-computer-icons-email-graphy-e-mail-miscellaneous-angle-rectangle-thumbnail.png" width="48px"/><br/>
        <b>E-posta Gönderimi</b><br/>
        <small>Toplu mail sistemi</small>
      </td>
    </tr>
  </table>
</div>
# 🏠 Emlak Müteahhitlik Projesi Kurulum Rehberi

## 📋 Sistem Gereksinimleri

- **PHP**: 7.0 veya üzeri
- **MySQL**: 5.6 veya üzeri
- **Web Sunucusu**: Apache veya Nginx
- **Yerel Geliştirme Ortamı**: XAMPP, Laragon, WAMP vb.

## 🚀 Kurulum Adımları

### 1. Projeyi İndirin

```bash
git clone https://github.com/mehmetdogandev/real-estate-contracting-project.git
cd real-estate-contracting-project
```

### 2. Dosyaları Web Sunucusuna Taşıyın

Proje klasörü içindeki **tüm dosya ve klasörleri** kullandığınız yerel sunucunun web dizinine taşıyın:

#### Laragon için:
```bash
# Hedef dizin
C:\laragon\www\
```

#### XAMPP için:
```bash
# Hedef dizin
C:\xampp\htdocs\
```

#### WAMP için:
```bash
# Hedef dizin
C:\wamp64\www\
```

> ⚠️ **Önemli Not**: Projenin düzgün çalışması için `real-estate-contracting-project` klasörü içindeki tüm dosyaların doğrudan web sunucusunun kök dizinine yerleştirilmesi gerekir.

### 3. Veritabanı Kurulumu

#### MySQL Workbench Kullanıyorsanız:
1. MySQL Workbench'i açın
2. Yeni bir bağlantı oluşturun
3. Aşağıdaki SQL dosyasını içe aktarın:
   ```sql
   SOURCE /path/to/www/db/workbanch-emlak.sql;
   ```

#### phpMyAdmin Kullanıyorsanız:
1. phpMyAdmin'e giriş yapın
2. Yeni veritabanı oluşturun:
   - **Veritabanı Adı**: `emlak`
   - **Karakter Seti**: `utf8mb4_0900_ai_ci`
3. Oluşturulan veritabanını seçin
4. "İçe Aktar" sekmesine gidin
5. `www/db/phpmyadmin-emlak.sql` dosyasını seçin ve içe aktarın

#### Komut Satırı ile Kurulum:
```bash
# Veritabanını oluşturun
mysql -u root -p -e "CREATE DATABASE emlak CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;"

# SQL dosyasını içe aktarın
mysql -u root -p emlak < www/db/workbanch-emlak.sql
```

## ⚙️ Yapılandırma

### Veritabanı Bağlantı Ayarları

`vtabani.php` dosyasını düzenleyerek veritabanı bağlantı bilgilerinizi güncelleyin:

```php
<?php
// Veritabanı bağlantı ayarları
define("DBHOST", "localhost");    // Veritabanı sunucu adresi
define("DBUSER", "root");         // Veritabanı kullanıcı adı
define("DBPASS", "");             // Veritabanı şifresi (genellikle boş)
define("DBNAME", "emlak");        // Veritabanı adı

// Bağlantı test kodu (isteğe bağlı)
try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME.";charset=utf8mb4", DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Veritabanı bağlantısı başarılı!";
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
```
### E-posta Ayarları

E-posta gönderim ayarları artık merkezi olarak veritabanında saklanmaktadır.

1. Yönetici panelinde oturum açın: `http://localhost/admin/profil/ayarlar.php`
2. Sol menüden **"Ayarlar"** sekmesine gidin.
3. Ardından **"Mail Ayarları"** sekmesini seçin.
4. SMTP sunucu adresi, port, e-posta adresi ve şifrenizi girerek ayarları yapılandırın.
5. **"Kaydet"** butonuna basarak değişiklikleri kaydedin.

**Not:** Varsayılan olarak Gmail SMTP ayarları kullanılmaktadır. Gmail hesabı ile gönderim yapacaksanız:
* "Daha az güvenli uygulama erişimi" özelliğini aktif edin **veya**
* Hesabınız için özel bir **Uygulama Şifresi** oluşturun.

5. **Web Sunucusuna Yükleme**

- Projeyi web sunucusunda erişilebilir bir dizine kopyalayın
- Tarayıcıdan `http://localhost/` adresine erişin

## 📂 Proje Yapısı

```
www/ (veya htdocs/)
│
├── admin/                        # Yönetici paneli arayüz dosyaları
│   ├── index.php                 # Admin giriş sayfası
│   ├── panel.php                 # Admin ana paneli
│   └── ...                       # Diğer admin işlemleri
│
├── config/                       # Yapılandırma ayarları
│   └── vtabani.php               # Veritabanı bağlantı ayarları
│
├── content/                      # Statik içerikler ve medya dosyaları
│   ├── css/                      # CSS dosyaları
│   │   └── style.css             # Ana stil dosyası
│   ├── js/                       # JavaScript dosyaları
│   ├── images/                   # Kullanıcıya gösterilecek görseller
│   ├── img/                      # Sistem içi kullanılan ikon, arkaplan vs.
│   ├── fonts/                    # Font dosyaları
│   └── ajax.php 
│
├── k_mesaj/                      #Kullanıcı mesajlarını listeleyen, yanıtlama ve yeni mesaj oluşturma işlemlerini sağlayan dosyalar
│
├── db/                           # Veritabanı dosyaları
│   └── emlak.sql                 # Veritabanı şeması
│
├── index.php                     # Ana sayfa
└── README.md                     # Proje dokümantasyonu
```

## 👨‍💼 Admin Paneli Erişimi

Admin paneline giriş yapabilmek için:

- URL: `http://localhost/admin/`
- Kullanıcı Adı: `test`
- Şifre: `test`

<div align="center">
  <img src="about-images/image001.jpg" alt="Admin Paneli Giriş Ekranı" width="600">
  <p><small>Admin Paneli Giriş Ekranı</small></p>
</div>

### Admin Paneli Özellikleri

<div align="center">
  <table>
    <tr>
      <td align="center" width="25%">
        <img src="https://img.icons8.com/color/48/000000/businessman.png" width="32px"/><br/>
        <b>Kullanıcı Yönetimi</b><br/>
        <small>Onaylama ve düzenleme</small>
      </td>
      <td align="center" width="25%">
        <img src="https://img.icons8.com/color/48/000000/post-office.png" width="32px"/><br/>
        <b>İlan Yönetimi</b><br/>
        <small>Onaylama ve düzenleme</small>
      </td>
      <td align="center" width="25%">
        <img src="https://img.icons8.com/color/48/000000/category.png" width="32px"/><br/>
        <b>Kategori Yönetimi</b><br/>
        <small>Ekleme ve düzenleme</small>
      </td>
      <td align="center" width="25%">
        <img src="https://img.icons8.com/color/48/000000/send-mass-email.png" width="32px"/><br/>
        <b>E-posta Gönderimi</b><br/>
        <small>Toplu mail yönetimi</small>
      </td>
    </tr>
  </table>
  
  <img src="about-images/admin-panel.png" alt="Admin Panel" width="700">
  <p><small>Admin Kontrol Paneli</small></p>
</div>

## 📱 Kullanıcı Sayfası İşleyişi

<div align="center">
  <table>
    <tr>
      <td><b>Kullanıcı Kayıt Ekranı</b></td>
      <td><b>İlan Verme Sayfası</b></td>
    </tr>
    <tr>
      <td><img src="about-images/sign-up.gif" width="400"/></td>
      <td><img src="about-images/birlestirilmis2.gif" width="400"/>
      </td>
    </tr>
    <tr>
      <td><b>Mesajlaşma Sistemi</b></td>
      <td><b>İlanları Görüntüleme</b></td>
    </tr>
    <tr>
      <td><img src="about-images/mesajlasma2.gif" width="400"/></td>
      <td><img src="about-images/ilanlari-goruntuleme.gif" width="400"/></td>
    </tr>
  </table>
</div>

### Üyelik İşlemleri

Kullanıcılar aşağıdaki işlemleri yapabilir:

1. Sisteme kayıt olma
2. Kullanıcı girişi yapma
3. Profil bilgilerini güncelleme
4. Şifre değiştirme

### İlan İşlemleri

Kullanıcılar aşağıdaki işlemleri yapabilir:

1. Yeni ilan ekleme (fotoğraflar ve detaylarla)
2. İlanlarını görüntüleme
3. İlanlarını düzenleme
4. İlanlarını silme

## 📊 Veritabanı Yapısı

Proje aşağıdaki ana veritabanı tablolarını kullanmaktadır:

<div align="center">
  <table>
    <tr>
      <th>Tablo Adı</th>
      <th>Açıklama</th>
    </tr>
    <tr>
      <td><code>kullanicilar</code></td>
      <td>Kullanıcı bilgileri ve giriş verileri</td>
    </tr>
    <tr>
      <td><code>kategoriler</code></td>
      <td>İlan kategorileri (Kiralık, Satılık, vb.)</td>
    </tr>
    <tr>
      <td><code>urunler</code></td>
      <td>İlan bilgileri ve detayları</td>
    </tr>
    <tr>
      <td><code>projeler</code></td>
      <td>Müteahhit projelerinin bilgileri</td>
    </tr>
    <tr>
      <td><code>kullanicilar_mesaj</code></td>
      <td>Kullanıcılar arası mesajlaşma verileri</td>
    </tr>
    <tr>
      <td><code>kisiler</code></td>
      <td>E-posta gönderilecek kişilerin listesi</td>
    </tr>
    <tr>
      <td><code>admin_mesajlar</code></td>
      <td>Yönetici mesajları</td>
    </tr>
    <tr>
      <td><code>arsabilgi</code></td>
      <td>Arsa bilgileri</td>
    </tr>
    <tr>
      <td><code>evarsa</code></td>
      <td>Emlak verisi varsa</td>
    </tr>
    <tr>
      <td><code>evbilgi</code></td>
      <td>Ev bilgileri</td>
    </tr>
    <tr>
      <td><code>gonderilenler</code></td>
      <td>Gönderilen veriler</td>
    </tr>
    <tr>
      <td><code>il</code></td>
      <td>İl İsimleri Listesi</td>
    </tr>
    <tr>
      <td><code>ilce</code></td>
      <td>İlçe İsimleri Listesi</td>
    </tr>
    <tr>
      <td><code>slider</code> / <code>logo</code></td>
      <td>Site tasarım öğeleri</td>
    </tr>
  </table>
</div>


## 📨 E-posta Gönderme Sistemi

Admin paneli üzerinden sisteme kayıtlı kullanıcılara toplu e-posta gönderilebilir. Bu özellik, duyurular, kampanyalar ve bilgilendirmeler için idealdir.

<div align="center">
  <img src="about-images/mail.gif" alt="E-posta Sistemi" width="600">
  <p><small>E-posta Gönderme Sistemi</small></p>
</div>

### Kullanım Adımları

#### Yöntem 1: Doğrudan Mail İşlemleri Menüsünden  

1. Yönetici paneline giriş yapın.
2. "Mail İşlemleri" menüsüne tıklayın
3. Alıcıları seçin ve e-posta içeriğini oluşturun
4. "Gönder" butonuna tıklayın

#### Yöntem 2: İlan veya Proje Üzerinden

1. Yönetici paneline giriş yapın.
2. E-posta ile göndermek istediğiniz ilan veya projeyi bulun.
3. İlgili içeriğin üzerindeki "Detay" butonuna tıklayın.
4. Açılan sayfanın alt kısmında bulunan "İlanı Gönder" butonuna tıklayın.
5. Yeni açılan sayfada alıcıları seçin.
6. "Gönder" butonuna tıklayarak e-posta gönderimini gerçekleştirin.

> ⚠️ **Not:** E-posta gönderimi için SMTP ayarlarınızı doğru yapılandırdığınızdan emin olun.

## ⚙️ Özelleştirme Seçenekleri

### Logo Değiştirme

Admin paneli üzerinden site logosunu değiştirebilirsiniz:

1. Admin paneline giriş yapın
2. "Logo İşlemleri" menüsüne tıklayın
3. Mevcut logoyu değiştirin veya yeni logo ekleyin

### Slider Ayarları

Ana sayfa slider görsellerini ve içeriklerini yönetebilirsiniz:

1. Admin paneline giriş yapın
2. "Slider İşlemleri" menüsüne tıklayın
3. Mevcut sliderleri düzenleyin veya yeni ekleyin

## 📈 Gelecek Özellikler

- [ ] Gelişmiş arama filtreleri
- [ ] Çoklu dil desteği
- [ ] Mobil uygulama versiyonu
- [ ] Ödeme sistemi entegrasyonu
- [ ] API desteği
- [ ] İstatistik raporları

## 👥 Geliştiriciler

<div align="center">
  <table>
    <tr>
      <td align="center">
        <a href="https://github.com/mehmetdogandev">
          <img src="https://github.com/mehmetdogandev.png" width="100px;" alt="Mehmet Doğan"/>
          <br />
          <b>Mehmet DOĞAN</b>
        </a>
        <br />
        <small>Proje Geliştiricisi</small>
      </td>
    </tr>
  </table>
</div>

## 📄 Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.

---

<div align="center">
  <p>Developed with ❤️ by <a href="https://github.com/mehmetdogandev">Mehmet DOĞAN</a></p>
  <a href="https://github.com/mehmetdogandev/real-estate-contracting-project/stargazers">
    <img src="https://img.shields.io/github/stars/mehmetdogandev/real-estate-contracting-project?style=flat-square" alt="Stars"/>
  </a>
  <a href="https://github.com/mehmetdogandev/real-estate-contracting-project/network/members">
    <img src="https://img.shields.io/github/forks/mehmetdogandev/real-estate-contracting-project?style=flat-square" alt="Forks"/>
  </a>
  <a href="https://github.com/mehmetdogandev/real-estate-contracting-project/issues">
    <img src="https://img.shields.io/github/issues/mehmetdogandev/real-estate-contracting-project?style=flat-square" alt="Issues"/>
  </a>
</div>
