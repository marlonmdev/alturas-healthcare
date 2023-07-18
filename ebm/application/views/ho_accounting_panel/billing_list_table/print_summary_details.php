<!-- Start of Page Wrapper -->
    <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
            <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2"></h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Print Report
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
                <div class="col-lg-5 ps-5 pb-3 pt-1 pb-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white">
                            <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <select class="form-select fw-bold" name="matched-hospital-filter" id="matched-hospital-filter">
                            <option value="">Please Select...</option>
                            <option value="details">Print Summary Details</option>
                            <option value="business_unit">Print Business Unit Charging</option>
                        </select>
                    </div>
                </div>
                <div class="col-4 pt-1">
                    <button class="btn btn-danger ls-1" onclick="printDiv('#printableDiv')"><i class="mdi mdi-printer"></i> Print </button>
                </div>
            </div>
                <div class="row" id="printableDiv" style="background:#ffff;padding:20px 40px;">
                    <div class="card shadow">
                        <div class="card-body">
                            <?php if($month == '01'){
                                    $word_month = 'January';
                                }else if($month == '02'){
                                    $word_month = 'February';
                                }else if($month == '03'){
                                    $word_month = 'March';
                                }else if($month == '04'){
                                    $word_month = 'April';
                                }else if($month == '05'){
                                    $word_month = 'May';
                                }else if($month == '06'){
                                    $word_month = 'June';
                                }else if($month == '07'){
                                    $word_month = 'July';
                                }else if($month == '08'){
                                    $word_month = 'August';
                                }else if($month == '09'){
                                    $word_month = 'September';
                                }else if($month == '10'){
                                    $word_month = 'October';
                                }else if($month == '11'){
                                    $word_month = 'November';
                                }else if($month == '12'){
                                    $word_month = 'December';
                                }?>
                            <div class="text-center">
                                <h4>ALTURAS HEALTHCARE SYSTEM</h4>
                                <h4>Billing Summary Details</h4>
                                <h5>For the Month of <?php echo $word_month; ?>, <?php echo $year; ?></h5>
                                <?php  $prev_payment_no = null; ?>
                                <?php foreach($payment_no as $pay) : 
                                    if ($pay['payment_no'] != $prev_payment_no) { // check if bill number is different from previous
                                        echo '<h5>' . $pay['payment_no'] . '</h5>'; // display bill number
                                        
                                        $prev_payment_no = $pay['payment_no']; // set current bill number as previous for next iteration
                                    }?>
                                    <input type="hidden" id="payment_no" value="<?php echo $pay['payment_no']; ?>">
                                <?php endforeach; ?>
                                
                            </div> 
                            <div class="pt-4">
                                <table class="table table-sm">
                                    <thead>
                                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                                        <th class="text-center fw-bold ls-2"><strong>Billing No</strong></th>
                                        <th class="text-center fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                                        <th class="text-center fw-bold ls-2"><strong>Name</strong></th>
                                        <th class="text-center fw-bold ls-2"><strong>Business Unit</strong></th>
                                        <th class="text-center fw-bold ls-2"><strong>Healthcare Bill</strong></th>
                                     
                                    </tr>
                                    </thead>
                                    <tbody class="pt-2">
                                    <?php foreach($payable as $pay) : ?>
                                            <?php 	if($pay['loa_id'] != ''){
                                                $loa_noa = $pay['loa_no'];

                                            }else if($pay['noa_id'] != ''){
                                                $loa_noa = $pay['noa_no'];
                                            }
                                            $fullname =  $pay['first_name'] . ' ' . $pay['middle_name'] . ' ' . $pay['last_name'] . ' ' . $pay['suffix'];?>
                                        <tr>
                                            <td class="text-center ls-1"><?php echo $pay['billing_no'];?></td>
                                            <td class="text-center ls-1"><?php echo $loa_noa;?></td>
                                            <td class="text-center ls-1"><?php echo $fullname;?></td>
                                            <td class="text-center ls-1"><?php echo $pay['business_unit'];?></td>
                                            <td class="text-center ls-1"><?php echo number_format($pay['net_bill'],2, ',','.');?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-bold">TOTAL</td>
                                            <td class="fw-bold text-center ls-1"><span id="total_bill"></span></td>
                                        </tr>
                                      
                                    </tbody>
                                </table><br>
                                <div class="row offset-1 pt-3 ps-5">
                                    <div class="col-4">
                                        <span>Prepared by : </span><br><br>
                                        <span class="text-decoration-underline fw-bold fs-5">__<?php echo $user; ?>__</span>
                                    </div>
                                    <div class="col-4">
                                        <span>Audited by : </span><br><br>
                                        <span class="text-decoration-underline">_______________________</span>
                                    </div>
                                    <div class="col-4">
                                        <span>Noted by : </span><br><br>
                                        <span class="text-decoration-underline">_______________________</span>
                                    </div>
                                </div>
                                <br><br><br>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-12 offset-10 mb-4 mt-2">
                    <div class="input-group">
                        <a href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/billed-loa-noa" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                            <strong class="ls-2" style="vertical-align:middle">
                                <i class="mdi mdi-arrow-left-bold"></i> Go Back
                            </strong>
                        </a>
                    </div>
                </div>
        </div>
    </div>

<script>
  const baseUrl = "<?php echo base_url(); ?>";
const printDiv = (layer) => {
    $(layer).printThis({
      importCSS: true,
      copyTagClasses: true,
      copyTagStyles: true,
      removeInline: false,
    });
  }
 $(document).ready(function(){
  
 }) ;
 
 window.onload = function() {
    getTotalBill();
 }

  const getTotalBill = () => {
    const payment_no = document.querySelector('#payment_no').value;
    const total_bill = document.querySelector('#total_bill').value;
  
    $.ajax({
          type: 'post',
          url: `${baseUrl}head-office-accounting/bill/accounting/total-bill/fetch`,
          dataType: "json",
          data: {
              'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'payment_no' : payment_no,
          },
       
          success: function(response){
            $('#total_bill').html(response.total_bill);

            console.log(response.total_bill);
            // total_bill.html(response.total_bill);
          },

      });
  }
</script>
   
</html>