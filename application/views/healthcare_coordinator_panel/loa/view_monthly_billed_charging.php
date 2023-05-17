<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <?php 
          if($payable['month'] == '01'){
  				  $month = 'January';
  			  }else if($payable['month'] == '02'){
				    $month = 'February';
			    }else if($payable['month'] == '03'){
				    $month = 'March';
			    }else if($payable['month'] == '04'){
				    $month = 'April';
			    }else if($payable['month'] == '05'){
				    $month = 'May';
			    }else if($payable['month'] == '06'){
				    $row[] = $payable['hp_name'];
				    $month = 'June';
			    }else if($payable['month'] == '07'){
				    $month = 'July';
			    }else if($payable['month'] == '08'){
				    $month = 'August';
			    }else if($payable['month'] == '09'){
				    $month = 'September';
			    }else if($payable['month'] == '10'){
				    $month = 'October';
			    }else if($payable['month'] == '11'){
				    $month = 'November';
			    }else if($payable['month'] == '12'){
				    $month = 'December';
			    }
        ?>
      <h4 class="page-title ls-2">Billing for the Month of <?php echo $month . ', ' . $payable['year']; ?></h4>
      <input type="hidden" id="bill-no" value="<?php echo $payable['bill_no']; ?>">
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">LOA Charging</li>
              <li class="breadcrumb-item"><?php echo $payable['hp_name']; ?></li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="col-12 offset-11 mb-4 mt-2">
        <div class="input-group">
          <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
            <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
          <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
                
          <div class="card shadow" style="background-color:">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-responsive" id="billedLoaTable">
                  <thead style="background-color:#00538C">
                    <tr>
                      <th class="fw-bold" style="color: white;">LOA NO.</th>
                      <th class="fw-bold" style="color: white;">PATIENT NAME</th>
                      <th class="fw-bold" style="color: white;">PERCENTAGE</th>
                      <th class="fw-bold" style="color: white;">TOTAL NET BILL</th>
                      <th class="fw-bold" style="color: white;">COMPANY CHARGE</th>
                      <th class="fw-bold" style="color: white;">PERSONAL CHARGE</th>
                      <th class="fw-bold" style="color: white;">PREVIOUS MBL</th>
                      <th class="fw-bold" style="color: white;">REMAINING MBL</th>
                    </tr>
                  </thead>
                  <tbody id="billed-tbody">
                  </tbody>
                </table>
              </div>
            </div>

          </div>
          <?php include 'view_completed_loa_details.php'; ?>
        </div>
        <?php include 'view_pdf_bill_modal.php'; ?>
      </div>
      <?php include 'view_coordinator_bill_modal.php'; ?>
    </div>
  </div>
</div>

<script>
     const baseUrl = "<?php echo base_url(); ?>";
     const bill_no = document.querySelector('#bill-no').value;
    
 $(document).ready(function(){
    
    let billedTable = $('#billedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/monthly-bill/charging/${bill_no}`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
        }
      },
      //Set column definition initialisation properties.
      columnDefs: [{
        "orderable": false, //set not orderable
      }, ],
      data: [],  // Empty data array
      deferRender: true,  // Enable deferred rendering
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });
 });

</script>