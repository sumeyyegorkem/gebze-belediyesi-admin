<?php
/**
 * includes/hata-yakalayici.php
 * -------------------------------------------------------
 * Sitedeki HER sayfa, config/db.php'yi kendi başında "require" eder; bu dosya
 * da tam olarak db.php'nin en başında bir kez require edildiği için, fiilen
 * tüm siteyi kapsamış olur.
 *
 * Amacı: kodda unutulan bir hata, tanımsız bir değişken, ya da veritabanı
 * bağlantı sorunu gibi beklenmedik bir PHP hatası oluştuğunda, ziyaretçiye
 * ham/teknik PHP hata mesajı (dosya yolları, sorgu metni vb. sızdırabilecek
 * bilgiler içerebilir) göstermek yerine 500.php'deki düzgün ekranı göstermek,
 * ve hatanın ne olduğunu arka planda (config/hata-kayitlari.log) kayıt altına
 * almak — böylece siz sorunu daha sonra o log dosyasından görebilirsiniz.
 * -------------------------------------------------------
 */

// Ham PHP hata/uyarı mesajları ziyaretçiye ASLA doğrudan gösterilmesin
// (hem güvenlik hem görünüm açısından). Teknik detay sadece log dosyasına yazılır.
ini_set('display_errors', '0');
error_reporting(E_ALL);

// O ana kadar üretilmiş olabilecek yarım/bozuk çıktıyı, bir hata durumunda
// silip yerine temiz 500 sayfasını koyabilmek için çıktıyı arabelleğe alıyoruz.
if (ob_get_level() === 0) {
    ob_start();
}

if (!function_exists('gbHataGoster')) {
    /**
     * Yakalanan bir hatayı log dosyasına yazar ve 500 sayfasını göstererek
     * sayfa çalışmasını sonlandırır. config/db.php içindeki veritabanı
     * bağlantı hatasından da bilerek çağrılabilir.
     */
    function gbHataGoster(string $detay = ''): void {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        if (!headers_sent()) {
            http_response_code(500);
        }
        if ($detay !== '') {
            $satir = '[' . date('Y-m-d H:i:s') . '] ' . $detay . "\n";
            @file_put_contents(__DIR__ . '/../config/hata-kayitlari.log', $satir, FILE_APPEND | LOCK_EX);
        }
        require __DIR__ . '/../500.php';
        exit;
    }
}

// Yakalanmamış (try/catch içine alınmamış) bir Exception/Error fırlatılırsa devreye girer.
set_exception_handler(function (Throwable $e): void {
    gbHataGoster('Yakalanmamış hata: ' . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ')');
});

// PHP'nin kendisinin durdurduğu "ölümcül" (fatal) hatalarda devreye girer
// (örn. tanımsız bir fonksiyon çağrısı, sözdizimi hatası içeren bir dosyanın
// dahil edilmesi gibi durumlar try/catch ile yakalanamaz).
register_shutdown_function(function (): void {
    $hata = error_get_last();
    $olumculTurler = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
    if ($hata !== null && in_array($hata['type'], $olumculTurler, true)) {
        gbHataGoster('Ölümcül hata: ' . $hata['message'] . ' (' . $hata['file'] . ':' . $hata['line'] . ')');
    }
});
