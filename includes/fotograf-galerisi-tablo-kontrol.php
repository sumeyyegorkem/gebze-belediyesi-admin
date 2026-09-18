<?php
/**
 * includes/fotograf-galerisi-tablo-kontrol.php
 * -------------------------------------------------------
 * "fotograf_galerisi" tablosunun var olduğundan ve güncel sütunlara sahip
 * olduğundan emin olur. Bu tablo iki farklı yerde kullanıldığı için
 * (includes/fotograf-galerisi-bolum.php ve gebze/kent-rehberi.php),
 * oluşturma/migrasyon kontrolü tekrar tekrar yazılmasın diye TEK YERDE
 * toplanmıştır — her ikisi de bu dosyayı "require" eder.
 * Kullanmadan önce $pdo bağlantısının hazır olması gerekir.
 * -------------------------------------------------------
 */
$pdo->exec("CREATE TABLE IF NOT EXISTS fotograf_galerisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(150) DEFAULT '',
    resim_url VARCHAR(255) NOT NULL,
    eklenme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
if ($pdo->query("SHOW COLUMNS FROM fotograf_galerisi LIKE 'sayfa'")->rowCount() === 0) {
    $pdo->exec("ALTER TABLE fotograf_galerisi ADD COLUMN sayfa VARCHAR(30) NOT NULL DEFAULT 'fotograflar' AFTER baslik");
}
