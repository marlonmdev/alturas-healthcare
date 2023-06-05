<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">RESET MBL FOR THE YEAR <span class="text-info"><?php echo date('Y'); ?></span></h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Reset MBL</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
       

        <div class="card shadow">
          <div class="card-body">
            <div class="text-center">
              <div class="col-3 border offset-4">
                <div class="pt-4 pb-4">
                    <a class="btn btn-lg btn-success" id="reset-btn" href="JavaScript:void(0)" onclick="submitResetMbl()" title="click to reset MBL of all employees">Reset MBL</a>
                </div>
              </div>
            </div>
            <div class="offset-4">
                <small><i class="text-danger">Note: All Alturas Employees' MBLs will be affected by the MBL reset.</i></small>
            </div>
            <br>
            <div class="ps-2">
              <h5>LIST OF NEWLY PROMOTED EMPLOYEES</h5><br>
              <table class="table table-bordered" id="promotedTable">
                <thead>
                  <th class="fw-bold text-center">#</th>
                  <th class="fw-bold text-center">Healthcard No.</th>
                  <th class="fw-bold text-center">Employee Name</th>
                  <th class="fw-bold text-center">Business Unit</th>
                  <th class="fw-bold text-center">New Position Level</th>
                  <th class="fw-bold text-center">New MBL</th>
                </thead>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script>
  const baseurl = "<?php echo base_url(); ?>";

  $(document).ready(function(){
    let promotedTable = $('#promotedTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseurl}healthcare-coordinator/mbl/newly-promoted/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

  });

  const submitResetMbl = () => {
    $.confirm({
          title: '<strong>Confirmation!</strong>',
          content: 'Are you sure you want to reset MBL?<br>Please Confirm.',
          type: 'blue',
          buttons: {
              confirm: {
                  text: 'Yes',
                  btnClass: 'btn-blue',
                  action: function(){

                      $.ajax({
                          url: `${baseUrl}healthcare-coordinator/mbl/reset-mbl/submit`,
                          method: "POST",
                          data: {
                              'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                          },
                          dataType: "json",
                          success: function(response){
                              const { 
                                  token,payment_no,status,message
                              } = response;

                              if(status == 'success'){
                                  swal({
                                      title: 'Success',
                                      text: message,
                                      timer: 3000,
                                      showConfirmButton: false,
                                      type: 'success'
                                  });
                                  // setTimeout(function () {
                                  //     window.location.href = '<?php echo base_url();?>head-office-accounting/billing-list/for-payment';
                                  // }, 2600);
                              }
                              if(status == 'error'){
                                  swal({
                                      title: 'Error',
                                      text: message,
                                      timer: 3000,
                                      showConfirmButton: true,
                                      type: 'error'
                                  });
                              }
                          }
                      }); 
                  },
              },
              cancel: {
                  btnClass: 'btn-dark',
                  action: function() {
                      // close dialog
                  }
              },
          }
      });
  }
</script>

