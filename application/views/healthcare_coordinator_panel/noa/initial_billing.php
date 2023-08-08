<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2" style="font-size:13px">INITIAL BILLING</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">Healthcare Coordinator</li>
            <li class="breadcrumb-item active" aria-current="page">Initial Billing</li>
              </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow" style="background-color:">
          <div class="card-body">
            <div class="">
              <table class="table table-hover table-responsive" id="initial_billing">
                <thead style="background-color:#ADD8E6">
                  <tr>
                    <th style="color:black;font-weight:bold;font-size:10px">BILLING #</th>
                    <th style="color:black;font-weight:bold;font-size:10px">NOA NO.</th>
                    <th style="color:black;font-weight:bold;font-size:10px">NAME OF PATIENT</th>
                    <th style="color:black;font-weight:bold;font-size:10px">BUSINESS UNIT</th>
                    <th style="color:black;font-weight:bold;font-size:10px">DEPARTMENT</th>
                    <th style="color:black;font-weight:bold;font-size:10px">WORK RELATED</th>
                    <th style="color:black;font-weight:bold;font-size:10px">COMPANY CHARGE</th>
                    <th style="color:black;font-weight:bold;font-size:10px">PERSONAL CHARGE</th>
                    <th style="color:black;font-weight:bold;font-size:10px">HEALTHCARE ADVANCE</th>
                    <th style="color:black;font-weight:bold;font-size:10px">ACTION</th>
                  </tr>
                </thead>
                <tbody id="billed-tbody" style="color:black;font-size:11px">
                </tbody> 
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
</div>


<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function () {
    $('#initial_billing').DataTable({ 
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/billed/initial_billing`,
        type: "POST",
        data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>' }
      },

      columnDefs: [{ 
        // "targets": [5],
        "orderable": false,
      },
      ],
      data: [],
      deferRender: true,
      info: false,
      paging: false,
      // filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });      
  }); 
</script>