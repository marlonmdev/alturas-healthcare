<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/loa_controller/view_ledger" type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">History of Charges</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <input type="hidden" id="b-emp-id" value="<?php echo $emp_id; ?>">
    <div class="row">
      <div class="col-lg-12">
      	<div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="ledgertable">
                <thead class="fs-5"style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">MBL</th>
                    <th class="fw-bold" style="color: white;">ACCOUNT NAME</th>
                    <th class="fw-bold" style="color: white;">ACCOUNT NUMBER</th>
                    <th class="fw-bold" style="color: white;">CHEQUE NUMBER</th>
                    <th class="fw-bold" style="color: white;">BANK</th>
                    <th class="fw-bold" style="color: white;">CHEQUE DATE</th>
                    <th class="fw-bold" style="color: white;">AMOUNT</th>
                    <th class="fw-bold" style="color: white;">SUPPORTING DOCUMENT (CV)</th>
                    <th class="fw-bold" style="color: white;">REMARK</th>
                    <th class="fw-bold" style="color: white;">PURPOSE</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal to dispaly image -->
<div class="modal fade" id="viewPDFBillModal" tabindex="-1" data-bs-backdrop="static" style="height: 100%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Check Voucher Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="pdf-viewer" src="" style="width: 100%; height: 500px;"></iframe>
      </div>
    </div>
  </div>
</div>
<!-- End -->


<div class="modal fade" id="recordmodal" tabindex="-1" data-bs-backdrop="static" style="height: 100%">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Patient Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input id="m-loa-id" name="m-loa-id" type="hidden">
        <input id="m-noa-id" name="m-noa-id" type="hidden">
        <!-- Add patient information details here -->
        <table class="table table-bordered table-striped table-hover table-responsive table-sm">
          <tr>
            <td class="fw-bold ls-1">Name of Patient:</td>
            <td class="fw-bold ls-1" id="name_patient"></td>
          </tr>
          <tr>
            <td class="fw-bold ls-1">Type of Request:</td>
            <td class="fw-bold ls-1" id="type_request"></td>
          </tr>
          <tr>
            <td class="fw-bold ls-1">Date of Request:</td>
            <td class="fw-bold ls-1" id="date_request"></td>
          </tr>
          <tr>
            <td class="fw-bold ls-1">Chief Complaint:</td>
            <td class="fw-bold ls-1" id="chief_complaint"></td>
          </tr>
          <tr>
            <td class="fw-bold ls-1">Work Related:</td>
            <td class="fw-bold ls-1" id="work_related"></td>
          </tr>
          <tr>
            <td class="fw-bold ls-1">Percentage:</td>
            <td class="fw-bold ls-1" id="percentage"></td>
          </tr>
        </table>
        
        <!-- Add more patient details as needed -->
      </div>
    </div>
  </div>
</div>


<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function () {
    $('#ledgertable').DataTable({ 
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa_controller/fetch_ledger_data`,
        type: "POST",
        data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
          'emp_id' : $('#b-emp-id').val(),
        }
      },

      columnDefs: [{ 
        "orderable": false,
      },],
      data: [],
      deferRender: true,
      info: false,
      paging: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });      
  });
  function viewPDFBill(pdfFile) {
    $('#viewPDFBillModal').modal('show');
    let iframe = document.getElementById('pdf-viewer');
    iframe.src = baseUrl + 'uploads/paymentDetails/' + pdfFile;
  }

  const viewRecords = (loa_id,noa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa_controller/view_record`,
      type:"GET",
      data : {
        'loa_id' : loa_id,
        'noa_id' : noa_id
      },
      success:function(response){
        const res=JSON.parse(response);
        const base_url=window.location.origin;
        const{
          status,
          token,
          first_name,
          middle_name,
          last_name,
          suffix,
          loa_request_type,
          request_date,
          chief_complaint,
          work_related,
          percentage,
          attending_physician,
          rx_file,
          healthcare_provider,
          med_services,
        }=res;
        $('#recordmodal').modal('show');
        $('#m-loa-id').val(loa_id);
        $('#m-noa-id').val(noa_id);

        $('#name_patient').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#type_request').html(loa_request_type);
        $('#date_request').html(request_date);
        $('#chief_complaint').html(chief_complaint);
        $('#work_related').html(work_related);
        $('#percentage').html(percentage);

      }
    });
  }

</script>
