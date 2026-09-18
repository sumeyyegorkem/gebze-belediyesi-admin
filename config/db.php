<?php
/**
 * config/db.php
 * -------------------------------------------------------
 * Bu dosya, PHP ile MariaDB veritabanı arasında PDO
 * (PHP Data Objects) kullanarak bağlantı kurar.
 *
 * Diğer tüm .php dosyaları, veritabanına erişmek istediğinde
 * bu dosyayı en başta "require" eder ve $pdo değişkenini kullanır.
 * -------------------------------------------------------
 */

// --- XAMPP varsayılan ayarları (gerekirse değiştir) ---
$host    = '127.0.0.1';        // MariaDB sunucusu (XAMPP'ta genelde localhost)
$dbAdi   = 'gebze_belediyesi'; // veritabani.sql ile oluşturduğumuz veritabanı adı
$kullanici = 'root';           // XAMPP varsayılan kullanıcı adı
$sifre     = '';               // XAMPP varsayılan şifre BOŞTUR
$karakterSeti = 'utf8mb4';

// Alt klasördeki sayfalarda (örn. gebze/gebze.php) da menü ve linklerin doğru
// çalışması için kullanılan "site kökü" sabiti. navbar.php, footer.php gibi
// hem kök dizindeki hem alt klasördeki sayfalarda ORTAK kullanılan dosyalar,
// linkleri hep bu sabitle başlatarak, sayfa hangi klasörde olursa olsun
// doğru adrese gitmesini sağlar.
define('SITE_KOK', '/gebze-belediyesi');

$dsn = "mysql:host={$host};dbname={$dbAdi};charset={$karakterSeti}";

// PDO'ya nasıl davranması gerektiğini söyleyen ayarlar
$secenekler = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Hata olursa Exception fırlat
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Sonuçları isimli dizi olarak döndür
    PDO::ATTR_EMULATE_PREPARES   => false,                    // Gerçek "prepared statement" kullan (SQL Injection'a karşı önemli)
];

try {
    $pdo = new PDO($dsn, $kullanici, $sifre, $secenekler);
} catch (PDOException $hata) {
    // Bağlantı kurulamazsa kullanıcıya güvenli/genel bir mesaj göster,
    // teknik detayı ekrana basma (güvenlik açısından önemli).
    die('Veritabanı bağlantısı kurulamadı. Lütfen XAMPP\'ta Apache ve MySQL servislerinin çalıştığından ve "gebze_belediyesi" veritabanının içe aktarıldığından emin olun.');
}

/**
 * Otomatik veritabanı geçişi: içerikleri "yayından kaldırma" (aktif/pasif) özelliği.
 * Haberler, duyurular, etkinlikler ve projeler tablolarına "aktif" sütunu eklenir
 * (yoksa). Sütun zaten varsa hiçbir şey yapılmaz. Bu sayede admin panelinden
 * ayrı bir migrasyon sayfası çalıştırmaya gerek kalmadan, site her açıldığında
 * sütunun var olduğu garanti edilir.
 */
foreach (['haberler', 'duyurular', 'etkinlikler', 'projeler'] as $gbAktifTablo) {
    try {
        $gbKontrol = $pdo->query("SHOW COLUMNS FROM `$gbAktifTablo` LIKE 'aktif'");
        if ($gbKontrol && $gbKontrol->rowCount() === 0) {
            $pdo->exec("ALTER TABLE `$gbAktifTablo` ADD COLUMN aktif TINYINT(1) NOT NULL DEFAULT 1");
        }
    } catch (PDOException $gbHata) {
        // Tablo henüz oluşturulmadıysa (ör. veritabanı yeni kuruluyorsa) sessizce geç
    }
}

/**
 * Otomatik veritabanı geçişi: "aktif/pasif" özelliğinin admin panelindeki TÜM
 * yönetim listelerine yayılması. Her satırın yanındaki İşlem menüsüne "Pasife Al"
 * eklenebilmesi için, bu tablolara da (yoksa) aynı şekilde "aktif" sütunu eklenir.
 * Genel (public) sayfalar bu sütuna göre pasif kayıtları gizler.
 */
foreach ([
    'arabuluculuk_uyeleri', 'baskan_yardimcilari', 'mudurlukler', 'eski_baskanlar',
    'etik_komisyonu_uyeleri', 'faaliyet_kategorileri', 'faaliyet_ogeleri', 'hizmet_kartlari',
    'kardes_sehirler', 'kurumsal_raporlar', 'meclis_kararlari', 'meclis_uyeleri',
    'tarihi_yerler', 'uye_birlikler', 'videolar', 'yayinlar',
    'e_belediye_kategoriler', 'e_belediye_bolumler', 'e_belediye_hizmetleri',
] as $gbAktifTablo2) {
    try {
        $gbKontrol2 = $pdo->query("SHOW COLUMNS FROM `$gbAktifTablo2` LIKE 'aktif'");
        if ($gbKontrol2 && $gbKontrol2->rowCount() === 0) {
            $pdo->exec("ALTER TABLE `$gbAktifTablo2` ADD COLUMN aktif TINYINT(1) NOT NULL DEFAULT 1");
        }
    } catch (PDOException $gbHata) {
        // Tablo henüz oluşturulmadıysa (ör. o özellik henüz kurulmadıysa) sessizce geç
    }
}

/**
 * Otomatik veritabanı geçişi: Tarihçe ve Bugünkü Gebze sayfalarının içeriği.
 * Daha önce bu iki sayfanın metni doğrudan kodun içine yazılıydı (statik).
 * Artık veritabanından geliyor ve admin panelinden düzenlenebiliyor.
 * Tablo ilk defa oluşturulduğunda, sitede o ana kadar görünen metin
 * aynen veritabanına aktarılır; hiçbir içerik kaybolmaz.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS sabit_sayfa_icerikleri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sayfa_anahtari VARCHAR(40) NOT NULL UNIQUE,
        icerik LONGTEXT NOT NULL,
        guncelleme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $gbSabitSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM sabit_sayfa_icerikleri")->fetch()['toplam'];
    if ($gbSabitSayisi === 0) {
        $gbTarihceIcerik = <<<'HTML'
<h4 class="fw-bold mt-4">Tarihte Gebze</h4>
<p class="metin-govde">
    Gebze'nin de içinde bulunduğu, eski Yunanlılar'ın ve Romalılar'ın Bitinya (Bithynie) dedikleri
    coğrafi bölgenin bilinen en eski tarihi, M.Ö. XII. yüzyıla kadar dayanır. Bölge, özellikle
    Kocaeli Yarımadası, coğrafi konumunun öneminden dolayı, tarihin hemen hemen bütün dönemlerinde
    birçok ulusa yurt olmuştur. Asya ile Avrupa kıtaları arasındaki en önemli geçit yeri olan
    Kocaeli Yarımadası, ya birçok ulusun yurdu ya da gelip geçtikleri, medeniyetlerinden izler
    bıraktığı bir yer olmuştur.
</p>
<p class="metin-govde">
    Bilinen ilk ulus göçü de M.Ö. XII. yüzyılın başlarındadır. Bu ulus Yunan kökenli Frikler'dir;
    Boğaz (Bosforos) yoluyla Anadolu'ya inmişlerdir. XII. yüzyıla kadar Trakya'dan İzmit dolaylarına
    göçler devam etmiş, fakat bu dönemde eski Gebze'nin yerine dair kesin bilgi edinilememiştir.
</p>
<p class="metin-govde">
    Bugün Gebze'nin bulunduğu yerde, M.Ö. 281-246 yıllarında Kral I. Nikomedes'in egemenliğindeki
    Bitinya Krallığı döneminde Dakibyza ve Libyssa adında yerleşmeler bulunmaktaydı. Bu yerleşim
    alanlarının araştırmalara konu olmasının en önemli nedeni, ünlü Kartacalı komutan Hannibal'ın
    krallık döneminde burada yaşamış olmasıdır.
</p>
<p class="metin-govde">
    Zama Savaşı'ndaki yenilgisinin ardından ülkesinde itibarını yitiren Hannibal, Bitinya Krallığı'na
    sığınmış ve I. ile II. Prusias'ın savaş danışmanlığını yapmıştır. II. Prusias'ın ihaneti sonucu
    düşmanın eline düşmemek için hayatına son vermiş ve Libyssa'ya defnedilmiştir. Roma kuvvetlerinden
    gizlenmek isteyen Hannibal, korunaklı, kaçışa elverişli ve denize yakın bu bölgeyi bilinçli olarak
    seçmiştir.
</p>
<p class="metin-govde">
    1330 yılında Osmanlılar ile Bizans arasındaki savaşın ardından Gebze'nin de içinde bulunduğu
    bölge Osmanlı idaresine dahil edilmiştir. Bugünkü Gebze'nin kurucusu Orhan Gazi'dir; bölgede kendi
    adına bir cami de yaptıran Orhan Gazi, imar ve iskân için işletmeler kurmuş, vakıfları desteklemiştir.
    Akçakoca Bey'in oğlu İlyas Çelebi de hem fetihte hem de kuruluşta önemli rol oynamıştır.
</p>
<p class="metin-govde">
    Gebze, Osmanlı İmparatorluğu'nun son dönemlerine kadar kimi zaman İstanbul'a, çoğunlukla da
    Kocaeli'ye bağlı önemli bir kaza olma özelliğini korumuştur. I. Dünya Savaşı sonrasında Anadolu
    ve Trakya'nın birçok bölgesi gibi Gebze de işgale uğramış; 1920'de İngilizler'in, 1921 başında
    ise Yunanlılar'ın işgaline sahne olmuştur. 18-19 Ocak 1923 tarihli Hakimiyet-i Milliye gazetesinde,
    Atatürk'ün bölgeyi ve Gebze'yi ziyaret ettiği ve buradaki askeri birliklerin durumundan memnun
    kaldığı aktarılır. Cumhuriyet'in ilanının ardından Gebze, yeni iller kanununa göre İzmit'e
    bağlanmıştır.
</p>

<h4 class="fw-bold mt-4">Libyssa'dan Gebze'ye</h4>
<p class="metin-govde">
    Gebze adının kökeni, araştırmacıların çoğuna göre bölgedeki eski yerleşim adlarına
    dayanmaktadır. Antik çağ kaynaklarında Libyssa, Roma ve Bizans dönemlerinde ise Dakibyza adı
    kullanılmıştır; bu isimlerin okunuşunun günümüzdeki "Gebze" sözcüğüne benzerliği, kelimenin
    kökeninin çok eskiye dayandığını göstermektedir.
</p>
<p class="metin-govde">
    Tarih boyunca kaynaklara göre Gebseh, Gebisseh, Gjabseh, Gekbuze, Ghviza, Gavize, Dschebse,
    Dschebize, Gebize gibi farklı yazımlar da kullanılmıştır. Evliya Çelebi, Seyahatnamesi'nde
    bölgeden bir kez "Kekbeziye" olarak söz etmiş, başka bir yerde ise "Gebze" adının "Gelbize"den
    geldiğini yazmıştır. Araştırmacı İbrahim Hakkı Konyalı, Osmanlı arşiv kayıtlarında Geybüyze,
    Geybüveyze, Geyibüveyze, Geyiboyze, Geykivize gibi biçimlerin yer aldığını, günümüzde ise
    "Gebze" adının yerleştiğini belirtmiştir.
</p>
<p class="metin-govde">
    Halk arasında bir söylenceye göre, Osmanlı ve Bizans akınları sırasında sıkça el değiştiren
    ve özlenen bir yer olması nedeniyle "Gel bize" / "Bize gel" ifadelerinin zamanla halk dilinde
    "Gebze"ye dönüştüğü de aktarılır; ancak 1640'ta bölgeye gelen Evliya Çelebi, bu adın "Gelbize"
    kelimesinin bozulmuş biçimi olduğunu ifade etmiştir.
</p>

<h4 class="fw-bold mt-4">Yöresel Kültür ve Gelenekler</h4>

<h6 class="fw-bold mt-3">Düğün</h6>
<p class="metin-govde">
    Köylerde düğünlerde dışarıda ateş yakılır, kazanlarda düğün yemekleri pişirilir; misafirlere
    düğün çorbası, etli yemek, pilav ve zerde tatlısı ikram edilir. Düğünden önce çeyiz sergisi
    yapılır, Cuma günü gelin hamamına gidilir ve gelin çalgılarla hamamdan çıkarılır. Köylüler,
    genç kızların ev ev dolaşarak yaptığı davetlerle düğüne çağrılır. Büyük kına (düğün) gününde
    gelinin başında "bereket" dileğiyle ekmek kırılır.
</p>

<h6 class="fw-bold mt-3">Cenaze</h6>
<p class="metin-govde">
    Cenaze geleneksel usullere göre yıkanır ve kıbleye yönlendirilerek hazırlanır; vefatın
    duyurulmasında "sela" okunur. Mevta dışarıda yıkanmışsa, yedi gece boyunca o alanda ışık
    yakılmaya devam edilir.
</p>

<h6 class="fw-bold mt-3">Bayramlar</h6>
<p class="metin-govde">
    Bayramlaşmanın hangi köyde ne zaman yapılacağı camilerde duyurulur; o gün ev sahibi köye
    komşu köylerden ziyaretler gerçekleşir, pilav ve ikramlar hazırlanır, gençler gruplar hâlinde
    köy köy gezerek bayramlaşır.
</p>

<h6 class="fw-bold mt-3">Hıdırellez</h6>
<p class="metin-govde">
    5-6 Mayıs'ta kutlanan Hıdırellez'de mayasız hamur yoğrulur, bereket ve bolluğa dair çeşitli
    inanışlar canlı tutulur. Genç kızlar küçük eşyalarını gömüp erkeklerin bulmasını bekler, ateş
    yakılıp üzerinden atlanır, soğan yapraklarıyla dilek tutulur.
</p>

<h6 class="fw-bold mt-3">Yöresel Yemekler</h6>
<p class="metin-govde">
    Çarşır mancarı, kazayağı mancarı, ebegümeci mancarı, efelik mancarı, mantı, yamayuka böreği,
    tava tutuşturması, bulgurlu börek, sirkem mancar, kabak tatlısı, höşmerim, peynir höşmeli,
    kocagörmez, cızlama (nazlım), kabaklı börek ve tarta (dartı) yöreye özgü başlıca lezzetlerdir.
</p>

<h6 class="fw-bold mt-3">Giysiler</h6>
<p class="metin-govde">
    Özellikle keten tarımıyla uğraşan dağ köylerinde halk, kendi el işi giysilerini tercih
    etmiştir. Kadınlar çoğunlukla şalvar, yelek ve hırka giyer; başlarına işlemeli ya da beyaz
    yazma örter, boyunlarına gerdanlık takarlardı.
</p>

<h6 class="fw-bold mt-3">Yöresel Atasözleri</h6>
<ul class="metin-govde">
    <li>Anahtarı belinde, her gün babası evinde.</li>
    <li>Avludan bez alma; kına tamından kız alma.</li>
    <li>Çocuk, evin yemişidir.</li>
    <li>Dön dolaş, yine değirmen taşı.</li>
    <li>Eşek kendi yüküne yenilmez.</li>
    <li>İç güveyisi, iç ağrısı.</li>
    <li>Kırk taşımız var; ata ata vuracağız bu işi.</li>
    <li>Kırk kulpu kazan, birinden tut sen de kazan.</li>
    <li>Ye tatlıyı, iç suyu; ağzın dönsün yala.</li>
    <li>Ye tuzluyu, iç suyu; ağzın dönsün bala.</li>
</ul>
HTML;

        $gbBugunkuGebzeIcerik = <<<'HTML'
<p class="metin-govde">
    Gebze, Marmara Bölgesi'nin doğusunda, İzmit Körfezi'nin kuzey kesiminde yer alan; tarımı,
    hayvancılığı ve özellikle sanayisiyle hızla gelişen bir Kocaeli ilçesidir. İstanbul'a 45,
    İzmit'e 49 kilometre uzaklıkta, deniz seviyesinden 130 metre yükseklikte bulunan ilçe,
    Marmara Bölgesi'nin en büyük ikinci ilçesi olup Türkiye sanayi üretiminin yaklaşık %15'ini
    barındırmaktadır.
</p>
<p class="metin-govde">
    Limanlara, havalimanına, devlet demiryollarına ve E-5 ile TEM karayollarına yakınlığı,
    Gebze'yi hem Avrupa'ya yönelik ticarette hem de Anadolu ve Orta Asya'ya geçişte önemli bir
    kavşak konumuna taşımıştır. Ucuz ve kolay bulunur arazi maliyetleri, sanayi tesislerinin
    yıllar içinde İstanbul'dan Gebze'ye kaymasında belirleyici olmuştur.
</p>
<p class="metin-govde">
    İlçe sınırları içinde göl, dağ ve akarsu bulunmamakla birlikte, en yükseği Gaziler Tepesi
    olan 650 metreyi geçmeyen tepeler ve sırtlar yer alır. Karadeniz ile Akdeniz iklimleri
    arasında geçiş özelliği taşıyan Gebze'de yıllık ortalama yağış 550 mm'dir; en sıcak ay
    ortalaması 24,2 derece ile Ağustos, en soğuk ay ortalaması 6,5 derece ile Ocak'tır.
</p>
<p class="metin-govde">
    2008 yılında yürürlüğe giren kanunla Çayırova, Darıca ve Dilovası ilçe olarak Gebze'den
    ayrılmış, bu değişiklik ilçenin nüfusuna da yansımıştır:
</p>
<ul class="list-unstyled metin-govde mb-4">
    <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">1973:</strong> 27.000 kişi</li>
    <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">1990:</strong> 159.116 kişi</li>
    <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">2000:</strong> 253.487 kişi</li>
    <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">2007:</strong> 521.291 kişi</li>
    <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">2008 (yeni ilçe ayrımı sonrası):</strong> 288.569 kişi</li>
</ul>

<h4 class="fw-bold mt-4">Önemli Kurum ve Kuruluşlar</h4>
<p class="metin-govde">
    1985 yılında kurulan Gebze Organize Sanayi Bölgesi (GOSB), Gebze merkezine 7 km mesafede
    10.370.000 m²'lik alanda 85 firmada yaklaşık 9.100 kişiye istihdam sağlamaktadır; yatırımların
    tutar bazında %65'i yabancı sermayelidir. GOSB bünyesinde makine, kimya, otomotiv yan sanayi,
    optik, elektronik, gıda-ambalaj ve bilişim sektörlerinde üretim yapan firmalar yer alır.
</p>
<p class="metin-govde">
    Türk Standartları Enstitüsü (TSE), Gebze'deki laboratuvarlarında kalibrasyon, deney ve
    tahribatsız muayene hizmetleriyle çeşitli belgelendirme hizmetleri sunar. 1985'te kurulan
    TÜSSİDE ise kamu ve özel sektör yönetici ve çalışanlarına liderlik, stratejik yönetim ve
    kalite kültürü alanlarında eğitim vermektedir.
</p>
<p class="metin-govde">
    Gebze Teknik Üniversitesi, Türkiye'nin tıp fakültesi bulunmayan üniversiteler arasında en
    iyi ikincisi olarak gösterilen, ilçe sınırları içindeki başlıca yükseköğretim kurumudur.
    TÜBİTAK Marmara Araştırma Merkezi de Bilişim Teknolojileri, Enerji, Yer ve Deniz Bilimleri
    ile Malzeme Enstitüleri ve MARTEK Teknopark'ıyla bölgenin bilim ve teknoloji üssü
    niteliğindedir.
</p>
HTML;

        $gbSabitEkle = $pdo->prepare("INSERT INTO sabit_sayfa_icerikleri (sayfa_anahtari, icerik) VALUES (:anahtar, :icerik)");
        $gbSabitEkle->execute(['anahtar' => 'tarihce', 'icerik' => $gbTarihceIcerik]);
        $gbSabitEkle->execute(['anahtar' => 'bugunku-gebze', 'icerik' => $gbBugunkuGebzeIcerik]);
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Özgeçmiş (hakkimizda.php - Başkanın Biyografisi).
 * Bu anahtar, tarihçe/bugünkü gebze tablo tamamen boşken eklendiği için, tablo
 * zaten dolu olan (siteyi daha önce kurmuş) kurulumlarda ayrıca ve tek başına
 * kontrol edilip ekleniyor; böylece "hakkimizda" satırı hiçbir kurulumda eksik kalmaz.
 */
try {
    $gbHakkimizdaVarMi = $pdo->prepare("SELECT COUNT(*) AS toplam FROM sabit_sayfa_icerikleri WHERE sayfa_anahtari = 'hakkimizda'");
    $gbHakkimizdaVarMi->execute();
    if ((int)$gbHakkimizdaVarMi->fetch()['toplam'] === 0) {
        $gbHakkimizdaIcerik = <<<'HTML'
<p class="metin-govde">
    1964 yılında Erzurum'da doğan Zinnur Büyükgöz, ilk ve orta öğrenimini İstanbul'da
    tamamladıktan sonra 1983'te Gebze İmam Hatip Lisesi'nden mezun oldu. 1987 yılında
    Yıldız Teknik Üniversitesi Mimarlık Fakültesi'nden Şehir ve Bölge Plancısı unvanıyla
    mezun olan Büyükgöz, aynı üniversitede yüksek lisansını tamamlayarak 30 yılı aşkın
    süredir şehir plancılığı mesleğini sürdürmektedir. Evli ve dört çocuk babasıdır.
</p>
<p class="metin-govde">
    Siyasi hayatına Darıca Belde Başkanlığı ve çeşitli parti yönetim kurulu üyelikleriyle
    başlayan Büyükgöz, 2004-2009 döneminde Gebze Belediyesi Teknik Başkan Yardımcılığı ve
    Belediye Meclis Üyeliği ile Kocaeli Büyükşehir Belediyesi Meclis ve İmar Komisyonu
    üyeliklerinde bulundu. 2004 yılından itibaren İstanbul, Bursa ve Kocaeli Kültür
    Varlıklarını Koruma Bölge Kurulları'nda üyelik yaptı; ayrıca İdare Mahkemeleri'nde
    bilirkişilik görevlerinde bulundu.
</p>
<p class="metin-govde">
    2014-2016 yılları arasında İstanbul Ticaret Odası Proje Danışma Kurulu Üyesi olarak
    görev yapan Büyükgöz, 2014'ten bu yana Teknopark İstanbul Proje Danışma Kurulu
    Üyeliği'ni sürdürmektedir. 31 Mart 2019 Mahalli İdareler Seçimi'nde Gebze halkının
    teveccühüyle Belediye Başkanı seçilmiş olup, halen bu görevi yürütmektedir.
</p>
HTML;
        $gbHakkimizdaEkle = $pdo->prepare("INSERT INTO sabit_sayfa_icerikleri (sayfa_anahtari, icerik) VALUES ('hakkimizda', :icerik)");
        $gbHakkimizdaEkle->execute(['icerik' => $gbHakkimizdaIcerik]);
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Yönetici kişiselleştirme alanları.
 * Her yöneticinin kendi profil fotoğrafı, avatar rengi ve unvanı olabilsin diye
 * "yoneticiler" tablosuna sonradan eklenen sütunlar.
 */
try {
    if ($pdo->query("SHOW COLUMNS FROM yoneticiler LIKE 'fotograf'")->rowCount() === 0) {
        $pdo->exec("ALTER TABLE yoneticiler ADD COLUMN fotograf VARCHAR(255) NULL DEFAULT NULL AFTER ad_soyad");
    }
    if ($pdo->query("SHOW COLUMNS FROM yoneticiler LIKE 'unvan'")->rowCount() === 0) {
        $pdo->exec("ALTER TABLE yoneticiler ADD COLUMN unvan VARCHAR(100) NULL DEFAULT NULL AFTER fotograf");
    }
    if ($pdo->query("SHOW COLUMNS FROM yoneticiler LIKE 'avatar_rengi'")->rowCount() === 0) {
        $pdo->exec("ALTER TABLE yoneticiler ADD COLUMN avatar_rengi VARCHAR(20) NOT NULL DEFAULT 'mavi' AFTER unvan");
    }
} catch (PDOException $gbYoneticiHata) {
    // sessizce geç
}

/**
 * İşlem Geçmişi (aktivite günlüğü): hangi yöneticinin ne zaman ne yaptığını
 * kaydeden tablo. "yonetici_adi" o anki adın bir anlık görüntüsüdür; yönetici
 * daha sonra silinse veya adını değiştirse bile geçmiş kayıt değişmez.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS islem_kayitlari (
        id INT AUTO_INCREMENT PRIMARY KEY,
        yonetici_id INT NULL DEFAULT NULL,
        yonetici_adi VARCHAR(100) NOT NULL DEFAULT 'Bilinmiyor',
        eylem VARCHAR(150) NOT NULL,
        hedef VARCHAR(255) NULL DEFAULT NULL,
        tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
} catch (PDOException $gbIslemHata) {
    // sessizce geç
}

/**
 * Bir admin işlemini (ekleme/düzenleme/silme vb.) İşlem Geçmişi tablosuna
 * kaydeder. $eylem kısa bir eylem adı ("Haber Ekledi" gibi), $hedef ise
 * işlemin yapıldığı kaydın adı/başlığıdır (isteğe bağlı). Oturumdaki
 * yönetici bilgisi kullanılır; bu fonksiyon başarısız olsa bile asıl
 * işlemi (haberi/duyuruyu kaydetme vb.) asla engellemez.
 */
function islemKaydet($pdo, $eylem, $hedef = '') {
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO islem_kayitlari (yonetici_id, yonetici_adi, eylem, hedef) VALUES (:yonetici_id, :yonetici_adi, :eylem, :hedef)"
        );
        $stmt->execute([
            'yonetici_id' => $_SESSION['admin_id'] ?? null,
            'yonetici_adi' => $_SESSION['admin_ad'] ?? 'Bilinmiyor',
            'eylem' => $eylem,
            'hedef' => ($hedef !== '' ? $hedef : null),
        ]);
    } catch (Exception $islemKayitHata) {
        // sessizce geç - günlük kaydı asıl işlemi asla bozmamalı
    }
}

/**
 * Otomatik veritabanı geçişi: Tarihi Yerler (kent-rehberi.php).
 * Önceden sabit bir PHP dizisiydi; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS tarihi_yerler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ikon VARCHAR(50) NOT NULL DEFAULT 'bi-geo-alt-fill',
        baslik VARCHAR(150) NOT NULL,
        kisa_aciklama VARCHAR(255) NOT NULL DEFAULT '',
        resim_url VARCHAR(255) NOT NULL DEFAULT '',
        detay TEXT NOT NULL,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbTarihiYerSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM tarihi_yerler")->fetch()['toplam'];
    if ($gbTarihiYerSayisi === 0) {
        $gbTarihiYerler = [
            ['bi-building', 'Çoban Mustafa Paşa Camii ve Külliyesi', 'Kanuni Sultan Süleyman döneminden kalma büyük bir külliye.', 'img/mekan-coban-mustafa-pasa.jpg', "Kanuni Sultan Süleyman döneminde, Mimar Sinan'ın katkılarıyla inşa edilen külliye; cami, medrese, hamam, kervansaray ve türbeyi bir arada barındırır. İnşası hicri 930 (miladi 1523) yılında tamamlanmış olup dönemin vezirlerinden Mustafa Paşa tarafından yaptırılmıştır."],
            ['bi-shield-fill', 'Eskihisar Kalesi', 'Bizans dönemine ait, İzmit Körfezi manzaralı tarihi kale kalıntısı.', 'img/mekan-eskihisar-kalesi.jpg', 'Bizans İmparatoru I. Manuel Komnenos döneminde, yaklaşık 800 yıl önce İzmit Körfezi kıyı şeridini ve limanı korumak amacıyla inşa edilmiştir. Dikdörtgen planlı kale, 10 burç ve 4 kapıyla günümüze özgün haliyle ulaşmıştır.'],
            ['bi-easel-fill', 'Osman Hamdi Bey Evi ve Müzesi', "Ünlü ressamın Eskihisar'daki köşkü, bugün müze olarak ziyarete açık.", 'img/mekan-osman-hamdi-bey.jpg', 'Türk müzeciliğinin öncülerinden, "Kaplumbağa Terbiyecisi" tablosunun ressamı Osman Hamdi Bey tarafından 1884 yılında Eskihisar\'da yaptırılan köşk; bugün ressamın kişisel eşyaları ve eserlerinin sergilendiği bir müze olarak hizmet vermektedir. Giriş ücretsizdir, Pazartesi hariç her gün ziyarete açıktır.'],
            ['bi-award-fill', "Hannibal'ın Anıt Mezarı", 'Ünlü Kartacalı komutanın anısına yapılan anıt mezar.', 'img/mekan-hannibal-mezari.jpg', "Kartacalı ünlü komutan Hannibal'ın anısına, çevresi selvilerle çevrili bir alanda 24 tonluk taş lahitten oluşan anıt mezar bulunmaktadır. Anıt mezar, 24 Temmuz 1981 tarihinde düzenlenen bir törenle açılmıştır."],
            ['bi-moon-stars-fill', 'Sultan Orhan Camii', "Orhan Gazi tarafından yaptırılan, ilçenin ilk Osmanlı eserlerinden biri.", 'img/mekan-sultan-orhan-camii.jpg', "Osmanlı Beyliği'nin kurucularından Orhan Gazi tarafından, Gebze'nin fethinin ardından yaptırılan tarihi cami; ilçedeki en eski Osmanlı dönemi eserlerinden biridir."],
            ['bi-moon-stars-fill', 'İlyas Bey Camii', "Gebze'nin fethinde rol oynayan İlyas Bey adına yaptırılan cami.", 'img/mekan-ilyas-bey-camii.jpg', "Gebze'nin fethinde önemli rol oynayan Akçakocaoğlu İlyas Bey adına yaptırılan tarihi cami, ilçenin erken Osmanlı dönemi mimarisini yansıtan önemli eserlerdendir."],
            ['bi-droplet-fill', 'Çoban Mustafa Paşa Hamamı', 'Halk arasında Çarşı Hamamı olarak bilinen tarihi çifte hamam.', 'img/mekan-coban-mustafa-hamami.jpg', "1523 yılında Çoban Mustafa Paşa tarafından yaptırılan, halk arasında Çarşı Hamamı ve Çifte Hamam olarak da bilinen tarihi hamam; iki kubbesi ve geniş bir sarnıcıyla günümüzde de hizmet vermeye devam etmektedir."],
            ['bi-water', 'Eskihisar Sahili', "İzmit Körfezi'ne nazır yürüyüş ve dinlenme alanı.", 'img/mekan-eskihisar-sahili.jpg', "Kale eteklerinde, İzmit Körfezi'ne nazır yaklaşık 2 kilometre uzunluğunda bir sahil şeridi olan Eskihisar Sahili, özellikle yaz akşamları keyifli bir yürüyüş güzergahı sunmaktadır."],
        ];
        $gbTyEkle = $pdo->prepare("INSERT INTO tarihi_yerler (ikon, baslik, kisa_aciklama, resim_url, detay, sira) VALUES (:ikon, :baslik, :kisa, :resim, :detay, :sira)");
        foreach ($gbTarihiYerler as $gbSira => $gbYer) {
            $gbTyEkle->execute(['ikon' => $gbYer[0], 'baslik' => $gbYer[1], 'kisa' => $gbYer[2], 'resim' => $gbYer[3], 'detay' => $gbYer[4], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Hizmet kartları (hizmetler.php).
 * Önceden sabit bir PHP dizisiydi; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS hizmet_kartlari (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ikon VARCHAR(50) NOT NULL DEFAULT 'bi-grid-fill',
        resim_url VARCHAR(255) NOT NULL DEFAULT '',
        baslik VARCHAR(150) NOT NULL,
        ozet VARCHAR(255) NOT NULL DEFAULT '',
        detay TEXT NOT NULL,
        link VARCHAR(255) NOT NULL DEFAULT '',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbHizmetSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM hizmet_kartlari")->fetch()['toplam'];
    if ($gbHizmetSayisi === 0) {
        $gbHizmetler = [
            ['ikon' => 'bi-heart-fill', 'gorsel' => 'img/nikah-salonu.jpg', 'baslik' => 'Nikah İşlemleri', 'ozet' => 'Online randevu ve başvuru işlemleri', 'detay' => 'Nikah salonumuz 150 kişilik kapasiteye sahiptir. Başvuru için nüfus cüzdanı, 4 adet vesikalık fotoğraf ve sağlık raporu gereklidir.', 'link' => 'hizmetler/nikah-islemleri.php'],
            ['ikon' => 'bi-cone-striped', 'gorsel' => 'img/fen-isleri.jpeg', 'baslik' => 'Fen İşleri', 'ozet' => 'Yol, altyapı ve bakım çalışmaları', 'detay' => 'Yol yapım/onarım, altyapı, kaldırım düzenleme, park ve aydınlatma çalışmaları ile arıza/şikayet bildirimi bu birim tarafından yürütülür.', 'link' => 'hizmetler/fen-isleri.php'],
            ['ikon' => 'bi-shield-check', 'gorsel' => 'img/zabita.jpg', 'baslik' => 'Zabıta', 'ozet' => 'Şikayet ve ihbar bildirimi', 'detay' => 'İşgal, gürültü, seyyar satıcı ve trafik denetimleri ile ilgili şikayet ve ihbarlarınızı 7/24 zabıta hattımıza iletebilirsiniz.', 'link' => 'hizmetler/zabita.php'],
            ['ikon' => 'bi-house-door-fill', 'gorsel' => 'img/emlak-istimlak.jpg', 'baslik' => 'Emlak & İstimlak', 'ozet' => 'İmar durumu ve harita bilgileri', 'detay' => 'İmar durumu belgesi, kamulaştırma, yapı ruhsatı ve iskan işlemleri bu birim tarafından yürütülür.', 'link' => 'hizmetler/emlak-istimlak.php'],
            ['ikon' => 'bi-trash-fill', 'gorsel' => 'img/temizlik-isleri.jpg', 'baslik' => 'Temizlik İşleri', 'ozet' => 'Çöp toplama ve çevre temizliği', 'detay' => 'İlçemizde düzenli çöp toplama, cadde/sokak temizliği ve geri dönüşüm çalışmaları Temizlik İşleri Müdürlüğümüz tarafından yürütülmektedir. Toplama saatleri mahalleye göre değişiklik gösterebilir.', 'link' => 'hizmetler/temizlik-isleri.php'],
            ['ikon' => 'bi-palette-fill', 'gorsel' => 'img/kultur-sosyal-isler.jpg', 'baslik' => 'Kültür ve Sosyal İşler', 'ozet' => 'Kültürel etkinlikler ve sosyal projeler', 'detay' => 'Konserler, sergiler, kurs ve atölyeler ile sosyal yardım projeleri Kültür ve Sosyal İşler Müdürlüğümüz koordinasyonunda gerçekleştirilir. Güncel etkinlikler için Etkinlikler sayfamızı takip edebilirsiniz.', 'link' => 'hizmetler/kultur-sosyal-isler.php'],
            ['ikon' => 'bi-heart-pulse-fill', 'gorsel' => 'img/veteriner-hizmetleri.jpg', 'baslik' => 'Veteriner Hizmetleri', 'ozet' => 'Sokak hayvanları ve hayvan sağlığı', 'detay' => 'Sokak hayvanlarının aşılanması, kısırlaştırılması ve bakımı ile ilgili hizmetler Veteriner İşleri Müdürlüğümüz tarafından yürütülür. Yaralı/hasta hayvan ihbarları için bize ulaşabilirsiniz.', 'link' => 'hizmetler/veteriner-hizmetleri.php'],
            ['ikon' => 'bi-cash-coin', 'gorsel' => 'img/mali-hizmetler.jpg', 'baslik' => 'Mali Hizmetler', 'ozet' => 'Vergi ve borç ödeme işlemleri', 'detay' => 'Emlak vergisi, çevre temizlik vergisi ve diğer belediye borçlarınızla ilgili bilgi ve ödeme seçenekleri için E-Belediye sayfamızı ziyaret edebilirsiniz.', 'link' => 'e-belediye.php?panel=hizmet'],
        ];
        $gbHkEkle = $pdo->prepare("INSERT INTO hizmet_kartlari (ikon, resim_url, baslik, ozet, detay, link, sira) VALUES (:ikon, :resim, :baslik, :ozet, :detay, :link, :sira)");
        foreach ($gbHizmetler as $gbSira => $gbH) {
            $gbHkEkle->execute(['ikon' => $gbH['ikon'], 'resim' => $gbH['gorsel'], 'baslik' => $gbH['baslik'], 'ozet' => $gbH['ozet'], 'detay' => $gbH['detay'], 'link' => $gbH['link'], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Belediye Meclisi Üyeleri (belediye-meclisi.php).
 * Önceden sabit bir PHP dizisiydi; artık veritabanından geliyor, admin panelinden
 * üye eklenip çıkarılabiliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS meclis_uyeleri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad_soyad VARCHAR(150) NOT NULL,
        foto_url VARCHAR(255) NOT NULL DEFAULT '',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbMeclisSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM meclis_uyeleri")->fetch()['toplam'];
    if ($gbMeclisSayisi === 0) {
        $gbMeclisUyeleri = [
            ['Hasan SOBA', 'https://www.gebze.bel.tr/resim/20240418135605.jpg'],
            ['Mahmut YANDIK', 'https://www.gebze.bel.tr/resim/20240418135708.jpg'],
            ['Mustafa DEMİRHAN', 'https://www.gebze.bel.tr/resim/20240418135846.jpg'],
            ['Ömer Cihan KAVAK', 'https://www.gebze.bel.tr/resim/20240418135945.jpg'],
            ['Habibe ÇIRAK', 'https://www.gebze.bel.tr/resim/20240418140101.jpg'],
            ['Selim MALKOÇ', 'https://www.gebze.bel.tr/resim/20240418140213.jpg'],
            ['Mehmet Fatih İŞLEK', 'https://www.gebze.bel.tr/resim/20240418140329.jpg'],
            ['Talip DEMİR', 'https://www.gebze.bel.tr/resim/20240418140432.jpg'],
            ['Azim UYSAL', 'https://www.gebze.bel.tr/resim/20240418140531.jpg'],
            ['Efari BAHÇEVAN', 'https://www.gebze.bel.tr/resim/20240418140726.jpg'],
            ['Güler ŞAHİN GENCAY', 'https://www.gebze.bel.tr/resim/20240418140833.jpg'],
            ['Selamet GÜNER', 'https://www.gebze.bel.tr/resim/20240418140936.jpg'],
            ['Birgül TOKMAK', 'https://www.gebze.bel.tr/resim/20240418141040.jpg'],
            ['Mustafa DEMİR', 'https://www.gebze.bel.tr/resim/20240418141456.jpg'],
            ['Mustafa ÖNAL', 'https://www.gebze.bel.tr/resim/20240418141606.jpg'],
            ['Ayhan YILMAZ', 'https://www.gebze.bel.tr/resim/20240418141704.jpg'],
            ['Vasfiye AYDIN', 'https://www.gebze.bel.tr/resim/20240418141808.jpg'],
            ['Şener AKIN', 'https://www.gebze.bel.tr/resim/20240418141935.jpg'],
            ['Mehmet DİNÇ', 'https://www.gebze.bel.tr/resim/20240418142104.jpg'],
            ['Okan ŞEN', 'https://www.gebze.bel.tr/resim/20240418142214.jpg'],
            ['Halil AYTAÇ', 'https://www.gebze.bel.tr/resim/20240418142450.jpg'],
            ['Osman SEZER', 'https://www.gebze.bel.tr/resim/20240418142542.jpg'],
            ['Mustafa ATEŞ', 'https://www.gebze.bel.tr/resim/20240418142637.jpg'],
            ['Hasan ÖZDEMİR', 'https://www.gebze.bel.tr/resim/20240418142810.jpg'],
            ['Emrullah BİLGİN', 'https://www.gebze.bel.tr/resim/20240418143002.jpg'],
            ['Hüseyin ÖNDER', 'https://www.gebze.bel.tr/resim/20240418143104.jpg'],
            ['İrfan İRTEGÜN', 'https://www.gebze.bel.tr/resim/20240418143232.jpg'],
            ['Ahmet KADI', 'https://www.gebze.bel.tr/resim/20240418143831.jpg'],
            ['Gülcan AKSU', 'https://www.gebze.bel.tr/resim/20240418144018.jpg'],
            ['Engin SÖZBİR', 'https://www.gebze.bel.tr/resim/20240418144118.jpg'],
            ['Ferman TORUN', 'https://www.gebze.bel.tr/resim/20240418144248.jpg'],
            ['Nuran GÖKDEMİR', 'https://www.gebze.bel.tr/resim/20240418144356.jpg'],
            ['Birol ELÜSTÜ', 'https://www.gebze.bel.tr/resim/20240418144626.jpg'],
            ['Zeynep ASLAN ÇAPÇI', 'https://www.gebze.bel.tr/resim/20241008153016.png'],
            ['Yunus Umut AYDOĞDU', 'https://www.gebze.bel.tr/resim/20241008153116.png'],
            ['Hakan KAHRAMAN', 'https://www.gebze.bel.tr/resim/20240418145601.jpg'],
            ['Hüseyin KATI', 'https://www.gebze.bel.tr/resim/20260805120350.jpg'],
        ];
        $gbMuEkle = $pdo->prepare("INSERT INTO meclis_uyeleri (ad_soyad, foto_url, sira) VALUES (:ad, :foto, :sira)");
        foreach ($gbMeclisUyeleri as $gbSira => $gbUye) {
            $gbMuEkle->execute(['ad' => $gbUye[0], 'foto' => $gbUye[1], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Eski Başkanlar (eski-baskanlar.php).
 * Önceden sabit bir PHP dizisiydi; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS eski_baskanlar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        donem_grubu VARCHAR(20) NOT NULL,
        yil_araligi VARCHAR(50) NOT NULL,
        ad_soyad VARCHAR(150) NOT NULL,
        foto_url VARCHAR(255) NOT NULL DEFAULT '',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbEskiBaskanSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM eski_baskanlar")->fetch()['toplam'];
    if ($gbEskiBaskanSayisi === 0) {
        $gbEskiBaskanlar = [
            ["2000'ler", '2009-2019', 'Adnan Köşker', 'https://www.gebze.bel.tr/resim/20191118115843.jpg'],
            ["2000'ler", '2004-2009', 'İbrahim Pehlivan', 'https://www.gebze.bel.tr/resim/20191118115643.jpg'],
            ["1990'lar", '1994-2004', 'Ahmet Penbegüllü', 'https://www.gebze.bel.tr/resim/20191118115604.jpg'],
            ["1980'ler", '1989-1994', 'Mehmet Emin Akın', 'https://www.gebze.bel.tr/resim/20191118115501.jpg'],
            ["1980'ler", '1984-1987', 'Bülent Atasayan', 'https://www.gebze.bel.tr/resim/20191118115433.jpg'],
            ["1980'ler", '1980 (4 ay)', 'Bnb. Erol Sanver', 'https://www.gebze.bel.tr/resim/20191118115350.jpg'],
            ["1980'ler", '1980-1983', 'Kubilay İlgün', 'https://www.gebze.bel.tr/resim/20191118115301.jpg'],
            ["1970'ler", '1977-1980', 'Sedat Tüze', 'https://www.gebze.bel.tr/resim/20191118115154.jpg'],
            ["1970'ler", '1973-1977', 'Ziya Fırat', 'https://www.gebze.bel.tr/resim/20191118115126.jpg'],
            ["1960'lar", '1963-1973', 'Mehmet Üstündağ', 'https://www.gebze.bel.tr/resim/20191118115046.jpg'],
            ["1960'lar", '1960-1963', 'Selahattin Altaş', 'https://www.gebze.bel.tr/resim/20191118114956.jpg'],
            ["1950'ler", '1950-1960', 'Hüseyin Özgen', 'https://www.gebze.bel.tr/resim/20191118114923.jpg'],
            ["1940'lar", '1945-1950', 'Hayri Gökçen', 'https://www.gebze.bel.tr/resim/20191118114843.jpg'],
            ["1930'lar", '1939-1945', 'Esat Sayduk', 'https://www.gebze.bel.tr/resim/20191118114810.jpg'],
            ["1930'lar", '1935-1939', 'Ahmet Eldem', 'https://www.gebze.bel.tr/resim/20191118114728.jpg'],
            ["1930'lar", '1933-1935', 'Lütfü Bey', 'https://www.gebze.bel.tr/resim/20191118114620.jpg'],
            ["1930'lar", '1932-1933', 'Bekir Kandilci', 'https://www.gebze.bel.tr/resim/20191118114550.jpg'],
            ["1930'lar", '1930-1932', 'İsmail Artar', 'https://www.gebze.bel.tr/resim/20191118114433.jpg'],
            ["1920'ler", '1928-1930', 'Mustafa Zeki Toros', 'https://www.gebze.bel.tr/resim/20211227090335.jpg'],
            ["1920'ler", '1926-1928', 'Arif Çavuş Söğütlü', 'https://www.gebze.bel.tr/resim/20191118114246.jpg'],
            ["1920'ler", '1924-1926', 'A. Mashar Akifoğlu', 'https://www.gebze.bel.tr/resim/20191118114151.jpg'],
            ["1920'ler", '1923-1924', 'İzzet Bey', 'https://www.gebze.bel.tr/resim/20191118114059.jpg'],
            ["1920'ler", '1922-1923', 'Hacı Mehmet Bey', 'https://www.gebze.bel.tr/resim/20191118114019.jpg'],
            ["1920'ler", '1921-1922', 'Sandıkçı Hüseyin Efe', 'https://www.gebze.bel.tr/resim/20191118113915.jpg'],
            ["1920'ler", '1920-1921', 'Nazmi Çavuş', 'https://www.gebze.bel.tr/resim/20191118113841.jpg'],
            ["1910'lar", '1919-1920', 'Cerrah Apdullah Efendi', 'https://www.gebze.bel.tr/resim/20191118113705.jpg'],
            ["1910'lar", '1918-1919', 'Halil Akifoğlu', 'https://www.gebze.bel.tr/resim/20191118113626.jpg'],
            ["1910'lar", '1916-1918', 'Nalbant Kadir Usta', 'https://www.gebze.bel.tr/resim/20191118113557.jpg'],
            ["1910'lar", '1915-1916', 'Vodinalı Hafız Bey', 'https://www.gebze.bel.tr/resim/20191118113443.jpg'],
            ["1910'lar", '1914-1915', 'Hafız Ali Dönmez', 'https://www.gebze.bel.tr/resim/20191118113023.jpg'],
            ["1910'lar", '1911-1914', 'Sapcı Mehmet Çavuş', 'https://www.gebze.bel.tr/resim/20191118113357.jpg'],
        ];
        $gbEbEkle = $pdo->prepare("INSERT INTO eski_baskanlar (donem_grubu, yil_araligi, ad_soyad, foto_url, sira) VALUES (:grup, :yil, :ad, :foto, :sira)");
        foreach ($gbEskiBaskanlar as $gbSira => $gbEb) {
            $gbEbEkle->execute(['grup' => $gbEb[0], 'yil' => $gbEb[1], 'ad' => $gbEb[2], 'foto' => $gbEb[3], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Arabuluculuk Komisyonu (arabuluculuk-komisyonu.php).
 * Önceden sabit iki PHP dizisiydi (asıl/yedek); artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS arabuluculuk_uyeleri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tip ENUM('asil','yedek') NOT NULL DEFAULT 'asil',
        ad_soyad VARCHAR(150) NOT NULL,
        gorev VARCHAR(255) NOT NULL DEFAULT '',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbArabuluculukSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM arabuluculuk_uyeleri")->fetch()['toplam'];
    if ($gbArabuluculukSayisi === 0) {
        $gbArabuluculukUyeleri = [
            ['asil', 'Av. Murat Tunca', 'Başkan — Hukuk İşleri Müdürlüğü V.'],
            ['asil', 'Mustafa Karataş', 'İnsan Kaynakları ve Eğitim Müdür V.'],
            ['asil', 'İsmail Denk', 'Mali Hizmetler Müdür V.'],
            ['yedek', 'Av. Cahit Polat', 'Hukuk İşleri Müdürlüğü'],
            ['yedek', 'Av. Ebru Ünal', 'Hukuk İşleri Müdürlüğü'],
            ['yedek', 'Av. Gizem Özmete', 'Hukuk İşleri Müdürlüğü'],
            ['yedek', 'Av. Sümeyye Elif Pehlivan', 'Hukuk İşleri Müdürlüğü'],
            ['yedek', 'Av. Tarkan Demir', 'Hukuk İşleri Müdürlüğü'],
            ['yedek', 'Enes Fatih Gezen', 'Zabıta Memuru — İnsan Kaynakları ve Eğitim Müdürlüğü'],
            ['yedek', 'Berna Yılmaz', 'Tekniker — İnsan Kaynakları ve Eğitim Müdürlüğü'],
            ['yedek', 'Erkan Yakın', 'Bilgisayar İşletmeni — Mali Hizmetler Müdürlüğü'],
            ['yedek', 'Elvan Gülfidane', 'V.H.K.İ — Mali Hizmetler Müdürlüğü'],
        ];
        $gbAkEkle = $pdo->prepare("INSERT INTO arabuluculuk_uyeleri (tip, ad_soyad, gorev, sira) VALUES (:tip, :ad, :gorev, :sira)");
        foreach ($gbArabuluculukUyeleri as $gbSira => $gbAu) {
            $gbAkEkle->execute(['tip' => $gbAu[0], 'ad' => $gbAu[1], 'gorev' => $gbAu[2], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Etik Komisyonu Üyeleri (etik-komisyonu.php).
 * Önceden sabit bir tablo satırıydı; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS etik_komisyonu_uyeleri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad_soyad VARCHAR(150) NOT NULL,
        unvan VARCHAR(150) NOT NULL DEFAULT '',
        gorev VARCHAR(150) NOT NULL DEFAULT '',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbEtikSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM etik_komisyonu_uyeleri")->fetch()['toplam'];
    if ($gbEtikSayisi === 0) {
        $gbEtikUyeleri = [
            ['Ahmet Hüseyinçelebi', 'Başkan Yardımcısı', 'Komisyon Başkanı'],
        ];
        $gbEkEkle = $pdo->prepare("INSERT INTO etik_komisyonu_uyeleri (ad_soyad, unvan, gorev, sira) VALUES (:ad, :unvan, :gorev, :sira)");
        foreach ($gbEtikUyeleri as $gbSira => $gbE) {
            $gbEkEkle->execute(['ad' => $gbE[0], 'unvan' => $gbE[1], 'gorev' => $gbE[2], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Meclis Kararları (meclis-kararlari.php).
 * Önceden sabit bir PHP dizisiydi; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS meclis_kararlari (
        id INT AUTO_INCREMENT PRIMARY KEY,
        baslik VARCHAR(255) NOT NULL,
        aciklama VARCHAR(255) NOT NULL DEFAULT '',
        yil VARCHAR(4) NOT NULL,
        ay VARCHAR(2) NOT NULL,
        pdf_url VARCHAR(255) NOT NULL DEFAULT ''
    ) ENGINE=InnoDB");

    $gbKararSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM meclis_kararlari")->fetch()['toplam'];
    if ($gbKararSayisi === 0) {
        $gbKararlar = [
            ['Meclis Toplantısı: 4 Ağustos 2026', 'Karar Yayın Tarihi: 10 Ağustos 2026', '2026', '08', 'https://www.gebze.bel.tr/dosya/20260810150555.pdf'],
            ['Meclis Toplantısı: 2 Temmuz 2026', 'Karar Yayın Tarihi: 7 Temmuz 2026', '2026', '07', 'https://www.gebze.bel.tr/dosya/20260707140518.pdf'],
            ['Meclis Toplantısı: 2 Haziran 2026', 'Karar Yayın Tarihi: 5 Haziran 2026', '2026', '06', 'https://www.gebze.bel.tr/dosya/20260605100834.pdf'],
            ['Meclis Toplantısı: 5-8 Mayıs 2026', 'Karar Yayın Tarihi: 11 Mayıs 2026', '2026', '05', 'https://www.gebze.bel.tr/dosya/20260511172544.pdf'],
            ['Meclis Toplantısı: 2 Nisan 2026', 'Karar Yayın Tarihi: 9 Nisan 2026', '2026', '04', 'https://www.gebze.bel.tr/dosya/20260409153041.pdf'],
            ['Meclis Toplantısı: 3 Mart 2026', 'Karar Yayın Tarihi: 6 Mart 2026', '2026', '03', 'https://www.gebze.bel.tr/dosya/20260306085344.pdf'],
            ['Meclis Toplantısı: 3 Şubat 2026', 'Karar Yayın Tarihi: 6 Şubat 2026', '2026', '02', 'https://www.gebze.bel.tr/dosya/20260206102101.pdf'],
            ['Meclis Toplantısı: 6 Ocak 2026', 'Karar Yayın Tarihi: 9 Ocak 2026', '2026', '01', 'https://www.gebze.bel.tr/dosya/20260109100129.pdf'],
            ['Meclis Toplantısı: 2 Aralık 2025', 'Karar Yayın Tarihi: 11 Aralık 2025', '2025', '12', 'https://www.gebze.bel.tr/dosya/20251211092144.pdf'],
            ['Meclis Toplantısı: 4 Kasım 2025', 'Karar Yayın Tarihi: 7 Kasım 2025', '2025', '11', 'https://www.gebze.bel.tr/dosya/20251107164817.pdf'],
        ];
        $gbMkEkle = $pdo->prepare("INSERT INTO meclis_kararlari (baslik, aciklama, yil, ay, pdf_url) VALUES (:baslik, :aciklama, :yil, :ay, :pdf)");
        foreach ($gbKararlar as $gbK) {
            $gbMkEkle->execute(['baslik' => $gbK[0], 'aciklama' => $gbK[1], 'yil' => $gbK[2], 'ay' => $gbK[3], 'pdf' => $gbK[4]]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Kurumsal Raporlar (kurumsal-raporlar.php).
 * Önceden sabit bir PHP dizisiydi; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS kurumsal_raporlar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        baslik VARCHAR(255) NOT NULL,
        yayin_tarihi VARCHAR(20) NOT NULL DEFAULT '',
        pdf_url VARCHAR(255) NOT NULL DEFAULT '',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbRaporSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM kurumsal_raporlar")->fetch()['toplam'];
    if ($gbRaporSayisi === 0) {
        $gbRaporlar = [
            ['2025 Mali Yılı İdare Faaliyet Raporu', '30.04.2026', 'https://www.gebze.bel.tr/dosya/20260430103244.pdf'],
            ['2024 Mali Yılı İdare Faaliyet Raporu', '18.04.2025', 'https://www.gebze.bel.tr/dosya/20250418112541.pdf'],
            ['2023 Mali Yılı İdare Faaliyet Raporu', '26.04.2024', 'https://www.gebze.bel.tr/dosya/20240426164245.pdf'],
            ['2022 Mali Yılı İdare Faaliyet Raporu', '20.04.2023', ''],
            ['2021 Mali Yılı İdare Faaliyet Raporu', '18.04.2022', 'https://www.gebze.bel.tr/dosya/20220418122036.pdf'],
        ];
        $gbKrEkle = $pdo->prepare("INSERT INTO kurumsal_raporlar (baslik, yayin_tarihi, pdf_url, sira) VALUES (:baslik, :tarih, :pdf, :sira)");
        foreach ($gbRaporlar as $gbSira => $gbR) {
            $gbKrEkle->execute(['baslik' => $gbR[0], 'tarih' => $gbR[1], 'pdf' => $gbR[2], 'sira' => $gbSira]);
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Faaliyet Alanları (faaliyet-alanlari.php).
 * Önceden sabit bir PHP dizisiydi (9 kategori, ~38 öge); artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS faaliyet_kategorileri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        anahtar VARCHAR(60) NOT NULL UNIQUE,
        baslik VARCHAR(150) NOT NULL,
        ikon VARCHAR(50) NOT NULL DEFAULT 'bi-grid-fill',
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS faaliyet_ogeleri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kategori_id INT NOT NULL,
        baslik VARCHAR(255) NOT NULL,
        href VARCHAR(500) NOT NULL DEFAULT '',
        img VARCHAR(500) NOT NULL DEFAULT '',
        detay MEDIUMTEXT NULL,
        ic_baglanti TINYINT(1) NOT NULL DEFAULT 0,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbFaaliyetKatSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM faaliyet_kategorileri")->fetch()['toplam'];
    if ($gbFaaliyetKatSayisi === 0 && file_exists(__DIR__ . '/faaliyet-seed-data.php')) {
        $gbFaaliyetVeri = require __DIR__ . '/faaliyet-seed-data.php';
        $gbFkEkle = $pdo->prepare("INSERT INTO faaliyet_kategorileri (anahtar, baslik, ikon, sira) VALUES (:anahtar, :baslik, :ikon, :sira)");
        $gbFoEkle = $pdo->prepare(
            "INSERT INTO faaliyet_ogeleri (kategori_id, baslik, href, img, detay, ic_baglanti, sira)
             VALUES (:kategori_id, :baslik, :href, :img, :detay, :ic, :sira)"
        );
        $gbFaaliyetKatSira = 0;
        foreach ($gbFaaliyetVeri as $gbAnahtar => $gbKat) {
            $gbFkEkle->execute([
                'anahtar' => $gbAnahtar,
                'baslik' => $gbKat['label'],
                'ikon' => $gbKat['icon'],
                'sira' => $gbFaaliyetKatSira,
            ]);
            $gbKategoriId = (int)$pdo->lastInsertId();
            $gbFaaliyetOgeSira = 0;
            foreach ($gbKat['ogeler'] as $gbOge) {
                $gbFoEkle->execute([
                    'kategori_id' => $gbKategoriId,
                    'baslik' => $gbOge['baslik'],
                    'href' => $gbOge['href'] ?? '',
                    'img' => $gbOge['img'] ?? '',
                    'detay' => $gbOge['detay'] ?? null,
                    'ic' => !empty($gbOge['ic']) ? 1 : 0,
                    'sira' => $gbFaaliyetOgeSira,
                ]);
                $gbFaaliyetOgeSira++;
            }
            $gbFaaliyetKatSira++;
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: E-Belediye (e-belediye.php ve 4 alt sayfası).
 * Önceden her sayfada sabit bir PHP dizisiydi; artık veritabanından geliyor.
 * Not: Bazı hizmetler (örn. e-Beyan, İmar Durumu Başvurusu, SENDE) gerçek sitedeki
 * gibi çok adımlı bir açılır pencere (modal) ile çalışır; bu hizmetlerin "ozel_hedef"
 * alanı doludur ve modal içeriği ilgili sayfada sabit kalır (admin panelinden sadece
 * başlık/ikon/sıra/silme yönetilebilir, modal içeriği admin dışıdır).
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS e_belediye_kategoriler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        anahtar VARCHAR(60) NOT NULL UNIQUE,
        baslik VARCHAR(150) NOT NULL,
        ikon VARCHAR(50) NOT NULL DEFAULT 'bi-grid-fill',
        aciklama VARCHAR(255) NOT NULL DEFAULT '',
        hedef_sayfa VARCHAR(100) NOT NULL,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS e_belediye_bolumler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kategori_id INT NOT NULL,
        baslik VARCHAR(150) NOT NULL,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS e_belediye_hizmetleri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        bolum_id INT NOT NULL,
        ikon VARCHAR(50) NOT NULL DEFAULT 'bi-info-circle-fill',
        baslik VARCHAR(255) NOT NULL,
        href VARCHAR(500) NULL,
        ozel_hedef VARCHAR(100) NULL,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbEbKatSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM e_belediye_kategoriler")->fetch()['toplam'];
    if ($gbEbKatSayisi === 0 && file_exists(__DIR__ . '/e-belediye-seed-data.php')) {
        $gbEbSeed = require __DIR__ . '/e-belediye-seed-data.php';

        $gbEbKatEkle = $pdo->prepare(
            "INSERT INTO e_belediye_kategoriler (anahtar, baslik, ikon, aciklama, hedef_sayfa, sira)
             VALUES (:anahtar, :baslik, :ikon, :aciklama, :hedef, :sira)"
        );
        $gbEbBolumEkle = $pdo->prepare("INSERT INTO e_belediye_bolumler (kategori_id, baslik, sira) VALUES (:kategori_id, :baslik, :sira)");
        $gbEbHizmetEkle = $pdo->prepare(
            "INSERT INTO e_belediye_hizmetleri (bolum_id, ikon, baslik, href, ozel_hedef, sira)
             VALUES (:bolum_id, :ikon, :baslik, :href, :ozel, :sira)"
        );

        $gbEbKatSira = 0;
        foreach ($gbEbSeed['e_belediye'] as $gbKat) {
            // $gbKat = [ikon, baslik, aciklama, hedef_sayfa, sayi(kullanılmıyor - artık dinamik hesaplanıyor)]
            $gbHedefSayfa = $gbKat[3];
            $gbAnahtar = preg_replace('/\.php$/', '', $gbHedefSayfa);
            $gbEbKatEkle->execute([
                'anahtar' => $gbAnahtar,
                'baslik' => $gbKat[1],
                'ikon' => $gbKat[0],
                'aciklama' => $gbKat[2],
                'hedef' => $gbHedefSayfa,
                'sira' => $gbEbKatSira,
            ]);
            $gbKategoriId = (int)$pdo->lastInsertId();

            if (isset($gbEbSeed[$gbAnahtar])) {
                $gbEbBolumSira = 0;
                foreach ($gbEbSeed[$gbAnahtar] as $gbBolumBaslik => $gbOgeler) {
                    $gbEbBolumEkle->execute([
                        'kategori_id' => $gbKategoriId,
                        'baslik' => $gbBolumBaslik,
                        'sira' => $gbEbBolumSira,
                    ]);
                    $gbBolumId = (int)$pdo->lastInsertId();

                    $gbEbHizmetSira = 0;
                    foreach ($gbOgeler as $gbOge) {
                        // $gbOge = [ikon, baslik, href-veya-null, ozel_hedef(varsa)]
                        $gbEbHizmetEkle->execute([
                            'bolum_id' => $gbBolumId,
                            'ikon' => $gbOge[0],
                            'baslik' => $gbOge[1],
                            'href' => $gbOge[2] ?? null,
                            'ozel' => $gbOge[3] ?? null,
                            'sira' => $gbEbHizmetSira,
                        ]);
                        $gbEbHizmetSira++;
                    }
                    $gbEbBolumSira++;
                }
            }
            $gbEbKatSira++;
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Kardeş Şehirler (kardes-sehirler.php) ve
 * Üye Olduğumuz Birlikler (uye-birlikler.php).
 * Önceden her ikisi de sabit birer PHP dizisiydi; artık veritabanından geliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS kardes_sehirler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tur ENUM('ici','disi') NOT NULL,
        ad VARCHAR(150) NOT NULL,
        il VARCHAR(100) NULL,
        sehir VARCHAR(100) NULL,
        ulke VARCHAR(100) NULL,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS uye_birlikler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad VARCHAR(255) NOT NULL,
        url VARCHAR(500) NULL,
        sira INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $gbKsSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM kardes_sehirler")->fetch()['toplam'];
    $gbUbSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM uye_birlikler")->fetch()['toplam'];

    if (($gbKsSayisi === 0 || $gbUbSayisi === 0) && file_exists(__DIR__ . '/gebze-baglantilar-seed-data.php')) {
        $gbGbSeed = require __DIR__ . '/gebze-baglantilar-seed-data.php';

        if ($gbKsSayisi === 0) {
            $gbKsEkle = $pdo->prepare(
                "INSERT INTO kardes_sehirler (tur, ad, il, sehir, ulke, sira) VALUES (:tur, :ad, :il, :sehir, :ulke, :sira)"
            );
            $gbKsSira = 0;
            foreach ($gbGbSeed['kardes_yurt_ici'] as $gbKs) {
                // $gbKs = [ad, il]
                $gbKsEkle->execute(['tur' => 'ici', 'ad' => $gbKs[0], 'il' => $gbKs[1], 'sehir' => null, 'ulke' => null, 'sira' => $gbKsSira]);
                $gbKsSira++;
            }
            $gbKsSira = 0;
            foreach ($gbGbSeed['kardes_yurt_disi'] as $gbKs) {
                // $gbKs = [ad, sehir, ulke]
                $gbKsEkle->execute(['tur' => 'disi', 'ad' => $gbKs[0], 'il' => null, 'sehir' => $gbKs[1], 'ulke' => $gbKs[2], 'sira' => $gbKsSira]);
                $gbKsSira++;
            }
        }

        if ($gbUbSayisi === 0) {
            $gbUbEkle = $pdo->prepare("INSERT INTO uye_birlikler (ad, url, sira) VALUES (:ad, :url, :sira)");
            $gbUbSira = 0;
            foreach ($gbGbSeed['uye_birlikler'] as $gbUb) {
                // $gbUb = [ad, url-veya-null]
                $gbUbEkle->execute(['ad' => $gbUb[0], 'url' => $gbUb[1] ?? null, 'sira' => $gbUbSira]);
                $gbUbSira++;
            }
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Ana sayfa "Hero" slaytları.
 * Önceden anasayfanın en üstündeki slayt (carousel) resimleri, başlıkları
 * ve açıklamaları index.php içine sabit yazılıydı; artık veritabanından
 * geliyor ve admin panelinden resim ekle/çıkar/düzenle yapılabiliyor.
 * Başlıktaki vurgulu kelime (ör. "Geleceğine") ayrı bir sütunda tutulur ki
 * sayfada aynı şekilde renkli/vurgulu gösterilebilsin.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS hero_slaytlari (
        id INT AUTO_INCREMENT PRIMARY KEY,
        resim_url VARCHAR(500) NOT NULL,
        baslik_on VARCHAR(255) NOT NULL DEFAULT '',
        baslik_vurgu VARCHAR(255) NOT NULL DEFAULT '',
        baslik_son VARCHAR(255) NOT NULL DEFAULT '',
        aciklama TEXT NULL,
        sira INT NOT NULL DEFAULT 0,
        aktif TINYINT(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB");

    $gbHeroSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM hero_slaytlari")->fetch()['toplam'];

    if ($gbHeroSayisi === 0) {
        $gbHeroSeed = [
            ['img/hero-1.jpg', "Gebze'nin ", 'Geleceğine', ' Birlikte Yön Veriyoruz', 'Şeffaf, katılımcı ve çözüm odaklı belediyecilik anlayışıyla her mahallemize eşit hizmet götürüyoruz.'],
            ['img/hero-2.jpg', 'Tarihi ve Kültürel ', 'Mirasımız', '', "Eskihisar Kalesi'nden Osman Hamdi Bey Müzesi'ne, Gebze'nin zengin tarihini keşfedin."],
            ['img/hero-3.jpg', 'Kültür ve Sanat ', 'Etkinliklerimiz', '', 'Konserler, sergiler ve daha fazlası için güncel etkinlik takvimimizi takip edin.'],
            ['img/hero-4.jpg', 'Yeşil ve ', 'Yaşanabilir', ' Bir Kent', "Parklarımız, millet bahçelerimiz ve yeşil alan projelerimizle Gebze'yi geleceğe taşıyoruz."],
        ];
        $gbHeroEkle = $pdo->prepare(
            "INSERT INTO hero_slaytlari (resim_url, baslik_on, baslik_vurgu, baslik_son, aciklama, sira)
             VALUES (:resim_url, :baslik_on, :baslik_vurgu, :baslik_son, :aciklama, :sira)"
        );
        $gbHeroSira = 0;
        foreach ($gbHeroSeed as $gbHs) {
            $gbHeroEkle->execute([
                'resim_url' => $gbHs[0],
                'baslik_on' => $gbHs[1],
                'baslik_vurgu' => $gbHs[2],
                'baslik_son' => $gbHs[3],
                'aciklama' => $gbHs[4],
                'sira' => $gbHeroSira,
            ]);
            $gbHeroSira++;
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Site iletişim bilgileri (Adres/Telefon/E-posta).
 * Önceden iletisim.php ve includes/footer.php içinde birbirinden FARKLI sabit
 * değerler olarak yazılıydı. Artık tek bir "site_ayarlari" tablosundan (tek
 * satır, id=1) geliyor ve admin panelindeki tek ayar sayfasından güncelleniyor.
 * Başlangıç değeri olarak footer'daki daha güncel/detaylı bilgiler kullanıldı;
 * hatalıysa admin panelinden kolayca düzeltilebilir.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_ayarlari (
        id INT PRIMARY KEY,
        adres VARCHAR(500) NOT NULL DEFAULT '',
        telefon VARCHAR(100) NOT NULL DEFAULT '',
        eposta VARCHAR(255) NOT NULL DEFAULT ''
    ) ENGINE=InnoDB");

    $gbSiteSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM site_ayarlari")->fetch()['toplam'];

    if ($gbSiteSayisi === 0) {
        $gbSiteEkle = $pdo->prepare("INSERT INTO site_ayarlari (id, adres, telefon, eposta) VALUES (1, :adres, :telefon, :eposta)");
        $gbSiteEkle->execute([
            'adres' => 'Güzeller Mah. Bahar Cad. No:1, 41400 Gebze/Kocaeli',
            'telefon' => '+90 262 642 04 30',
            'eposta' => 'gebze@gebze.bel.tr',
        ]);
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Sosyal medya hesapları.
 * Önceden includes/footer.php içinde sabit ikon/link listesiydi; artık
 * veritabanından geliyor ve admin panelinden ekle/çıkar yapılabiliyor.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS sosyal_medya (
        id INT AUTO_INCREMENT PRIMARY KEY,
        platform VARCHAR(100) NOT NULL,
        ikon VARCHAR(100) NOT NULL,
        url VARCHAR(500) NOT NULL,
        sira INT NOT NULL DEFAULT 0,
        aktif TINYINT(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB");

    $gbSosyalSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM sosyal_medya")->fetch()['toplam'];

    if ($gbSosyalSayisi === 0) {
        $gbSosyalSeed = [
            ['WhatsApp', 'bi-whatsapp', 'https://wa.me/902626420430'],
            ['Facebook', 'bi-facebook', 'https://www.facebook.com/gebzebelediye'],
            ['Twitter / X', 'bi-twitter-x', 'https://twitter.com/gebze_belediye'],
            ['Instagram', 'bi-instagram', 'https://www.instagram.com/gebze_belediyesi'],
            ['YouTube', 'bi-youtube', 'https://www.youtube.com/@gebzebelediyesi7295'],
        ];
        $gbSosyalEkle = $pdo->prepare("INSERT INTO sosyal_medya (platform, ikon, url, sira) VALUES (:platform, :ikon, :url, :sira)");
        $gbSosyalSira = 0;
        foreach ($gbSosyalSeed as $gbSm) {
            $gbSosyalEkle->execute([
                'platform' => $gbSm[0],
                'ikon' => $gbSm[1],
                'url' => $gbSm[2],
                'sira' => $gbSosyalSira,
            ]);
            $gbSosyalSira++;
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}

/**
 * Otomatik veritabanı geçişi: Mahalle Muhtarları.
 * Önceden muhtarlar.php içinde 40 kaydı barındıran sabit bir PHP dizisiydi;
 * artık veritabanından geliyor ve admin panelinden ekle/çıkar/düzenle
 * yapılabiliyor. İlk kurulum verisi, mevcut sabit diziden birebir alınarak
 * config/muhtarlar-seed-data.php dosyasına taşınmıştır.
 */
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS muhtarlar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad VARCHAR(150) NOT NULL,
        mahalle VARCHAR(150) NOT NULL,
        tel VARCHAR(50) NOT NULL,
        eposta VARCHAR(255) NULL,
        adres VARCHAR(500) NULL,
        harita VARCHAR(500) NULL,
        foto VARCHAR(255) NULL,
        sira INT NOT NULL DEFAULT 0,
        aktif TINYINT(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB");

    $gbMuhtarSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM muhtarlar")->fetch()['toplam'];

    if ($gbMuhtarSayisi === 0 && file_exists(__DIR__ . '/muhtarlar-seed-data.php')) {
        $gbMuhtarSeed = require __DIR__ . '/muhtarlar-seed-data.php';
        $gbMuhtarEkle = $pdo->prepare(
            "INSERT INTO muhtarlar (ad, mahalle, tel, eposta, adres, harita, foto, sira)
             VALUES (:ad, :mahalle, :tel, :eposta, :adres, :harita, :foto, :sira)"
        );
        $gbMuhtarSira = 0;
        foreach ($gbMuhtarSeed as $gbMh) {
            $gbMuhtarEkle->execute([
                'ad' => $gbMh['ad'],
                'mahalle' => $gbMh['mahalle'],
                'tel' => $gbMh['tel'],
                'eposta' => $gbMh['eposta'] ?? null,
                'adres' => $gbMh['adres'] ?? null,
                'harita' => $gbMh['harita'] ?? null,
                'foto' => $gbMh['foto'] ?? null,
                'sira' => $gbMuhtarSira,
            ]);
            $gbMuhtarSira++;
        }
    }
} catch (PDOException $gbHata) {
    // sessizce geç
}
