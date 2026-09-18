-- =========================================================
--  GEBZE BELEDİYESİ ÖRNEK SİTESİ - VERİTABANI ŞEMASI
--  Bu dosyayı phpMyAdmin üzerinden İÇE AKTAR (Import) sekmesinden
--  yükleyeceksin. Aşağıda her adım README.md dosyasında anlatılıyor.
-- =========================================================

CREATE DATABASE IF NOT EXISTS gebze_belediyesi
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_turkish_ci;

USE gebze_belediyesi;

-- ---------------------------------------------------------
-- 1) YÖNETİCİLER TABLOSU (Admin paneline giriş yapacak kişiler)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS yoneticiler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(50) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL,          -- şifre HASH'lenmiş olarak saklanır, düz metin DEĞİL
    ad_soyad VARCHAR(100) NOT NULL,
    fotograf VARCHAR(255) NULL DEFAULT NULL,      -- profil fotoğrafı (uploads/... yolu), yoksa baş harfli avatar gösterilir
    unvan VARCHAR(100) NULL DEFAULT NULL,         -- örn. "İçerik Editörü", "Sistem Yöneticisi"
    avatar_rengi VARCHAR(20) NOT NULL DEFAULT 'mavi', -- fotoğraf yokken baş harf avatarının rengi
    olusturma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- NOT: Admin kullanıcısını burada elle eklemiyoruz.
-- Bunun yerine tarayıcıdan bir kere "admin/ilk_kurulum.php" dosyasını
-- çalıştıracaksın. O dosya, şifreni PHP'nin kendi password_hash()
-- fonksiyonuyla güvenli biçimde üretip veritabanına ekleyecek.
-- Bu, elle yazılmış/yanlış bir hash yüzünden giriş yapamama sorununu önler.

-- ---------------------------------------------------------
-- 2) DUYURULAR / HABERLER TABLOSU
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS duyurular (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    seo_baslik VARCHAR(70) NULL DEFAULT NULL,               -- SEO: boşsa "baslik" kullanılır
    ozet VARCHAR(500) NOT NULL,
    icerik TEXT NOT NULL,
    kategori VARCHAR(50) DEFAULT 'Genel',
    resim_url VARCHAR(255) DEFAULT 'https://placehold.co/800x450?text=Gebze+Belediyesi',
    yayin_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    goruntulenme INT DEFAULT 0,
    seo_aciklama VARCHAR(160) NULL DEFAULT NULL,            -- SEO: boşsa "ozet" kullanılır
    seo_anahtar_kelimeler VARCHAR(255) NULL DEFAULT NULL    -- SEO: virgülle ayrılmış anahtar kelimeler
) ENGINE=InnoDB;

INSERT INTO duyurular (baslik, ozet, icerik, kategori, yayin_tarihi) VALUES
('Başkan Büyükgöz Spor Kulübü Sporcularıyla Bir Araya Geldi',
 'Belediye Başkanımız, Gebze Belediyesi Spor Kulübü sporcuları ve teknik heyetiyle bir araya gelerek başarı dileklerini iletti.',
 'Belediye Başkanımız, ilçemizi çeşitli branşlarda temsil eden Gebze Belediyesi Spor Kulübü sporcuları ve teknik heyetiyle bir araya geldi. Görüşmede sporcuların yeni sezon hedefleri ve belediyemizin spora sağladığı destekler konuşuldu.',
 'Etkinlik', '2026-08-26 10:00:00'),
('Kargalı ve Orhanlı''da İmar Uygulaması Tamamlandı',
 'İki mahallemizde geniş bir alanı kapsayan imar uygulaması çalışması sonuçlandırıldı.',
 'Kargalı ve Orhanlı mahallelerimizde yürütülen imar uygulaması çalışması, geniş bir alanı kapsayacak şekilde tamamlanmıştır. Çalışma sayesinde bölgedeki parsellerin imar durumu netleşmiş, altyapı planlaması için önemli bir adım atılmıştır.',
 'Altyapı', '2026-08-25 09:30:00'),
('Sultan Murat Parkı''nda Çocuklarla Buluşma',
 'Atölye etkinliğimiz kapsamında çocuklarımız Sultan Murat Parkı''nda keyifli vakit geçirdi.',
 'Belediyemizin düzenlediği yaz atölyesi etkinlikleri kapsamında çocuklarımız Sultan Murat Parkı''nda bir araya geldi. Etkinlikte çeşitli el sanatları ve oyun aktiviteleri gerçekleştirildi.',
 'Etkinlik', '2026-08-19 14:00:00'),
('Gebze''de Yaz Konserleri Sürüyor',
 'İlçemizin farklı noktalarında düzenlenen ücretsiz yaz konserleri vatandaşlarımızla buluşmaya devam ediyor.',
 'Belediyemiz tarafından organize edilen yaz konserleri serisi, ilçemizin farklı meydan ve parklarında ücretsiz olarak vatandaşlarımızla buluşmaya devam ediyor. Konser takvimi belediyemizin sosyal medya hesaplarından duyurulmaktadır.',
 'Kültür', '2026-08-17 20:00:00'),
('Yaz Okulları Coşkuyla Tamamlandı',
 'Çocuklarımız için düzenlenen ücretsiz yaz okulu etkinlikleri bu yıl da yoğun ilgiyle sona erdi.',
 'Gençlik ve Spor Müdürlüğümüz koordinasyonunda yürütülen yaz okulu programı, binlerce çocuğumuzun katılımıyla başarıyla tamamlanmıştır. Basketbol, yüzme, satranç ve halk oyunları branşlarında eğitim verilmiştir.',
 'Etkinlik', '2026-08-13 11:00:00'),
('Eskihisar 3024. Sokak Bakım ve Onarım İhalesi',
 'Eskihisar Mahallesi''ndeki sokak bakım ve onarım işi için ihale süreci başlatılmıştır.',
 'Fen İşleri Müdürlüğümüz tarafından Eskihisar Mahallesi 3024. Sokak''ta yürütülecek bakım ve onarım yapım işi için ihale süreci başlatılmıştır. İhaleyle ilgili detaylı şartname belediyemiz İhale Servisi''nden temin edilebilir.',
 'İhale', '2026-08-25 08:00:00');

-- ---------------------------------------------------------
-- 2b) ETKİNLİKLER TABLOSU
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS etkinlikler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    seo_baslik VARCHAR(70) NULL DEFAULT NULL,               -- SEO: boşsa "baslik" kullanılır
    tur VARCHAR(50) DEFAULT 'Etkinlik',      -- Konser, Çocuk Sineması, Tiyatro vb.
    mekan VARCHAR(150) NOT NULL,
    etkinlik_tarihi DATE NOT NULL,
    etkinlik_saati VARCHAR(10) DEFAULT '',
    resim_url VARCHAR(255) DEFAULT 'https://placehold.co/500x300?text=Etkinlik',
    olusturma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    seo_aciklama VARCHAR(160) NULL DEFAULT NULL,            -- SEO: boşsa tür/mekan/tarihten otomatik üretilir
    seo_anahtar_kelimeler VARCHAR(255) NULL DEFAULT NULL    -- SEO: virgülle ayrılmış anahtar kelimeler
) ENGINE=InnoDB;

INSERT INTO etkinlikler (baslik, tur, mekan, etkinlik_tarihi, etkinlik_saati) VALUES
('Gebze''de Müziğin Ritmi', 'Konser', 'Eskihisar Kale Altı', '2026-09-05', '21:30'),
('Mahallemde Sinema Var', 'Çocuk Sineması', 'Kargalı Ortaokulu', '2026-09-02', '21:30'),
('Yaz Konserleri', 'Konser', 'Gebze Millet Bahçesi', '2026-09-08', '21:00'),
('Atölyem Parkta', 'Çocuk Etkinliği', 'Sultan Murat Parkı', '2026-09-10', '17:00');

-- ---------------------------------------------------------
-- 2c) PROJELER TABLOSU ("Projelerimiz" sayfası için)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS projeler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    seo_baslik VARCHAR(70) NULL DEFAULT NULL,               -- SEO: boşsa "baslik" kullanılır
    aciklama VARCHAR(500) NOT NULL,
    kategori VARCHAR(100) DEFAULT 'Fiziki Yatırımlar',
    durum ENUM('devam_eden','tamamlanmis','planli') NOT NULL DEFAULT 'planli',
    resim_url VARCHAR(255) DEFAULT 'https://placehold.co/500x350?text=Proje',
    olusturma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    seo_aciklama VARCHAR(160) NULL DEFAULT NULL,            -- SEO: boşsa "aciklama" kullanılır
    seo_anahtar_kelimeler VARCHAR(255) NULL DEFAULT NULL    -- SEO: virgülle ayrılmış anahtar kelimeler
) ENGINE=InnoDB;

INSERT INTO projeler (baslik, aciklama, kategori, durum) VALUES
('Sultan Orhan Meydanı Yenileme', 'Meydan zemini, aydınlatma ve peyzaj çalışmaları tamamlanarak vatandaşlarımızın kullanımına açıldı.', 'Fiziki Yatırımlar', 'tamamlanmis'),
('Kargalı Spor Kompleksi', 'Kapalı yüzme havuzu, spor salonu ve açık spor alanlarını içeren kompleksin inşaatı tamamlandı.', 'Gençlik, Spor ve Eğitim', 'tamamlanmis'),
('Bahçelievler Bisiklet Yolu', 'İlçe genelinde 12 km uzunluğunda bisiklet yolu ağı oluşturuldu.', 'Ulaşım, Altyapı ve Üstyapı', 'tamamlanmis'),
('İlyasbey Sağlıklı Yaşam Merkezi', 'Spor salonu ve yürüyüş parkurlarını içeren yaşam merkezinin inşaatı sürüyor.', 'Sosyal Belediyecilik', 'devam_eden'),
('Gebze Metrosu Bağlantı Yolları', 'Metro istasyonlarına erişimi kolaylaştıracak yeni bağlantı yollarının yapımı devam ediyor.', 'Ulaşım, Altyapı ve Üstyapı', 'devam_eden'),
('Gebze Millet Bahçesi 2. Etap', 'Millet Bahçesi''nin ikinci etabında yeni yürüyüş parkurları ve etkinlik alanları planlanıyor.', 'Fiziki Yatırımlar', 'planli'),
('Akse Deresi Islah Projesi', 'Taşkın riskini azaltmak amacıyla dere yatağının ıslahı için proje çalışmaları sürüyor.', 'Çevre ve Sıfır Atık', 'planli');

-- ---------------------------------------------------------
-- 2d) FOTOĞRAF GALERİSİ TABLOSU ("Fotoğraflarla Gebze" sayfası için)
-- NOT: Bu tablo, fotograflarla-gebze.php ve admin/fotograf-galerisi.php
-- sayfaları ilk çalıştığında "CREATE TABLE IF NOT EXISTS" ile otomatik
-- olarak da oluşturulur; burada sadece dokümantasyon amacıyla tutulur.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS fotograf_galerisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(150) DEFAULT '',
    resim_url VARCHAR(255) NOT NULL,
    eklenme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO fotograf_galerisi (baslik, resim_url) VALUES
('Gebze', 'img/galeri-1.jpg'),
('Gebze', 'img/galeri-2.jpg'),
('Gebze', 'img/galeri-3.jpg'),
('Gebze', 'img/galeri-4.webp');

-- ---------------------------------------------------------
-- 3) İLETİŞİM MESAJLARI TABLOSU (İletişim formundan gelenler)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS iletisim_mesajlari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(100) NOT NULL,
    eposta VARCHAR(150) NOT NULL,
    telefon VARCHAR(20),
    konu VARCHAR(150) NOT NULL,
    mesaj TEXT NOT NULL,
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    okundu TINYINT(1) DEFAULT 0
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 4) HABERLER TABLOSU ("Haberler" sayfası için, duyurulardan ayrı)
-- NOT: Site zaten çalışıyorsa bu tablo mevcut olabilir; IF NOT EXISTS
-- sayesinde var olan veriler etkilenmez, şema burada belgelenir.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS haberler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    seo_baslik VARCHAR(70) NULL DEFAULT NULL,               -- SEO: boşsa "baslik" kullanılır
    ozet VARCHAR(500) NOT NULL,
    icerik TEXT NOT NULL,
    kategori VARCHAR(50) DEFAULT 'Genel',
    resim_url VARCHAR(255) DEFAULT 'https://placehold.co/800x450?text=Gebze+Belediyesi',
    yayin_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    goruntulenme INT DEFAULT 0,
    seo_aciklama VARCHAR(160) NULL DEFAULT NULL,            -- SEO: boşsa "ozet" kullanılır
    seo_anahtar_kelimeler VARCHAR(255) NULL DEFAULT NULL    -- SEO: virgülle ayrılmış anahtar kelimeler
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 5) VİDEOLAR TABLOSU ("Videolar" sayfası için)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS videolar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    youtube_id VARCHAR(20) NOT NULL,
    tarih DATE NOT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 6) EMLAK & İSTİMLAK SORGU TALEPLERİ
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS emlak_sorgu_talepleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    eposta VARCHAR(150),
    mahalle VARCHAR(100) NOT NULL,
    ada_parsel VARCHAR(100) NOT NULL,
    aciklama TEXT,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 7) NİKAH RANDEVU TALEPLERİ
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS nikah_randevu_talepleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gelin_damat_adi VARCHAR(150) NOT NULL,
    esinin_adi VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    eposta VARCHAR(150),
    istenen_tarih DATE NOT NULL,
    notlar TEXT,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 8) FEN İŞLERİ TALEPLERİ
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS fen_isleri_talepleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    eposta VARCHAR(150),
    mahalle VARCHAR(100) NOT NULL,
    adres VARCHAR(255),
    konu VARCHAR(150) NOT NULL,
    aciklama TEXT NOT NULL,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 9) ZABITA İHBARLARI
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS zabita_ihbarlari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anonim TINYINT(1) NOT NULL DEFAULT 0,
    ad_soyad VARCHAR(150),
    telefon VARCHAR(20),
    mahalle VARCHAR(100) NOT NULL,
    konu VARCHAR(150) NOT NULL,
    aciklama TEXT NOT NULL,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 10) TEMİZLİK TALEPLERİ
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS temizlik_talepleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    eposta VARCHAR(150),
    mahalle VARCHAR(100) NOT NULL,
    adres VARCHAR(255),
    konu VARCHAR(150) NOT NULL,
    aciklama TEXT NOT NULL,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 11) KÜLTÜR VE SOSYAL İŞLER TALEPLERİ
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS kultur_talepleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    eposta VARCHAR(150),
    konu VARCHAR(150) NOT NULL,
    aciklama TEXT NOT NULL,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 12) VETERİNER İHBARLARI
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS veteriner_ihbarlari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    eposta VARCHAR(150),
    konum VARCHAR(150) NOT NULL,
    konu VARCHAR(150) NOT NULL,
    aciklama TEXT NOT NULL,
    durum ENUM('beklemede','inceleniyor','tamamlandi') NOT NULL DEFAULT 'beklemede',
    gonderim_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 13) YAYINLAR TABLOSU ("Yayınlar" sayfası için)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS yayinlar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    tarih VARCHAR(50) DEFAULT '',        -- "2 Ocak 2026" gibi serbest metin
    kategori ENUM('kultur','projeler','manset') NOT NULL DEFAULT 'kultur',
    pdf_url VARCHAR(255) DEFAULT '',
    olusturma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO yayinlar (baslik, tarih, kategori, pdf_url) VALUES
('Söz Verdiğimiz Gibi', '2 Ocak 2026', 'projeler', 'https://www.gebze.bel.tr/dosya/20230522124133.pdf');
-- ---------------------------------------------------------
-- 14) BASKAN YARDIMCILARI TABLOSU (baskan-yardimcilari.php, yonetim-semasi.php)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS baskan_yardimcilari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(150) NOT NULL,
    foto VARCHAR(255) DEFAULT '',
    slug VARCHAR(150) NOT NULL UNIQUE,
    sira INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO baskan_yardimcilari (id, ad, foto, slug, sira) VALUES
(1, 'Şerif Canpolat', 'https://www.gebze.bel.tr/resim/20240520122153.jpg', 'serif-canpolat', 1),
(2, 'Muharrem Baltacıoğlu', 'https://www.gebze.bel.tr/resim/20240520122358.jpg', 'muharrem-baltacioglu', 2),
(3, 'Mahmut Yandık', 'https://www.gebze.bel.tr/resim/20240610101559.jpg', 'mahmut-yandik', 3),
(4, 'Şener Akın', 'https://www.gebze.bel.tr/resim/20240625085313.jpg', 'sener-akin', 4),
(5, 'Zeynep Yıldırım', 'https://www.gebze.bel.tr/resim/20250211154736.jpg', 'zeynep-yildirim', 5);

-- ---------------------------------------------------------
-- 15) MUDURLUKLER TABLOSU (mudurlukler.php, yonetim-semasi.php, baskan-yardimcilari.php)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS mudurlukler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(255) NOT NULL,
    mudur VARCHAR(150) DEFAULT '',
    eposta VARCHAR(150) DEFAULT '',
    sayfa VARCHAR(150) DEFAULT NULL,   -- ilgili hizmet sayfası varsa dosya adi (ör: fen-isleri.php)
    aciklama TEXT,
    foto VARCHAR(255) DEFAULT '',
    telefon VARCHAR(50) DEFAULT '',
    adres VARCHAR(255) DEFAULT '',
    biyografi LONGTEXT,
    yonetmelik LONGTEXT,
    baskan_yardimcisi_id INT DEFAULT NULL,   -- NULL ise dogrudan Baskana bagli
    sira INT DEFAULT 0,
    FOREIGN KEY (baskan_yardimcisi_id) REFERENCES baskan_yardimcilari(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO mudurlukler (ad, mudur, eposta, sayfa, aciklama, foto, telefon, adres, biyografi, yonetmelik, baskan_yardimcisi_id, sira) VALUES
('Afet İşleri ve Risk Yönetimi Müdürlüğü', 'Soner Bilir', 'afet.isleri@gebze.bel.tr', NULL, 'AFAD standartları gereğince belediye personelinin eğitim, teçhizat, sertifikasyon ve entegrasyonu yapılarak afet öncesi hazırlık ve önlem alınır. Afet esnasında kurumlar arası hızlı koordinasyon sağlanarak eğitimli ve kalifiye personelle arama-kurtarma işlemleri yürütülür.', 'https://www.gebze.bel.tr/resim/20250206132818.jpg', '0262 642 04 30', 'Mevlana Mahallesi Issıkgöl Caddesi No: 111/3', '1976 yılında Artvin''de doğdu ve ilk ile ortaöğrenimini memleketinde tamamladı. Eğitim hayatına Van Yüzüncü Yıl Üniversitesi İnşaat Bölümü''nden (2004) mezun olarak başladı. Ardından Anadolu Üniversitesi Kamu Yönetimi (2009) ve Atatürk Üniversitesi İş Sağlığı ve Güvenliği (2016) programlarını tamamladı. İstanbul Gelişim Üniversitesi''nde İş Sağlığı ve Güvenliği alanında yüksek lisans yaparak (2015), uzmanlık alanını akademik düzeyde de pekiştirdi.

Kamu görevine, 2001 yılında Van Sivil Savunma Arama Kurtarma Birliği''nde başladı. Türkiye genelinde, afet ve acil durumlarda kritik öneme sahip birçok arama kurtarma operasyonunda aktif rol aldı. 2005 yılında Artvin İl Sivil Savunma Müdürlüğü''ne, 2008 yılında ise Kocaeli İl Sivil Savunma Müdürlüğü''ne naklen geçiş yaptı. 2010-2012 yılları arasında Kocaeli Büyükşehir Belediyesi İtfaiye Dairesi KBRN ekibinde geçici görev aldı. 2012''de Kocaeli İl Afet ve Acil Durum Müdürlüğü''nde arama kurtarma görevine geri döndü. 2015 yılında Gebze Belediyesi Sivil Savunma Uzmanlığı''na naklen geçiş yaptı.

Sivil Savunma Koleji''nde profesyonel arama kurtarma eğitimlerini, AFADEM''de KBRN (Kimyasal, Biyolojik, Radyolojik, Nükleer) eğitimlerini ve Türkiye Dağcılık Federasyonu''ndan profesyonel dağcılık eğitimlerini tamamladı. Su altı ve su üstü arama kurtarma operasyonlarında da aktif yer alan Bilir, CMAS tarafından onaylı "3 Yıldız Dalgıç" unvanı ve brövesini aldı. 2019 yılında Gebze Belediyesi Arama Kurtarma Ekibi''nin (GE-AK) kuruluş sürecini yönetti; 2022 yılında AFAD akreditasyonuyla "Ulusal Hafif Seviye Kentsel Arama Kurtarma Ekibi" statüsüne ulaştı.

Evli ve iki çocuk babası olan Soner Bilir, Haziran 2024''ten itibaren Gebze Belediyesi Afet İşleri Müdür Vekili olarak görev yapmaktadır.', 'AFET İŞLERİ MÜDÜRLÜĞÜ GÖREV, YETKİ VE SORUMLULUKLARI İLE ÇALIŞMA USUL VE ESASLARINA DAİR YÖNETMELİK

Madde 1 - Amaç
Bu yönetmeliğin amacı Afet İşleri Müdürlüğü teşkilat yapısını, hukuki statüsünü, görev, yetki ve sorumluluklarıyla, çalışma usul ve esaslarını belirleyerek, hizmetlerinin daha etkin ve verimli yürütülmesini sağlamaktır.

Madde 2 - Kapsam
Bu yönetmelik Afet İşleri Müdürlüğü''nün; hukuki statüsünü, görev, yetki ve sorumlulukları ile çalışma usul ve esaslarını kapsar.

Madde 3 - Dayanaklar
Bu yönetmelik yürürlükteki 5393 sayılı Belediye Kanunu, 5216 Sayılı Büyükşehir Belediye Kanunu, 6360 sayılı Kanun, Afet Ve Acil Durum Arama Ve Kurtarma Birlik Müdürlükleri İle İl Afet Ve Acil Durum Arama Ve Kurtarma Ekiplerinin Kuruluşu, Görevleri, Çalışma Usul Ve Esasları Hakkında Yönetmelik ile Afet Ve Acil Durum Yönetimi Başkanlığı Afet Ve Acil Durumlara İlişkin Hizmet Standartları Ve Akreditasyon Esaslarının Belirlenmesi Hakkında Yönetmelik ilgili mevzuat hükümlerine istinaden hazırlanmıştır.', 4, 1),
('Basın Yayın ve Halkla İlişkiler Müdürlüğü', 'Dr. Yusuf Ataseven', 'basin@gebze.bel.tr', NULL, 'Müdürlük; ilçenin sorunlarıyla ilgilenip halk ile belediye arasında iletişimi sağlar, telefon veya internet yoluyla gelen istek/talep/şikayetleri ilgili müdürlüklere yönlendirir. Basın Yayın Servisi; basında çıkan haberleri takip eder, basın bültenleri hazırlar ve basın toplantıları organize eder.', 'https://www.gebze.bel.tr/resim/20260401085341.jpg', '0262 642 0430', NULL, NULL, 'BASIN YAYIN VE HALKLA İLİŞKİLER MÜDÜRLÜĞÜ GÖREV VE ÇALIŞMA YÖNETMELİĞİ

BİRİNCİ BÖLÜM
Amaç, Kapsam, Dayanak, Bağlılık, Tanımlar ve Temel İlkeler

AMAÇ:
MADDE 1- Bu yönetmeliğin amacı, 5393 Sayılı Belediye Kanununun 48''inci maddesi ve ISO 9001-2015 kapsamında 03.07.2019 tarih ve 2019/121 sayılı Meclis Kararı ile kurulan; Halkla İlişkiler Servisi, Sosyal Medya ve İletişim Servisi ve Basın Yayın Servislerinden oluşan Basın Yayın ve Halkla İlişkiler Müdürlüğünün Görev, Çalışma Usul ve Esaslarını düzenlemektir.

KAPSAM:
MADDE 2- Bu yönetmelik, Basın Yayın ve Halkla İlişkiler Müdürlüğündeki personelin görev, yetki ve sorumlulukları ile çalışma usul ve esaslarını kapsar.

DAYANAK:
MADDE 3- (1) Bu yönetmeliğin hazırlanmasında; 657 Sayılı Devlet Memuru Kanunu, 4734 Sayılı Kamu İhale Kanunu, 4982 Sayılı Bilgi Edinme Hakkı Kanunu, 5018 Sayılı Kamu Mali Yönetimi ve Kontrol Kanunu, 5216 Sayılı Büyükşehir Belediye Kanunu, 5393 Sayılı Belediye Kanunu ve ISO 9001 Kalite Yönetimi Kalite El Kitabı esas alınmıştır.

BAĞLILIK
MADDE 4- Müdürlük, Belediye Başkanına veya Başkan Yardımcısına bağlıdır. Başkan bu görevi bizzat veya görevlendireceği kişi eliyle yürütür.

TEMEL İLKELER:
MADDE 6- Gebze Belediye Başkanlığı Basın Yayın ve Halkla İlişkiler Müdürlüğü tüm çalışmalarında; karar alma, uygulama ve eylemlerinde şeffaflığı, hizmetlerin temin ve sunumunda yerindelik ve ihtiyaca uygunluğu, hesap verebilirliği, kurum içi yönetimde ve ilçeyi ilgilendiren kararlarda katılımcılığı, uygulamalarda kanunlara uygun iş yapmayı, belediye kaynaklarının kullanımında etkinlik ve verimliliği, hizmetlerde geçici çözümler ve anlık kararlar yerine sürdürülebilir temel ilkeleri esas alır.', 5, 2),
('Bilgi İşlem Müdürlüğü', 'Mehmet Uçar', 'bilgiislem@gebze.bel.tr', NULL, 'Müdürlük; belediyenin bilgi işlem ihtiyacının envanterini çıkarır, sistem/teçhizat/malzeme ihtiyacını belirler. Yazılım Geliştirme Servisi, Bilgi Teknolojileri Servisi ve Web Tasarım Servisi olmak üzere üç birimden oluşur.', NULL, '0262 642 04 30', NULL, NULL, 'BİLGİ İŞLEM MÜDÜRLÜĞÜ GÖREV VE ÇALIŞMA YÖNETMELİĞİ

BİRİNCİ BÖLÜM
Amaç, Kapsam, Dayanak ve Tanımlar

Amaç
MADDE 1 – (1) Bu yönetmeliğin amacı, Bilgi İşlem Müdürlüğünün teşkilat yapısını, hukukî statüsünü, görev, yetki, çalışma usul ve esaslarını belirleyerek, hizmetlerin daha etkin ve verimli bir şekilde yürütülmesini sağlamaktır.

Kapsam
MADDE 2 – (1) Bu yönetmelik Bilgi İşlem Müdürlüğünün; hukuki statüsünü, görev, yetki ve sorumlulukları ile çalışma usul ve esaslarını kapsar.

Dayanak
MADDE 3 – (1) Bu Yönetmelik 5393 sayılı Belediye Kanununun 15. maddesinin (b) fıkrası hükümlerine dayanılarak hazırlanmıştır.

Tanımlar
MADDE 4 – (1) Bu Yönetmelikte; Yazılım Geliştirme Servisi Yönetim Bilgi Sistemi ile ilgili iş ve işlemleri, Bilgi Teknolojileri Servisi her türlü elektronik veriyi oluşturmak/işlemek/saklamak/korumak için kullanılan depolama, ağ ve diğer fiziksel aygıtların altyapı ve işlemlerinin yürütülmesini, Web Tasarım Servisi ise belediyeye ait web sayfalarının tasarımı, yönetilmesi ve kontrolünü sağlayan birimleri ifade eder.', 3, 3),
('Destek Hizmetleri Müdürlüğü', 'Hamza Melih Malkoç', 'destek@gebze.bel.tr', NULL, 'Müdürlük; belediyenin mal ve hizmet ihtiyaçlarını ilgili mevzuat çerçevesinde temin eder, hizmet binalarının bakım/onarım/temizlik, ısıtma-soğutma ve tesisat işlerini, araçların bakım-onarım süreçlerini yürütür.', NULL, NULL, NULL, NULL, 'DESTEK HİZMETLERİ MÜDÜRLÜĞÜ GÖREV VE ÇALIŞMA YÖNETMELİĞİ

BİRİNCİ BÖLÜM
Amaç, Kapsam, Hukuki Dayanak, Tanımlar ve Temel İlkeler

Amaç
MADDE 1- (1) Bu yönetmeliğin amacı; belediyenin mal ve hizmet ihtiyaçlarını ilgili kanun, tüzük, kararname, yönetmelik, genelge ve tebliğler çerçevesinde temin etmek, hizmet binalarının/ünitelerinin bakım, onarım, temizlik, ısıtma-soğutma ve tesisat işlerini, araçların bakım ve onarımını yürütmek; birimlere gerekli demirbaş, tüketim malzemesi, yakacak, basılı evrak ve kırtasiye ihtiyaçlarını bütçe imkanları dahilinde temin ederek Destek Hizmetleri Müdürlüğünün görev, yetki ve sorumlulukları ile çalışma usul ve esaslarını düzenlemektir.

Kapsam
MADDE 2- (1) Bu yönetmelik Gebze Belediyesi Destek Hizmetleri Müdürlüğü''nün kuruluş, görev, yetki ve sorumlulukları ile çalışma usul ve esaslarını kapsar.', 1, 4),
('Emlak ve İstimlak Müdürlüğü', 'Şaban Sarıay', 'emlak@gebze.bel.tr', 'emlak-istimlak.php', 'Müdürlük; imar mevzuatı çerçevesinde tevhit (birleştirme) ve ifraz (ayırma) işlemlerini yürütür, kendi görev alanındaki iş ve işlemlerin hatasız ve zamanında sonuçlandırılmasını sağlar.', 'https://www.gebze.bel.tr/resim/20191218082422.jpg', '0262 642 04 30', NULL, '1965 yılında Aksaray''da doğan Şaban Sarıay, lise öğrenimini İzmit Endüstri Meslek Lisesinde tamamladı. 1991 yılında Selçuk Üniversitesi Mimarlık Mühendislik Fakültesinden mezun oldu.

Özel sektörde 2 yıl çalıştıktan sonra 1993 yılında Karamürsel Belediyesinde Harita Mühendisi olarak, 1995 yılında İhsaniye Belediyesinde Fen İşleri amiri olarak görev yaptı. 1996 yılından itibaren Gebze Belediyesinde 4 yıl Harita - Emlak ve İstimlak Müdürlüğü, 1 yıl İmar Müdürlüğü, 11 yıl Fen İşleri Müdürlüğü ve son olarak 2016 yılından bu yana Emlak ve İstimlak Müdürlüğü görevini yürütmektedir. Evli ve üç çocuk babasıdır.', 'EMLAK VE İSTİMLAK MÜDÜRLÜĞÜ GÖREV VE ÇALIŞMA YÖNETMELİĞİ

BİRİNCİ BÖLÜM
Amaç, Kapsam, Bağlılık, Hukuki Dayanak, Tanımlar

AMAÇ:
Madde 1- Bu yönetmeliğin amacı; 5393 sayılı Belediye Kanunu''nun 48. maddesi, ISO 9001-2015 kapsamında görev, çalışma usul ve esaslarını belirler ve düzenler.

KAPSAM:
Madde 2- Bu Yönetmelik Müdürlüğün görev, yetki ve sorumluluklarını, çalışma usul ve esaslarını, işbirliği ve diğer birimlerle olan koordinasyonu kapsar.

BAĞLILIK
Madde 3- Emlak ve İstimlak Müdürlüğü, Belediye Başkanına veya Başkan Yardımcısına bağlıdır. Başkan bu görevi bizzat veya görevlendireceği kişi eliyle yürütür.

HUKUKİ DAYANAK
Madde 4- Emlak ve İstimlak Müdürlüğü''nün görev kapsamı; 3194 Sayılı İmar Kanunu, 2981/3290/3366 Sayılı Kanun, 2942 Yasa ve değişik 4650 sayılı Kamulaştırma Yasası, 5393 Sayılı Belediye Kanunu, 2886 Sayılı Devlet İhale Kanunu, 4734 Sayılı Kamu İhale Kanunu, 5018 Sayılı Kamu Mali Yönetimi ve Kontrol Kanunu, ISO 9001 Kalite Yönetim Sistemi Kalite El Kitabı, 1475 Sayılı İş Kanunu, 657 Sayılı Devlet Memurları Kanunu ile bunun ek ve değişiklikleri, Planlı Alanlar Tip İmar Yönetmeliği ve yürürlükteki diğer kanun, kararname ve yönetmeliklere dayanır.', 2, 5),
('Etüt ve Proje Müdürlüğü', 'Asker Çoban', 'etutproje@gebze.bel.tr', NULL, 'Müdürlük; belediye sınırları içerisindeki 1/1000 ölçekli uygulama imar planlarının hazırlanması için gerekli tüm işlemleri yapar, üst ölçekli imar planlarının Kocaeli Büyükşehir Belediyesi ile irtibat sağlanarak takibini yürütür.', 'https://www.gebze.bel.tr/resim/20220908115840.jpg', '0262 642 04 30', 'Güzeller Mahallesi. Bahar Cad. N:1 41400 Gebze/KOC', '1985 yılında Gebze''de dünyaya geldi. İlk ve orta öğrenimini Gazi İlköğretim Okulu''nda, lise öğrenimini Darıca Neşet Yalçın Süper Lisesinde tamamlamıştır. 2005-2010 yılları arasında Konya Selçuk Üniversitesi Mühendislik Mimarlık Fakültesi Şehir ve Bölge Planlama Bölümü''nden, 2018-2020 yılları arasında Eskişehir Anadolu Üniversitesi Açıköğretim Fakültesi İlahiyat Bölümü''nden mezun oldu.

2011 yılında Gebze Belediyesi Plan ve Proje Müdürlüğünde Şehir Plancısı olarak göreve başlayan Asker Çoban, 06.10.2021 tarihi itibariyle Plan ve Proje Müdür Vekili olarak atandı. Aralık 2025 tarihinden itibaren ise Etüt ve Proje Müdürlüğü görevini yürütmektedir. Evli ve iki çocuk babasıdır.', NULL, 1, 6),
('Fen İşleri Müdürlüğü', 'Cezmi Irva', 'fenisleri@gebze.bel.tr', 'fen-isleri.php', 'Müdürlüğün en yetkili amiri, Başkan ve ilgili Başkan Yardımcısına karşı sorumludur. Müdürlüğün idari ve teknik tüm işlerini kanun, yönetmelik ve Başkanlık direktifleri çerçevesinde sevk ve idare eder.', 'https://www.gebze.bel.tr/resim/20260120104602.jpg', '0262 642 04 30', 'Köşklü Çeşme Yeni Bağdat Cd. No: 118 Gebze Kocaeli', 'Cezmi Irva, 1984 yılında Gebze''de doğmuştur. İlköğrenimini Cumaköyü İlkokulu''nda, ortaöğrenimini Gebze İnönü İlköğretim Okulu''nda, lise eğitimini ise Gebze Anadolu Lisesi''nde tamamlamıştır. 2007 yılında Sakarya Üniversitesi Mühendislik Fakültesi İnşaat Mühendisliği Bölümü''nden mezun olmuştur.

Meslek hayatına 2007-2010 yılları arasında özel bir yapı denetim firmasında Kontrol Mühendisi olarak başlamıştır. 2010 yılında Gebze Belediyesi''nde göreve başlamış olup; 2010-2016 yılları arasında İmar ve Şehircilik Müdürlüğü bünyesinde Kaçak Yapı Servisi, Yapı Kontrol Servisi ve Ruhsat Servisi''nde kontrolör, 2016-2022 yılları arasında Fen İşleri Müdürlüğünde Kontrol Amirliği ile Yol Yapım ve Bakım-Onarım Servis Sorumlusu, 2022-2025 yılları arasında ise Etüt ve Proje Müdürlüğü bünyesinde İnşaat Takip ve Kontrol Servisi Sorumlusu olarak görev yapmıştır.

Aralık 2025 tarihinden itibaren Fen İşleri Müdür Vekili olarak görevine devam etmektedir. Orta düzeyde İngilizce bilmektedir.', 'FEN İŞLERİ MÜDÜRLÜĞÜ GÖREV VE ÇALIŞMA YÖNETMELİĞİ

BİRİNCİ BÖLÜM
Amaç, Kapsam, Hukuki Dayanak, Tanımlar

Amaç:
Madde 1- Bu Yönetmeliğin amacı Gebze Belediyesi Fen İşleri Müdürlüğünün kuruluş, görev ve çalışma esaslarını düzenlemektir.

Kapsam:
Madde 2- Bu Yönetmelik; Gebze Belediyesi Fen İşleri Müdürlüğünün kuruluşuna, görevlisine, yetki ve sorumlulukları ile müdürlük personelinin kadrolarına ve kıyafetlerine dair esas ve usulleri kapsar.

Bağlılık:
Madde 3- Fen İşleri Müdürlüğü, Belediye Başkanı''na veya görevlendireceği Başkan Yardımcısı''na bağlıdır. Başkan, bu görevi bizzat veya görevlendireceği kişi eliyle yürütür.

Hukuki Dayanak:
Madde 4- Fen İşleri Müdürlüğü; 5393 Sayılı Belediye Kanunu, 5018 Sayılı Kamu Mali Yönetimi ve Kontrol Kanunu, 657 Sayılı Devlet Memurları Kanunu ile bunun ek ve değişiklikleri, 2886 Sayılı Devlet İhale Kanunu, 4734 Sayılı Kamu İhale Kanunu, 4735 Sayılı Kamu İhaleleri Sözleşmeleri Kanunu, 3194 Sayılı İmar Kanunu, 1475 Sayılı İş Kanunu, 2821 ve 2822 sayılı kanunlar, ilgili yönetmelikler ve ISO 9001 Kalite Yönetim Sistemi Kalite El Kitabı hükümlerine göre görev yapar.', 1, 7),
('Gelirler Müdürlüğü', 'Erhan Horuz', 'gelirler@gebze.bel.tr', NULL, '', 'https://www.gebze.bel.tr/resim/20201016084355.jpg', '0262 642 04 30', NULL, '25.06.1986 yılında Ankara''da dünyaya geldi. İlk ve ortaokulu Adana''da, liseyi Erzurum Lisesinde tamamladı. 2003 yılı üniversite giriş sınavı neticesinde Gaziantep Üniversitesi Gıda Mühendisliği Bölümüne yerleşti. Aynı bölümden 2008, 2011 ve 2018 yıllarında sırasıyla Lisans, Yüksek Lisans ve Doktora derecelerini alan Erhan Horuz, yine aynı bölümde 8 yıl Araştırma Görevlisi olarak görev yaptı.

2019 yılında Gaziantep Büyükşehir Belediyesine naklen geçiş yaptı ve Tarımsal Hizmetler ve Çevre Koruma ve Kontrol Daire Başkanlıklarında Mühendis unvanı ile görev yaptı. Nisan 2020''de Gebze Belediyesi bünyesine katıldı. Aralık 2025 tarihinden itibaren Gelirler Müdür Vekili olarak görevini sürdürmektedir.', NULL, NULL, 8),
('Gençlik ve Spor Hizmetleri Müdürlüğü', 'Hacı Key', 'genclik.spor@gebze.bel.tr', NULL, '', 'https://www.gebze.bel.tr/resim/20201020105708.jpg', '0262 642 04 30', 'Güzeller Mahallesi. Bahar Cad. N:1 41400 Gebze/KOC', '05.01.1977 tarihinde İstanbul-Kartal''da doğdu. Eğitim-öğretim hayatına Cumhuriyet İlkokulu''nda başladı. Sırasıyla 60. Yıl İlköğretim Okulu ve Darıca Lisesi''nde okuduktan sonra 1999 yılında Sakarya Üniversitesi Mahalli İdareler Bölümü''nden mezun oldu. Ardından Anadolu Üniversitesi İktisadi ve İdari Bilimler Fakültesi Kamu Yönetimini bitirdi.

2000-2004 yılları arasında Darıca Nene Hatun İlköğretim Okulu''nda öğretmenlik yaptı. Aynı zamanda çeşitli sivil toplum kuruluşlarında üye ve yönetici olarak görev yaptı. 2004 yılında Gebze Belediyesi''nde göreve başlayarak çeşitli birimlerde çalıştı. Aralık 2025 tarihinden itibaren Gençlik ve Spor Hizmetleri Müdürlüğü görevini yürütmektedir. Evli ve üç çocuk babasıdır.', 'GENÇLİK VE SPOR HİZMETLERİ MÜDÜRLÜĞÜ GÖREV VE ÇALIŞMA YÖNETMELİĞİ

BİRİNCİ BÖLÜM
Amaç, Kapsam, Dayanak ve Tanımlar

MADDE 1- Amaç: Bu yönetmeliğin amacı, Gebze Belediyesi Gençlik ve Spor Hizmetleri Müdürlüğü''nün kuruluş, görev, yetki ve sorumlulukları ile çalışma usul ve esaslarını düzenlemektir.

MADDE 2- Kapsam: Bu Yönetmelik, yürürlükteki ilgili mevzuat çerçevesinde Gebze Belediyesi Gençlik ve Spor Hizmetleri Müdürlüğü''nün kuruluş, görev, yetki ve sorumlulukları ile işleyişini kapsar.

MADDE 3- Dayanak: Bu Yönetmelik, 03.07.2005 tarihli 5393 sayılı Belediye Kanunu, 657 sayılı Devlet Memurları Kanunu ve Belediye ve Bağlı Kuruluşları ile Mahalli İdare Birlikleri Norm Kadro İlke ve Standartlarına İlişkin Esaslar Hakkındaki Yönetmeliğe dayanılarak hazırlanmıştır.', 5, 9),
('Hukuk İşleri Müdürlüğü', 'Av. Murat Tunca', 'hukuk@gebze.bel.tr', NULL, '5393 Sayılı Belediye Kanunu''nun 48. maddesi gereğince kurulan Müdürlük, Belediye Başkanına bağlı olarak görev yapar. Belediyenin davacı veya davalı olduğu adli-idari yargı yerlerinde belediye tüzel kişiliğini vekaleten temsil eder.', NULL, NULL, NULL, NULL, NULL, NULL, 10),
('İklim Değişikliği ve Sıfır Atık Müdürlüğü', 'Kader Duran', 'iklimdegisikligi@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 4, 11),
('İmar ve Şehircilik Müdürlüğü', 'Mücahit Köksal', 'imar@gebze.bel.tr', NULL, 'Müdürlüğün görev ve hizmet alanı içerisindeki hukuki, fiili ve idari statüsü; her türlü görev, yetki ve sorumlulukları bir yönetmelikle düzenlenmiştir.', NULL, NULL, NULL, NULL, NULL, 2, 12),
('İnsan Kaynakları ve Eğitim Müdürlüğü', 'Mustafa Karataş', 'personel@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 13),
('İşletme ve İştirakler Müdürlüğü', 'Yücel Er', 'isletme.istirak@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 2, 14),
('Kadın ve Aile Hizmetleri Müdürlüğü', 'Zeynep Yüksel', 'kadinailehizmetleri@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 5, 15),
('Kültür İşleri Müdürlüğü', 'Carullah Recai Er', 'kultur@gebze.bel.tr', 'kultur-sosyal-isler.php', 'Müdürlük; kültürel etkinlikler, kütüphane, müze, evlendirme ve dış ilişkiler birimleriyle Gebzelilere sanat, kültür ve sosyal yaşam alanında hizmet sunar.', NULL, NULL, NULL, NULL, NULL, 5, 16),
('Makine İkmal, Bakım ve Onarım Müdürlüğü', 'Dursun Ali Yayla', 'dursun.yayla@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 1, 17),
('Mali Hizmetler Müdürlüğü', 'İsmail Denk', 'malihizmetler@gebze.bel.tr', NULL, 'Müdürlük, Belediye Başkanına veya görevlendireceği Başkan Yardımcısına bağlı olarak, 5018 sayılı Kamu Mali Yönetimi ve Kontrol Kanunu çerçevesinde faaliyet gösterir.', NULL, NULL, NULL, NULL, NULL, NULL, 18),
('Mezarlıklar Müdürlüğü', 'İslam Özdağ', 'mezarlik@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 3, 19),
('Özel Kalem Müdürlüğü', 'Mücahit Birben', 'ozelkalem@gebze.bel.tr', NULL, 'Bu birim, Belediye Başkanı''nın resmi, özel ve gizlilik taşıyan yazışmalarını yürütür; harcama yetkilisi eliyle Başkan adına temsil, tören ve ağırlama bütçesini kullanır.', NULL, NULL, NULL, NULL, NULL, NULL, 20),
('Park ve Bahçeler Müdürlüğü', 'Tuncay Türetken', 'parkbahceler@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 1, 21),
('Plan ve Proje Müdürlüğü', 'Yusuf Burkut', 'planproje@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 2, 22),
('Rehberlik ve Teftiş Kurulu Müdürlüğü', 'Hasan Güler', 'teftis@gebze.bel.tr', NULL, 'Kurul, Başkanın onayı üzerine Başkan adına görev yapar; belediyenin yönetimi ve denetimi altındaki kişi, birim ve tüm iş/işlemlerle ilgili teftiş, denetim, inceleme ve soruşturma yapar.', NULL, NULL, NULL, NULL, NULL, NULL, 23),
('Ruhsat ve Denetim Müdürlüğü', 'Abdullah Talha Akyüz', 'ruhsat@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 3, 24),
('Sosyal Destek Hizmetleri Müdürlüğü', 'Mecit Keskinoğlu', 'sosyalyardim@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 3, 25),
('Temizlik İşleri Müdürlüğü', 'Senay Altıntaş', 'temizlikisleri@gebze.bel.tr', 'temizlik-isleri.php', 'Müdürlük; ilçedeki cadde, sokak ve meydanların düzenli temizliği, evsel atıkların toplanması, geri dönüşüm çalışmaları ve çevre denetimi gibi birçok alanda 7/24 hizmet verir.', NULL, NULL, NULL, NULL, NULL, 1, 26),
('Veteriner İşleri Müdürlüğü', 'Cevat Altıntaş', 'veteriner@gebze.bel.tr', 'veteriner-hizmetleri.php', 'Müdürlük; sokak hayvanlarının tedavisi, kısırlaştırılması, aşılanması ve sahiplendirilmesi başta olmak üzere hayvan sağlığı ve halk sağlığını koruyucu birçok alanda hizmet verir.', NULL, NULL, NULL, NULL, NULL, 4, 27),
('Yapı Kontrol Müdürlüğü', 'Abdulkadir Akkurt', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 2, 28),
('Yazı İşleri Müdürlüğü', 'Bahar Özalp', 'yaziisleri@gebze.bel.tr', NULL, '', NULL, NULL, NULL, NULL, NULL, 4, 29),
('Zabıta Müdürlüğü', 'Yusuf Erhan Kaya', 'zabita@gebze.bel.tr', 'zabita.php', 'Müdürlük; ilçede huzur, güven ve düzenin sağlanması amacıyla denetim ve kontrol çalışmaları yürütür.', NULL, NULL, NULL, NULL, NULL, 3, 30);
