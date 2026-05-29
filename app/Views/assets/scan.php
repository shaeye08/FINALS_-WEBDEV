<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AssetFlow - Live Barcode Engine Terminal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-dark text-white">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🖨️ Real-Time Barcode Scanner Matrix</h2>
                <a href="<?= base_url('/assets') ?>" class="btn btn-outline-light">Return to Ledger</a>
            </div>

            <div class="card bg-secondary text-white shadow-lg border-0 mb-4">
                <div class="card-body">
                    <div id="reader" style="width: 100%; min-height: 350px; background: #212529; border-radius: 8px;"></div>
                </div>
            </div>

            <div id="scanner-feedback" class="alert alert-info text-center d-none" role="alert">
                Analyzing barcode sequence telemetry data...
            </div>
        </div>
    </div>
</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Pause the live scanning module immediately to stop multiple processing cycles
        html5QrcodeScanner.clear();
        
        const feedbackAlert = document.getElementById('scanner-feedback');
        feedbackAlert.classList.remove('d-none', 'alert-danger', 'alert-info');
        feedbackAlert.classList.add('alert-warning');
        feedbackAlert.innerText = `Decoded Sequence: "${decodedText}". Querying database records...`;

        // Dispatch background AJAX call to check if the barcode matches an asset
        fetch(`<?= base_url('/assets/lookup') ?>/${encodeURIComponent(decodedText)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Asset signature not located.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    feedbackAlert.className = "alert alert-success";
                    feedbackAlert.innerText = "Match established! Redirecting to hardware ledger management record...";
                    // Route the staff user straight into the asset's edit modification profile view
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                feedbackAlert.className = "alert alert-danger";
                feedbackAlert.innerText = `Error: sequence value "${decodedText}" does not exist in inventory records.`;
                
                // Automatically re-initialize the live streaming camera matrix after a short delay
                setTimeout(() => {
                    feedbackAlert.classList.add('d-none');
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                }, 3500);
            });
    }

    function onScanFailure(error) {
        // Continuous debug loop tracking - left blank to prevent console log cluttering
    }

    // Spin up the scanning class framework with standard 1D barcode formatting configurations
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { 
            fps: 15, 
            qrbox: { width: 450, height: 180 }, // Wide bounding rectangle optimized specifically for horizontal 1D barcodes
            aspectRatio: 1.777778
        },
        /* verbose= */ false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
</body>
</html>