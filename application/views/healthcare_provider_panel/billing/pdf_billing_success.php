<!-- Page wrapper  -->
 <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">PDF Billing</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Provider</li>
                <li class="breadcrumb-item active" aria-current="page">
                  LOA Billing
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
            <div class="card shadow">
              <div class="card-body">

                <div class="container mb-4">
                  <div class="row">
                    <div class="text-center">
                      <i class="mdi mdi-checkbox-marked-circle-outline text-success" style="font-size: 5rem;"></i>
                    </div>
                    <div class="text-center">
                      <h3 class="ls-2">Billed Successfully!</h3>
                    </div>
                  </div>
                </div>

                <div class="mt-1" style="background:#F8F8F8;border:2px dashed #495579;width:500px;padding:20px;margin:0 auto;">
                  <div class="container" id="printableDiv" style="padding:10px 40px;">
                    <div class="row mt-1">
                      <div class="col-12 d-flex justify-content-center">
                        <table>
                          <tr class="text-center">
                            <td>
                              <h4> BILLING #: <?= $bill['billing_no'] ?></h4>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <span class="fs-5">
                                Healthcard No. : <?= $bill['health_card_no'] ?>
                              </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <span class="fs-5">
                                Patient Name : <?= $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'] ?>
                              </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <span class="fs-5">
                                Billed By : <?= $bill['billed_by'] ?>
                              </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <span class="fs-5">
                                Billed On : <?= date('m/d/Y', strtotime($bill['billed_on'])) ?>
                              </span>
                            </td>
                          </tr>  

                          <tr>
                            <td>
                              <span class="fs-5">
                                Healthcare Provider : <?= $bill['hp_name'] ?>
                              </span>
                            </td>
                          </tr>

                          <tr>
                            <td class="text-center">
                               <h4> NET BILL : <span class="text-danger"><?= '&#8369;'. number_format($bill['net_bill'], 2) ?></span></h4>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="container mt-4 mb-4">
                  <div class="row">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                      <button class="btn btn-outline-info btn-lg ls-1 me-3" onclick="saveAsImage()">
                        <i class="mdi mdi-file-image"></i> Save as Image
                      </button>
                      <button class="btn btn-outline-danger btn-lg ls-1 me-2" onclick="printDiv('#printableDiv')">
                        <i class="mdi mdi-printer"></i> Print Receipt
                      </button>
                    </div>
                  </div>
                </div>
  
              </div>
            </div>
          </div>
      </div>
    </div>
</div>
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  const saveAsImage = () => {
    // Get the div element you want to save as an image
    const element = document.querySelector("#printableDiv");
    // Use html2canvas to take a screenshot of the element
    html2canvas(element)
      .then(function(canvas) {
        // Convert the canvas to an image data URL
        const imgData = canvas.toDataURL("image/png");
        // Create a temporary link element to download the image
        const link = document.createElement("a");
        link.download = `receipt_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }
  
  const printDiv = (layer) => {
    $(layer).printThis({
      importCSS: true,
      copyTagClasses: true,
      copyTagStyles: true,
      removeInline: false,
    });
  }
</script>