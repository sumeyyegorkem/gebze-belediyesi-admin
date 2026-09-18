<?php
/**
 * config/gebze-baglantilar-seed-data.php
 * Kardeş Şehirler (yurt içi / yurt dışı) ve Üye Olduğumuz Birlikler için
 * tek seferlik veritabanı tohumlama verisi. Kaynak: kardes-sehirler.php ve
 * uye-birlikler.php dosyalarındaki eski sabit PHP dizileri (birebir).
 */
return [
    'kardes_yurt_ici' => [
            ['Acıgöl Belediyesi', 'Nevşehir'],
            ['Gülşehir Belediyesi', 'Nevşehir'],
            ['Silvan Belediyesi', 'Diyarbakır'],
            ['Selçuk Belediyesi', 'İzmir'],
            ['Saltukova Belediyesi', 'Zonguldak'],
            ['Malazgirt Belediyesi', 'Muş'],
            ['Durankaya Belediyesi', 'Hakkari'],
        ],

    'kardes_yurt_disi' => [
            ['Değirmenlik Belediyesi', 'Değirmenlik', 'KKTC'],
            ['Karakol Şehri', 'Issık-Göl', 'Kırgızistan'],
            ['Samuil Belediyesi', 'Razgrad', 'Bulgaristan'],
            ['Pilea Belediyesi', 'Selanik', 'Yunanistan'],
            ['Oeiras Belediyesi', 'Lizbon', 'Portekiz'],
            ['Kakanj Belediyesi', 'Kakanj', 'Bosna Hersek'],
            ['Garowe Belediyesi', 'Garowe', 'Somali'],
            ['Tyulyachi Belediyesi', 'Tyulyachi', 'Tataristan'],
            ['Studenicani Belediyesi', 'Studenicani', 'Makedonya'],
            ['Kiseljak Belediyesi', 'Kiseljak', 'Bosna Hersek'],
            ['Hasköy Belediyesi', 'Hasköy', 'Bulgaristan'],
        ],

    'uye_birlikler' => [
            ['Anadolu Medeniyetler Belediyeler Birliği', 'https://www.anadolubirlik.org.tr/'],
            ['Birleşmiş Kentler ve Yerel Yönetimler (UCLG-MEWA)', 'https://uclg-mewa.org/'],
            ['Marmara Belediyeler Birliği', 'https://www.marmara.gov.tr/'],
            ['Türk Dünyası Belediyeler Birliği', 'http://www.tdbb.org.tr/?lang=tr'],
            ['Türkiye Belediyeler Birliği', 'https://www.tbb.gov.tr/Tr/'],
            ['Türkiye Sağlıklı Kentler Birliği', 'https://www.skb.gov.tr/'],
            ['Agricities Uluslararası Tarım Şehirleri Birliği', 'https://www.agricities.com/'],
        ],
];
