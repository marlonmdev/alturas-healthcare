<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">HEALTHCARE ADVANCE REQUEST</h4><h4 style="color:red">(Disapproved)</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Healthcare Advance</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/healthcare_advance/view_healthcare_advance_pending" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/healthcare_advance/view_healthcare_advance_approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/healthcare_advance/view_healthcare_advance_disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="">
              <table class="table table-striped table-hover" id="pending">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">LOA/NOA #</th>
                    <th class="fw-bold" style="color: white">PATIENT NAME</th>
                    <th class="fw-bold" style="color: white">BILLING #</th>
                    <th class="fw-bold" style="color: white">NAME OF HOSPITAL</th>
                    <th class="fw-bold" style="color: white">DATE REQUEST</th>
                    <th class="fw-bold" style="color: white">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php include 'view_healthcare_advance_disapproved.php'; ?>
      </div>
    </div>
  </div>
</div>







<script>
  const baseUrl = "<?= base_url() ?>";
  $(document).ready(function() {

    $("#pending").DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/healthcare_advance/healthcare_advance_datatable_disapproved`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [4, 5], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });
  });

  function viewPChargeModal(billing_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/healthcare_advance/healthcare_advance_modal_disapproved/${billing_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          date_disapproved,
          date_request,
          billed_on,
          admission_date,
          attending_doctors,
          billing_no,
          loa_noa_no,
          healthcard_no,
          patient_name,
          patient_address,
          hospital_name,
          percentage,
          work_related,
          remaining_mbl,
          hospital_bill,
          company_charge,
          personal_charge,
          healthcare_advance
        } = res;

        $("#viewPersonalChargeModal").modal("show");
        $('#date_request').html(date_request);
        $('#date_disapproved').html(date_disapproved);
        $('#billing-no').html(billing_no);
        $('#loa-noa-no').html(loa_noa_no);
        $('#billed_on').html(billed_on);
        $('#admission_date').html(admission_date);
        $('#attending_doctors').html(attending_doctors);
        $('#billing_no').html(billing_no);
        $('#healthcard_no').html(healthcard_no);
        $('#patient_name').html(patient_name);
        $('#patient_address').html(patient_address);
        $('#hospital_name').html(hospital_name);
        $('#remaining_mbl').html(remaining_mbl);
        $('#hospital_bill').html(hospital_bill);
        $('#company_charge').html(company_charge);
        $('#personal_charge').html(personal_charge);
        $('#healthcare_advance').html(healthcare_advance);

        if(work_related == 'Yes'){ 
          if(percentage == ''){
            wpercent = '100% W-R';
            nwpercent = '';
          }else{
            wpercent = percentage+'%  W-R';
            result = 100 - parseFloat(percentage);
            if(percentage == '100'){
              nwpercent = '';
            }else{
              nwpercent = result+'% Non W-R';
            } 
          } 
        }else if(work_related == 'No'){
          if(percentage == ''){
            wpercent = '';
            nwpercent = '100% Non W-R';
          }else{
            nwpercent = percentage+'% Non W-R';
            result = 100 - parseFloat(percentage);
            if(percentage == '100'){
              wpercent = '';
            }else{
              wpercent = result+'%  W-R';
            }
          }
        }
        $('#percentage').html(wpercent+', '+nwpercent);
      }
    });
  }

  const tagPersonalChargeModal = (billing_id,personal_charge) => {
    $("#tagPersonalChargeModal").modal("show");
    $('#tag-personal-charge').val(personal_charge);
    $('#tag-billing-id').val(billing_id);
  }
</script>