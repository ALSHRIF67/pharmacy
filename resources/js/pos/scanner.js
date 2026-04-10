import { getProductByBarcode, saveProductLocally } from '../offline/db.js';

window.addEventListener('DOMContentLoaded', () => {
    const html5QrCode = new Html5Qrcode("reader");
    const barcodeInput = document.getElementById('barcode-input');

    const qrCodeSuccessCallback = async (decodedText, decodedResult) => {
        barcodeInput.value = decodedText;
        barcodeInput.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter' }));
    };

    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    
    // Manual focus helper
    document.addEventListener('click', () => {
        barcodeInput.focus();
    });
});
