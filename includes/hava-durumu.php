<?php
// Ana sayfadaki "Gebze Hava Durumu" kutusu için canlı sıcaklık bilgisini
// Open-Meteo API'sinden (ücretsiz, API anahtarı gerektirmez) çeker.
// Dış servise her sayfa yüklemesinde istek atmamak için sonucu 30 dakika
// boyunca config/hava-durumu-onbellek.json dosyasında önbelleğe alır.
// API'ye ulaşılamazsa (internet yok, servis kapalı vb.) eski önbellek
// varsa onu, hiç önbellek yoksa "--°C" gösterecek varsayılan değeri döner.
//
// 'durum' alanı, kutunun arka planını hava durumuna göre değiştirmek için
// kullanılır (bkz. css/style.css .hava-kutu-* sınıfları).

function gebzeHavaDurumuGetir(): array
{
    $varsayilan = [
        'sicaklik' => null,
        'ikon' => 'bi-cloud-sun-fill',
        'durum' => 'varsayilan',
        'etiket' => 'Hava Durumu',
    ];

    $onbellekDosyasi = __DIR__ . '/../config/hava-durumu-onbellek.json';
    $onbellekSuresi = 30 * 60; // 30 dakika

    $eskiOnbellek = null;
    if (file_exists($onbellekDosyasi)) {
        $okunan = json_decode((string) file_get_contents($onbellekDosyasi), true);
        if (is_array($okunan) && isset($okunan['zaman'], $okunan['veri'])) {
            $eskiOnbellek = $okunan;
            // Önbellek hâlâ taze ise VE güncel alanları (durum vb.) içeriyorsa
            // dış servise hiç gitmeden onu döndür. Eski/eksik formatlı bir
            // önbellek varsa (ör. 'durum' alanı eklenmeden önce yazılmışsa)
            // taze sayılmaz, yeniden çekilir.
            $onbellekGuncelFormatta = isset($okunan['veri']['durum'], $okunan['veri']['etiket']);
            if ($onbellekGuncelFormatta && (time() - (int) $okunan['zaman']) < $onbellekSuresi) {
                return array_merge($varsayilan, $okunan['veri']);
            }
        }
    }

    // Gebze, Kocaeli koordinatları
    $lat = 40.8028;
    $lon = 29.4306;
    $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current_weather=true&timezone=Europe%2FIstanbul";

    $json = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_CONNECTTIMEOUT => 4,
        ]);
        $sonuc = curl_exec($ch);
        $hataYok = ($sonuc !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200);
        curl_close($ch);
        $json = $hataYok ? $sonuc : false;
    } elseif (ini_get('allow_url_fopen')) {
        $baglam = stream_context_create(['http' => ['timeout' => 4]]);
        $json = @file_get_contents($url, false, $baglam);
    }

    if ($json) {
        $veri = json_decode($json, true);
        if (isset($veri['current_weather']['temperature'])) {
            $kodBilgisi = havaDurumuKoduBilgisi((int) ($veri['current_weather']['weathercode'] ?? 0));
            $sonucVeri = [
                'sicaklik' => (int) round($veri['current_weather']['temperature']),
                'ikon' => $kodBilgisi['ikon'],
                'durum' => $kodBilgisi['durum'],
                'etiket' => $kodBilgisi['etiket'],
            ];
            // Diskte yazma izni yoksa sessizce devam et; sadece önbellekleme atlanmış olur.
            @file_put_contents($onbellekDosyasi, json_encode(['zaman' => time(), 'veri' => $sonucVeri]));
            return $sonucVeri;
        }
    }

    // API'ye ulaşılamadı: eski (süresi geçmiş olsa bile) önbellek varsa onu göster.
    if ($eskiOnbellek !== null) {
        return array_merge($varsayilan, $eskiOnbellek['veri']);
    }

    return $varsayilan;
}

// WMO hava durumu koduna göre ikon + kutunun arka plan temasını (durum) +
// ekranda gösterilecek Türkçe etiketi belirler.
function havaDurumuKoduBilgisi(int $kod): array
{
    if ($kod === 0) {
        return ['ikon' => 'bi-sun-fill', 'durum' => 'gunesli', 'etiket' => 'Güneşli'];
    }
    if (in_array($kod, [1, 2], true)) {
        return ['ikon' => 'bi-cloud-sun-fill', 'durum' => 'parcali-bulutlu', 'etiket' => 'Parçalı Bulutlu'];
    }
    if ($kod === 3) {
        return ['ikon' => 'bi-clouds-fill', 'durum' => 'bulutlu', 'etiket' => 'Bulutlu'];
    }
    if (in_array($kod, [45, 48], true)) {
        return ['ikon' => 'bi-cloud-fog-fill', 'durum' => 'sisli', 'etiket' => 'Sisli'];
    }
    if (in_array($kod, [51, 53, 55, 56, 57, 61, 63, 65, 66, 67, 80, 81, 82], true)) {
        return ['ikon' => 'bi-cloud-rain-fill', 'durum' => 'yagmurlu', 'etiket' => 'Yağmurlu'];
    }
    if (in_array($kod, [71, 73, 75, 77, 85, 86], true)) {
        return ['ikon' => 'bi-cloud-snow-fill', 'durum' => 'karli', 'etiket' => 'Karlı'];
    }
    if (in_array($kod, [95, 96, 99], true)) {
        return ['ikon' => 'bi-cloud-lightning-rain-fill', 'durum' => 'firtinali', 'etiket' => 'Fırtınalı'];
    }
    return ['ikon' => 'bi-cloud-sun-fill', 'durum' => 'varsayilan', 'etiket' => 'Hava Durumu'];
}
