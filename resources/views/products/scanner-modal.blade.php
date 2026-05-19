<!-- QR READER MODAL -->
<div id="scanner-modal" class="scanner-modal">
    <div class="scanner-container">
        <div class="scanner-header">
            <h5><i class="fas fa-barcode me-2"></i>Scanner QR/Barcode</h5>
            <button type="button" class="close-btn" onclick="stopScanner()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="scanner-body">
            <canvas id="video-canvas"></canvas>
            <div class="scanner-overlay"></div>
        </div>
        <div class="scanner-footer">
            <div class="scanner-status scanning" id="scanner-status">
                <span class="spinner-small"></span>Scanning...
            </div>
            <small class="text-muted">Arahkan kamera ke barcode atau QR code</small>
        </div>
    </div>
</div>
