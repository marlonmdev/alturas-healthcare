<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Import Text File</title>
  <link rel="icon" href="<?= base_url(); ?>assets/images/hmo-logo.png">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/Toastr/build/toastr.min.css">
</head>
<style type="text/css">
  .btn {
    padding: 8px 16px;
  }

  .btn,
  input[type="file"] {
    border-radius: 5px;
  }
</style>

<body>
  <div class="container mt-4">
    <h2 style="background:#001253;color:#ffff;padding:20px;border-radius:5px;" class="text-center">Barcode Scanner</h2>

    <div class="alert alert-success alert-dismissible fade show mt-4 d-none" id="successAlert" role="alert">
      <h3 class="text-center"><strong><i class="bi bi-check-circle-fill"></i> Success!</strong></h3>
      <h5 class="text-center" id="successMsg"></h5>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row d-flex justify-content-center align-items-center my-4" id="importForm">
      <form action="<?php echo base_url('scan/barcode'); ?>" method="post" enctype="multipart/form-data" id="barcode-form">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="col-md-8 offset-2">
          <div class="form-group input-group-lg">
            <input type="text" class="form-control" id="barcode-input" placeholder="Scan barcode here">
          </div>
        </div>
        <div class="col-md-12 d-flex justify-content-center mt-3">
          <button type="button" id="barcode-button" class="btn btn-primary">
            <strong>
              SCAN
            </strong>
          </button>
          <br><br>
          <div id="barcode-result"></div>
        </div>
      </form>
      <br><br>
      <form id="qrcode-form">
        <input type="text" id="qrcode-input" placeholder="Scan QR code here">
        <button type="button" id="qrcode-button">Scan</button>
        <div id="qrcode-result"></div>
      </form>
    </div>

  </div>

</body>
<script src="<?= base_url() ?>assets/vendors/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/vendors/Toastr/build/toastr.min.js"></script>
<script src="<?= base_url() ?>assets/vendors/quanggaJS/dist/quangga.min.js"></script>
<script>
  // barcode reader
  const barcodeBtn = document.querySelector("#barcode-button");
  barcodeBtn.addEventListener("click", function() {
    Quagga.init({
      inputStream: {
        type: "Live",
        constraints: {
          width: 640,
          height: 480,
          facingMode: "environment",
        },
      },
      locator: {
        patchSize: "medium",
        halfSample: true,
      },
      numOfWorkers: 2,
      decoder: {
        readers: ["code_128_reader"],
      },
      locate: true,
    });
    Quagga.start();
  });

  Quagga.onProcessed(function(result) {
    var drawingCtx = Quagga.canvas.ctx.overlay,
      drawingCanvas = Quagga.canvas.dom.overlay;

    if (result) {
      if (result.boxes) {
        drawingCtx.clearRect(
          0,
          0,
          parseInt(drawingCanvas.getAttribute("width")),
          parseInt(drawingCanvas.getAttribute("height"))
        );
        result.boxes
          .filter(function(box) {
            return box !== result.box;
          })
          .forEach(function(box) {
            Quagga.ImageDebug.drawPath(box, {
              x: 0,
              y: 1
            }, drawingCtx, {
              color: "green",
              lineWidth: 2,
            });
          });
      }
    }
  })

  // qr code reader
  const qrcodeBtn = document.querySelector("#qrcode-button");
  qrcodeBtn.addEventListener("click", function() {
    Quagga.init({
      inputStream: {
        type: "Live",
        constraints: {
          width: 640,
          height: 480,
          facingMode: "environment",
        },
      },
      decoder: {
        readers: ["qr_reader"],
      },
      locate: true,
    });
    Quagga.start();
  });

  Quagga.onDetected(function(result) {
    document.getElementById("qrcode-result").innerHTML = result.codeResult.code;
    Quagga.stop();
  });
</script>

</html>