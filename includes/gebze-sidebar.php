<?php
// Bu dosya, tüm "Gebze" sayfalarında (gebze.php, bugunku-gebze.php, muhtarlar.php,
// kent-rehberi.php, fotograflarla-gebze.php, kardes-sehirler.php, uye-birlikler.php)
// ortak bir yan menü göstermek için include edilir (Kurumsal sayfalarındaki
// includes/kurumsal-sidebar.php ile birebir aynı yapı/stil kullanılır).
// Kullanmadan önce $aktifGebze değişkenini tanımla (örn: $aktifGebze = 'tarihce';)
// - o zaman ilgili link vurgulanır.
$aktifGebze = $aktifGebze ?? '';

$gebzeMenu = [
    ['tarihce', 'Tarihçe', 'gebze.php', 'bi-clock-history'],
    ['bugunku-gebze', 'Bugünkü Gebze', 'bugunku-gebze.php', 'bi-graph-up-arrow'],
    ['muhtarlar', 'Mahalle Muhtarları', 'muhtarlar.php', 'bi-people-fill'],
    ['tarihi-yerler', 'Tarihi Yerler', 'kent-rehberi.php', 'bi-compass-fill'],
    ['fotograflar', 'Fotoğraflarla Gebze', 'fotograflarla-gebze.php', 'bi-images'],
    ['kardes-sehirler', 'Kardeş Şehirler', 'kardes-sehirler.php', 'bi-globe-americas'],
    ['uye-birlikler', 'Üye Olduğumuz Birlikler', 'uye-birlikler.php', 'bi-diagram-3-fill'],
];
?>
<div class="bg-white rounded-4 p-3 shadow-sm mb-4">
    <h6 class="fw-bold text-uppercase small text-muted bolum-baslik mb-3" style="letter-spacing:.5px;padding-bottom:10px;">Gebze</h6>
    <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste gebze-menu-liste">
        <?php foreach ($gebzeMenu as $item): ?>
            <?php $aktifMi = ($item[0] === $aktifGebze); ?>
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
