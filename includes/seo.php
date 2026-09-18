<?php
/**
 * includes/seo.php
 * -------------------------------------------------------
 * SEO başlığı ve açıklaması oluşturmak için yardımcı fonksiyonlar.
 * Admin panelinde bir haber/duyuru/etkinlik/proje için "SEO Başlık"
 * veya "SEO Açıklama" alanı doldurulmuşsa onlar kullanılır; boş
 * bırakılmışsa içeriğin kendi başlık/özetinden otomatik olarak
 * uygun uzunlukta bir SEO metni türetilir.
 * -------------------------------------------------------
 */

if (!function_exists('seoBaslikOlustur')) {
    function seoBaslikOlustur($varsayilanBaslik, $seoBaslik = '') {
        $baslik = !empty($seoBaslik) ? $seoBaslik : $varsayilanBaslik;
        return trim($baslik) . ' | Gebze Belediyesi';
    }
}

if (!function_exists('seoAciklamaOlustur')) {
    function seoAciklamaOlustur($varsayilanAciklama, $seoAciklama = '') {
        $aciklama = !empty($seoAciklama) ? $seoAciklama : $varsayilanAciklama;
        $aciklama = trim(preg_replace('/\s+/u', ' ', strip_tags((string)$aciklama)));

        if ($aciklama === '') {
            return 'Gebze Belediyesi resmi web sitesi.';
        }
        if (mb_strlen($aciklama, 'UTF-8') > 160) {
            $aciklama = mb_substr($aciklama, 0, 157, 'UTF-8') . '...';
        }
        return $aciklama;
    }
}
