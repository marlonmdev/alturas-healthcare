<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">DISAPPROVED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Provider</li>
              <li class="breadcrumb-item active" aria-current="page">Disapproved</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-provider/noa-requests/pending" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-provider/noa-requests/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-provider/noa-requests/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-provider/noa-requests/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">BILLED</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-provider/noa-requests/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Completed</span>
            </a>
          </li> -->
        </ul>


        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">  
              <?php include 'view_disapproved_noa_details.php'; ?>
              <table id="disapprovedNoaTable" class="table table-striped" style="width:100%">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">NOA NO.</th>
                    <th class="fw-bold" style="color: white">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white">NAME OF HOSPITAL</th>
                    <th class="fw-bold" style="color: white">DATE OF ADMISSION</th>
                    <th class="fw-bold" style="color: white">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">STATUS</th>
                    <th class="fw-bold" style="color: white">ACTION</th>
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




<script>
    const baseUrl = "<?php echo base_url(); ?>";
    $(document).ready(function() {

        $('#disapprovedNoaTable').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}healthcare-provider/noa-requests/disapproved/fetch`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: {
            'token': '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        //Set column definition initialisation properties.
        columnDefs: [{
            "targets": [5, 6], // numbering column
            "orderable": false, //set not orderable
        }, ],
        responsive: true,
        fixedHeader: true,
        });

    });

    function viewNoaInfo(req_id) {
        $.ajax({
            url: `${baseUrl}healthcare-provider/noa-requests/view/${req_id}`,
            type: "GET",
            success: function(response) {
                const res = JSON.parse(response);
                const base_url = window.location.origin;
                const {
                status,
                token,
                noa_no,
                disapproved_by,
                disapproved_on,
                disapprove_reason,
                member_mbl,
                remaining_mbl,
                first_name,
                middle_name,
                last_name,
                suffix,
                date_of_birth,
                age,
                hospital_name,
                health_card_no,
                requesting_company,
                admission_date,
                chief_complaint,
                request_date,
                req_status,
                work_related,
                percentage
                } = res;

                $("#viewNoaModal").modal("show");

                $('#noa-no').html(noa_no);
                $('#noa-status').html('<strong class="text-danger">[' + req_status + ']</strong>');
                $('#disapproved-by').html(disapproved_by);
                $('#disapproved-on').html(disapproved_on);
                $('#disapprove-reason').html(disapprove_reason);
                $('#member-mbl').html(member_mbl);
                $('#remaining-mbl').html(remaining_mbl);
                $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
                $('#date-of-birth').html(date_of_birth);
                $('#age').html(age);
                $('#hospital-name').html(hospital_name);
                $('#admission-date').html(admission_date);
                $('#chief-complaint').html(chief_complaint);
                $('#request-date').html(request_date);
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
</script>
