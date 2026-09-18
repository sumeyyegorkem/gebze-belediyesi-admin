// =========================================================
// GEBZE BELEDİYESİ - GENEL JAVASCRIPT DOSYASI
// =========================================================

document.addEventListener('DOMContentLoaded', function () {

    // 1) Bootstrap form doğrulama (iletişim formu vb. için)
    //    Kullanıcı zorunlu alanları boş bırakırsa kırmızı uyarı gösterir.
    const dogrulamaFormlari = document.querySelectorAll('.js-dogrula');
    dogrulamaFormlari.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // 2) 5 saniye sonra otomatik kaybolan bildirim (alert) mesajları
    const bildirimler = document.querySelectorAll('.otomatik-kaybol');
    bildirimler.forEach(function (bildirim) {
        setTimeout(function () {
            bildirim.classList.remove('show');
            bildirim.classList.add('fade');
        }, 4000);
    });

    // 3) Sayfa içi (aynı sayfa) linklerde yumuşak kaydırma
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            // Sadece "#" olan linkler (sayfalama butonları gibi) burada işlenmez,
            // onların kendi tıklama davranışı zaten kendi sayfalarında yönetiliyor.
            if (href.length <= 1) return;
            const hedef = document.querySelector(href);
            if (hedef) {
                e.preventDefault();
                hedef.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // 4) Duyuru şeridi ok butonları: bir tık = bir kart genişliği kadar kaydır
    const serit = document.getElementById('duyuruSerit');
    const solBtn = document.getElementById('solKaydir');
    const sagBtn = document.getElementById('sagKaydir');
    if (serit && solBtn && sagBtn) {
        const kaydirmaMiktari = 320;
        solBtn.addEventListener('click', function () {
            serit.scrollBy({ left: -kaydirmaMiktari, behavior: 'smooth' });
        });
        sagBtn.addEventListener('click', function () {
            serit.scrollBy({ left: kaydirmaMiktari, behavior: 'smooth' });
        });
    }

    // 5) Ana sayfadaki Projelerimiz filtre butonları
    const projeFiltreButonlari = document.querySelectorAll('.btn-filtre-ana');
    const projeKartlari = document.querySelectorAll('.ana-proje-karti');
    projeFiltreButonlari.forEach(function (buton) {
        buton.addEventListener('click', function () {
            projeFiltreButonlari.forEach(b => b.classList.remove('active'));
            buton.classList.add('active');
            const secilenFiltre = buton.getAttribute('data-filtre');
            projeKartlari.forEach(function (kart) {
                if (secilenFiltre === 'hepsi' || kart.getAttribute('data-durum') === secilenFiltre) {
                    kart.style.display = '';
                } else {
                    kart.style.display = 'none';
                }
            });
        });
    });

});
