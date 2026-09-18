<?php
// includes/kurumsal-icerik-ust.php ile açılan #icerikMetni ve .hizmet-kutu kutularını kapatır,
// ayrıca metinBoyutAyarla() JS fonksiyonunu tanımlar (yazı büyüt/küçült/sıfırla butonları için).
?>
    </div>
</div>

<script>
if (typeof metinBoyutAyarla !== 'function') {
    function metinBoyutAyarla(yon) {
        const icerik = document.getElementById('icerikMetni');
        if (!icerik) return;
        let boyut = parseFloat(icerik.dataset.boyut || '1');
        if (yon === 0) {
            boyut = 1;
        } else {
            boyut = Math.min(1.4, Math.max(0.8, boyut + (yon * 0.1)));
        }
        icerik.dataset.boyut = boyut;
        icerik.style.fontSize = boyut + 'em';
    }
}
</script>
