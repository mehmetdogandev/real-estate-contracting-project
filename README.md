# 🏢 Emlak & Müteahhit Projesi

<div align="center">
  <img src="about-images/banner.png" alt="Emlak Projesi Banner" width="800">
  
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

## 🛠️ Kurulum

### Gereksinimler

- PHP 7.0+
- MySQL 5.6+
- Apache/Nginx Web Sunucusu

### Adım Adım Kurulum

1. **Repository'i Klonlayın**

```bash
git clone https://github.com/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI.git
```

2. **Klasör Adını Değiştirin**

```bash
mv EMLAKCI-ILE-MUTEAHHIT-PROJESI proje
```

> ⚠️ **ÖNEMLİ:** Projenin düzgün çalışması için klasör adının kesinlikle "proje" olması gerekmektedir, çünkü tüm kod yapısı buna göre tasarlanmıştır.

3. **Veritabanı Kurulumu**

- MySQL üzerinde `emlak` adında yeni bir veritabanı oluşturun
- SQL dosyasını içe aktarın:

```bash
mysql -u kullaniciadi -p emlak < proje/db/emlak.sql
```

4. **Yapılandırma Ayarları**

- `vtabani.php` dosyasını açın ve veritabanı bağlantı ayarlarını güncelleyin:

```php
define("DBHOST", "localhost");
define("DBUSER", "root"); // Kendi kullanıcı adınızla değiştirin
define("DBPASS", ""); // Kendi şifrenizle değiştirin
define("DBNAME", "emlak");
```

- E-posta gönderme işlevi için kendi e-posta adresinizi ve şifrenizi güncelleyin:

```php
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_mail_adres@gmail.com'; // Kendi e-posta adresinizle değiştirin
$mail->Password = 'mail_sifreniz'; // Kendi şifrenizle değiştirin
```

5. **Web Sunucusuna Yükleme**

- Projeyi web sunucusunda erişilebilir bir dizine kopyalayın
- Tarayıcıdan `http://localhost/proje/` adresine erişin

## 📂 Proje Yapısı

```
proje/
│
├── admin/                    # Admin paneli dosyaları
│   ├── index.php             # Admin giriş sayfası
│   ├── panel.php             # Admin kontrol paneli
│   └── ...                   # Diğer admin sayfaları
│
├── css/                      # Stil dosyaları
│   └── style.css             # Ana stil dosyası
│
├── js/                       # JavaScript dosyaları
│
├── images/                   # Görsel dosyaları
│
├── db/                       # Veritabanı dosyaları
│   └── emlak.sql             # Veritabanı şeması
│
├── vtabani.php               # Veritabanı bağlantı ayarları
├── index.php                 # Ana sayfa
└── README.md                 # Proje dokümantasyonu
```

## 👨‍💼 Admin Paneli Erişimi

Admin paneline giriş yapabilmek için:

- URL: `http://localhost/proje/admin/`
- Kullanıcı Adı: `test`
- Şifre: `test`

<div align="center">
  <img src="" alt="Admin Paneli Giriş Ekranı" width="600">
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
  
  <img src="" alt="Admin Panel" width="700">
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
      <td><img src="" width="400"/></td>
      <td><img src="" width="400"/></td>
    </tr>
    <tr>
      <td><b>Mesajlaşma Sistemi</b></td>
      <td><b>İlanları Görüntüleme</b></td>
    </tr>
    <tr>
      <td><img src="" width="400"/></td>
      <td><img src="" width="400"/></td>
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
      <td><code>slider</code> / <code>logo</code></td>
      <td>Site tasarım öğeleri</td>
    </tr>
  </table>
</div>

## 📨 E-posta Gönderme Sistemi

Admin paneli üzerinden sisteme kayıtlı kullanıcılara toplu e-posta gönderilebilir. Bu özellik, duyurular, kampanyalar ve bilgilendirmeler için idealdir.

<div align="center">
  <img src="" alt="E-posta Sistemi" width="600">
  <p><small>E-posta Gönderme Sistemi</small></p>
</div>

### Kullanım Adımları

1. Admin paneline giriş yapın
2. "Mail İşlemleri" menüsüne tıklayın
3. Alıcıları seçin ve e-posta içeriğini oluşturun
4. "Gönder" butonuna tıklayın

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
  <a href="https://github.com/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI/stargazers">
    <img src="https://img.shields.io/github/stars/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI?style=flat-square" alt="Stars"/>
  </a>
  <a href="https://github.com/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI/network/members">
    <img src="https://img.shields.io/github/forks/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI?style=flat-square" alt="Forks"/>
  </a>
  <a href="https://github.com/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI/issues">
    <img src="https://img.shields.io/github/issues/mehmetdogandev/EMLAKCI-ILE-MUTEAHHIT-PROJESI?style=flat-square" alt="Issues"/>
  </a>
</div>