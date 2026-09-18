<?php
$aktifHizmet = $aktifHizmet ?? '';

// Not: bu menü hem hizmetler/ klasöründeki sayfalardan hem de kök dizindeki
// e-belediye.php'den (Mali Hizmetler aktifken) çağrıldığı için, bağlantılar
// derinlikten bağımsız çalışsın diye SITE_KOK ile mutlak yol olarak veriliyor.
$hizmetMenu = [
    ['nikah', 'Nikah İşlemleri', SITE_KOK . '/hizmetler/nikah-islemleri.php', 'bi-heart-fill'],
    ['fen-isleri', 'Fen İşleri', SITE_KOK . '/hizmetler/fen-isleri.php', 'bi-cone-striped'],
    ['zabita', 'Zabıta', SITE_KOK . '/hizmetler/zabita.php', 'bi-shield-check'],
    ['emlak-istimlak', 'Emlak & İstimlak', SITE_KOK . '/hizmetler/emlak-istimlak.php', 'bi-house-door-fill'],
    ['temizlik-isleri', 'Temizlik İşleri', SITE_KOK . '/hizmetler/temizlik-isleri.php', 'bi-trash-fill'],
    ['kultur-sosyal-isler', 'Kültür ve Sosyal İşler', SITE_KOK . '/hizmetler/kultur-sosyal-isler.php', 'bi-palette-fill'],
    ['veteriner-hizmetleri', 'Veteriner Hizmetleri', SITE_KOK . '/hizmetler/veteriner-hizmetleri.php', 'bi-heart-pulse-fill'],
    ['mali-hizmetler', 'Mali Hizmetler', SITE_KOK . '/e-belediye/e-belediye.php?panel=hizmet', 'bi-cash-coin'],
];
?>
<div class="bg-white rounded-4 p-3 shadow-sm mb-4">
    <h6 class="fw-bold text-uppercase small text-muted bolum-baslik mb-3" style="letter-spacing:.5px;padding-bottom:10px;">Hizmetler</h6>
    <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste hizmetler-menu-liste">
        <?php foreach ($hizmetMenu as $item): ?>
            <?php $aktifMi = ($item[0] === $aktifHizmet); ?>
            <li class="nav-item">
                <a href="<?php echo htmlspecialchars($item[2]); ?>"
                   class="nav-link w-100 text-start <?php echo $aktifMi ? 'active' : ''; ?>">
                    <span class="hizmet-kutu"><i class="bi <?php echo $item[3]; ?>"></i></span>
                    <?php echo htmlspecialchars($item[1]); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
