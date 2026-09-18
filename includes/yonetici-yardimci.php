<?php
/**
 * admin/includes/yonetici-yardimci.php
 * -------------------------------------------------------
 * Yönetici (admin hesabı) kişiselleştirme ile ilgili ortak yardımcılar:
 * avatar renk paleti, avatar HTML üretimi (fotoğraf ya da baş harf) ve
 * renk seçici (swatch) formu. admin-baslangic.php tarafından otomatik
 * olarak dahil edilir; başka bir yerde ayrıca require etmeye gerek yoktur.
 * -------------------------------------------------------
 */

function adminAvatarRenkleri() {
    return [
        'mavi'     => 'Mavi',
        'yesil'    => 'Yeşil',
        'mor'      => 'Mor',
        'turuncu'  => 'Turuncu',
        'kirmizi'  => 'Kırmızı',
        'pembe'    => 'Pembe',
        'lacivert' => 'Lacivert',
        'altin'    => 'Altın',
    ];
}

/**
 * Bir yöneticinin avatarını (fotoğrafı varsa fotoğraf, yoksa renkli baş harf
 * dairesi) HTML olarak üretir. $cssClass, boyutu belirleyen temel sınıftır
 * (ör. admin-profil-avatar, admin-mini-avatar, admin-tablo-avatar).
 */
function adminAvatarHtml($adSoyad, $fotograf, $renk, $cssClass = 'admin-profil-avatar') {
    $renkAnahtari = preg_replace('/[^a-z]/', '', (string)($renk ?: 'mavi'));
    if (!array_key_exists($renkAnahtari, adminAvatarRenkleri())) {
        $renkAnahtari = 'mavi';
    }

    if (!empty($fotograf)) {
        $foto = (string)$fotograf;
        $src = (stripos($foto, 'http://') === 0 || stripos($foto, 'https://') === 0) ? $foto : '../' . $foto;
        return '<div class="' . $cssClass . ' admin-avatar-foto"><img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($adSoyad) . '"></div>';
    }

    $harf = mb_strtoupper(mb_substr((string)$adSoyad, 0, 1, 'UTF-8'), 'UTF-8');
    return '<div class="' . $cssClass . ' admin-avatar-' . $renkAnahtari . '">' . htmlspecialchars($harf) . '</div>';
}

/**
 * Renk seçici (swatch) radyo grubu üretir. $seciliRenk mevcut seçili renk,
 * $inputName form alanının "name" değeridir (varsayılan: avatar_rengi).
 */
function adminRenkSeciciHtml($seciliRenk, $inputName = 'avatar_rengi') {
    $seciliRenk = $seciliRenk ?: 'mavi';
    $html = '<div class="admin-renk-secici">';
    foreach (adminAvatarRenkleri() as $anahtar => $etiket) {
        $id = 'renk_' . $inputName . '_' . $anahtar;
        $secili = ($anahtar === $seciliRenk) ? ' checked' : '';
        $html .= '<input type="radio" name="' . htmlspecialchars($inputName) . '" id="' . htmlspecialchars($id) . '" value="' . htmlspecialchars($anahtar) . '"' . $secili . '>';
        $html .= '<label for="' . htmlspecialchars($id) . '" class="admin-avatar-' . htmlspecialchars($anahtar) . '" title="' . htmlspecialchars($etiket) . '"></label>';
    }
    $html .= '</div>';
    return $html;
}
