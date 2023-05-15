<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Referral</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <hr style="color:red">
      <div class="col-12">
        <div class="text-center mb-4 mt-0"><h4 class="page-title ls-2" style="letter-spacing:10px">REFERRAL FORM</h4></div>
      </div>
    <hr style="color:red">

    <form method="post" action="<?= base_url(); ?>healthcare-coordinator/loa/requested-loa/submit" class="mt-2" id="coordinatorLoaRequestForm">
      <input type="hidden" name="user-role" value="<?php echo $user_role ?>">
      <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
      <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
      <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
      <input type="hidden" name="approved_by" value="<?php echo $approved_by ?>">

      <div class="row pt-2">
        <div class="col-sm-6 mb-2">
          <label class="colored-label fs-5">Full Name</label>
          <input type="text" class="form-control fw-bold" name="full-name" value="<?php echo $fullname ?>" disabled>
        </div>

        <div class="col-lg-3">
          <label class="fw-bold fs-5">Request Type : </label>
          <input class="form-control fw-bold" type="text" name="request-type" id="request-type" value="<?php echo $request_type ?>" readonly>
        </div>  

        <div class="col-lg-3 col-sm-12 col-lg-offset-4">
          <?php
            $month = date('m');
            $day = date('d');
            $year = date('Y');
            $today = $year . '-' . $month . '-' . $day;
          ?>
          <label class="colored-label fs-5">Date Creation</label>
          <input type="text" class="form-control fw-bold" name="request-date" value="<?= $today; ?>" disabled>
        </div>
      </div>
                    
      <div class="form-group row">
        <input type="hidden" name="old-hp-id" id="old-hp-id" value="<?php echo $hp_id ?>">
        <div class="col-lg-6 pt-2 provider" style="display:block">
          <label class="fw-bold fs-5">Previous Healthcare Provider : </label>
          <input class="form-control fw-bold text-danger" type="text" name="old-hp-name" id="old-hp-name" value="<?php echo $hp_name  ?>" readonly>
        </div> 
        <div class="col-lg-3 pt-2">
          <label class="fw-bold fs-5">Previous LOA Number : </label>
          <input class="form-control fw-bold text-danger" type="text" name="loa-num" id="loa-num" value="<?php echo $loa_no ?>" readonly>
        </div> 
      </div>

      <label class="fw-bold fs-5">Previous Medical Service/s : </label>
      <?php foreach($resched_services as $med_services) : ?>
        <div class="col-lg-6 pb-3 ">
          <input type="hidden" name="old-ctype-id[]" value="<?php echo $med_services['ctype_id'] ?>">
          <input type="text" class="form-control fw-bold ls-1 text-danger" name="old-ct-name[]" value="<?php echo $med_services['item_description'] ?>" readonly>
        </div>
      <?php endforeach; ?><hr>

      <div class="row">
        <div class="col-lg-6 mb-2 pt-2 change-provider">
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
        </div>

        <div class="col-lg-6 form-group row">
          <div class="col-lg-12 col-sm-12 mb-2 pt-2" id="med-services-div">
            <label class="colored-label fs-5"> Select Medical Service/s : </label><br>
            <div id="med-services-wrapper"></div>
            <em id="med-services-error" class="text-danger"></em>
          </div>
        </div>
      </div>
                

      <div class="row mt-2 offset-10">
        <div class="col-sm-12 mb-2 d-flex justify-content-start">
          <button type="submit" class="btn btn-info me-2"><i class="mdi mdi-content-save-settings"></i> SUBMIT</button>
        </div>
      </div>

    </form>
  </div>
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
                window.location.href = `${baseUrl}healthcare-coordinator/loa/requests-list/rescheduled`;
              }, 3200);
              break;
          }
        },
      })
    });

    $('#healthcare-provider').on('change', function(){
        const hp_id = $(this).val();
        const token = `<?php echo $this->security->get_csrf_hash(); ?>`;

        if(hp_id != ''){
          $.ajax({
              url: `${baseUrl}healthcare-coordinator/get-services/${hp_id}`,
              type: "GET",
              dataType: "json",
              success:function(response){

                $('#med-services-wrapper').empty();                

                $('#med-services-wrapper').append(response);

                $(".chosen-select").chosen({
                  width: "100%",
                  no_results_text: "Oops, nothing found!"
                }); 
              }
          });
        }
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