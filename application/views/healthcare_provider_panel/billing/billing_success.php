<!-- Page wrapper  -->
 <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Billing</h4>
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
        <!-- <div class="vh-100 d-flex justify-content-center align-items-center"> -->
          <div class="col-md-12">
            <!-- <div class="border border-3 border-info"></div> -->
            <div class="card shadow">
              <div class="card-body">

                <div class="container">
                  <div class="row">
                    <div class="text-center">
                      <i class="mdi mdi-checkbox-marked-circle-outline text-success" style="font-size: 5rem;"></i>
                    </div>
                    <div class="text-center">
                      <p class="fs-4 ls-2">Billed Successfully!</p>
                    </div>
                  </div>
                </div>

                <div class="container" id="printableDiv">
                  <div class="row">
                    <div class="col-12">
                      <ul class="list-unstyled">

                        <li class="ls-1 fs-5">
                          <i class="mdi mdi-checkbox-blank-circle text-secondary"></i> 
                          Patient's Name: Marlon M.
                        </li>

                        <li class="ls-1 fs-5">
                          <i class="mdi mdi-checkbox-blank-circle text-secondary"></i> 
                          Healthcare Provider: Hospital's Name
                        </li>

                        <li class="ls-1 fs-5">
                          <i class="mdi mdi-checkbox-blank-circle text-secondary"></i> 
                          Billing No.: #123-45687
                        </li>

                        <li class="ls-1 fs-5">
                          <i class="mdi mdi-checkbox-blank-circle text-secondary"></i> 
                          Billed On: June 23,2023
                        </li>

                        <li class="ls-1 fs-5">
                          <i class="mdi mdi-checkbox-blank-circle text-secondary"></i> Status:
                          <span class="badge rounded-pill bg-warning text-black fw-bold">
                            Unpaid
                          </span>
                        </li>

                      </ul>
                    </div>
                  </div>

                  <div class="row my-2 mx-1 justify-content-center">
                    <table class="table table-striped">
                      <thead class="bg-dark">
                        <tr>
                          <th class="text-white ls-2">Service</th>
                          <th class="text-white ls-2">Qty</th>
                          <th class="text-white ls-2">Fee</th>
                          <th class="text-white ls-2">Amount</th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td>Pro Package</td>
                          <td>4</td>
                          <td>$200</td>
                          <td>$800</td>
                        </tr>
                        <tr>
                          <td>Web hosting</td>
                          <td>1</td>
                          <td>$10</td>
                          <td>$10</td>
                        </tr>
                        <tr>
                          <td>Consulting</td>
                          <td>1</td>
                          <td>$300</td>
                          <td>$300</td>
                        </tr>
                      </tbody>

                    </table>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <ul class="list-unstyled">
                        <li class="text-muted ms-3"><span class="text-black me-2">Total Bill:</span>P 1110</li>
                        <li class="text-muted ms-3 mt-2"><span class="text-black me-2">Total Deduction:</span>$111</li>
                        <li class="text-muted ms-3 mt-2"><span class="text-black me-2">Net Bill:</span>$111</li>
                        <li class="text-muted ms-3 mt-2"><span class="text-black me-2">Personal Charge:</span>$111</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="container mb-4">
                  <div class="row">
                    <div class="col-12 d-flex justify-content-end align-items-end">
                      <button class="btn btn-outline-dark ls-1" onclick="printDiv('#printableDiv')">Print Receipt</button>
                    </div>
                  </div>
                </div>
  
              </div>
            </div>
          </div>
        <!-- </div> -->
      </div>
    </div>
</div>
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  
  function printDiv(layer) {
    $(layer).printThis({
      importCSS: true,
      copyTagClasses: true,
      copyTagStyles: true,
      removeInline: false,
    });
  }
</script>