<?php
// Tüm "Kurumsal" sayfalarında ortak breadcrumb + başlık + yazı boyutu/yazdır kutusu.
// Not: hero alanı (üstteki mavi şerit) bu sayfa ailesinde artık yazısız/sade bırakıldığı
// için sayfanın gerçek başlığı (breadcrumb + h1) burada gösteriliyor.
// Kullanmadan önce $sayfaBasligi değişkenini tanımla (örn: $sayfaBasligi = 'Belediye Meclisi';)
// Bu include ile açılan .hizmet-kutu ve #icerikMetni div'lerini, sayfanın içeriği bittikten
// sonra "</div></div>" ile kapatmayı unutma (bkz: includes/kurumsal-icerik-alt.php).
$sayfaBasligi = $sayfaBasligi ?? 'Kurumsal';
?>
<div class="icerik-kutusu mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
        <div>
            <p class="small text-muted mb-2 text-start">
                <a href="<?php echo SITE_KOK; ?>/index.php" class="text-decoration-none text-muted">Anasayfa</a> /
                <span class="fw-bold" style="color:var(--lacivert-koyu);"><?php echo htmlspecialchars($sayfaBasligi); ?></span>
            </p>
            <h1 class="fw-bold text-start mb-0" style="color:var(--lacivert-koyu);"><?php echo htmlspecialchars($sayfaBasligi); ?></h1>
            <div style="width:60px;height:4px;background:var(--altin);margin-top:.75rem;"></div>
        </div>

        <div class="d-flex gap-2 p-2 rounded-pill flex-shrink-0" style="background:var(--acik-gri);">
            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazıyı Küçült" onclick="metinBoyutAyarla(-1)"><i class="bi bi-zoom-out" style="color:var(--lacivert);"></i></button>
            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazı Boyutunu Sıfırla" onclick="metinBoyutAyarla(0)"><i class="bi bi-arrow-counterclockwise" style="color:var(--lacivert);"></i></button>
            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazıyı Büyüt" onclick="metinBoyutAyarla(1)"><i class="bi bi-zoom-in" style="color:var(--lacivert);"></i></button>
            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazdır" onclick="window.print()"><i class="bi bi-printer" style="color:var(--lacivert);"></i></button>
        </div>
    </div>

    <div id="icerikMetni">
