// ==========================================================================
// GLOBAL SCANNER STATE
// ==========================================================================

let scannerState = {
    isRunning: false,
    lastScannedTime: 0,
    debounceTime: 1500,
    stream: null
};

// ==========================================================================
// START SCANNER
// ==========================================================================

async function startScanner() {

    const scannerModal =
        document.getElementById('scanner-modal');

    scannerModal.classList.add('active');

    updateScannerStatus(
        'scanning',
        '📷 Membuka kamera belakang...'
    );

    /*
    |--------------------------------------------------------------------------
    | STOP DULU JIKA MASIH ADA
    |--------------------------------------------------------------------------
    */

    try {

        if (scannerState.isRunning) {

            Quagga.stop();

            scannerState.isRunning = false;
        }

    } catch (e) {}

    /*
    |--------------------------------------------------------------------------
    | INIT QUAGGA
    |--------------------------------------------------------------------------
    */

    Quagga.init({

            inputStream: {

                name: "Live",

                type: "LiveStream",

                target: document.querySelector('#video-canvas'),

                constraints: {

                    width: {
                        ideal: 1920
                    },

                    height: {
                        ideal: 1080
                    },

                    /*
                    |--------------------------------------------------------------------------
                    | PAKSA KAMERA BELAKANG
                    |--------------------------------------------------------------------------
                    */

                    facingMode: {
                        exact: "environment"
                    }

                }
            },

            locator: {

                patchSize: "medium",

                halfSample: false
            },

            numOfWorkers: navigator.hardwareConcurrency || 4,

            frequency: 10,

            decoder: {

                readers: [

                    "ean_reader",

                    "ean_8_reader",

                    "code_128_reader",

                    "upc_reader",

                    "upc_e_reader",

                    "code_39_reader",

                    "codabar_reader",

                    "i2of5_reader"

                ]
            },

            locate: true

        },

        function(err) {

            /*
            |--------------------------------------------------------------------------
            | FALLBACK JIKA exact environment GAGAL
            |--------------------------------------------------------------------------
            */

            if (err) {

                console.warn(
                    'Environment camera gagal, fallback...',
                    err
                );

                Quagga.init({

                        inputStream: {

                            name: "Live",

                            type: "LiveStream",

                            target: document.querySelector('#video-canvas'),

                            constraints: {

                                width: {
                                    ideal: 1280
                                },

                                height: {
                                    ideal: 720
                                },

                                facingMode: "environment"
                            }
                        },

                        locator: {

                            patchSize: "medium",

                            halfSample: false
                        },

                        decoder: {

                            readers: [

                                "ean_reader",

                                "ean_8_reader",

                                "code_128_reader",

                                "upc_reader",

                                "upc_e_reader",

                                "code_39_reader"

                            ]
                        },

                        locate: true

                    },

                    function(err2) {

                        if (err2) {

                            console.error(err2);

                            updateScannerStatus(
                                'error',
                                '❌ Kamera gagal dibuka'
                            );

                            return;
                        }

                        startQuaggaEngine();
                    }
                );

                return;
            }

            startQuaggaEngine();
        }
    );
}

// ==========================================================================
// START ENGINE
// ==========================================================================

function startQuaggaEngine() {

    Quagga.start();

    scannerState.isRunning = true;

    updateScannerStatus(
        'success',
        '✅ Kamera siap, arahkan ke barcode'
    );

    /*
    |--------------------------------------------------------------------------
    | REMOVE EVENT LAMA
    |--------------------------------------------------------------------------
    */

    Quagga.offDetected(onScanSuccess);

    Quagga.offProcessed(onProcessed);

    /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

    Quagga.onDetected(onScanSuccess);

    Quagga.onProcessed(onProcessed);
}

// ==========================================================================
// DRAW BOX
// ==========================================================================

function onProcessed(result) {

    const drawingCtx =
        Quagga.canvas.ctx.overlay;

    const drawingCanvas =
        Quagga.canvas.canvas.overlay;

    if (!drawingCtx || !drawingCanvas) {
        return;
    }

    drawingCtx.clearRect(
        0,
        0,
        drawingCanvas.width,
        drawingCanvas.height
    );

    if (result && result.boxes) {

        result.boxes

            .filter(box => box !== result.box)

            .forEach(box => {

                Quagga.ImageDebug.drawPath(

                    box,

                    {
                        x: 0,
                        y: 1
                    },

                    drawingCtx,

                    {
                        lineWidth: 2
                    }
                );
            });
    }

    if (result && result.box) {

        Quagga.ImageDebug.drawPath(

            result.box,

            {
                x: 0,
                y: 1
            },

            drawingCtx,

            {
                lineWidth: 3
            }
        );
    }

    if (
        result &&
        result.codeResult &&
        result.codeResult.code
    ) {

        Quagga.ImageDebug.drawPath(

            result.line,

            {
                x: 'x',
                y: 'y'
            },

            drawingCtx,

            {
                color: 'green',
                lineWidth: 4
            }
        );
    }
}

// ==========================================================================
// SCAN SUCCESS
// ==========================================================================

function onScanSuccess(result) {

    const currentTime = Date.now();

    /*
    |--------------------------------------------------------------------------
    | DEBOUNCE
    |--------------------------------------------------------------------------
    */

    if (
        currentTime - scannerState.lastScannedTime <
        scannerState.debounceTime
    ) {

        return;
    }

    if (
        result.codeResult &&
        result.codeResult.code
    ) {

        const scannedValue =
            result.codeResult.code;

        console.log(
            'Scanned:',
            scannedValue
        );

        /*
        |--------------------------------------------------------------------------
        | SOUND
        |--------------------------------------------------------------------------
        */

        playBeep();

        /*
        |--------------------------------------------------------------------------
        | INPUT SEARCH
        |--------------------------------------------------------------------------
        */

        document.getElementById('searchInput').value =
            scannedValue;

        updateScannerStatus(
            'success',
            `✅ Barcode: ${scannedValue}`
        );

        scannerState.lastScannedTime =
            currentTime;

        /*
        |--------------------------------------------------------------------------
        | AUTO SUBMIT
        |--------------------------------------------------------------------------
        */

        setTimeout(() => {

            stopScanner();

            document
                .querySelector('form[method="GET"]')
                .submit();

        }, 700);
    }
}

// ==========================================================================
// SOUND BEEP
// ==========================================================================

// function playBeep() {
//     try {
//         const audio = new Audio('data:audio/wav;base64,...');
//         audio.volume = 0.3;
//         audio.play();
//     } catch (e) {}
// }

// ==========================================================================
// UPDATE STATUS
// ==========================================================================

function updateScannerStatus(type, message) {

    const statusEl =
        document.getElementById('scanner-status');

    statusEl.className =
        'scanner-status ' + type;

    statusEl.textContent =
        message;
}

// ==========================================================================
// STOP SCANNER
// ==========================================================================

function stopScanner() {

    try {

        Quagga.offDetected(onScanSuccess);

        Quagga.offProcessed(onProcessed);

        Quagga.stop();

    } catch (err) {

        console.error(err);
    }

    scannerState.isRunning = false;

    const scannerModal =
        document.getElementById('scanner-modal');

    scannerModal.classList.remove('active');
}

// ==========================================================================
// CLOSE MODAL
// ==========================================================================

document
    .getElementById('scanner-modal')

    .addEventListener('click', function(e) {

        if (e.target === this) {

            stopScanner();
        }
    });

// ==========================================================================
// ESC
// ==========================================================================

document.addEventListener('keydown', function(e) {

    if (
        e.key === 'Escape' &&
        document
        .getElementById('scanner-modal')
        .classList.contains('active')
    ) {

        stopScanner();
    }
});
