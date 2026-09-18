<?php
// Bu dosya, tüm "Kurumsal" sayfalarında (hakkimizda.php, enerji-politikamiz.php vb.)
// ortak bir yan menü göstermek için include edilir.
// Kullanmadan önce, isteğe bağlı olarak $aktifKurumsal değişkenini tanımlayabilirsin
// (örn: $aktifKurumsal = 'vizyon';) - o zaman ilgili link vurgulanır.
$aktifKurumsal = $aktifKurumsal ?? '';

$kurumsalMenu = [
    ['vizyon', 'Vizyonumuz', 'kurumsal.php?sayfa=vizyon', false, 'bi-eye-fill'],
    ['misyon', 'Misyonumuz', 'kurumsal.php?sayfa=misyon', false, 'bi-bullseye'],
    ['ilkeler', 'İlkelerimiz', 'kurumsal.php?sayfa=ilkeler', false, 'bi-list-check'],
    ['enerji', 'Enerji Politikamız', 'enerji-politikamiz.php', false, 'bi-lightning-charge-fill'],
    ['meclis', 'Belediye Meclisi', 'belediye-meclisi.php', false, 'bi-bank2'],
    ['yonetim-semasi', 'Yönetim Şeması', 'yonetim-semasi.php', false, 'bi-diagram-3-fill'],
    ['baskan-yardimcilari', 'Başkan Yardımcıları', 'baskan-yardimcilari.php', false, 'bi-person-badge-fill'],
    ['baskan-danismanlari', 'Başkan Danışmanları', 'baskan-danismanlari.php', false, 'bi-person-lines-fill'],
    ['mudurlukler', 'Müdürlükler', 'mudurlukler.php', false, 'bi-building'],
    ['eski-baskanlar', 'Eski Başkanlar', 'eski-baskanlar.php', false, 'bi-clock-history'],
    ['arabuluculuk', 'Arabuluculuk Komisyonu', 'arabuluculuk-komisyonu.php', false, 'bi-people-fill'],
    ['etik', 'Etik Komisyonu', 'etik-komisyonu.php', false, 'bi-shield-check'],
    ['meclis-kararlari', 'Meclis Kararları', 'meclis-kararlari.php', false, 'bi-journal-text'],
    ['kurumsal-kimlik', 'Kurumsal Kimlik', 'kurumsal-kimlik.php', false, 'bi-award-fill'],
    ['kurumsal-rapor', 'Kurumsal Raporlar', 'kurumsal-raporlar.php', false, 'bi-file-earmark-bar-graph-fill'],
    ['dokumanlar', 'Kurumsal Dökümanlar', 'kurumsal.php?sayfa=dokumanlar', false, 'bi-folder2-open'],
    ['yayinlar', 'Yayınlar', 'yayinlar.php', false, 'bi-book-fill'],
    ['kvkk', 'KVKK Aydınlatma Metni', 'kvkk.php', false, 'bi-shield-lock-fill'],
];
?>
<div class="bg-white rounded-4 p-3 shadow-sm mb-4">
    <h6 class="fw-bold text-uppercase small text-muted bolum-baslik mb-3" style="letter-spacing:.5px;padding-bottom:10px;">Kurumsal Başlıklar</h6>
    <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste kurumsal-menu-liste">
        <?php foreach ($kurumsalMenu as $item): ?>
            <?php $aktifMi = ($item[0] === $aktifKurumsal); ?>
            <li class="nav-item">
                <a href="<?php echo htmlspecialchars($item[2]); ?>"
                   <?php echo $item[3] ? 'target="_blank"' : ''; ?>
                   class="nav-link w-100 text-start <?php echo $aktifMi ? 'active' : ''; ?>">
                    <span class="hizmet-kutu"><i class="bi <?php echo $item[4]; ?>"></i></span>
                    <?php echo htmlspecialchars($item[1]); ?>
                    <?php if ($item[3]): ?>
                        <i class="bi bi-box-arrow-up-right small ms-1"></i>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
