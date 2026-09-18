<?php
/**
 * config/faaliyet-seed-data.php
 * -------------------------------------------------------
 * Faaliyet Alanları sayfasının eski sabit (hardcoded) veri dizisi.
 * Bu dosya SADECE bir kereye mahsus veritabanı migration'ında
 * (config/db.php) tohum veri olarak kullanılır; orijinal
 * faaliyet-alanlari.php dosyasındaki dizinin BİREBİR kopyasıdır.
 * -------------------------------------------------------
 */
    return [
        'atolyeler' => [
            'label' => 'Atölyeler',
            'icon' => 'bi-easel-fill',
            'ogeler' => [
                ['baslik' => 'Enderun Çocuk Atölyeleri', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_enderun-cocuk-atolyeleri_6.html', 'img' => 'https://www.gebze.bel.tr/resim/20260819145806.jpeg', 'detay' => "Enderun Çocuk Atölyeleri; 2-6 yaş çocukların gelişimlerini oyun, keşif ve deneyim yoluyla destekleyen, çocuk merkezli eğitim anlayışıyla hizmet veren bir atölye modelidir.\n\nAtölyelerimizde çocukların yaş ve gelişim özelliklerine uygun olarak; sanat, bilim, doğa, matematik, hareket, müzik, drama, keşif, İngilizce, geziler, değerler eğitimi alanlarında zenginleştirilmiş etkinlikler gerçekleştirilmektedir.\n\nGünün Farklı Saatlerinde Farklı Deneyimler\n\nAnne-Çocuk Atölyeleri\nAnne ve çocukların birlikte katıldığı bu programlarda; oyun, sanat, müzik, hareket ve keşif temelli etkinliklerle çocukların gelişimleri desteklenirken anne-çocuk etkileşiminin güçlendirilmesi amaçlanmaktadır. Çocuklar akranlarıyla sosyalleşirken anneler de çocuklarının öğrenme süreçlerine eşlik eder.\n\n4 Yaş Eğitim Programı\n4 yaş grubundaki çocuklara yönelik programımızda çocukların bağımsızlaşma, sosyalleşme, iletişim, problem çözme ve üreticilik becerilerini destekleyen çalışmalar gerçekleştirilmektedir. Farklı eğitim modellerinden yararlanılarak hazırlanan etkinliklerde çocukların merak etmeleri, soru sormaları, araştırmaları ve kendi deneyimleriyle öğrenmeleri desteklenmektedir.\n\nEnderun'da her etkinlik bir keşif, her keşif yeni bir öğrenme fırsatıdır. Çocukların kendilerini özgürce ifade edebildikleri, meraklarını takip edebildikleri ve öğrenirken keyif aldıkları güvenli öğrenme ortamları oluşturuyoruz."],
                ['baslik' => 'Sportif Çocuk Atölyesi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_sportif-cocuk-atolyesi_26.html', 'img' => 'https://www.gebze.bel.tr/resim/20220218171335.jpg', 'detay' => "Sportif Çocuk Atölyesi; 5-8 yaş arası çocuklarımızın spor branşları ile tanışıp, temel eğitimlerinin ardından uygulanan testler sonrasında branşlara yönlendirilmesi ve sporcu kimliklerinin oluşturulması amaçlanmıştır.\n\nBranşlar;\nOkçuluk\nBasketbol\nMasa Tenisi\nKort Tenisi\nFutsal\nCimnastik\nBadminton\nVoleybol\nEğlenceli Atletizm\nBeceri ve Koordinasyon Parkurları\nYetenek Taraması"],
                [
                    'baslik' => 'Güzide Gençlik Merkezi Atölyeleri',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_guzide-genclik-merkezi-atolyeleri_38.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20250307151042.jpg',
                    'detay' => <<<'EOT'
Güzide Gençlik Merkezinde atölye faaliyetleri lise öğrencilerini hem akademiye hem de hayata hazırlayacak şekilde organize edilmektedir.

Akademik atölyeler başlığında Türk Dili ve Edebiyatı, Matematik ve Geometri derslerinin haricinde; XR Laboratuvarında yapılan faaliyetler, sosyal bilimlere dair atölyeler ve sınavlara ilişkin çalışmalar şeklinde üçlü bir yapılanma mevcuttur.

Hayata hazırlama amacı güden ve çeşitli yaşam becerilerini de kapsama alan atölye çalışmalarında ise Merkez içi yahut dışında yapılan bazı aktiviteler, genel kültüre katkı sunacak çalışmalar ve tekno-eğitim/tekno-eğlence faaliyetleri şeklinde yine üçlü bir yapılanma üzerinden gidilmektedir.

ATÖLYELER:

Akademik Atölyeler
Güzide Yarıyıl Kampı
Güzide Yaz Okulu
Fizik Dersleri
Arapça Dersleri
Osmanlıca Dersleri
Kur'an-ı Kerim Dersleri
Tarih Seminerleri
Sosyoloji Okumaları
Din Kültürü ve Ahlak Bilgisi Dersleri
Felsefe Okumaları
Psikoloji Okumaları
Güzide Edebiyat Kartları (GEK)
Klasik Şiir Atölyesi

Tekno-Akademik Atölyeler
XR Tanıtım Atölyesi
XR Fizik
XR Kimya
XR Biyoloji
XR Matematik
XR Geometri

Sınav Atölyeleri
YKS (TYT-AYT) Denemeleri
Soru Çözüm Kampları
Konu Tekrar Kampları
Sınav Analizleri

Genel Kültür Atölyeleri
Güzide Doğa Okulu (Güz, Kış ve Yaz Doğa Kampları, Geziler, İstikamet Programları)
Yazarlık Okulu
Kitap Tahlilleri
Sesli Kitap Atölyesi
Etimoloji Atölyesi
Bilgi Yarışmaları
Diksiyon ve Hitabet Dersleri
Edebiyat Söyleşileri
Dergicilik Okulu
Film Okumaları
Münazara

Sanat Atölyeleri
Geleneksel Sanat Atölyeleri (Hat, Tezhip, Kaligrafi, Ebru, Minyatür, Kat'ı)
Modern Sanat Atölyeleri (Resim / Kara Kalem, Yağlı Boya, Kuru Boya, Toz Pastel, Akrilik)
Tiyatro
Drama
El Sanatları Atölyesi (Kanaviçe, Örgü, Biçki, Dikiş)
Savunma Sanatı (Kick-Box)
Mutfak Sanatları Atölyesi
Ahşap
Cam Takı
Mimarlık Atölyesi
Müzik Atölyesi (Bendir, Kalimba)
Sergiler

Tekno-Eğitim Atölyeleri
Yazılım Atölyeleri (C#, Java, Python, Web [HTML-CSS], Web Tasarım, GeoGebra)
Robotik Atölyeleri (Arduino, Esp 32, Rex, Mblock 5, Pinoo)
Güzide Garaj
Dijital Eğitim Sınıfları
3 D Yazıcı Atölyesi
Greenbox Stüdyosu
Podcast Stüdyosu

Tekno-Eğlence Atölyeleri
Laser-Tag
VR (Sanal Gerçeklik Gözlüğü)
Uçuş Simülatörü
Araç Simülatörü
PS 5
Klasik Atari

Spor Atölyeleri
Fitness
Pilates
Masa Tenisi
Bilardo
Langırt
Shuffleboard
Satranç
Okçuluk
Voleybol
Futsal
Cornhole

Rehberlik-Psikolojik Danışmanlık Atölyeleri
Manevi Danışmanlık (Değerler Eğitimi)
Psikolog (Psikoloji Kulübü, Grup Etkinlikleri)
Psikolojik Danışman (Kariyer Okulu, Sınav Kaygısı, Sınav Stratejileri Seminerleri)
Aile Danışmanı (Ebeveyn Psikoloji Atölyeleri, Ergenlik, Akademik Başarı Seminerleri)
Eğitim Danışmanı (Tercih Danışmanlığı, Sınav Bilgilendirme Seminerleri)
Sağlık Danışmanlığı (Hemşire) (Beslenme ve Diyetetik, Geleneksel Tıp, Kadın Sağlığı, Spor ve Sağlık Seminerleri)
Öğrenci Seminerleri
Veli Seminerleri
Zarafet Atölyesi

Diğer Atölyeler
Mangala
Lego
Resfebe
Zekâ Oyunları
Materyal Tasarım
Kriptoloji
Tabu
MaTabu

Mukabele
EOT,
                ],
            ],
        ],
        'kutuphane' => [
            'label' => 'Kütüphane',
            'icon' => 'bi-book-half',
            'ogeler' => [
                ['baslik' => 'İstasyon Bilim ve Sanat Merkezi Kütüphanesi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_istasyon-bilim-ve-sanat-merkezi-kutuphanesi_27.html', 'img' => 'https://www.gebze.bel.tr/resim/20220221164833.jpg', 'detay' => "İstasyon Bilim ve Sanat Merkezi Kütüphanemiz Haftaiçi ve Haftasonu 09:00/21:00 saatleri arasında her gün tüm halkımıza açıktır.\n\nKütüphanemizde 7.000 adet yetişkin, çocuk ve okul öncesi yaş gruplarına ait koleksiyon bulunmaktadır. Süreli yayın koleksiyonumuzla her yaş grubunda farklı içerikleri okuyucularımızla buluşturuyoruz.\n\nKütüphanemizde ziyaretçilerimizin kullanımına sunduğumuz bilgisayarlar ile internete erişim sağlayabilirsiniz. Kütüphanemizin her noktasında ziyaretçilerimizin kendi kişisel tablet, telefon ve bilgisayarları ile kullanabilecekleri ücretsiz Wi-Fi ağımız bulunmaktadır.\n\nSizlere sunduğumuz bu hizmetlerden kütüphanemize üyelik yaptırarak ücretsiz olarak yararlanabilirsiniz. Ayrıca https://kutuphane.gebze.bel.tr/yordam web adresi üzerinden üyelik bilgileriniz ile oturum açarak online kitap arama, süre uzatma, kitap ayırtma ve rezervasyon işlemlerinizi gerçekleştirebilirsiniz."],
                ['baslik' => 'Beylikbağı Bilim ve Sanat Merkezi Kütüphanesi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_beylikbagi-bilim-ve-sanat-merkezi-kutuphanesi_28.html', 'img' => 'https://www.gebze.bel.tr/resim/20220221165132.jpg', 'detay' => "Beylikbağı Bilim ve Sanat Merkezi Kütüphanemiz Haftaiçi ve Haftasonu 09:00/21:00 saatleri arasında her gün tüm halkımıza açıktır.\n\nKütüphanemizde 8.000 adet yetişkin, çocuk ve okul öncesi yaş gruplarına ait koleksiyon bulunmaktadır. Süreli yayın koleksiyonumuzla her yaş grubunda farklı içerikleri okuyucularımızla buluşturuyoruz.\n\nKütüphanemizde ziyaretçilerimizin kullanımına sunduğumuz bilgisayarlar ile internete erişim sağlayabilirsiniz. Kütüphanemizin her noktasında ziyaretçilerimizin kendi kişisel tablet, telefon ve bilgisayarları ile kullanabilecekleri ücretsiz Wi-Fi ağımız bulunmaktadır.\n\nSizlere sunduğumuz bu hizmetlerden kütüphanemize üyelik yaptırarak ücretsiz olarak yararlanabilirsiniz. Ayrıca https://kutuphane.gebze.bel.tr/yordam web adresi üzerinden üyelik bilgileriniz ile oturum açarak online kitap arama, süre uzatma, kitap ayırtma ve rezervasyon işlemlerinizi gerçekleştirebilirsiniz."],
                ['baslik' => 'Çoban Mustafa Paşa Kütüphanesi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_coban-mustafa-pasa-kutuphanesi_29.html', 'img' => 'https://www.gebze.bel.tr/resim/20230518141633.jpg', 'detay' => "Çoban Mustafa Paşa Kütüphanemiz Haftaiçi ve Haftasonu 09:00/24:00 saatleri arasında her gün tüm halkımıza açıktır. Kütüphanemizdeki oturma alanlarını kullanabilmek için https://kutuphane.gebze.bel.tr/yordam web adresi üzerinden üyelik bilgileriniz ile oturum açarak rezervasyon yaptırmanız gerekmektedir. Ayrıca web adresi üzerinden üyelik bilgileriniz ile oturum açarak online kitap arama, süre uzatma, kitap ayırtma ve rezervasyon işlemlerinizi gerçekleştirebilirsiniz.\n\nKütüphanemizde 10 kişilik ve 12 kişilik grup çalışma odası bulunmakta olup bu alanlarda ekip arkadaşlarınızla proje tasarlayabilir ve Smart TV ile görsel paylaşım yapabilirsiniz.\n\nKütüphanemizde 20.000 adet yetişkin, çocuk ve okul öncesi yaş gruplarına ait koleksiyon bulunmaktadır. Süreli yayın koleksiyonumuzla her yaş grubunda farklı içerikleri okuyucularımızla buluşturuyoruz.\n\nKütüphanemizde ziyaretçilerimizin kullanımına sunduğumuz bilgisayarlar ile internete erişim sağlayabilirsiniz. Kütüphanemizin her noktasında ziyaretçilerimizin kendi kişisel tablet, telefon ve bilgisayarları ile kullanabilecekleri ücretsiz Wi-Fi ağımız bulunmaktadır."],
                ['baslik' => 'Arapçeşme Bilim ve Sanat Merkezi Kütüphanesi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_arapcesme-bilim-ve-sanat-merkezi-kutuphanesi_33.html', 'img' => 'https://www.gebze.bel.tr/resim/20230518135556.jpg', 'detay' => "Arapçeşme Bilim ve Sanat Merkezi Kütüphanemiz Haftaiçi ve Haftasonu 09:00/21:00 saatleri arasında her gün tüm halkımıza açıktır.\n\nKütüphanemizde 5.250 adet yetişkin, çocuk ve okul öncesi yaş gruplarına ait koleksiyon bulunmaktadır. Süreli yayın koleksiyonumuzla her yaş grubunda farklı içerikleri okuyucularımızla buluşturuyoruz.\n\nKütüphanemizde ziyaretçilerimizin kullanımına sunduğumuz bilgisayarlar ile internete erişim sağlayabilirsiniz. Kütüphanemizin her noktasında ziyaretçilerimizin kendi kişisel tablet, telefon ve bilgisayarları ile kullanabilecekleri ücretsiz Wi-Fi ağımız bulunmaktadır.\n\nSizlere sunduğumuz bu hizmetlerden kütüphanemize üyelik yaptırarak ücretsiz olarak yararlanabilirsiniz. Ayrıca https://kutuphane.gebze.bel.tr/yordam web adresi üzerinden üyelik bilgileriniz ile oturum açarak online kitap arama, süre uzatma, kitap ayırtma ve rezervasyon işlemlerinizi gerçekleştirebilirsiniz."],
                [
                    'baslik' => 'Güzide Gençlik Merkezi Kütüphanesi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_guzide-genclik-merkezi-kutuphanesi_34.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20250604134614.jpeg',
                    'detay' => <<<'EOT'
Güzide Gençlik Merkezi kütüphanemiz eğitim-öğretim döneminde hafta içi ve hafta sonu 09.00 / 21.00 saatleri arasında, yaz döneminde hafta içi 09.00 / 18.00 saatleri arasında 14-21 yaş aralığındaki lise ve mezun öğrencilerimize hizmet vermektedir.

Kütüphanemizde 2025 yılı 5. ay itibariyle lise öğrencilerine uygun olmak kaydıyla 7.175 adet kitap ve dergihanemizde 15 farklı süreli yayın bulunmaktadır.

Bilgisayardan ve uygulama üzerinden randevu usulü ile çalışan kütüphanemizin 16'sı bilgisayarlı olmak kaydıyla 114 kapasitesi mevcuttur. Ayrıca kütüphanemizin her noktasında öğrencilerimiz kendi kişisel tablet, telefon ve bilgisayarları ile ücretsiz Wi-Fi hizmeti alabilmektedir.

Bunun yanı sıra Güzide Gençlik Merkezi Kütüphanemizde öğrencilerimize Türk Dili ve Edebiyatı, Matematik, Geometri ve Felsefe alanlarında sürekli soru çözüm desteği sağlanmaktadır.

2025 yılı 5. ay itibariyle 1.505 öğrencinin üyesi olduğu kütüphanemize mezkûr kapasiteyle 2024 yılında 112.655 öğrenci rezervasyon yaptırmıştır.

Online kitap arama, süre uzatma, kitap ayırtma ve rezervasyon işlemleri https://kutuphane.gebze.bel.tr/yordam web adresi üzerinden yapılabilmektedir.

Sosyal Medya Hesapları:
Instagram: https://www.instagram.com/gebzeguzidegenclik/
Facebook: https://www.facebook.com/gebzeguzidegenclik
EOT,
                ],
                ['baslik' => 'Barış Sosyal Tesis Kütüphanesi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_baris-sosyal-tesis-kutuphanesi_37.html', 'img' => 'https://www.gebze.bel.tr/resim/20240812171333.jpg', 'detay' => "Barış Sosyal Tesis Kütüphanemiz Haftaiçi 09:00/18:00 saatleri arasında tüm halkımıza açıktır.\n\nKütüphanemizde 3.000 adet genç ve yetişkin yaş gruplarına ait koleksiyon bulunmaktadır.\n\nKütüphanemizde ziyaretçilerimizin kullanımına sunduğumuz ücretsiz Wi-Fi ağı ile internete erişim sağlayabilirsiniz.\n\nSizlere sunduğumuz bu hizmetlerden kütüphanemize üyelik yaptırarak ücretsiz olarak yararlanabilirsiniz. Ayrıca https://kutuphane.gebze.bel.tr/yordam web adresi üzerinden üyelik bilgileriniz ile oturum açarak online kitap arama, süre uzatma, kitap ayırtma ve rezervasyon işlemlerinizi gerçekleştirebilirsiniz."],
            ],
        ],
        'bebek-cocuk-bakimevi' => [
            'label' => 'Bebek ve Çocuk Bakımevi',
            'icon' => 'bi-balloon-heart-fill',
            'ogeler' => [
                [
                    'baslik' => 'Güzide 7/24 Bebek ve Çocuk Bakımevi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_guzide-7-24-bebek-ve-cocuk-bakimevi_31.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20220906143134.jpg',
                    'detay' => <<<'EOT'
"Gece gündüz demeden, sevgiyle…" düsturuyla yola çıktığımız Güzide Bebek ve Çocuk Bakımevi Türkiye'de bir ilke imza atarak, 7/24 bebek ve çocuk bakım hizmeti veren ilk kuruluş olmuştur.

Güzide Bebek ve Çocuk Bakımevi 0-66 ay arası çocuklara hizmet vermektedir. Günün her saatinde kendilerini güvende hissetmelerini sağlamak, temel öz bakımlarını ve gelişimsel ihtiyaçlarını karşılamak hedeflerimizin başında gelmektedir. Ayrıca İngilizce, Değerler Eğitimi ve Sanat Atölyeleri ile gelişimleri desteklenmektedir.

Ferah ve geniş bahçesiyle de çocuklarımıza büyüme, gelişme ve eğlenme ortamı sunulmaktadır.

Kurumumuz anne şefkati ile deneyimli öğretmenleri, hizmetli personeli ve yönetimiyle hizmet vermeye devam etmektedir.
EOT,
                ],
            ],
        ],
        'mesire-alani' => [
            'label' => 'Mesire Alanı',
            'icon' => 'bi-tree-fill',
            'ogeler' => [],
        ],
        'merkezler' => [
            'label' => 'Merkezler',
            'icon' => 'bi-building',
            'ogeler' => [
                [
                    'baslik' => 'Atlı Rehabilitasyon ve Eğitim Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_atli-rehabilitasyon-ve-egitim-merkezi_1.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20260226133437.jpeg',
                    'detay' => <<<'EOT'
Gebze Belediyesi olarak, Gebze ve çevresindeki il ve ilçelerde yaşayan vatandaşlarımıza binicilik branşında eğitim hizmetleri sunmakta; gezinti faaliyetleri, çeşitli projeler ve sosyal etkinlikler aracılığıyla bireylerin binicilik sporu ile tanışmalarını sağlamaktayız.

Tesisimizde, online randevu sistemi üzerinden başvuru yapan vatandaşlarımıza gezinti ve temel binicilik eğitimleri verilmektedir. Eğitimler başlangıç seviyesinden başlatılmakta olup, 10 haftalık planlı eğitim programı çerçevesinde yürütülmektedir. 8 yaş ve üzeri çocuklar ile yetişkinlere yönelik 20 dakika süreli temel binicilik eğitimi sonunda programı tamamlayan katılımcılara katılım sertifikası verilmektedir.

Eğitim programı kapsamında çocuk ve yetişkin katılımcılara; atı tanıma, ata yaklaşma, temel binicilik ve güvenli biniş konularında uygulamalı eğitimler sunulmaktadır. Ayrıca 4 yaş ve üzeri bireyler için düzenlenen biniş alanında, belirlenen parkurda 5 tur şeklinde gezinti faaliyetleri gerçekleştirilmektedir.

Özel gereksinimli çocuk ve bireylere yönelik atlı hippoterapi uygulamaları ile katılımcıların fiziksel, zihinsel ve psikolojik gelişimlerine katkı sağlanmakta; bireylerin sosyal hayata uyumlarının desteklenmesi ve tedavi süreçlerine yardımcı olunması amaçlanmaktadır.
EOT,
                ],
                [
                    'baslik' => 'İstasyon Bilim ve Sanat Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_istasyon-bilim-ve-sanat-merkezi_2.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20191125071343.jpg',
                    'detay' => <<<'EOT'
Bilginin, sanatın ve hareketin buluşma noktası.

İstasyon Bilim Sanat Merkezi; her yaş grubuna hitap eden eğitimleriyle öğrenmeyi hayatın merkezine taşıyor.

0-6 yaş arası çocuklarımız için;
Enderun Çocuk Atölyeleri, çocukların gelişimini desteklemek amacıyla düzenlenen; oyun, sanat, bilim ve değerler eğitimi temelli etkinliklerin yer aldığı atölye programıdır. Çocukların eğlenerek öğrenmesini hedefleyen atölyeler, farklı merkezlerde yaş gruplarına uygun olarak uygulanmaktadır.

6-14 yaş arası çocuklar burada;
Akademik destek dersleriyle sağlam bir temel kazanırken, sanat ve müzik branşlarıyla kendilerini ifade etmeyi öğreniyor. Robotik kodlama ve Meraklı Çocuk Atölyeleriyle sorgulayan, düşünen ve üreten bireyler yetişiyor. Satranç, cimnastik ve masa tenisiyle fiziksel gelişim de destekleniyor.

14 yaş ve üzeri bireyler için;
GESMEK kursları sayesinde yeni bir hobi edinmek, mesleki beceriler kazanmak ya da kendini geliştirmek mümkün. El sanatları, giyim üretimi, Kur'an-ı Kerim eğitimleri ve okuma yazma kurslarının yanı sıra step aerobik ve pilates ile aktif bir yaşam sunuluyor.

İstasyon Bilim Sanat Merkezi;
100 kişilik konferans salonu, kütüphane, spor salonu ve uzman destek hizmetleri ile öğrenmenin her hâline ev sahipliği yapıyor.
EOT,
                ],
                [
                    'baslik' => 'Beylikbağı Bilim ve Sanat Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_beylikbagi-bilim-ve-sanat-merkezi_3.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20191125071548.jpg',
                    'detay' => <<<'EOT'
Her yaş için öğrenme, her adımda gelişim.

Beylikbağı Bilim Sanat Merkezi; çocukların potansiyelini ortaya çıkaran, yetişkinlerin üretkenliğini destekleyen samimi bir eğitim ortamı sunuyor.

6-14 yaş arası çocuklar için;
Akademik destek dersleriyle okul başarısı güçlendirilirken, sanat ve müzik branşlarıyla özgüven gelişiyor. Meraklı Çocuk Atölyeleri çocukları keşfetmeye teşvik ederken, satranç ile stratejik düşünme becerileri kazandırılıyor.

14 yaş ve üzeri bireyler için;
GESMEK kursları sayesinde el becerileri, mesleki yetkinlikler ve kişisel gelişim destekleniyor. Giyim üretimi, örgü, dantel, el sanatları, okuma yazma ve Kur'an-ı Kerim eğitimleriyle üretken bireyler yetişiyor.

Merkez;
Konferans salonu, kütüphane ve uzman danışmanlık hizmetleri ile sadece bugünü değil, geleceği de şekillendiriyor.
EOT,
                ],
                [
                    'baslik' => 'Arapçeşme Bilim ve Sanat Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_arapcesme-bilim-ve-sanat-merkezi_4.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20191125074454.jpeg',
                    'detay' => <<<'EOT'
Keşfet, öğren, geliş!

Arapçeşme Bilim Sanat Merkezi; çocukların yeteneklerini keşfettiği, gençlerin kendini geliştirdiği, yetişkinlerin ise yeni beceriler kazandığı çok yönlü bir yaşam alanıdır.

0-6 yaş arası çocuklarımız için;
Enderun Çocuk Atölyeleri, çocukların gelişimini desteklemek amacıyla düzenlenen; oyun, sanat, bilim ve değerler eğitimi temelli etkinliklerin yer aldığı atölye programıdır. Çocukların eğlenerek öğrenmesini hedefleyen atölyeler, farklı merkezlerde yaş gruplarına uygun olarak uygulanmaktadır.

6-14 yaş arası çocuklarımız için;
Akademik destek derslerinden sanata, robotik kodlamadan spora uzanan zengin içerikler sunuluyor. Türkçe, Matematik, Fen Bilimleri, İngilizce ve Kur'an-ı Kerim destek eğitimleriyle okul başarısı desteklenirken; resim, drama, müzik ve zekâ oyunlarıyla çocukların hayal gücü güçleniyor. Meraklı Çocuk Atölyeleri ve satranç, cimnastik, geleneksel okçuluk gibi spor branşlarıyla öğrenme eğlenceye dönüşüyor.

14 yaş ve üzeri bireyler için;
GESMEK kurslarıyla hem mesleki hem kişisel gelişim destekleniyor. El sanatlarından giyim üretimine, bağlamadan filografiye, Kur'an-ı Kerim eğitimlerinden okuma yazmaya kadar birçok alanda eğitim veriliyor. Step aerobik, pilates ve fitness ile sağlıklı yaşam da bu merkezin bir parçası.

Arapçeşme Bilim Sanat Merkezi;
Konferans salonu, kütüphane, spor salonu ve psikolog-pedagog-diyetisyen desteği ile yalnızca bir kurs merkezi değil, tam anlamıyla bir gelişim merkezidir.
EOT,
                ],
                ['baslik' => 'Gebze Kültür Merkezi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_gebze-kultur-merkezi_5.html', 'img' => 'https://www.gebze.bel.tr/resim/20191125075147.jpg', 'detay' => "Gebze Kültür Merkezi; kültür, sanat, spor, sağlık gibi bir çok alanda 7'den 70'e her kesime hitap eden ücretsiz programlarımızla hizmet vermektedir. Ayrıca GKM salonumuz ve Kardelen salonumuz kamu kurum ve kuruluşlarına ücret karşılığında tahsis edilmektedir."],
                [
                    'baslik' => 'GESMEK',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_gesmek_8.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20191125104342.jpg',
                    'detay' => <<<'EOT'
Yeni bir beceri, yeni bir başlangıç.

GESMEK; 14 yaş ve üzeri yetişkinler için tasarlanmış, öğrenmenin yaşı olmadığını kanıtlayan büyük bir eğitim ailesidir.

Sanattan mesleğe, hobiden kişisel gelişime, dilden teknolojiye uzanan 77 farklı branşta, 17 kurs merkezinde eğitim imkânı sunulmaktadır.

El sanatlarından müziğe, sahne sanatlarından bilgisayar programlarına, dil eğitimlerinden mesleki gelişime kadar herkes için bir kurs mutlaka vardır.

GESMEK ile; yeni bir meslek edinebilir, hobinizi geliştirebilir, kendinize zaman ayırabilir ve sosyal hayata daha güçlü katılabilirsiniz.

Hobi Kursları:
1. Örgü Oyuncak
2. El Nakışı (Elde Türk İşi)
3. Kumaş Boyama
4. Zikzaklı Makine Nakışı
5. Sanayi Makinesinde Türk Nakışı
6. Tel Kırma
7. Geleneksel Keçe Yapımı
8. Kırkyama
9. Bebek Odası (Aplike Yapımı)
10. İğne Oyası (Oyalar)
11. Makinada Dantel Sarma ve Piko Yapma
12. Dekoratif Ev Aksesuarları
13. Giyim Üretiminde Temel İşlemler
14. Kadın Giysileri Kalıp Hazırlama
15. Dekoratif Ahşap Boyama
16. Deri Çanta Kemer ve Aksesuarları
17. Takı Yapım Teknikleri
18. Atık Kâğıt Ev Aksesuarları
19. Şiş Örücülüğü
20. Sukulent Teraryum Tasarımı
21. El Nakışı (Elde Maraş İşi)
22. Kitre Bebek
23. Kilim Halı Dokuma
24. El Nakışı (Elde Antep İşi)
25. El Nakışı (Kanaviçe Tekniği)
26. Hazır Giyim Makinelerinde Geliştirme
27. Fotoğraf Çekimi
28. Türk Halk Müziği
29. Türk Sanat Müziği
30. Türk Tasavvuf Müziği
31. Bağlama 1. Seviye
32. Bağlama 2. Seviye
33. Keman 1. Seviye
34. Keman 2. Seviye
35. Gitar Klasik/Popüler
36. Ney
37. Def/Ritim
38. Piyano
39. Tiyatro
40. Halk Oyunları
41. Sepet Örücülüğü (Bitkisel Örücülük)
42. Klarnet Eğitimi

Sanat Kursları:
43. Hüsn-i Hat Deneyim
44. Hüsn-i Hat İhtisas
45. Hüsn-i Hat Başlangıç
46. Minyatür
47. Tezhip
48. Tezhip İhtisas
49. Kaligrafi
50. Ebru
51. Resim
52. Naht
53. Seramik Başlangıç
54. Seramik Mozaik İhtisas
55. Çini Dökümcü Başlangıç
56. Çini Dökümcü Deneyim
57. Çini Tahrirci İhtisas
58. Filografi

Meslek Kursları:
59. Ofis Programları
60. İleri Excel Uyum Eğitimi
61. AutoCAD
62. SolidWorks
63. Hızlı Klavye Kullanımı
64. Genel Muhasebe

Kişisel Gelişim Kursları:
65. Diksiyon/Spikerlik/Sunuculuk
66. Türk İşaret Dili
67. Kur'an-ı Kerim Elif-Be Hızlandırılmış
68. Kur'an-ı Kerimi Tecvidli Okuma Hızlandırılmış
69. Yetişkinler İçin 1. Kademe Okuma-Yazma
70. Gassallık

Dil Kursları:
71. İngilizce A1
72. İngilizce A2
73. Speaking A1
74. Speaking A2
75. Arapça A1
76. Arapça A2
77. Arapça B1

Merkezler:
1. GESMEK Merkez Bina
2. Arapçeşme Bilim Sanat Merkezi
3. İstasyon Bilim Sanat Merkezi
4. Beylikbağı Bilim Sanat Merkezi
5. Yenikent Mh. Kursu
6. Gaziler Mh. Kursu
7. Ademyavuz Mh. Kursu
8. Barış Mh. Kursu
9. Muallimköy Mh. Kursu
10. Yavuz Selim Anaokulu
11. Cumaköy Mh. Kursu
12. Tavşanlı Mh. Kursu
13. Kirazpınar Mh. Kursu
14. Mevlana Mh. Kursu
15. Çoban Mustafa Paşa Külliyesi
16. Mimoza Salonu
17. Kardelen Salonu
EOT,
                ],
                [
                    'baslik' => 'Aile Danışmanlık Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_aile-danismanlik-merkezi_23.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20220217103753.jpg',
                    'detay' => <<<'EOT'
Gebze Belediyesi Aile Danışmanlık Merkezi, bireyin ve ailenin fiziksel, ruhsal ve sosyal iyilik hâlini güçlendirmeyi amaçlayan bütüncül bir anlayışla hizmet sunmaktadır. Alanında uzman kadrosuyla çocuklardan yetişkinlere, bireysel ihtiyaçlardan aile bütünlüğüne uzanan geniş bir yelpazede destek sağlamaktadır.

Merkezimizde görev yapan diyetisyen ve yetişkin psikologlarımız; sağlıklı yaşam alışkanlıklarının kazandırılması, ruhsal dayanıklılığın artırılması ve yaşam kalitesinin yükseltilmesi adına danışanlarımıza profesyonel rehberlik sunmaktadır.

Hizmet ağımız, Aile Danışmanlık Merkezi'nin yanı sıra Gebze'nin farklı noktalarında yer alan Bilim Sanat Merkezlerimizle de güçlenmektedir. Arapçeşme Bilim Sanat Merkezi, Beylikbağı Bilim Sanat Merkezi ve İstasyon Bilim Sanat Merkezlerimizde, yetişkinlere yönelik hizmetlerimizin yanı sıra çocuk ve ergenlere özel danışmanlık desteği de verilmektedir.

Alanında deneyimli çocuk psikologları tarafından yürütülen oyun terapisi, çocukların duygu ve davranışlarını sağlıklı bir şekilde ifade etmelerine, güvenli bağlar kurmalarına ve gelişim süreçlerinde güçlü adımlar atmalarına yardımcı olmaktadır.

Gebze Belediyesi olarak; güçlü bireyler, sağlıklı aileler ve bilinçli bir toplum hedefiyle, uzman kadromuz ve erişilebilir hizmet anlayışımızla her zaman vatandaşlarımızın yanında olmaya devam ediyoruz.
EOT,
                ],
                [
                    'baslik' => 'Eray Şamdan Spor Salonu',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_eray-samdan-spor-salonu_24.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20220218170627.jpg',
                    'detay' => <<<'EOT'
Tesisimizde 1 adet spor salonu, cimnastik, okçuluk ve masa tenisi salonları da bulunmaktadır.

Spor salonumuzda yaz ve kış olmak üzere iki dönemde birçok spor branşında eğitim verilmektedir.

Salonumuz okul müsabakalarına da ev sahipliği yapmaktadır.

Salonumuzun uygunluğuna göre vatandaşlarımızın ücretsiz olarak spor faaliyetleri yapmalarına imkân sağlanmaktadır.

Branşlar:
Basketbol
Cimnastik
Hentbol
Masa Tenisi
Okçuluk
Sportif Çocuk Atölyesi
Tenis
Voleybol
EOT,
                ],
                ['baslik' => 'Sokak Hayvanları Tedavi, Rehabilitasyon ve Eğitim Merkezi', 'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_sokak-hayvanlari-tedavi-rehabilitasyon-ve-egitim-merkezi_30.html', 'img' => 'https://www.gebze.bel.tr/resim/20220630145338.jpg', 'detay' => "Veteriner İşleri Müdürlüğü bünyesinde bulunan Sokak Hayvanları Tedavi, Rehabilitasyon ve Eğitim Merkezimiz, ilçemizin sahipsiz sokak hayvanlarına 7/24 esaslı çalışma prensibi ile hizmet etmektedir.\n\nMerkezimizde sahipsiz sokak hayvanlarımıza hizmet için; 3 hayvan refah ekibi, 1 adet mobil klinik (acil durum müdahale aracı), 3 alanında uzman veteriner hekim, 12 destek ve yardımcı personel, donanımlı bir ameliyathane, muayenehane, laboratuvar, yoğun bakım üniteleri, müşahede bölümü bulunmaktadır. Ayrıca merkezimizde ilk, orta ve lise düzeyi öğrencilerimiz için eğitim salonu mevcut olup hayvan sevgisi, zoonoz hastalıklar, hijyen ve veteriner hekimlik mesleği ile ilgili yıl boyunca eğitimler ve seminerler düzenlenmektedir."],
                [
                    'baslik' => 'Güzide Gençlik Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_guzide-genclik-merkezi_35.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20250604135539.png',
                    'detay' => <<<'EOT'
Güzide Gençlik Merkezi, Gebze'nin merkezi sayılabilecek bir konumunda, eski Gebze Adliye binasının restore edilmesiyle ortaya çıkan ve mülkü Gebze Belediyesine ait olan bir yapıdır. Lise gençlik merkezi olarak kurgulanan bu yapıda hâlihazırda lise okuyan ve yeni mezun olan gençlere hizmet verilmektedir. Yaş itibariyle 14-21 yaş arası bu yapıdan hizmet alabilmektedir.

Güzide Gençlik Merkezinde akademik program Türk Dili ve Edebiyatı ile Matematik derslerinin merkeze alındığı ve yoğun olarak işlendiği bir anlayışla inşa edilmiştir. Ayrıca diğer branşlar; seminerler, özel çalışma grupları ve farklı eğitim metotları üzerinden öğrencilere sunulmaktadır.

Akademik programın yanı sıra Güzide Gençlik Merkezinde beden ve ruh sağlığına matuf, sosyoloji, psikoloji, fizyoloji, eğitim bilimleri, aile danışmanlığı, akademik ve manevi rehberlik alanlarından müteşekkil çalışmalar "Denge" başlığı altında toplanmıştır. Denge'den maksat, yukarıda mezkûr tüm alanlarda öğrencilerimize dengeli olmayı teklif etmektir.

Hizmetler:
Güzide Teras
Kütüphane
Dergihane
Sesli Kitap Atölyesi
Edebiyat Derslikleri
Matematik Derslikleri
Geleneksel Sanat Atölyeleri (Hat, Tezhip, Kaligrafi, Ebru, Minyatür, Kat'ı)
El Sanatları Atölyesi (Kanaviçe, Biçki, Dikiş, Örgü)
Resim Atölyesi (Kara Kalem, Yağlı Boya, Kuru Boya, Toz Pastel, Akrilik)
Gastronomi Atölyesi
Savunma Sanatı (Kick-Box)
Klasik Oyun Salonu (Satranç, Mangala, Ahşap Oyunlar)
Robotik Atölyesi
Güzide Garaj (Teknofest Çalışma Alanı)
Yazılım Sınıfları
Dijital Eğitim Sınıfları
3D Baskı Atölyesi
Uçuş Simülatörü
Araç Simülatörü
VR (Sanal Gerçeklik Gözlüğü)
Dijital Oyun Salonu
PS 5
Klasik Atari
Psikolog
Aile Danışmanı
Eğitim Danışmanı
Aile İletişim Ofisi
Değerler Eğitimi
Çok Amaçlı Eğitim Salonları
Hemşire
Diyetisyen
XR Laboratuvarı (Genişletilmiş Gerçeklik Teknolojisi)
Güzide Kafe
Greenbox Stüdyo
Podcast Stüdyosu
Bilardo Salonları
Masa Tenisi
Langırt
Shuffleboard
Fitness Salonu
Pilates
Laser Tag
EOT,
                ],
                [
                    'baslik' => 'Güzide Spor ve Sağlıklı Yaşam Merkezi',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_guzide-spor-ve-saglikli-yasam-merkezi_42.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20251015163900.jpg',
                    'detay' => <<<'EOT'
Gebze Belediyesi Kadın ve Aile Hizmetleri Müdürlüğü bünyesinde hizmet veren Güzide Spor ve Sağlıklı Yaşam Merkezi, kadınların fiziksel ve ruhsal olarak güçlenmeleri için modern, konforlu ve profesyonel bir ortam sunuyor.

Modern Donanım - Ferah Alanlar
Geniş ve ferah spor salonunda yer alan son teknoloji spor aletleri, hijyenik ortam ve konforlu bir alanda spor yapmanın keyfini yaşayabilirsiniz. Mekânın aydınlık mimarisi, motivasyonunuzu artırırken günün stresini geride bırakmanızı sağlar.

Rahatlık Ön Planda
Spor sonrası kullanabileceğiniz modern soyunma odaları, duş alanları ve kişisel dolaplar sayesinde kendinizi evinizin konforunda hissedeceksiniz.

Kadınlara Özel, Sağlıklı Yaşam Alanı
Merkezimizde yalnızca kadınlara özel alanlar bulunmakta olup, mahremiyet ve rahatlık ön planda tutulmuştur.

Uzman Eşliğinde Güvenli Spor
Spor yaparken yalnız değilsiniz! Diyetisyen ve fizyoterapist desteğiyle hem vücudunuzun ihtiyaçlarına uygun bir beslenme planı oluşturabilir hem de sağlıklı ve doğru şekilde egzersiz yapabilirsiniz.

Güzide'de Sunulan Hizmetler:
Aletli Pilates
Medikal Pilates
Mat Pilates
Fitness
Crunch
Zumba
Hiit Cardio
Step-Aerobik

Adres: Yenikent, 2432. Sk. No:8, 41400 Gebze/Kocaeli
Kayıt: http://sende.gebze.bel.tr/
Bilgi: 0262 642 0548

Güzide Spor ve Sağlıklı Yaşam Merkezi'nde her adımınızda sağlık, denge ve huzur var. Gelin, birlikte daha güçlü, daha sağlıklı ve daha mutlu bir siz için yola çıkalım!
EOT,
                ],
            ],
        ],
        'geri-donusum' => [
            'label' => 'Geri Dönüşüm',
            'icon' => 'bi-recycle',
            'ogeler' => [
                [
                    'baslik' => 'Sıfır Atık Nedir?',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_sifir-atik-nedir_14.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20201214104343.jpg',
                    'detay' => <<<'EOT'
"Sıfır Atık"; israfın önlenmesini, kaynakların daha verimli kullanılmasını, atık oluşumunun engellenmesi veya azaltılması, atığın oluşması durumunda ise kaynağında ayrı toplanması ve geri kazanılmasını kapsayan atık yönetim felsefesi; kültürel, ekonomik ve sosyal gelişimin elde edilmesi için atıkların yaşam döngüsünü dikkate alan bir yaklaşım biçimidir.

Sıfır atık yönetiminde, ürünlerin yeniden kullanılması, kullanım ömürlerinin uzatılması, ürünlerin üretiminde zararlı maddelerin kullanılmaması veya azaltılması, geri dönüşümü mümkün ürünlerin üretilmesi esastır. Atık yönetim hiyerarşisi sıfır atık yönetim sisteminin temelini oluşturur. Buna göre atık oluşumunun önlenmesi, atık azaltımı, atık oluşumunun önlenemediği durumda yeniden kullanımı sıfır atık yönetim sisteminin basamaklarıdır. Yeniden kullanımı mümkün olmayan atıkların ise maddesel geri kazanımı veya enerji olarak geri kazanımı şeklinde değerlendirilmesi gereklidir. Sıfır atık yönetim sisteminin oluşturulması çevre kirliliğinin giderilmesi için gerekli maliyetlerin azaltılması bakımından da önemlidir. Sıfır atık yönetim yaklaşımı esasen herhangi bir yaşam alanının tasarım aşamasında planlanmalıdır. Başarılı bir yönetim modeli elde etmek için atık yönetim çalışmaları, site, apartman, mahalle-cadde, kent meydanı, okul, plaza, alışveriş merkezleri (AVM), havaalanı, OSB ve sanayi tesisleri, pazar yerleri, eğitim sağlık ve turizm yerleşkeleri gibi bir şehre ait ne kadar yaşam alanı var ise bunların mimari tasarım aşamasında ele alınmalıdır. Binaların ve yaşam alanlarının tasarım aşamasında planlama yapılarak bu alanların sıfır atık yönetimine uygun şekilde inşa edilmesi en ideal durumdur. Ancak bunun mümkün olmaması durumunda sistem kurulumu için uygulama basamakları takip edilerek sonuca ulaşmak mümkündür.

Sıfır Atık Hakkında:
Sıfır atık yaklaşımının esas alınması ile sağlanacak avantajlar;
- Verimliliğin artması
- Temiz ortam kaynaklı olarak performansın artması
- İsrafın önüne geçildiğinden maliyetlerin azaltılması
- Çevresel risklerin azalmasının sağlanması
- Çevre koruma bilincinin kurum bünyesinde gelişmesine katkı sağlandığından çalışanların "duyarlı tüketici" duygusuna sahip olmasının sağlanması
- Ulusal ve uluslararası pazarlarda kurumun "Çevreci" sıfatına sahip olmasının sağlanması, bu sayede saygınlığının arttırılması

Sıfır Atık Yönetimi Sisteminin Uygulama Adımları:
1. Odak noktası
2. Mevcut durum
3. Planlama
4. İhtiyaç ve termin
5. Eğitim
6. Uygulama
7. Raporlama ve takip

Gebze Belediyesi olarak 2009 yılından itibaren GEKAP kapsamında yürütmüş olduğumuz geri dönüşüm faaliyetleri, 2017 yılında başlatılan Sıfır Atık Projesi öncülüğünde belediye ana-ek hizmet binaları ve genel hizmet alanında sürdürülmektedir. Okullar, kamu kurumları, hastaneler, işletmeler ve tüm hanelerden oluşabilecek atık türlerinin kaynağında ayrı biriktirilmesi, atık kategorizasyonunun sağlanması, israfın önlenmesi için ambalaj atıkları yönetim planı hazırlanmıştır. Tüm birimlerde atıkların ayrı biriktirilmesi hususunda ekipmanlar yerleştirilmiş, personellere bilgilendirmeler yapılmıştır.

Odak Noktası
Odak noktaları, kurumdaki sıfır atık yönetiminin etkin ve verimli bir şekilde uygulanmasından, sistemin kurulmasından, izlenmesinden, bilgi akışının sağlanmasından sorumludur.

Mevcut Durum
Atık kaynakları ve türleri:
- Ofisler: Ambalaj atıkları (kağıt, plastik, cam, metal), atık pil, elektrikli ve elektronik atıklar.
- Belediye sosyal tesisleri: Bitkisel atık yağ.
- Yemekhane: Yemek ve organik atıklar, ekmek artıkları.
- Sağlık birimi: Tıbbi atık.
- Bakım onarım atölyeleri: Tehlikeli atık, ömrünü tamamlamış lastik, atık motor yağı.

Belediyemizde yukarıda belirtilen atıklar 2009 yılından itibaren diğer atıklardan ayrı toplanmakta ve lisanslı firmalara verilmektedir. 2872 sayılı Çevre Kanunu ve ilgili yönetmeliklere uygun olarak; ambalaj atıkları, diğer atıklardan ayrı olarak mavi renkli geri dönüşüm kutularında biriktirilmektedir. Toplanan ambalaj atıkları, anlaşmalı lisanslı firma tarafından düzenli toplanarak depolama ünitesine götürülür.

Belediyemiz Tarafından Yönetimi Sağlanan Atık Türleri:
- Ambalaj Atıkları
- Atık Piller
- Bitkisel Atık Yağlar
- Evsel Çöpler - Organik Atıklar
- Elektrikli ve Elektronik Atıklar
- Tekstil Atıklar
- Moloz Atıkları
- İri Hacimli Atıklar

Planlama
2018 yılında başlatılan Sıfır Atık Projesi ile ilk olarak Gebze Belediyesi ana ve ek hizmet binalarında uygulamalar başlatılmıştır. Binalarda ofisler, koridorlar ve diğer birimlerde gerekli incelemeler yapılarak Sıfır Atık Projesi kapsamında geri dönüşüm veriminin artırılması, atık ayrıştırma bilincinin sağlanması için personel sayılarına göre planlamalar yapılmıştır.

Belediye tüm çalışanlarının ve temizlik personelinin sıfır atık projesinin uygulanması ile ilgili olarak eğitimlerin verilmesi planlanmıştır. Temizlik ekibi sorumlularının atık toplama, taşıma, atık miktarlarının belirlenmesi vb. atık yönetimine ilişkin görev dağılımları planlanmıştır.

İhtiyaçların Belirlenmesi ve Temini
Belediye binamız ve diğer ek hizmet binaları her bir atık türü için ayrı biriktirmeye imkân sağlayacak şekilde; geri dönüşebilen atıklar için (kağıt, karton, cam, plastik, metal) dört bölmeli ve organik atıklar ve geri kazanımı mümkün olmayan atıklar için iki bölmeli olmak üzere toplam yaklaşık 34 adet atık biriktirme ekipmanı yerleştirilmiştir. Atık biriktirme kutuları, kullanımı ve boşaltımı kolay, paslanmaz ve temizlenebilir özellikte olup, bulundukları yerde hangi atığın hangi kutuda biriktirileceğine dair renk skalası da dikkate alınarak uyarıcı ifadeler ve bilgilendirme panoları yerleştirilmiştir.

Eğitim Faaliyetleri
Atık biriktirme ekipmanlarının temin sürecine kadar belediyede çalışan tüm personele ve temizlik personeline sıfır atık projesi uygulamasına yönelik eğitimler verilmiştir. İlçemiz sınırlarında bulunan tüm ilkokullarda her sene, talebe istinaden ise diğer okulların farklı yaş gruplarında, kamu kurum ve kuruluşlarında personellere çevre ve sıfır atık eğitimleri verilmektedir. 2019 yılında 15.000 öğrenciye eğitim verildi, bu yıl hedef 25.000 kişidir.

Uygulama
Temin edilen atık biriktirme ekipmanları, belediye ana yeni hizmet binasında tüm katlarda ofis koridorlarına, personelin kolayca ulaşabileceği yerlere uygun şekilde yerleştirilmiştir.

Atık biriktirme ekipmanlarında biriken atıklar, eğitim verilen temizlik personeli tarafından tartılmakta ve atık miktarları hakkında istatistik tutulmaktadır. Toplama verimliliği ile ilgili olarak değerlendirmeler yapılacaktır.

Hedeflerimiz:
- Belediyemiz diğer tüm ek hizmet binalarımızda sıfır atık proje uygulamasının yaygınlaştırılması.
- Oluşan organik atıkların (meyve sebze kabukları, çay ocaklarında oluşan çay posaları, bahçedeki çim ve yapraklar vb.) ayrı toplanarak kompost üretiminde kullanılmasının sağlanması ve bunun için sistem oluşturulması.
- Atıktan enerji ve hammadde geri kazanımına yönelik geri kazanım ve bertaraf tesislerinin kurulması.
- Eğitim verilen kişi sayısını artırarak çevre ve doğa bilincini tüm bireylere aşılamak.
- Sıfır atık bilinçlendirme eğitimleri ile projenin önemini anlatmak, neler yapabiliriz konusunda yeni çalışmalar üretmek.

Biliyor Muydunuz?
- Atık malzemelerin hammadde olarak kullanılması çevre kirliliğini önler; hurda kâğıdın tekrar kâğıt imalatında kullanılması hava kirliliğini %74-94, su kirliliğini %35, su kullanımını %45 azaltır.
- 1 ton kağıdı geri dönüştürerek; 0,8 m çapında 17 tane ağacı, 26,5 m³ suyu, 2 varil (318 lt) petrolü, 4100 kWh enerjiyi (ortalama bir eve 6 ay yetecek kadar enerji) korumak mümkündür.
- 1 ton plastiğin geri dönüşümüyle; 22 m³ alanı, 16,3 varil (2.591,7 lt) petrolü, 5774 kWh enerjiyi (2 kişinin 1 yılda kullandığı enerji) korumak mümkündür.
- 1 ton cam ambalajı geri dönüştürerek; 1,53 m³ alanı, 0,12 varil (19 lt) petrolü, 42 kWh enerjiyi korumak mümkündür.
- Atık camın geri kazanılmasıyla %25 oranında enerji tüketiminde azalma, %20 oranında hava kirliliğinde azalma, %80 oranında maden atığında azalma ve %50 oranında da su tüketiminde azalma sağlanır (PAGÇEV, 2016). Camın geri dönüşümünde kalite kaybolmadan neredeyse %100 oranında eski camdan imal edilebilir.
- 1 ton alüminyum ambalajı geri dönüştürerek; 7,65 m³ alanı, 39,6 varil (6.296,4 lt) petrolü, 14.000 kWh enerjiyi; 1 ton çelik ambalajı geri dönüştürerek; 3 m³ alanı, 1,8 varil (286,2 lt) petrolü, 642 kWh enerjiyi korumak mümkündür.
EOT,
                ],
                [
                    'baslik' => 'Ambalaj Atıkları',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_ambalaj-atiklari_15.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20230331090156.jpg',
                    'detay' => <<<'EOT'
"Ambalaj Atığı" Nedir?
Ambalaj, içerisindeki ürünün temiz kalmasını ve güvenilir bir şekilde tüketiciye ulaşmasını sağlayan, taşınmasını kolaylaştıran, ürünü koruyan ve aynı zamanda ürünün tanıtımını yapan endüstriyel bir üründür. Yiyecek-içecek, kozmetik, mobilya, hediyelik eşya, temizlik vb. sektörlerde ambalaj kullanımı oldukça yaygın olup, ürünün tanıtımı ve satışı da ambalaj aracılığıyla yapılır.

Öte yandan yönetmelikte ambalaj "hammaddeden işlenmiş ürüne kadar, bir ürünün üreticiden kullanıcıya veya tüketiciye ulaştırılması aşamasında, taşınması, korunması, saklanması ve satışa sunulması için kullanılan herhangi bir malzemeden yapılmış, yönetmeliğin Ek-1'inde yer alan 'Ambalaj Tanımına İlişkin Açıklayıcı Örnekler'de belirtilenler ile geri dönüşsüz olanlar da dâhil tüm ürünler" olarak tanımlanmaktadır.

Belediyemiz Neler Yapıyor?
Belediyemiz 2009 yılından itibaren ambalaj atıklarının kaynağında ayrı toplanmasına pilot bölgeler oluşturarak başlamış ve bu konuda çeşitli etkinlikler, projeler hazırlayarak farkındalık oluşturulmuştur. 2005 yılında yürürlüğe giren Ambalaj Atıklarının Kontrolü Yönetmeliği'nce yasal zorunluluk haline gelen uygulama, 2009 yılında belediyemizce hazırlanan ve Çevre ve Şehircilik Bakanlığı'nca onaylanan "Gebze İlçesi Ambalaj Atıkları Yönetim Planı" ile tüm ilçeyi kapsamıştır.

2017 yılında Cumhurbaşkanımızın eşi Emine Erdoğan Hanımefendi'nin himayelerinde başlatılan Sıfır Atık Projesi ile daha da koordineli hale gelen çalışmalarımız aralıksız devam etmektedir.

Çalışmalarımız 6 adet sıkıştırmalı, 1 adet sıkıştırmasız atık aracı, 1 adet vinçli cam toplama kamyonu ve 30 personel ile yürütülmektedir. Haftanın 7 günü 08.00-19.00 saatleri içerisinde gerçekleştirilen atık toplama faaliyeti sonucunda elde edilen ambalaj atıkları, ayrıştırılmak üzere lisanslı firma tarafından kurulan geri dönüşüm tesisine ulaştırılarak atıkların burada ekonomiye kazandırılmak üzere geri dönüşüm çalışmaları yürütülmektedir.

İlçemiz mücavir alanında bulunan kamu kurumları, AVM'ler, marketler ve diğer tüm ticari faaliyette bulunan işletmelere belediyemiz tarafından temin edilen biriktirme ekipmanları ile Gebze'de tüm lokasyonlar sıfır atık sistemine dahil edilmiş oldu. Bu konuda hem belediyemiz içi hem de dışarıdan kurumlardan, işletmelerden ve vatandaşlarımızdan gelen talep ve önerilerin mevzuata uygunluğunun ve gerekli altyapısının sağlanması, çalışmaların yürütülmesi için Sıfır Atık Yönetim Birimimiz kurulmuştur.

Yıllara Göre Toplanan Ambalaj Atığı Miktarı (ton):
2012: 2.749,531
2013: 9.976,890
2014: 9.310,154
2015: 5.372,666
2016: 6.585,930
2017: 11.644,370
2018: 12.445,830
2019: 11.101,191

İlçemiz genelinde 600 geri dönüşüm kafesi-konteynırı, 200 adet cam kumbarası halkımızın biriktirmiş olduğu atıkları kolaylıkla geri dönüşüme kazandırması için kolaylıkla ulaşabileceği noktalara konumlandırılmıştır. Ayrıca iç mekânlarda atık biriktirme ekipmanlarımız dağıtılmaktadır. Talebe istinaden ekiplerimiz tarafından değerlendirme yapılarak uygun sayıda ekipman desteği sağlanmaktadır.

Hanelerimizde oluşan ambalaj atıkları her mahallenin toplama gününde anonslu araçlarımız tarafından düzenli bir şekilde toplanmaktadır.

Atıklarınızın hanenizden düzenli toplanmasını sağlamak, toplama sistemine dahil olmak için Alo Atık Hattı 0262 642 1010'u arayabilirsiniz.

Mevzuata Uyum
Ülkemizde katı atıklar içinde önemli bir yer tutan ambalaj atıklarının yarattığı çevre kirliliğinin azaltılması ve bunların yeniden ekonomik değer haline getirilebilmeleri için ambalaj atıklarının yönetimi önemli bir süreçtir.

Ambalaj Atıkları Kontrolü Yönetmeliği 8. Madde 1. Bendinde "ambalaj atıklarının ayrı toplanmasından, 5216 sayılı Kanunun 7 nci maddesi kapsamında büyükşehir belediye sınırları içerisinde ilçe belediyeleri ve 5393 sayılı Kanunun 15 inci maddesi kapsamında belediyeler sorumludur" ibaresi yer almaktadır. Bu kapsam çerçevesinde belediyemiz tüm mücavir alanda oluşan ambalaj atıklarını toplamak/toplattırmakla yükümlüdür.
EOT,
                ],
                [
                    'baslik' => 'Atık Pil ve Akümülatörler',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_atik-pil-ve-akumulatorler_16.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20201214111614.jpg',
                    'detay' => <<<'EOT'
"Atık Pil ve Akümülatör" Nedir?
Kullanım ömrünü tamamlamış veya uğramış olduğu fiziksel hasar sonucu kullanılamayacak duruma gelmiş pillere atık pil denilmektedir. Piller, türlerine göre bünyelerinde demir, çinko, mangan, nikel, kadmiyum, lityum, kobalt vb. metalleri, çeşitli kimyasal bileşikleri, plastik, karton ve kağıt esaslı maddeleri içerebilirler. Atık pillerin kontrolsüz biçimde çevreye atılması çevre kirlenmesine yol açabilir.

Belediyemiz Neler Yapıyor?
Atık piller, Temizlik İşleri Müdürlüğümüzce oluşturulan atık toplama sistemi tarafından hanelerden, işletmelerden, okullardan vs. tüm Gebze mücavir alandan toplanarak TAP'a (Taşınabilir Pil Üreticileri ve İthalatçıları Derneği) teslim edilerek uygun şekilde bertarafı sağlanarak doğaya vereceği zararın önüne geçilmiş olmaktadır. 31.08.2004 tarih ve 25596 sayılı Atık Pil ve Akümülatörlerin Kontrolü Yönetmeliği'nce belediyemiz, atık pillerin diğer atıklardan ayrı toplanması ve uygun şekilde bertarafı konusunda TAP ile anlaşarak çalışmalara başlanmıştır.

Atık Hattı 0262 642 1010'u arayarak adresinizden atık pillerinizi ve akümülatörlerinizi aldırabilirsiniz.

Müdürlüğümüzce 2009 yılından itibaren toplanan atık pil ve akümülatör miktarları (kg):
2009: 285
2010: 1.628
2011: 1.495
2012: 2.881
2013: 7.801
2014: 1.747
2015: 2.587
2016: 1.188
2017: 2.491
2018: 1.961
2019: 3.288

Mevzuata Uyum
31.08.2004 tarih ve 25569 sayılı Atık Pil ve Akümülatörlerin Kontrolü Yönetmeliği kapsamında mücavir alanımızda oluşan atık pil ve akümülatörler Temizlik İşleri Müdürlüğümüze bağlı atık toplama ekiplerimiz tarafından toplanarak uygun şekilde geri kazanım veya bertarafları yapılmaktadır.
EOT,
                ],
                [
                    'baslik' => 'Bitkisel Atık Yağ',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_bitkisel-atik-yag_17.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20230331090237.jpg',
                    'detay' => <<<'EOT'
"Bitkisel Atık Yağ" Nedir?
Bitkisel atık yağ, bitkisel yağların rafinerisinden çıkan yağlı topraklar, tortulu bitkisel yağlara ve kızartma yağlarına verilen genel addır. Ülkemizde bitkisel yağların tüketimi sonucu yaklaşık olarak 400 bin ton bitkisel atık yağ oluştuğu tahmin edilmektedir.

Kullanılmış bitkisel atık yağlar su kirliliğinin %25'ini oluşturmakta, bir litre atık yağın lavabodan dökülmesi yaklaşık bir milyon litre içme suyunu kullanılamaz hale getirmektedir. Kızartmalık atık yağlar ekotoksik özellik gösterir. Denizlere, göllere ve akarsulara döküldüğünde su yüzeyini kaplayarak havadan suya oksijen transferini önlemekte, balıklar ve diğer canlıların ölümüne neden olmaktadır. Başlıca bu nedenlerden ötürü 19.04.2005 tarih ve 25791 sayılı Bitkisel Atık Yağların Kontrolü Yönetmeliği bitkisel atık yağların doğrudan alıcı ortama verilmesini yasaklamıştır.

Kızartmalık atık yağların canlılar üzerindeki zararlı etkileri nedeniyle yem ve sabun sanayide kullanılması yasaktır. Kızartmalık atık yağlar biyodizel üretiminde kullanılarak geri kazanımı sağlanmaktadır.

Belediyemiz Neler Yapıyor?
İlgili yönetmelik gereği Belediyemiz sınırları içinde bulunan lokantalar, sanayi mutfakları, yemekhaneler, hazır yemek üretimi yapan firmalar, oteller ile diğer yerlerde gerekli atık yağ denetimleri Temizlik İşleri Müdürlüğümüze bağlı Çevre Denetim Ekibimiz tarafından yapılmaktadır. Bu denetimlerde kızartmalık yağ üreten işletmelerin lisanslı geri kazanım tesisleriyle yıllık sözleşme yapmaları sağlanmaktadır.

Belediyemiz sınırları dahilinde bütün Mahalle Muhtarlıkları, Belediye binamız ve okullarda bitkisel atık yağ bidonları mevcuttur. Evlerde biriktirilen atık yağlar, bu noktalara bırakılabilmektedir. Atık yağ bidonu talep eden site veya apartmanlara da bidon verilmektedir. Bidon dolduğu haber verildiğinde boşaltımı atık ekiplerimiz tarafından gerçekleştirilmektedir.

Ayrıca hanelerden düzenli şekilde biriktirme yapılması halinde 0262 642 1010 Atık Hattı'nın aranarak adres bildirilmesi sonucu Temizlik İşleri Müdürlüğü Atık Toplama Ekibimiz tarafından bitkisel atık yağlar güvenli şekilde toplanmaktadır. Bitkisel atık yağlarını biriktirip düzenli şekilde ekiplerimize teslim eden vatandaşlarımıza çeşitli hediyeler takdim edilmektedir.

Biriktirilen atık yağlar lisanslı firmaya gönderilerek biyodizel üretimi sağlanmaktadır.

Yıllara göre toplanan bitkisel atık yağ miktarı (lt):
2010: 200
2011: 545
2012: 1.730
2013: 1.590
2014: 1.350
2015: 2.370
2016: 2.420
2017: 1.900
2018: 1.500
2019: 3.000

Mevzuata Uyum
19.04.2005 tarih ve 25791 sayılı Bitkisel Atık Yağların Kontrolü Yönetmeliği gereği Belediyemiz; yetki sahasında bulunan lokantalar, sanayi mutfakları, oteller, tatil köyleri, motel ve yemekhaneler, hazır yemek üretimi yapan firmalar ile diğer yerlerde gerekli denetimleri yaparak kullanılmış kızartmalık yağların kanalizasyona dökülmesini önlemekte, kızartmalık yağların hanelerden toplanması için halkı bu konuda bilgilendirerek atık yağ toplama faaliyetlerini organize etmektedir.
EOT,
                ],
                [
                    'baslik' => 'Elektronik Atıklar',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_elektronik-atiklar_18.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20230331090328.jpg',
                    'detay' => <<<'EOT'
"Atık Elektrikli ve Elektronik Eşya" Nedir?
Alternatif akımla 1000 Volt'u, doğru akımla 1500 Volt'u geçmeyecek şekildeki kullanımlar için tasarlanmış ve uygun bir şekilde çalışması için elektrik akımı ya da elektromanyetik alana bağımlı olan eşyaların bütününe Elektrikli ve Elektronik eşya denir. Bu ürünlerin kullanım ömrü dolduğu andaki bütün bileşenleri ve içerdikleri sarf malzemeler "Atık Elektrikli ve Elektronik Eşya" olarak adlandırılır. Atık elektrikli ve elektronik eşyalara, "AEEE" veya "e-atık" isimleriyle de anılabilir.

Elektronik cihazların üretiminde kurşun, kadmiyum, civa vb. gibi birçok ağır metal bulunmaktadır. Özellikle bu sebeple, atık haline gelmiş elektronik cihazların, çevre ve insan sağlığına etkilerini minimize etmek amacıyla kaynağında ayrı toplanmaları ve geri dönüşüme kazandırılmaları gerekmektedir.

Belediyemiz Neler Yapıyor?
Vatandaşlarımız tarafından Belediyemiz iletişim mercilerine ulaştırılan e-atık ihbarları Temizlik İşleri Müdürlüğümüz Atık Toplama ekiplerince toplanmakta ve Çevre Lisanslı AEEE işletmelerine teslim edilmektedir.

Atık Hattı 0262 642 1010 aranarak elektronik atığım var denmesi halinde Atık Toplama Ekiplerimiz tarafından belirtilen adresten elektronik atıklar güvenli bir şekilde alınmaktadır.

Kaynağında ayrı toplama gerektiren e-atıklar:
- Büyük beyaz eşyalar
- Küçük ev aletleri
- Bilişim ve telekomünikasyon ekipmanları
- Tüketici ekipmanları
- Aydınlatma ekipmanları
- Büyük ve sabit sanayi aletleri hariç elektrikli ve elektronik aletler
- Oyuncaklar, eğlence ve spor ekipmanları
- Tıbbi cihazlar
- İzleme ve kontrol aletleri
- Otomatlar

Müdürlüğümüz tarafından 2010 yılından itibaren toplanan elektronik atık miktarı (kg):
2010: 391
2011: 1.787
2012: 3.085
2013: 6.463
2014: 3.840
2015: 1.723
2016: 5.192
2017: 1.528
2018: 3.181
2019: 5.840

Mevzuata Uyum
22.05.2012 tarih ve 28300 sayılı Atık Elektrikli ve Elektronik Eşyaların Kontrolü Yönetmeliği yayınlandıktan sonra Belediyeler için bu atıkları toplamak ve çevre lisanslı işleme tesislerine göndermek yasal bir zorunluluk haline gelmiştir. Toplama işlemine Temizlik İşleri Müdürlüğümüzce devam edilmektedir.
EOT,
                ],
                [
                    'baslik' => 'İri Hacimli Atıklar',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_iri-hacimli-atiklar_19.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20201214124204.jpeg',
                    'detay' => <<<'EOT'
"İri Hacimli Atıklar" Nedir?
Kullanılmış ev eşyası, mobilya, kanepe, yatak parçaları vb. atıklar iri hacimli atıklar olarak nitelendirilmektedir. Katı atıkların toplanması, uzaklaştırılması ve bertaraf edilmesi ilgili mevzuatlar gereği yerel yönetimlerin sorumluluk alanına girmektedir. Sürdürülebilir kalkınma ve doğal çevrenin aynı eksende yönetilmesi noktasında, katı atıkların ekonomiye kazandırılması ve ayrıca doğayı tahrip etmesinin önlenmesi günümüzde alınabilecek çeşitli önlemlerle mümkün olmaktadır.

Belediyemiz Neler Yapıyor?
İri hacimli katı atıklar; Temizlik İşleri Müdürlüğü ekipleri tarafından günlük ev, işyeri, okul, sanayi vb. kaynaklardan toplanan mobilya, yatak, bahçe atıkları vb. karışık atıklar olarak nitelendirilebilir. Atıklar belediyeye ait araçlar ile düzenli toplanmakta ve biriktirme alanına getirilmektedir.

Oluşan iri hacimli atıklarınızı en yakın çöp konteynırı yanına bırakarak Alo Atık Hattı 0262 642 1010'u arayabilirsiniz. Temizlik İşleri Müdürlüğü ekipleri tarafından belirtilen adreslerdeki atıkların toplaması yapılmaktadır.

Müdürlüğümüz tarafından 2012 yılından itibaren toplanan iri hacimli atık miktarları (nüfus / toplanan atık kg):
2012: Nüfus 319.307 - Toplanan Atık 298.700 kg
2013: Nüfus 329.195 - Toplanan Atık 825.200 kg
2014: Nüfus 338.412 - Toplanan Atık 649.900 kg
2015: Nüfus 350.115 - Toplanan Atık 310.500 kg
2016: Nüfus 357.743 - Toplanan Atık 1.767.500 kg
2017: Nüfus 368.278 - Toplanan Atık 1.958.800 kg
2018: Nüfus 371.000 - Toplanan Atık 1.550.750 kg
2019: Nüfus 382.166 - Toplanan Atık 1.534.600 kg

Bu atıklar ile ilgili etkin bir atık azaltımı ve geri dönüşüm sağlanmakta, çöp dağları oluşması önlenmekte, çevre ve insan sağlığını tehdit edecek unsurların oluşması engellenmektedir. Atık yönetiminin güçlendirilmesi, doğal kaynakların sürdürülebilir kullanımı, çevre ve insan sağlığının korunması için yaşamsal önemde olup, mevcut ve gelecek kuşaklara daha kaliteli, sürdürülebilir bir yaşam ortamı sağlanmasında temel bir faktördür.

Mevzuata Uyum
İri hacimli katı atıklar (mobilya, yatak, ağaç dal atıkları vb.) halk sağlığına uygun şekilde, fen ve sanat kaidelerine göre yönetilmesi, katı atıklardan madde geri kazanma ve sair şekilde istifade edilmesi "Umumi Hıfzıssıhha Kanunu" gereğidir. Katı atıklar 02.04.2015 tarih ve 29314 sayılı Atık Yönetimi Yönetmeliği 8. Maddesinde Belediyelerin Görev ve Sorumlulukları kısmında "Belediye atıkları ile ilgili mevzuat kapsamında yönetiminden sorumlu olduğu atıkları kaynağında ayrı toplamak/toplattırmakla, aktarma istasyonuna taşımakla ve ikili toplama sistemi ile atık getirme merkezi kurmak/kurdurtmakla, toplanan atıklara ilişkin bilgi ve belgeleri Bakanlığa sunmakla" yükümlüdür ve 2872 Sayılı Çevre Kanununun 11. Maddesinde "Büyükşehir Belediyeleri ve Belediyeler, evsel katı atık bertaraf tesislerini kurmak, kurdurmak, işletmek veya işlettirmekle yükümlüdür" denilmektedir. "Evsel katı atıkların toplanması, taşınması ve geri kazanılması ile çevre ve insan sağlığına olumsuz etki yapmadan nihai bertarafına ve geri kazanım yöntemine ilişkin yükümlülük" yetki ve sorumluluklar 5393 Sayılı Belediye Kanunu ile Belediyelere ve 5216 Sayılı Büyükşehir Belediyesi Kanunu ile Büyükşehir Belediyelerine verilmiştir.
EOT,
                ],
                [
                    'baslik' => 'Moloz Atıkları',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_moloz-atiklari_20.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20201214113148.jpg',
                    'detay' => <<<'EOT'
"Moloz Atıkları" Nedir?
Moloz genel olarak bir inşaatın veya yapının yıkıldıktan sonra tuğla, taş, kireç, alçı, boya, demir vb. malzemelerin karışımından meydana gelen döküntülerdir. Genellikle tadilat sonrası meydana gelir. Bu moloz genellikle ağır ve sağlığa zararlıdır, atımı zor ve zahmetlidir. Moloz atıklarının gelişigüzel çöp konteynerlerine veya çevreye rastgele atılması yasaktır.

Belediyemiz Neler Yapıyor?
Oluşan moloz atıkları için Atık Hattı 0262 642 1010'u arayarak belediyeye nasıl aldırılabileceği hususunda gerekli bilgi alınabilir. Çuval başı, traktör ve kamyon olarak fiyatlandırma yapılarak ödeme sonrası adresinizden Temizlik İşleri Ekipleri tarafından moloz atıkları alınmaktadır.

Müdürlüğümüzce 2009 yılından itibaren toplanan moloz atık miktarı (kg):
2009: 735.800
2010: 1580,650
2011: 3788,050
2012: 4142,050
2013: 5913,800
2014: 6587,650
2015: 5609,400
2016: 6613,800
2017: 7349,050
2018: 5486,250
2019: 9319,822

Mevzuata Uyum
3/5/1985 tarih ve 3194 sayılı İmar Kanunu 40. Maddesi "Arsalarda, evlerde ve sair yerlerde umumun sağlık ve selametini ihlal eden, şehircilik, estetik veya trafik bakımından mahzurlu görülen enkaz veya birikintilerin, gürültü ve duman tevlid eden tesislerin hususi mecra, lağım, çukur, kuyu, mağara ve benzerlerinin mahzurlarının giderilmesi ve bunların zuhuruna meydan verilmemesi ilgililere tebliğ edilir. Tebliğde belirtilen müddet içinde tebliğe riayet edilmediği takdirde belediye veya valilikçe mahzur giderilir; masrafı %20 fazlasıyla arsa sahibinden alınır veya mahzur tevlit edenlerin faaliyeti durdurulur." hükmü gereğince, moloz atıklarının gelişigüzel çöp konteynerine ve umuma açık alanlara bırakılması yasaktır.
EOT,
                ],
                [
                    'baslik' => 'Evsel Çöpler - Organik Atıklar',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_evsel-copler-organik-atiklar_21.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20230331090643.jpeg',
                    'detay' => <<<'EOT'
Evsel Çöp Nedir?
Yönetiminden belediyenin sorumlu olduğu, evlerden kaynaklanan ya da içerik veya yapısal olarak benzer olan ticari, endüstriyel ve kurumsal atıklara belediye atığı veya evsel atık denir.

"Organik Atık" Nedir?
Bitki ve hayvan kaynaklı, karbon içeren atıklara "organik atık" adı verilir. Evlerden ve işyerlerinden toplanarak çöp alanlarına taşınan atıkların önemli bir bölümü organik atıklardır. Organik atıklar, doğada mikroorganizmalar yardımıyla kolayca bozunarak temel bileşenlerine ayrılabilir. Organik atıkların biriktirilip kontrollü olarak bozunmaları sağlandığında, bitkiler için çok zengin gübre eldesi sağlanabilir (kompostlaştırma).

Belediyemiz Neler Yapıyor?
İlimizde evsel atıkların yönetimi, K.B.B. koordinatörlüğünde yürütülmektedir. Tüm mücavir alanımızda oluşan evsel çöpler her gün düzenli şekilde toplanmaktadır. Gebze'de yaklaşık olarak 8.000 adet çöp konteynırı, 150 adet yerüstü konteynırı, 30 adet yeraltı konteynırı ile biriktirme yapılmaktadır. Biriken çöpler 17 adet çöp toplama aracımız ile düzenli toplanmaktadır. Gelen talep ve şikayetlere istinaden konteynır bırakma ve yer değişikliği talepleri ekiplerimiz tarafından değerlendirilmektedir.

Belediyemiz tarafından özellikle Pazar yerlerinde oluşan pazar atıklarının değerlendirilmesi için Mevlana Kapalı Pazaryerinde kapalı sistem kompost makinalarının bulunduğu organik gübre (kompost) tesisi devreye alınmıştır. İlerleyen zamanlarda diğer kapalı Pazar yerlerimize ve organik atık oluşumunun fazla olduğu bölge ve işletmelere bu makinalar kurularak hem atık oluşumu azaltılmış olacak hem de atıktan ekonomik bir girdi sağlanarak sıfır atık projesi tam manasıyla yürütülmüş olacaktır. Haftalık ~1.400 lt organik atık ve 1/10 oranında karbonlayıcı madde (talaş vs.) ile 15 gün sonunda yaklaşık 500 lt kompost eldesi sağlayan makinalar 7/24 prensibine uygun çalışarak aralıksız üretime devam etmektedir.

Üretilen kompost hem vatandaşlarımıza ücretsiz dağıtılmakta hem de Park ve Bahçeler Müdürlüğümüze ait serada toprak iyileştirici ve toprak yapısını düzenleyici olarak kullanılmaktadır.

Mevzuata Uyum
02.04.2015 tarih 29314 sayılı Atık Yönetimi Yönetmeliği gereği oluşan evsel çöplerin yönetimi Kocaeli Büyükşehir Belediyesi tarafından yürütülmektedir. Belediyemiz tarafından toplanan evsel çöpler büyükşehir belediyesine ait aktarma tesisinden yine büyükşehir belediyesine ait depolama sahasına nakli yapılmaktadır.

05.03.2015 tarih 29286 sayılı Kompost Tebliğine istinaden kurulan tesisimizde organik atıklardan kompost (organik gübre) üretilmektedir.
EOT,
                ],
                [
                    'baslik' => 'Tekstil Atıkları',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_tekstil-atiklari_22.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20230331090720.jpg',
                    'detay' => <<<'EOT'
"Tekstil Atıkları" Nedir?
Tekstil atıkları; tüketicilerin kullanımı sonrası veya tekstil endüstrilerinde üretim süreçlerinde ortaya çıkan atıklardır. Bizi ilgilendiren kısım vatandaşlarımızın kullanımı sonrası artık ihtiyaç duymadığı veya kullanılmayacak derecede tahrip olmuş giysi ve ayakkabı gibi tekstil ürünleridir.

Belediyemiz Neler Yapıyor?
Oluşan tekstil atıklarının evsel çöpe karışarak depolama alanlarına taşınması, hem hacmen fazla yer kaplamalarının hem de bertaraf maliyetlerinin azaltılmasının sağlanması için bölgemize 140 adet tekstil kumbarası yerleştirildi. Haftanın tüm günleri lisanslı işletme tarafından kumbaralar kontrol edilerek gerekli düzenleme ve çalışmalar yapılmaktadır.

Kumbaralara kullanılmış tüm giysi (şapka, kazak, t-shirt, iç çamaşırı, çorap, elbise, pantolon, ayakkabı, terlik, bot vb.) ve diğer tekstil ürünlerini (battaniye, örtü, eşarp, perde, çanta, havlu vb.) atabilirsiniz. Alo Atık Hattı 0262 642 1010'u arayarak tekstil atıklarınızı verebilir veya tekstil kumbarası talep edebilirsiniz.

Müdürlüğümüzce 2018 yılından itibaren toplanan tekstil atık miktarı (kg):
2018: 8.980
2019: 121.844

Mevzuata Uyum
Tekstil atıklarının toplanması, geri dönüşümü veya bertarafı ile ilgili henüz herhangi bir mevzuat, alt mevzuat yayımlanmamış olsa dahi, atık yönetimi ve çevrenin korunması ile ilgili genel mevzuat metinleri konu ile alakalı bir çerçeve çizmektedir. Çevre Kanunu'nun 8. maddesi "Her türlü atık ve artığı, çevreye zarar verecek şekilde, ilgili yönetmeliklerde belirlenen standartlara ve yöntemlere aykırı olarak doğrudan ve dolaylı biçimde alıcı ortama vermek, depolamak, taşımak, uzaklaştırmak ve benzeri faaliyetlerde bulunmak yasaktır." şeklinde hükmetmektedir. Aynı zamanda 02.04.2015 tarihli 29314 sayılı Atık Yönetimi Yönetmeliği'nin 5. maddesinin 3. fıkrasının (b) bendi "Atık üretiminin kaçınılmaz olduğu durumlarda atıkların; yeniden kullanımı, geri dönüşümü ve ikincil hammadde elde etme amaçlı diğer işlemler ile geri kazanılması, enerji kaynağı olarak kullanılması veya bertaraf edilmesi esastır. Atıkların alternatif hammadde ve ek yakıt olarak kullanılmasına ilişkin esaslar Bakanlıkça belirlenir." şeklindedir. Buna istinaden geri dönüşüm süreçlerine dahil edilebilecek her atık, bertaraf/depolama/yakma vb. gibi nihai işlemlerden ziyade tekrar kullanım/geri dönüşüm ile değerlendirilmelidir.
EOT,
                ],
            ],
        ],
        'evlendirme' => [
            'label' => 'Evlendirme',
            'icon' => 'bi-heart-fill',
            'ogeler' => [
                ['baslik' => 'Nikah İşlemleri', 'href' => 'hizmetler.php?hizmet=nikah-islemleri', 'img' => 'https://www.gebze.bel.tr/resim/20191125082242.jpg', 'ic' => true],
            ],
        ],
        'egitimler' => [
            'label' => 'Eğitimler',
            'icon' => 'bi-mortarboard-fill',
            'ogeler' => [
                [
                    'baslik' => '41 Genç 41 Gelecek',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_41-genc-41-gelecek_9.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20200212133900.jpg',
                    'detay' => <<<'EOT'
Gebze Belediyesi'nin Gençlik ve Liderlik projesidir. Gebze'de okuyan lise 2. ve 3. sınıflardan uzmanlar eşliğinde yapılan mülakatlar sonucunda; liderlik vasfı olan, liderlik vasfını henüz keşfetmemiş, bu alanda kendini geliştirmeye hazır 41 öğrencinin seçilerek oluşturduğu 4 ay süren bir projedir.

Lider olacak gençlerin gelişimine katkı sağlamak amacı ile üniversite ortamında bilim, psikoloji, kültür, tarih, sosyoloji, edebiyat, siyaset bilimi ve uluslararası ilişkiler dallarında ders verilmektedir. Tarihi kimlik algılarını geliştirmek ve toplumunu daha iyi anlamalarını sağlamak amacı ile geziler düzenlenip öğrencilere bilgiler yerinde aktarılmaktadır. Rol model insanlarla söyleşiler ve öncü kurum ile firma ziyaretleri ile başarılı liderlik hikâyelerine tanıklık etmeleri sağlanmaktadır. Sosyal sorumluluk, sanat ve spor etkinlikleriyle; ekip olma, ekip yönetme, strateji üretme kazanımları desteklenmektedir.
EOT,
                ],
                [
                    'baslik' => 'Doğru Tercih Hazır Kursları',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_dogru-tercih-hazir-kurslari_10.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20220217104452.jpg',
                    'detay' => <<<'EOT'
Lise ve üniversite mezunu olan gençlerimize Doğru Tercih projemiz bünyesinde uzman antrenörler eşliğinde, doğru antrenman programları, rehberlik hizmetleri ve nizami parkur eğitimleri ile istedikleri hedefe ulaşmaları yolunda destek olmaktayız.

Branşlar:
- Spor Bilimleri
- Polis Akademisi
- Askeri Okullar
- Polis Meslek Yüksek Okulu
- Bekçilik

Kayıt: https://sende.gebze.bel.tr/egitimler?categories[0]=33
EOT,
                ],
                [
                    'baslik' => 'Yaz / Kış Okullar',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_yaz-kis-okullar_11.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20200212134549.jpg',
                    'detay' => "Yaz ve kış döneminde çocuklarımızın verimli ve güvenli bir şekilde eğlenerek öğrendikleri eğitimler düzenlemekteyiz. Değerler eğitimiyle çocukların temel değerlerinin güçlendirildiği bu dönemlerde, sanat ve spor branşlarında eğitim alan çocuklardan yetenekli olanlar alanlarındaki kurumlara yönlendirilmektedir.",
                ],
                [
                    'baslik' => 'FITNESS',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_fitness_39.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20250714115119.jpeg',
                    'detay' => <<<'EOT'
Verilen Hizmetler
Sağlıklı ve aktif yaşamı desteklemek adına profesyonel eğitmenler ve donanımlı tesislerimiz ile sizlere erişilebilir spor imkanı verilmektedir.

Tesisler:

Arapçeşme Bilim ve Sanat Merkezi
Arapçeşme Mahallesi, Kavak Caddesi, 1066. Sk. No:27, 41400 Gebze/Kocaeli
+90 262 641 24 93
genclik.spor@gebze.bel.tr

Sümeyye Boyacı Yarı Olimpik Yüzme Havuzu
Cumhuriyet Mahallesi, Necip Fazıl Cd., 41400 Gebze/Kocaeli
+90 262 641 24 93
genclik.spor@gebze.bel.tr

Detaylı bilgi ve kayıt: https://sende.gebze.bel.tr/egitimler?categories[0]=41&majors[0]=36
EOT,
                ],
                [
                    'baslik' => 'STEP AEROBİK',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_step-aerobik_40.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20250411160735.jpg',
                    'detay' => <<<'EOT'
Verilen Hizmetler
Sağlıklı ve formda olmanızı desteklemek adına kadınlara özel oluşturduğumuz grup derslerimizde müzik ve ritim eşliğinde keyifle yapabileceğiniz spor hizmetleri verilmektedir.

Tesisler:

Arapçeşme Bilim ve Sanat Merkezi
Arapçeşme Mahallesi, Kavak Caddesi, 1066. Sk. No:27, 41400 Gebze/Kocaeli
+90 262 641 24 93

Eray Şamdan Spor Salonu
Cumhuriyet Mahallesi, Yeni Bağdat Cd. No:117, 41400 Gebze/Kocaeli
+90 262 641 24 93

İstasyon Bilim ve Sanat Merkezi
İstasyon Mahallesi, Şehit Abdullah Horoz Caddesi No:26, Gebze/Kocaeli
+90 262 641 24 93

Detaylı bilgi ve kayıt: https://sende.gebze.bel.tr/egitimler?categories[0]=41&majors[0]=37
EOT,
                ],
                [
                    'baslik' => 'Güzide Gençlik Merkezi Eğitimleri',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_guzide-genclik-merkezi-egitimleri_41.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20250604135831.png',
                    'detay' => <<<'EOT'
Güzide Gençlik Merkezinde eğitim faaliyetleri lise öğrencilerini hem akademiye hem de hayata hazırlayacak şekilde düzenlenmektedir. Bunu yaparken eğitimin çok yönlü ve hayatın içinden olması üzerinde durulmaktadır.

Akademik Program
Güzide Gençlik Merkezinde akademik program Türk Dili ve Edebiyatı ile Matematik derslerinin merkeze alındığı ve yoğun olarak işlendiği bir anlayışla inşa edilmiştir. Bunun yanı sıra diğer branşlar; seminerler, özel çalışma grupları ve farklı eğitim metotları üzerinden öğrencilere sunulmaktadır. Ayrıca eğitimde teknolojiden istifade edilmesi hususu da öncelenmektedir.

Akademik programda uygulanan dersler:
- Türk Dili
- Türk Edebiyatı
- Matematik
- Geometri
- XR Matematik
- XR Geometri
- Fizik
- XR Fizik
- XR Kimya
- XR Biyoloji
- Arapça
- Osmanlıca
- Kur'an-ı Kerim
- Tarih
- Sosyoloji
- Felsefe
- Din Kültürü ve Ahlak Bilgisi

Denge Eğitim Programı (DEP)
Güzide Gençlik Merkezinde beden ve ruh sağlığına matuf, sosyoloji, psikoloji, fizyoloji, eğitim bilimleri, aile danışmanlığı, akademik ve manevi rehberlik alanlarından müteşekkil çalışmalar "Denge" başlığı altında toplanmıştır. Denge'den maksat, yukarıda belirtilen tüm alanlarda öğrencilerimize dengeli olmayı "teklif" etmektir. Denge Eğitim Programı aşağıdaki bileşenlerden oluşmaktadır:

Danışmanlıklar:
- Psikolojik Danışmanlık
- Aile Danışmanlığı
- Eğitim Danışmanlığı
- Tercih Danışmanlığı
- Manevi Danışmanlık

Güzide Doğa Okulu:
- Güz Kampları
- Kış Kampları
- Yaz Kampları
- Geziler
- İstikamet Programları

Yazarlık Okulu:
- Kitap Tahlilleri
- Bilgi Yarışmaları
- Diksiyon ve Hitabet Dersleri
- Edebiyat Söyleşileri
- Dergicilik Okulu
- Film Okumaları
- Etimoloji Atölyesi
- GEK Atölyesi
- Münazaralar

Sanat Atölyeleri:
- Geleneksel Sanat Atölyeleri (Hat, Tezhip, Kaligrafi, Ebru, Minyatür, Kat'ı)
- Modern Sanat Atölyeleri (Resim / Kara Kalem, Yağlı Boya, Kuru Boya, Toz Pastel, Akrilik)
- Tiyatro
- Drama
- El Sanatları Atölyesi (Kanaviçe, Örgü, Biçki, Dikiş)
- Savunma Sanatı (Kick-Box)
- Mutfak Sanatları Atölyesi
- Ahşap Atölyesi
- Cam Boncuk Atölyesi
- Mimarlık Atölyesi
- Müzik Atölyesi (Bendir, Kalimba)
- Sergiler

Tekno-Eğitim Atölyeleri:
- Yazılım Atölyeleri (C#, Java, Python, Web [HTML-CSS], Web Tasarım, GeoGebra)
- Robotik Atölyeleri (Arduino, Esp 32, Rex, Mblock 5, Pinoo)
- Güzide Garaj
- Dijital Eğitim Sınıfları
- 3D Yazıcı Atölyesi
- Greenbox Stüdyosu
- Podcast Stüdyosu

Tekno-Eğlence Atölyeleri:
- Laser-Tag
- VR (Sanal Gerçeklik Gözlüğü)
- Uçuş Simülatörü
- Araç Simülatörü
- PS5
- Klasik Atari

Spor Atölyeleri:
- Fitness
- Pilates
- Masa Tenisi
- Bilardo
- Langırt
- Shuffleboard
- Satranç
- Okçuluk
- Voleybol
- Futsal
- Cornhole

Rehberlik-Psikolojik Danışmanlık Atölyeleri:
- Manevi Danışmanlık (Değerler Eğitimi)
- Psikolog (Psikoloji Kulübü, Grup Etkinlikleri)
- Psikolojik Danışman (Kariyer Okulu, Sınav Kaygısı, Sınav Stratejileri Seminerleri)
- Aile Danışmanı (Ebeveyn Psikoloji Atölyeleri, Ergenlik, Akademik Başarı Seminerleri)
- Eğitim Danışmanı (Tercih Danışmanlığı, Sınav Bilgilendirme Seminerleri)
- Sağlık Danışmanlığı / Hemşire (Beslenme ve Diyetetik, Geleneksel Tıp, Kadın Sağlığı, Spor ve Sağlık Seminerleri)
- Öğrenci Seminerleri
- Veli Seminerleri
- Zarafet Atölyesi

Diğer Atölyeler:
- Mangala
- Lego
- Resfebe
- Zekâ Oyunları
- Materyal Tasarım
- Kriptoloji
- Tabu
- MaTabu
- Mukabele
EOT,
                ],
            ],
        ],
        'geleneksel-guresler' => [
            'label' => 'Geleneksel Hünkar Çayırı Yağlı Güreşleri',
            'icon' => 'bi-trophy-fill',
            'ogeler' => [
                [
                    'baslik' => 'Geleneksel Hünkar Çayırı Yağlı Güreşleri',
                    'href' => 'https://www.gebze.bel.tr/hizmet/hizmet-detay_geleneksel-hunkar-cayiri-yagli-guresleri_12.html',
                    'img' => 'https://www.gebze.bel.tr/resim/20200212135813.jpg',
                    'detay' => 'Her yıl Gebze\'de Fatih\'in Otağında bu organizasyon gerçekleştirilmektedir.',
                ],
            ],
        ],
    ];

