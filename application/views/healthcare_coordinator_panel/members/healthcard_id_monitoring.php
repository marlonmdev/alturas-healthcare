<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2" style="font-size:14px">HEALTHCARE MEMBER</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Members' ID</li>
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
          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/members" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">CREATE ACOOUNT</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/members/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">UPLOAD HEALTHCARD ID</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1 active" href="<?php echo base_url(); ?>healthcare-coordinator/members/approved/uploaded-scanned-id-form" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">HEALTHCARD ID</span>
            </a>
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="doneHcIdMembersTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">#</th>
                    <th class="fw-bold" style="color: white;">NAME OF EMPLOYEE</th>
                    <th class="fw-bold" style="color: white;">TYPE OF EMPLOYEE</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">BUSINESS UNIT</th>
                    <th class="fw-bold" style="color: white;">DEPARTMENT</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">HEALTHCARD ID</th>
                    <th class="fw-bold" style="color: white;">ACTION</th>
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
  <?php include 'view_healthcard_id.php' ?>
</div>

  



<script>
    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function(){
        $('#doneHcIdMembersTable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax:{
                url: `${baseUrl}healthcare-coordinator/members/approved/uploaded-scanned-id`,
                type: 'POST',
                data: {
                    'token': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            },
            columnDefs: [{
                "targets": [6, 7], // 6th and 7th column / numbering column
                "orderable": false, //set not orderable
            }, ],
            responsive: true,
            fixedHeader: true,
        });
    })

    function viewImage(emp_id) {
        $.ajax({
            type: "GET",
            url: `${baseUrl}healthcare-coordinator/members/helthcard/view-id/${emp_id}`,
            dataType: 'json',
            data: {
                    'token': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
            success: function(response){
                const { token,front_id, back_id }= response;

                $('#viewHcIdModal').modal('show');
                $('#front-id').attr('src', front_id);
                $('#back-id').attr('src', back_id);
            }  
        });
        
    }
</script>

<style>
  .nav-item1 {
    list-style-type: none;
  }

  .nav-link1 {
    display: inline-block;
    padding: 10px;
    padding-top:1px;
    padding-bottom:1px;
    text-decoration: none;
    background-color: #e6e6e6;
    color: #000;
    border: 1px solid gray;
    border-bottom: 3px solid gray;
    border-radius: 15px;

  }

  .nav-link1:hover {
    background-color: #002244;
    color: #fff;
    border: 1px solid #000;
  }

  .font-bold {
    font-weight: bold;
  }

  .hidden-xs-down {
    display: inline-block;
  }

  .fs-5 {
    font-size: 1.2rem;
  }
  .pending{
    color:red
  }
  .approved{
    color:green
  }
  .disapproved{
    color:red
  }
  .completed{
    color:green
  }
  .referral{
    color:orange
  }
  .expired{
    color:#a32cc4
  }
  .cancelled{
    color:red
  }
</style>