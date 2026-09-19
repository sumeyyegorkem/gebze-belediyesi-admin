# Gebze Belediyesi — Admin Panel

Gebze Belediyesi web sitesinin içerik yönetim (admin) panelidir. Bu depo kendi başına çalışır (site ile paylaşılan `config/`, `css/`, `img/`, `includes/`, `js/` dosyalarının birer kopyasını içerir). Ziyaretçi tarafındaki site ayrı bir depodadır: [gebze-belediyesi](https://github.com/sumeyyegorkem/gebze-belediyesi)

## 📋 Proje Hakkında

Bu panel, Gebze Belediyesi sitesindeki tüm dinamik içeriğin (haberler, duyurular, etkinlikler, projeler, kurumsal sayfalar, hizmetler, vatandaş talepleri vb.) eklenip düzenlendiği yönetim arayüzüdür. Sitenin gerçek menü yapısını yansıtan bir sidebar ile organize edilmiştir.

## 🛠️ Kullanılan Teknolojiler

- **Backend:** PHP (PDO ile MySQL/MariaDB bağlantısı), SQL injection'a karşı prepared statement kullanımı
- **Veritabanı:** MySQL/MariaDB (XAMPP üzerinde phpMyAdmin ile yönetilir)
- **Arayüz:** Bootstrap 5.3 + Bootstrap Icons, özel admin teması (`admin-tema.css`)
- **Kimlik Doğrulama:** Oturum (session) bazlı giriş, şifreler `password_hash()` ile saklanır
- **Geliştirme Ortamı:** XAMPP (Apache, MySQL, PHP), VS Code
- **Versiyon Kontrolü:** Git & GitHub

## 📁 Proje Yapısı

```
gebze-belediyesi-admin/
├── config/         # Veritabanı bağlantısı (db.php) — site ile ortak, kopya olarak bulunur
├── css/            # admin-tema.css ve site ile ortak style.css
├── img/            # Sitede kullanılan sabit görsellerin kopyası
├── includes/       # admin-baslangic.php / admin-bitis.php (ortak sayfa iskeleti), sayfalama ve yönetici yardımcıları
├── js/             # Ortak JavaScript dosyası
├── login.php       # Giriş ekranı
├── panel.php       # Dashboard (istatistik özeti)
└── *.php           # Her içerik türü için ekle / listele / düzenle / sil sayfaları
```

## 🚀 Kurulum

1. [XAMPP](https://www.apachefriends.org/) indirip kurun, Apache ve MySQL servislerini başlatın.
2. Önce ana siteyi kurun ([gebze-belediyesi](https://github.com/sumeyyegorkem/gebze-belediyesi) deposundaki adımları izleyin) — veritabanı ikisi için de ortaktır.
3. Bu depoyu, sitenin yanına `admin` adıyla klonlayın:
   ```
   cd C:\xampp\htdocs\gebze-belediyesi
   git clone https://github.com/sumeyyegorkem/gebze-belediyesi-admin.git admin
   ```
4. `config/db.php` içindeki veritabanı bilgilerinin kendi XAMPP kurulumunuzla eşleştiğinden emin olun.
5. Tarayıcıdan açın: `http://localhost/gebze-belediyesi/admin/login.php`

## 🔐 Yönetim Modülleri

**İçerik Yönetimi**
- Haberler, Duyurular (görsel + PDF ek), Etkinlikler, Fotoğraf Galerisi, Videolar, Hero Slayt

**Kurumsal**
- Belediye Meclisi, Meclis Kararları, Yönetim Şeması, Müdürlükler
- Başkan Yardımcıları, Başkan Danışmanları, Eski Başkanlar
- Arabuluculuk Komisyonu, Etik Komisyonu, Kurumsal Raporlar, Yayınlar

**Gebze & Hizmetler**
- Muhtarlar, Tarihi Yerler, Kardeş Şehirler, Üye Birlikler
- Faaliyet Alanları, Hizmet Kartları
- Vatandaş talepleri: Emlak, Fen İşleri, Kültür-Sosyal, Temizlik, Veteriner, Zabita İhbarları, Nikah Talepleri

**Sistem**
- Yöneticiler / kullanıcı yetkileri
- Mesajlar (iletişim formundan gelenler)
- Sosyal medya ve site ayarları, Bakım Modu
- İşlem Geçmişi (kim ne zaman ne değiştirdi)
- İstatistik gösteren Dashboard

## 👥 Üretici

Sümeyye Görkem

## 📝 Notlar

- Bu depo kasıtlı olarak site ile bazı dosyaları (`config/`, `css/`, `img/`, `includes/`, `js/`) tekrar içerir; böylece tek başına da incelenebilir/çalıştırılabilir.
- `config/db.php` içindeki bilgiler XAMPP'ın varsayılan yerel geliştirme ayarlarıdır (kullanıcı: root, şifre yok); gerçek bir sunucuya taşırken mutlaka değiştirilmelidir.
- Yönetici girişi `password_hash()` / `password_verify()` ile doğrulanır, şifreler düz metin olarak saklanmaz.
