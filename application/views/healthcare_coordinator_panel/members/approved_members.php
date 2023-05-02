<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">HEALTHCARE MEMBER</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Registered Account</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/members" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">CREATE ACCOUNT</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/members/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">UPLOAD HEALTHCARD ID</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/members/approved/uploaded-scanned-id-form" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">MBL MONITORING</span>
            </a>
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="membersApprovedTable">
                <thead>
                  <tr>
                    <th class="fw-bold">#</th>
                    <th class="fw-bold">NAME OF EMPLOYEE</th>
                    <th class="fw-bold">TYPE OF EMPLOYEE</th>
                    <th class="fw-bold">STATUS</th>
                    <th class="fw-bold">BUSINESS UNIT</th>
                    <th class="fw-bold">DEPARTMENT</th>
                    <th class="fw-bold">STATUS</th>
                    <th class="fw-bold">ACTION</th>
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
  <?php include 'insert_healthcard_id.php' ?>
</div>
  


<script>
    const redirectPage = (route, seconds) => {
                setTimeout(() => {
                window.location.href = route;
                }, seconds);
        }

    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function() {

        $('#membersApprovedTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}healthcare-coordinator/members/approved/fetch`,
                type: "POST",
                // passing the token as data so that requests will be allowed
                data: {
                    'token': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            },

            //Set column definition initialisation properties.
            columnDefs: [{
                "targets": [6, 7], // 6th and 7th column / numbering column
                "orderable": false, //set not orderable
            }, ],
            responsive: true,
            fixedHeader: true,
        });

    })

    const addEmployeeHcId = (emp_id, full_name) => {
      $('#insertHcIdModal').modal('show');
      $('#emp-name').val(full_name);
      $('#emp-id').val(emp_id);
    }

    $('#insertScannedIdForm').submit(function(event){
      event.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        type:'POST',
        url: $(this).attr('action'),
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success:function(response){
          const {
            token,
            status,
            message,
            front_id_error,
            back_id_error
          } = response;
          
          if(status == 'front-error'){
            swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: true,
                type: 'error'
            });
          }
          if(status == 'back-error'){
            swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: true,
                type: 'error'
            });
          }
          if(status == 'failed'){
            swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: true,
                type: 'error'
            });
          }
          if(front_id_error !== ''){
            $('#front-id-error').html(front_id_error);
            $('#front-id').addClass('is-invalid');
          }else{
            $('#front-id-error').html('');
            $('#front-id').removeClass('is-invalid');
          }
          if(back_id_error !== ''){
            $('#back-id-error').html(back_id_error);
            $('#back-id').addClass('is-invalid');
          }else{
            $('#back-id-error').html('');
            $('#back-id').removeClass('is-invalid');
          }
          if(status == 'success'){
            swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
            });
            let page = '<?php echo base_url(); ?>healthcare-coordinator/members/approved/uploaded-scanned-id-form';
            $('#insertScannedIdForm')[0].reset();
            $('#insertHcIdModal').hide();
            redirectPage(page, 2600);
          }
        }
      });
    });
</script>