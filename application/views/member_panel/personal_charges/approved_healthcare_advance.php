<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">APPROVED HEALTHCARE ADVANCE</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">Approved</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>member/personal-charges" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Charges</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>member/personal-charges/paid" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Requested</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>member/personal-charges/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>member/personal-charges/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span>
            </a>
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class=" table-responsive">
              <table class="table table-striped table-hover" id="memberPersonalCharges">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">#</th>
                    <th class="fw-bold" style="color: white">BILLING #</th>
                    <th class="fw-bold" style="color: white">REQUESTED AMOUNT</th>
                    <th class="fw-bold" style="color: white">APPROVED AMOUNT</th>
                    <th class="fw-bold" style="color: white">APPROVED ON</th>
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

      <?php include 'view_personal_charges.php'; ?>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = "<?= base_url() ?>";
  $(document).ready(function() {
    $("#memberPersonalCharges").DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}member/personal-charges/approved/fetch`,
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
      url: `${baseUrl}member/personal-charges/view/details/${billing_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          loa_noa_no,
          billing_no,
          work_related,
          percentage,
          before_remaining_bal,
          net_bill,
          company_charge,
          personal_charge,
          after_remaining_bal,
          billed_on,
        } = res;

        $("#viewPersonalChargeModal").modal("show");
        $('#billing-no').html(billing_no);
        $('#current-mbl').html(before_remaining_bal);
        $('#hospital-bill').html(net_bill);
        $('#company-charge').html(company_charge);
        $('#personal-charge').html(personal_charge);
        $('#remaining-mbl').html(after_remaining_bal);
        $('#billed-on').html(billed_on);
        $('#loa-noa-no').html(loa_noa_no);


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