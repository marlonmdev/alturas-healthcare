<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2"><i class="mdi mdi-format-float-none"></i> Audited</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office IAD</li>
              <li class="breadcrumb-item active" aria-current="page">Audited</li>
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
                    <a
                        class="nav-link"
                        href="<?php echo base_url(); ?>head-office-iad/biling/audit"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">For Audit</span></a
                    >
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="<?php echo base_url(); ?>head-office-iad/biling/audited"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Audited</span></a
                    >
                </li>
            </ul>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="">
              <table class="table table-hover table-responsive" id="forAuditTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th style="color: white;">CONSOLIDATED BILLING</th>
                    <th style="color: white;">DATE</th>
                    <th style="color: white;">HEALTHCARE PROVIDER</th>
                    <th style="color: white;">STATUS</th>
                    <th style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody class="fs-5">
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
 function redirectPage(route, seconds){
          setTimeout(() => {
          window.location.href = route;
          }, seconds);
  }
  $(document).ready(function() {
    let matchedTable = $('#forAuditTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      // Load data for the table's content from an Ajax source  
      ajax: {
        url: '<?php echo base_url();?>head-office-iad/biling/audited/fetch',
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
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });
  });
 const tagDoneAudit = (payment_no) => {
    $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: '<span class="fs-5">Are you sure it`s already Audited?</span>',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function(){

                        $.ajax({
                            url: '<?php echo base_url();?>head-office-iad/biling/submit-audited',
                            method: "POST",
                            data: {
                                'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                                'payment_no' : payment_no,
                            },
                            dataType: "json",
                            success: function(response){
                                const { 
                                    token,status,message
                                } = response;

                                if(status == 'success'){
                                    swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: true,
                                        type: 'success'
                                    });
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
                    }
                },
            }
        });
 }
</script>