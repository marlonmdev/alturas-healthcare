<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">LOA</h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Coordinator</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Match LOA  
                    </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
            <div class="col-12 mb-4 mt-0">
                <div class="input-group">
                    <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/billed" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 border border-light">
                    <div class="row pt-3 pb-3">
                        <div class="col-lg-7">
                            <label>Patient's Name: </label>
                            <input name="patient-name" type="text" class="form-control fw-bold fs-5 text-danger" value="<?php echo $fullname ?>" readonly>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-lg-7">
                            <label>Medical Services: </label><br>
                            <?php foreach($services as $service) : ?>
                            <span name="med-services[]"><?php echo $service['item_description'] ?></span>
                            <input name="ctype-id" type="hidden" value="<?php echo $service['ctype_id'] ?>">
                        </div>
                        <div class="col-lg-3">
                            <label>Amount: </label><br>
                            <script>
                                const fee = document.querySelectorAll('.service-fee');
                                let service_fee = parseFloat(<?php echo $service['service_fee'] ?>);
                                let quantity = parseFloat(<?php echo $service['quantity'] ?>);
                                let total = 0;
                                total = service_fee * quantity;
                                fee.value = total;
                            </script>
                            <span class="service-fee" name="amount[]"><?php echo $service['service_fee'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="offset-3">
                            <em class="fw-bold">Subtotal......................</em>
                            <span class="offset-2 fw-bold" name="total-services"><?php echo $total_services ?></span>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-lg-7">
                            <label>Deductions: </label><br>
                            <?php foreach($deductions as $deduct) : ?>
                            <span name="deductions[]"><?php echo $deduct['deduction_name'] ?></span>
                        </div>
                        <div class="col-lg-3"><br>
                            <span class="pt-2" name="deduct-amount[]"><?php echo $deduct['deduction_amount'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="offset-3">
                            <em class="fw-bold">Subtotal......................</em>
                            <span class="offset-2 fw-bold" name="total-deductions"><?php echo $total_deductions ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="offset-4">
                            <label class="fw-bold">TOTAL Net Bill</label>
                            <span class="fw-bold offset-1 fs-5 ps-3" name="net-bill"><?php echo $total_net_bill ?></span>
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-6 border" style="width:50%;height:650px;">
                        <iframe id="pdf-viewer" style="width:100%;height:580px;"></iframe><br>
                        <div class="row">
                        <div class="col pt-2">
                            <label class="fw-bold fw-bols fs-5 pt-2">Total Net Bill: </label>
                            <input class="fw-bold fs-5 ps-3 offset-1 text-danger" name="hc-provider-net-bill" value="<?php echo $net_bill ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- End Container fluid  -->
</div>
<!-- End Wrapper -->

<script>
const baseUrl = `<?php echo base_url(); ?>`;
   
    window.onload = (event) => {
        viewAttachPDF();
    };

    const viewAttachPDF = () => {
        let pdfViewer = document.querySelector('#pdf-viewer');
        let pdfFile = `${baseUrl}uploads/pdf_bills/<?php echo $pdf_bill ?>`;
        let fileExists = checkFileExists(pdfFile);

        if(fileExists){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', pdfFile, true);
        xhr.responseType = 'blob';

        xhr.onload = function(e) {
            if (this.status == 200) {
            let blob = this.response;
            let reader = new FileReader();

            reader.onload = function(event) {
                let dataURL = event.target.result;
                let iframe = document.querySelector('#pdf-viewer');
                iframe.src = dataURL;
            };
            reader.readAsDataURL(blob);
            }
        };
        xhr.send();
        }
    }

    const checkFileExists = (fileUrl) => {
        let xhr = new XMLHttpRequest();
        xhr.open('HEAD', fileUrl, false);
        xhr.send();

        return xhr.status == "200" ? true: false;
    }
</script>
