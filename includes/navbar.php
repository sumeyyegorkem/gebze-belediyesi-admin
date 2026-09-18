<!-- ============== ÜST MENÜ (hero'nun üzerine binen, şeffaf/lacivert çubuk) ============== -->
<nav class="navbar-overlay" id="ustMenu">
    <div class="container d-flex align-items-center py-2 position-relative">

        <!-- Sol: Menü (4 öge) -->
        <ul class="yatay-menu-liste d-none d-lg-flex mb-0" style="flex:1 1 0;justify-content:flex-end;">
            <li class="yatay-menu-dropdown">
                <a href="<?php echo SITE_KOK; ?>/genel/hakkimizda.php">Başkan <i class="bi bi-chevron-down small"></i></a>
                <ul class="yatay-menu-alt">
                    <li><a href="<?php echo SITE_KOK; ?>/genel/hakkimizda.php">Özgeçmiş</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/projeler/projeler.php">Projeler</a></li>
                    <li><a href="https://zinnurbuyukgoz.com" target="_blank">Başkanın Web Sayfası</a></li>
                </ul>
            </li>
            <li class="yatay-menu-dropdown">
                <a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php">Kurumsal <i class="bi bi-chevron-down small"></i></a>
                <ul class="yatay-menu-alt yatay-menu-alt-uzun">
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php#vizyon">Vizyonumuz</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php#misyon">Misyonumuz</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php#ilkeler">İlkelerimiz</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/enerji-politikamiz.php">Enerji Politikamız</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/belediye-meclisi.php">Belediye Meclisi</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/yonetim-semasi.php">Yönetim Şeması</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/baskan-yardimcilari.php">Başkan Yardımcıları</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/baskan-danismanlari.php">Başkan Danışmanları</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/mudurlukler.php">Müdürlükler</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/eski-baskanlar.php">Eski Başkanlar</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/arabuluculuk-komisyonu.php">Arabuluculuk Komisyonu</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/etik-komisyonu.php">Etik Komisyonu</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/meclis-kararlari.php">Meclis Kararları</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal-kimlik.php">Kurumsal Kimlik</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal-raporlar.php">Kurumsal Raporlar</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php?sayfa=dokumanlar">Kurumsal Dökümanlar</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/kurumsal/yayinlar.php">Yayınlar</a></li>
                </ul>
            </li>
            <li class="yatay-menu-dropdown">
                <a href="<?php echo SITE_KOK; ?>/gebze/gebze.php">Gebze <i class="bi bi-chevron-down small"></i></a>
                <ul class="yatay-menu-alt">
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/gebze.php">Tarihçe</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/bugunku-gebze.php">Bugünkü Gebze</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/muhtarlar.php">Mahalle Muhtarları</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/kent-rehberi.php">Tarihi Yerler</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/fotograflarla-gebze.php">Fotoğraflarla Gebze</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/kardes-sehirler.php">Kardeş Şehirler</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/uye-birlikler.php">Üye Olduğumuz Birlikler</a></li>
                </ul>
            </li>
            <li><a href="<?php echo SITE_KOK; ?>/hizmetler/hizmetler.php">Hizmetler</a></li>
        </ul>

        <!-- Ortada: Logo (beyaz, sadece masaüstü) -->
        <a class="d-none d-lg-flex align-items-center text-decoration-none flex-shrink-0 mx-3" href="<?php echo SITE_KOK; ?>/index.php">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg"
                 alt="Gebze Belediyesi Logo" class="navbar-logo me-2" style="filter: brightness(0) invert(1);">
            <span class="d-none d-sm-inline text-white fw-bold">GEBZE BELEDİYESİ</span>
        </a>

        <!-- Sağ: Menü (5 öge) -->
        <ul class="yatay-menu-liste d-none d-lg-flex mb-0" style="flex:1 1 0;justify-content:flex-start;">
            <li><a href="<?php echo SITE_KOK; ?>/genel/faaliyet-alanlari.php">Faaliyetler</a></li>
            <li><a href="<?php echo SITE_KOK; ?>/e-belediye/e-belediye.php">E-Belediye</a></li>
            <li><a href="<?php echo SITE_KOK; ?>/etkinlikler/etkinlikler.php">Etkinlikler</a></li>
            <li class="yatay-menu-dropdown">
                <a href="<?php echo SITE_KOK; ?>/haberler/haberler.php">Haberler <i class="bi bi-chevron-down small"></i></a>
                <ul class="yatay-menu-alt">
                    <li><a href="<?php echo SITE_KOK; ?>/haberler/haberler.php">Haberler</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/haberler/duyurular.php">Duyurular</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/haberler/videolar.php">Videolar</a></li>
                    <li><a href="<?php echo SITE_KOK; ?>/gebze/fotograflarla-gebze.php">Fotoğraf Galerisi</a></li>
                </ul>
            </li>
            <li><a href="<?php echo SITE_KOK; ?>/genel/iletisim.php">İletişim</a></li>
        </ul>

        <!-- Mobil: Logo (soldan hizalı, menü gizliyken) + Hamburger (sağda) -->
        <a class="d-lg-none d-flex align-items-center text-decoration-none" href="<?php echo SITE_KOK; ?>/index.php">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg"
                 alt="Gebze Belediyesi Logo" class="navbar-logo me-2" style="filter: brightness(0) invert(1);">
            <span class="d-none d-sm-inline text-white fw-bold">GEBZE BELEDİYESİ</span>
        </a>
        <button class="btn text-white d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#anaMenuPanel">
            <i class="bi bi-list fs-2"></i>
        </button>
    </div>
</nav>

<script>
// Sayfa aşağı kaydırılınca üst menüyü opak (dolu lacivert) yapıyoruz,
// en üstteyken şeffaf/hero üzerine binmiş halde kalıyor.
document.addEventListener('DOMContentLoaded', function () {
    const menu = document.getElementById('ustMenu');
    if (!menu) return;
    function kontrolEt() {
        if (window.scrollY > 60) {
            menu.classList.add('navbar-overlay-dolu');
        } else {
            menu.classList.remove('navbar-overlay-dolu');
        }
    }
    window.addEventListener('scroll', kontrolEt);
    kontrolEt();
});
</script>

<!-- ============== SAĞDAN KAYAN MENÜ (Offcanvas - sadece mobilde açılır) ============== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="anaMenuPanel">
    <div class="offcanvas-header ust-serit text-white">
        <h5 class="offcanvas-title d-flex align-items-center">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg"
                 alt="Gebze Belediyesi Logo" class="navbar-logo me-2" style="filter: brightness(0) invert(1);">
            Menü
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="<?php echo SITE_KOK; ?>/index.php" class="menu-link"><i class="bi bi-house-door-fill me-2"></i>Ana Sayfa</a></li>

            <!-- Başkan -->
            <li class="list-group-item p-0">
                <div class="d-flex">
                    <a href="<?php echo SITE_KOK; ?>/genel/hakkimizda.php" class="menu-link flex-grow-1"><i class="bi bi-person-badge-fill me-2"></i>Başkan</a>
                    <button class="btn px-3" type="button" data-bs-toggle="collapse" data-bs-target="#altBaskan"><i class="bi bi-chevron-down small"></i></button>
                </div>
                <div class="collapse" id="altBaskan">
                    <ul class="list-group list-group-flush bg-light">
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/genel/hakkimizda.php" class="menu-link small">Özgeçmiş</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/projeler/projeler.php" class="menu-link small">Projeler</a></li>
                        <li class="list-group-item ps-5"><a href="https://zinnurbuyukgoz.com" target="_blank" class="menu-link small">Başkanın Web Sayfası</a></li>
                    </ul>
                </div>
            </li>

            <!-- Kurumsal -->
            <li class="list-group-item p-0">
                <div class="d-flex">
                    <a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php" class="menu-link flex-grow-1"><i class="bi bi-building me-2"></i>Kurumsal</a>
                    <button class="btn px-3" type="button" data-bs-toggle="collapse" data-bs-target="#altKurumsal"><i class="bi bi-chevron-down small"></i></button>
                </div>
                <div class="collapse" id="altKurumsal">
                    <ul class="list-group list-group-flush bg-light">
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php#vizyon" class="menu-link small">Vizyonumuz</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php#misyon" class="menu-link small">Misyonumuz</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php#ilkeler" class="menu-link small">İlkelerimiz</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/enerji-politikamiz.php" class="menu-link small">Enerji Politikamız</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/belediye-meclisi.php" class="menu-link small">Belediye Meclisi</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/yonetim-semasi.php" class="menu-link small">Yönetim Şeması</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/baskan-yardimcilari.php" class="menu-link small">Başkan Yardımcıları</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/baskan-danismanlari.php" class="menu-link small">Başkan Danışmanları</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/mudurlukler.php" class="menu-link small">Müdürlükler</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/eski-baskanlar.php" class="menu-link small">Eski Başkanlar</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/arabuluculuk-komisyonu.php" class="menu-link small">Arabuluculuk Komisyonu</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/etik-komisyonu.php" class="menu-link small">Etik Komisyonu</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/meclis-kararlari.php" class="menu-link small">Meclis Kararları</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal-kimlik.php" class="menu-link small">Kurumsal Kimlik</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal-raporlar.php" class="menu-link small">Kurumsal Raporlar</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php?sayfa=dokumanlar" class="menu-link small">Kurumsal Dökümanlar</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/kurumsal/yayinlar.php" class="menu-link small">Yayınlar</a></li>
                    </ul>
                </div>
            </li>

            <!-- Gebze -->
            <li class="list-group-item p-0">
                <div class="d-flex">
                    <a href="<?php echo SITE_KOK; ?>/gebze/gebze.php" class="menu-link flex-grow-1"><i class="bi bi-signpost-split-fill me-2"></i>Gebze</a>
                    <button class="btn px-3" type="button" data-bs-toggle="collapse" data-bs-target="#altGebze"><i class="bi bi-chevron-down small"></i></button>
                </div>
                <div class="collapse" id="altGebze">
                    <ul class="list-group list-group-flush bg-light">
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/gebze.php" class="menu-link small">Tarihçe</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/bugunku-gebze.php" class="menu-link small">Bugünkü Gebze</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/muhtarlar.php" class="menu-link small">Mahalle Muhtarları</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/kent-rehberi.php" class="menu-link small">Tarihi Yerler</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/fotograflarla-gebze.php" class="menu-link small">Fotoğraflarla Gebze</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/kardes-sehirler.php" class="menu-link small">Kardeş Şehirler</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/uye-birlikler.php" class="menu-link small">Üye Olduğumuz Birlikler</a></li>
                    </ul>
                </div>
            </li>

            <li class="list-group-item"><a href="<?php echo SITE_KOK; ?>/hizmetler/hizmetler.php" class="menu-link"><i class="bi bi-grid-fill me-2"></i>Hizmetler</a></li>
            <li class="list-group-item"><a href="<?php echo SITE_KOK; ?>/genel/faaliyet-alanlari.php" class="menu-link"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Faaliyetler</a></li>
            <li class="list-group-item"><a href="<?php echo SITE_KOK; ?>/e-belediye/e-belediye.php" class="menu-link"><i class="bi bi-laptop me-2"></i>E-Belediye</a></li>
            <li class="list-group-item"><a href="<?php echo SITE_KOK; ?>/etkinlikler/etkinlikler.php" class="menu-link"><i class="bi bi-calendar-event-fill me-2"></i>Etkinlikler</a></li>

            <!-- Haberler -->
            <li class="list-group-item p-0">
                <div class="d-flex">
                    <a href="<?php echo SITE_KOK; ?>/haberler/haberler.php" class="menu-link flex-grow-1"><i class="bi bi-megaphone-fill me-2"></i>Haberler</a>
                    <button class="btn px-3" type="button" data-bs-toggle="collapse" data-bs-target="#altHaberler"><i class="bi bi-chevron-down small"></i></button>
                </div>
                <div class="collapse" id="altHaberler">
                    <ul class="list-group list-group-flush bg-light">
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/haberler/haberler.php" class="menu-link small">Haberler</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/haberler/duyurular.php" class="menu-link small">Duyurular</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/haberler/videolar.php" class="menu-link small">Videolar</a></li>
                        <li class="list-group-item ps-5"><a href="<?php echo SITE_KOK; ?>/gebze/fotograflarla-gebze.php" class="menu-link small">Fotoğraf Galerisi</a></li>
                    </ul>
                </div>
            </li>

            <li class="list-group-item"><a href="<?php echo SITE_KOK; ?>/genel/iletisim.php" class="menu-link"><i class="bi bi-envelope-fill me-2"></i>İletişim</a></li>
        </ul>
    </div>
</div>
