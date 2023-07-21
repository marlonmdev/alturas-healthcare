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
        <h4 class="page-title ls-2">Billing for the Month of <?php echo $month . ', ' . $payable['year']; ?> [Inpatient]</h4>
        <input type="hidden" id="bill-no" value="<?php echo $payable['bill_no']; ?>">
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Coordinator</li>
                <li class="breadcrumb-item active" aria-current="page">NOA Charging</li>
                <li class="breadcrumb-item"><?php echo $payable['hp_name']; ?></li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div><hr>

    <div class="col-12 offset-11 mb-4 mt-2">
      <div class="input-group">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/for_payment" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
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
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">NOA NO.</th>
                        <th class="fw-bold">NAME OF PATIENT</th>
                        <th class="fw-bold">BUSINESS UNIT</th>
                        <th class="fw-bold">PERCENTAGE</th>
                        <th class="fw-bold">TOTAL NET BILL</th>
                        <th class="fw-bold">COMPANY CHARGE</th>
                        <th class="fw-bold">CASH ADVANCE</th>
                        <th class="fw-bold">PERSONAL CHARGE</th>
                        <th class="fw-bold">PREVIOUS MBL</th>
                        <th class="fw-bold">REMAINING MBL</th>
                      </tr>
                    </thead>
                    <tbody id="billed-tbody">
                    </tbody>
                    <tfoot>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="fw-bold">TOTAL</td>
                        <td class="fw-bold" id="total-net-bill"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <?php include 'view_pdf_bill_modal.php'; ?>
        </div>
      </div>
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
        url: `${baseUrl}healthcare-coordinator/noa/monthly-bill/charging/${bill_no}`,
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

    billedTable.on('draw.dt', function() {
        let columnId = 4;
        let sum = 0;
        let rowss = billedTable.rows().nodes();

        if ($('#billedLoaTable').DataTable().data().length > 0) {
            // The table is not empty
            rowss.each(function(index, row) {
            let rowData = billedTable.row(row).data();
            let columnValue = rowData[columnId];
            let pattern = /-?[\d,]+(\.\d+)?/g;
            let matches = columnValue.match(pattern);

            if (matches && matches.length > 0) {
                let numberString = matches[0].replace(/,/g, ''); // Replace all commas
                let floatValue = parseFloat(numberString);
                sum += floatValue;
            }
            });
        }

        $('#total-net-bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });
 });

</script>