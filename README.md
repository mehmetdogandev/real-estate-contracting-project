# 🏢 Emlak & Müteahhit Projesi

![Emlak Projesi Banner](https://img.freepik.com/free-photo/3d-rendering-house-model_23-2150799866.jpg)

## 📋 Proje Hakkında

Bu proje, emlak ve müteahhitlik sektörüne yönelik modern bir web platformu sunar. Kullanıcıların gayrimenkul arama, inceleme süreçlerini kolaylaştırmak ve müteahhitler ile potansiyel müşteriler arasında bir köprü oluşturmak için tasarlanmıştır.

### ✨ Özellikler

1. **Kullanıcı Dostu Arayüz**: Basit ve sezgisel bir tasarımla, kullanıcıların kolayca gezinmesini sağlar.

2. **Detaylı Emlak Bilgileri**: Her ilan, detaylı fotoğraflar, açıklamalar, fiyat ve konum bilgilerini içerir.

3. **İnteraktif Harita**: Kullanıcılar, gayrimenkulleri harita üzerinde görüntüleyebilir ve çevresindeki önemli noktaları inceleyebilir.

4. **Müteahhit Profilleri**: Müteahhitler, projelerini ve referanslarını sergileyebilecekleri özel profiller oluşturabilir.

5. **İletişim ve Geri Bildirim**: Kullanıcılar, ilgilendikleri ilanlar veya müteahhitler ile doğrudan iletişime geçebilir.

6. **Mobil Uyumluluk**: Responsive tasarım ile her cihazdan erişim sağlanabilir.

7. **E-posta Bildirimleri**: Admin paneli üzerinden kullanıcılara toplu e-posta gönderilebilir.

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

## 👨‍💼 Admin Paneli Erişimi

Admin paneline giriş yapabilmek için:

- URL: `http://localhost/proje/admin/`
- Kullanıcı Adı: `test`
- Şifre: `test`

![Admin Paneli Giriş Ekranı](https://i.imgur.com/LUhcvpK.jpg)

## 🖥️ Proje Yapısı

### Admin Paneli

Admin paneli aşağıdaki özellikleri içerir:

- Kullanıcı yönetimi
- İlan yönetimi ve onaylama
- Proje yönetimi
- Kategori yönetimi
- E-posta gönderme işlemleri
- Mesajlaşma sistemi

![Admin Panel](https://i.imgur.com/JXmKgsp.jpg)

### Kullanıcı Arayüzü

Kullanıcı arayüzü şu özellikleri içerir:

- Üyelik sistemi (kayıt olma, giriş yapma)
- İlan görüntüleme
- İlan verme
- İlan filtreleme
- Kullanıcılar arası mesajlaşma
- Profil yönetimi

![Kullanıcı Arayüzü](https://i.imgur.com/cGe9TDY.jpg)

## 📊 Veritabanı Yapısı

Proje aşağıdaki ana veritabanı tablolarını kullanmaktadır:

- `kullanicilar`: Kullanıcı bilgileri
- `kategoriler`: İlan kategorileri (Kiralık, Satılık, Günlük Kiralık vb.)
- `urunler`: İlan bilgileri
- `projeler`: Müteahhit projelerinin bilgileri
- `kullanicilar_mesaj`: Kullanıcılar arası mesajlaşma sistemi
- `kisiler`: E-posta gönderilecek kişilerin listesi
- `slider`: Ana sayfa slider ayarları
- `logo`: Site logo ayarları

## 📱 Kullanıcı Sayfası İşleyişi

### Üyelik İşlemleri

Kullanıcılar sisteme kayıt olabilir, giriş yapabilir ve profil bilgilerini güncelleyebilir.

![Kullanıcı Kayıt](https://i.imgur.com/XHFcr7F.jpg)

### İlan Verme

Kullanıcılar sisteme fotoğraf ve detaylarla ilan ekleyebilir. İlanlar admin onayından sonra yayınlanır.

![İlan Verme](https://i.imgur.com/MNLwZyp.jpg)

### Mesajlaşma Sistemi

Kullanıcılar birbirleriyle mesajlaşabilir, gelen ve giden mesajlarını yönetebilir.

![Mesajlaşma](https://i.imgur.com/gWDjVMc.jpg)

## 📨 E-posta Gönderme Sistemi

Admin paneli üzerinden sisteme kayıtlı kullanıcılara toplu e-posta gönderilebilir. Bu özelliği kullanmak için:

1. Admin paneline giriş yapın
2. "Mail İşlemleri" menüsüne tıklayın
3. Alıcıları seçin ve e-posta içeriğini oluşturun
4. "Gönder" butonuna tıklayın

> ⚠️ E-posta gönderimi için SMTP ayarlarınızı doğru yapılandırdığınızdan emin olun.

## ⚙️ Özelleştirme

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

## 📝 Not

Bu proje, salt PHP ile geliştirilmiştir ve geliştirilmeye açıktır. Projenin detaylı tanıtımı için hazırlanan anlatım ve demo videolarını izleyebilirsiniz.

## 👨‍💻 İletişim

Proje hakkında sorularınız veya önerileriniz için iletişime geçebilirsiniz.

---

© 2025 Emlak & Müteahhit Projesi | Tüm Hakları Saklıdır