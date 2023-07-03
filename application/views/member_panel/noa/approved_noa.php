<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">APPROVED REQUEST</h4>
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

      <nav class="navbar navbar-expand-md navbar-light">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-noa/pending" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| PENDING</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url(); ?>member/requested-noa/approved" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 font-bold">| APPROVED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-noa/disapproved" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| DISAPPROVED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-noa/billed" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| BILLED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-noa/paid" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| PAID</span>
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="memberApprovedNoa">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">NOA NO.</th>
                    <th class="fw-bold" style="color: white">DATE OF ADMISSION</th>
                    <th class="fw-bold" style="color: white">NAME OF HOSPITAL</th>
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
        <?php include 'view_approved_noa_details.php'; ?>
      </div>
    </div>
  </div>
</div>



<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {
    $("#memberApprovedNoa").DataTable({
      ajax: {
        url: `${baseUrl}member/requested-noa/approved/fetch`,
        dataSrc: function(data) {
          if (data == "") {
            return [];
          } else {
            return data.data;
          }
        }
      },
      order: [],
      responsive: true,
      fixedHeader: true,
      columnDefs: [{
        "targets": [4, 5], // 6th and 7th column / numbering column
        "orderable": false, //set not orderable
      }, ],
    });
  });

  const saveAsImage = () => {
    // Get the div element you want to save as an image
    const element = document.querySelector("#printableDiv");
    // Use html2canvas to take a screenshot of the element
    html2canvas(element)
      .then(function(canvas) {
        // Convert the canvas to an image data URL
        const imgData = canvas.toDataURL("image/png");
        // Create a temporary link element to download the image
        const link = document.createElement("a");
        link.download = `noa_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }

  const viewNoaInfoModal = (req_id) => {
    $.ajax({
      url: `${baseUrl}member/requested-noa/view/approved/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          noa_no,
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
          approved_by,
          approved_on,
          percentage
        } = res;

        $("#viewNoaModal").modal("show");

        $('#noa-no').html(noa_no);
        $('#noa-status').html('<strong class="text-success">[' + req_status + ']</strong>');
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
        $('#full-name').html(first_name + ' ' + middle_name + ' ' + last_name + ' ' + suffix);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#hospital-name').html(hospital_name);
        $('#admission-date').html(admission_date);
        $('#chief-complaint').html(chief_complaint);
        $('#work-related').html(work_related);
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