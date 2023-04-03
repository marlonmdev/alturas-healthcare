
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Reschedule LOA</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                Request New LOA
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
   
    <div class="row">

      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">

            <form method="post" action="<?= base_url(); ?>healthcare-coordinator/loa/requested-loa/submit" class="mt-2" id="coordinatorLoaRequestForm">
              <!-- Start of Hidden Inputs -->
              <input type="hidden" name="user-role" value="<?php echo $user_role ?>">
              <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
              <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
              <!-- End of Hidden Inputs -->
              <span class="text-info fs-4 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> LOA REQUEST DETAILS</span>
                <div class="row pt-2">
                    <div class="col-sm-6 mb-2">
                    <label class="colored-label fs-5">Full Name</label>
                    <input type="text" class="form-control fw-bold" name="full-name" value="<?php echo $fullname ?>" disabled>
                    </div>

                    <div class="col-lg-3">
                        <label class="fw-bold fs-5">LOA Number : </label>
                        <input class="form-control fw-bold" type="text" name="loa-num" id="loa-num" value="<?php echo $loa_no ?>" readonly>
                    </div>  

                    <div class="col-lg-3">
                        <label class="fw-bold fs-5">Request Type : </label>
                        <input class="form-control fw-bold" type="text" name="request-type" id="request-type" value="<?php echo $request_type ?>" readonly>
                    </div>  
                </div>
                  
              
              <div class="form-group row">
                    <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2 pt-2 change-provider" style="display:none">
                        <label class="colored-label fs-5">New Healthcare Provider : </label>
                        <select class="form-select fw-bold" name="healthcare-provider" id="healthcare-provider" oninput="enableRequestType()">
                            <option value="">Select New Healthcare Provider</option>
                            <?php
                            if (!empty($hcproviders)) :
                            foreach ($hcproviders as $hcprovider) :
                            ?>
                                <option value="<?= $hcprovider['hp_id']; ?>"><?= $hcprovider['hp_name']; ?></option>
                            <?php
                            endforeach;
                            endif;
                            ?>
                        </select>
                        <em id="healthcare-provider-error" class="text-danger"></em>
                    </div> <input type="hidden" name="hp-id" id="hp-id" value="<?php echo $hp_id ?>">

                    <div class="col-lg-6 pt-2 provider" style="display:block">
                        <label class="fw-bold fs-5">Healthcare Provider : </label>
                       
                        <input class="form-control fw-bold" type="text" name="hp-name" id="hp-name" value="<?php echo $hp_name  ?>" readonly>
                    </div> 

                    <div class="col-lg-3 pt-5">
                        <button class="btn btn-info badge" title="Change HC Provider" id="change-btn" type="button" onclick="showHcSelection()"><i class="mdi mdi-hospital-building"></i> Change</button>
                    </div>
                
                <div class="col-lg-3 col-sm-12 col-lg-offset-4 pt-2">
                  <?php
                    $month = date('m');
                    $day = date('d');
                    $year = date('Y');
                    $today = $year . '-' . $month . '-' . $day;
                  ?>
                  <label class="colored-label fs-5">Date Created</label>
                  <input type="text" class="form-control fw-bold" name="request-date" value="<?= $today; ?>" disabled>
                </div>
               
              </div>
              <label class="fw-bold fs-5">Medical Service/s : </label>
              <?php foreach($resched_services as $med_services) : ?>

                <div class="col-lg-6 pb-3 ">
                    <input type="hidden" name="ctype-id[]" value="<?php echo $med_services['ctype_id'] ?>">
                    <input type="text" class="form-control fw-bold ls-1 text-danger" name="ct-name[]" value="<?php echo $med_services['item_description'] ?>" readonly>
                </div>

              <?php endforeach; ?>

              <div class="row mt-2 offset-9">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-primary me-2">
                    <i class="mdi mdi-content-save-settings"></i> SUBMIT
                  </button>
                  <a href="#" onclick="window.history.back()" class="btn btn-danger">
                    <i class="mdi mdi-arrow-left-bold"></i> GO BACK
                  </a>
                </div>
              </div>
            </form>
            <!-- End of Form -->
          </div>
          <!-- End of Card Body -->
        </div>
        <!-- End of Card -->
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
</div>
<script>
  const baseUrl = "<?= base_url() ?>";

  $(document).ready(function() {

    $('#coordinatorLoaRequestForm').submit(function(event) {
      event.preventDefault();
      let $data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $data,
        dataType: "json",
        success: function(response) {
          const {
            status,
            message,
          } = response;
          switch (status) {
            
            case 'failed':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });
              break;
            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
              setTimeout(function() {
                window.location.href = `${baseUrl}healthcare-coordinator/loa/requests-list/completed`;
              }, 3200);
              break;
          }
        },
      })
    });

});

const showHcSelection = () => {
    const selected_hc = document.querySelector('.provider');
    const new_provider = document.querySelector('.change-provider');
    const hp_id = document.querySelector('#hp-id');

    selected_hc.style.display = 'none';
    new_provider.style.display = 'block';
    selected_hc.value = '';
    hp_id.value = '';
}


</script>