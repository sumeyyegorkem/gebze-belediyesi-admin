<?php
/**
 * config/e-belediye-seed-data.php
 * -------------------------------------------------------
 * E-Belediye sayfalarinin eski sabit (hardcoded) veri dizileri.
 * Bu dosya SADECE bir kereye mahsus veritabani migration'inda
 * (config/db.php) tohum veri olarak kullanilir; orijinal
 * .php dosyalarindaki dizilerin BIREBIR kopyasidir.
 * -------------------------------------------------------
 */
return [
    'e_belediye' => [
        ['bi-cash-coin', 'Vergi ve Başvuru İşlemleri', 'Vergi/borç ödeme, e-Beyan ve Gebze İletişim Merkezi başvuruları', 'vergi-ve-basvuru-islemleri.php', 6],
        ['bi-building', 'İmar ve Yapı İşlemleri', 'Yapı ruhsatı, imar durumu ve İmar Yönetim Sistemi başvuruları', 'imar-ve-yapi-islemleri.php', 8],
        ['bi-info-circle-fill', 'Bilgilendirme Hizmetleri', 'Kent rehberi, imar durumu, nöbetçi eczane ve daha fazlası', 'bilgilendirme-hizmetleri.php', 10],
        ['bi-grid-3x3-gap-fill', 'Diğer İnteraktif Hizmetler', 'e-Eksper, e-İşyeri Ruhsat, sosyal yardım ve personel işlemleri', 'diger-interaktif-hizmetler.php', 13],
    ],

    'vergi-ve-basvuru-islemleri' => [
        'Vergi İşlemleri' => [
            ['bi-cash-coin', 'Vergi / Borç Ödeme', 'https://portal.gebze.bel.tr/ui/user/login'],
            ['bi-file-earmark-check-fill', 'e-Beyan', null, 'eBeyanModal'],
            ['bi-clock-history', 'e-Beyan Bildirim Takip', 'https://ulakbel.gebze.bel.tr/WebBasvuru/#/Status'],
        ],
        'Gebze İletişim Merkezi' => [
            ['bi-chat-left-text-fill', 'Başvuru Formu', null, 'basvuruFormuModal'],
            ['bi-journal-check', 'Başvuru Takip', 'https://ulakbel.gebze.bel.tr/WebBasvuru/#/Status'],
            ['bi-info-circle-fill', 'Bilgi Edinme (CİMER)', 'https://www.cimer.gov.tr/'],
        ],
    ],

    'imar-ve-yapi-islemleri' => [
        'İmar Yönetim Sistemi' => [
            ['bi-geo-alt-fill', 'İmar Durumu Başvurusu', null, 'imarDurumuModal'],
            ['bi-file-earmark-ruled-fill', 'Yapı Ruhsatına Esas Başvurular', null, 'yapiRuhsatModal'],
            ['bi-shield-fill-check', 'Güçlendirmeye Esas Başvurular', null, 'gucBasvuruModal'],
            ['bi-tools', 'Asansör Tescil Başvurusu', null, 'asansorTescilModal'],
            ['bi-file-earmark-plus-fill', 'Yapı Ruhsat Başvurusu', null, 'yapiRuhsatBasvuruModal'],
            ['bi-diagram-3-fill', 'Kat İrtifakı/Mülkiyeti Projesi Onay Başvurusu', 'https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=9X%2F%2Aq%2FY42LhoaqQrrtTRqg%3D%3D'],
            ['bi-file-earmark-medical-fill', 'Kırmızı Kot Belgesi Başvurusu', 'https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=7y34ME2FDKp6wD73fTJiOQ%3D%3D'],
            ['bi-search', 'Başvuru Sorgulama', 'https://eimar.gebze.bel.tr/YapiBelgeleriWeb/Kullanici/Giris?selectedTab=2'],
        ],
    ],

    'bilgilendirme-hizmetleri' => [
        'Bilgilendirme Hizmetleri' => [
            ['bi-signpost-2-fill', 'Acil Toplanma Alanları', 'https://kentrehberi.gebze.bel.tr/ToplanmaAlanlari'],
            ['bi-file-earmark-post-fill', 'Askıya Çıkan İmar Planları', 'https://www.gebze.bel.tr/imar-plan-ilanlari.html'],
            ['bi-rulers', 'Arsa Metrekare Birim Değeri Sorgulama', 'https://ebelediye.gebze.bel.tr/NicoPortal/faces/portal/gelir/ArsaRayicDegerListesi.xhtml'],
            ['bi-geo-alt-fill', 'İmar Durumu', 'https://imardurumu.gebze.bel.tr/'],
            ['bi-map-fill', 'İmar Planları', 'https://kentrehberi.gebze.bel.tr/Harita?m=plan'],
            ['bi-compass-fill', 'Kent Rehberi', 'https://kentrehberi.gebze.bel.tr/'],
            ['bi-flower1', 'Mezarlık Bilgi Sistemi', 'https://kentrehberi.gebze.bel.tr/Harita?m=mbs'],
            ['bi-heart-fill', 'Nikah Randevu Sistemi', 'https://ebelediye.gebze.bel.tr/NicoPortal/faces/portal/nikah/NikahRandevuListesi.xhtml'],
            ['bi-capsule', 'Nöbetçi Eczane', 'https://kentrehberi.gebze.bel.tr/GebzeNobetciEczaneler'],
            ['bi-file-earmark-person-fill', 'Vefat Edenler', 'https://ebelediye.gebze.bel.tr/NicoPortal/faces/portal/mezarlik/VefatEdenlerListesi.xhtml'],
        ],
    ],

    'diger-interaktif-hizmetler' => [
        'İnteraktif Hizmetler' => [
            ['bi-clipboard-check-fill', 'e-Eksper', 'https://ulakbel.gebze.bel.tr/WebBasvuru/eeksper#/'],
            ['bi-building-fill', 'e-İşyeri Ruhsat', 'https://ulakbel.gebze.bel.tr/WebBasvuru/e-isyeri-ruhsat#/'],
            ['bi-file-earmark-text-fill', 'e-Rayiç', null, 'eRayicModal'],
            ['bi-truck', 'E-Moloz', 'https://ulakbel.gebze.bel.tr/WebBasvuru/e-moloz-hatti#/'],
            ['bi-recycle', 'e-Geri Dönüşüm', 'https://ulakbel.gebze.bel.tr/WebBasvuru/e-geridonusum-hatti#/'],
            ['bi-bug-fill', 'E-İlaçlama (Özel Mülkiyet)', 'https://ulakbel.gebze.bel.tr/WebBasvuru/e-ilaclama-hatti#/'],
            ['bi-hand-holding-heart', 'Sosyal Yardım Başvurusu', 'https://ulakbel.gebze.bel.tr/WebBasvuru/sosyal-yardimlar-online-basvuru-formu#/'],
            ['bi-mortarboard-fill', '2026 Üniversite Sosyal Destek Yardımı', 'https://ulakbel.gebze.bel.tr/WebBasvuru/2026sosyaldestekyardimi#/'],
            ['bi-heart-fill', 'Evcil Hayvan Sahiplenme', 'https://ulakbel.gebze.bel.tr/WebBasvuru/sokaktan-eve#/'],
            ['bi-cloud-fill', 'Karbon Ayak İzi Hesaplama', 'https://www.gebze.bel.tr/karbonayakizi/'],
        ],
        'Spor ve Eğitim' => [
            ['bi-mortarboard-fill', 'SENDE (Kayıt ve Eğitim Portalı)', null, 'sendeModal'],
        ],
        'Kurum İçi İşlemler' => [
            ['bi-person-badge-fill', 'Personel Giriş', 'https://ebelediye.gebze.bel.tr/eBelediye/'],
            ['bi-person-workspace', 'Personel Portalı', 'https://ebelediye.gebze.bel.tr/eBelediye/'],
        ],
    ],

];
