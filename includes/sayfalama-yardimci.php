<?php
/**
 * admin/includes/sayfalama-yardimci.php
 * -------------------------------------------------------
 * Yönetim listelerinde (haberler, projeler, duyurular vb.) 20'den fazla
 * kayıt olduğunda sayfa numaraları göstermek için ortak yardımcı fonksiyon.
 * admin-baslangic.php tarafından otomatik include edilir; ayrıca elle
 * require etmeye gerek yoktur.
 * -------------------------------------------------------
 */

const ADMIN_SAYFA_BOYUTU = 20;

/**
 * Bir listeleme sayfası için o anki (GET'ten okunmuş, 1'den küçük olamayan)
 * sayfa numarasını döndürür.
 */
function adminMevcutSayfa(): int {
    $sayfa = (int)($_GET['sayfa'] ?? 1);
    return $sayfa < 1 ? 1 : $sayfa;
}

/**
 * Toplam kayıt sayısından toplam sayfa sayısını hesaplar (en az 1).
 */
function adminToplamSayfa(int $toplamKayit, int $sayfaBoyutu = ADMIN_SAYFA_BOYUTU): int {
    return max(1, (int)ceil($toplamKayit / $sayfaBoyutu));
}

/**
 * Sayfa numaralarını (yuvarlak, Kurumsal/Etkinlikler sayfalarındaki genel
 * sitedeki tasarımla uyumlu) bir <nav> olarak ekrana basar. Toplam sayfa
 * 1 veya daha azsa hiçbir şey basmaz (sayfalamaya gerek yoksa görünmez).
 *
 * $ekParametreler: mevcut arama/filtre gibi GET parametrelerini (sayfa
 * hariç) linklerde korumak için, örn. ['q' => $arama].
 */
function adminSayfalamaCiz(int $mevcutSayfa, int $toplamSayfa, array $ekParametreler = []): void {
    if ($toplamSayfa <= 1) {
        return;
    }

    $linkOlustur = function (int $sayfaNo) use ($ekParametreler): string {
        $params = $ekParametreler;
        $params['sayfa'] = $sayfaNo;
        return '?' . http_build_query($params);
    };

    // Çok sayıda sayfa olduğunda hepsini basmak yerine, mevcut sayfanın
    // etrafındaki birkaç sayfa + ilk/son sayfa gösterilir, aradakiler "..." ile kısaltılır.
    $gosterilecekler = [];
    for ($s = 1; $s <= $toplamSayfa; $s++) {
        if ($s === 1 || $s === $toplamSayfa || abs($s - $mevcutSayfa) <= 2) {
            $gosterilecekler[] = $s;
        }
    }

    echo '<nav aria-label="Sayfalama"><ul class="pagination admin-sayfalama justify-content-center mb-0">';

    echo '<li class="page-item' . ($mevcutSayfa <= 1 ? ' disabled' : '') . '">';
    echo '<a class="page-link rounded-circle mx-1" href="' . htmlspecialchars($linkOlustur(max(1, $mevcutSayfa - 1))) . '" aria-label="Önceki"><i class="bi bi-chevron-left"></i></a></li>';

    $oncekiSayfa = 0;
    foreach ($gosterilecekler as $s) {
        if ($oncekiSayfa !== 0 && $s - $oncekiSayfa > 1) {
            echo '<li class="page-item disabled"><span class="page-link border-0 bg-transparent">&hellip;</span></li>';
        }
        echo '<li class="page-item' . ($s === $mevcutSayfa ? ' active' : '') . '">';
        echo '<a class="page-link rounded-circle mx-1" href="' . htmlspecialchars($linkOlustur($s)) . '">' . $s . '</a></li>';
        $oncekiSayfa = $s;
    }

    echo '<li class="page-item' . ($mevcutSayfa >= $toplamSayfa ? ' disabled' : '') . '">';
    echo '<a class="page-link rounded-circle mx-1" href="' . htmlspecialchars($linkOlustur(min($toplamSayfa, $mevcutSayfa + 1))) . '" aria-label="Sonraki"><i class="bi bi-chevron-right"></i></a></li>';

    echo '</ul></nav>';
}
