<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">
                  Paid
              </li>
              </ol>
          </nav>
          </div>
      </div>
      </div>
  </div>
  <hr>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="col-12 pb-2">
        <div class="input-group">
            <a href="<?php echo base_url(); ?>head-office-accounting/billing-list/paid-bill" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                <strong class="ls-2" style="vertical-align:middle">
                    <i class="mdi mdi-arrow-left-bold"></i> Go Back
                </strong>
            </a>
        </div>
    </div>
    <div class="row pt-3 pt-1">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
                <div class="card-body">
                  <div class="table">
                    <table class="table table-hover table-responsive" id="paidTable">
                      <thead style="background-color:#eddcb7">
                        <tr>
                          <th class="fw-bold">Billing No.</th>
                          <th class="fw-bold">LOA/NOA #</th>
                          <th class="fw-bold">Patient Name</th>
                          <th class="fw-bold">Remaining MBL</th>
                          <th class="fw-bold">Percentage</th>
                          <th class="fw-bold">Hospital Bill</th>
                          <th class="fw-bold">Personal Charge</th>
                          <th class="fw-bold">Company Charge</th>
                          <th class="fw-bold">Healthcare Advance</th>
                          <th class="fw-bold">Total Paid Bill</th>
                 
                          <th class="fw-bold">Status</th>
                          <th class="fw-bold">View SOA</th>
                        </tr>
                      </thead>
                      <tbody id="billed-tbody">
                      </tbody>
                      <tfoot>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="fw-bold">TOTAL BILL </td>
                        <td><span class="text-danger fw-bold fs-5" id="pd-total-bill"></span></td>
                        <td></td>
                        <td></td>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col pt-4 offset-10">
              <button class="btn btn-danger ls-1" onclick="printDiv('#printableDiv')" title="click to print data"><i class="mdi mdi-printer"></i> Print </button>
            </div>
      </div>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
