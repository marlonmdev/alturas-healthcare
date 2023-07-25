<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
            <?php if($type == 'unpaid') {
                    $header = 'Receivables Details';
            }else {
                    $header = 'Paid Charges Details';
            }
                ?>
            <h4 class="page-title"><i class="mdi mdi-format-line-style"></i> <?php echo $header; ?> [ <span class="text-info fs-5"><?php echo $charging_no; ?></span> ]</h4>
            <div class="ms-auto text-end order-first order-sm-last">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office IAD</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Business Unit Charging
                    </li>
                    </ol>
                </nav>
            </div>
        </div>
        </div>
    </div><hr>
    <!-- End Bread crumb and right sidebar toggle -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 pb-1">
                <div class="input-group">
                    <a href="JavaScript:void(0)" onclick="goback()" class="btn btn-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 pt-1 offset-8">
                <button class="btn btn-danger btn-sm fs-5" type="button" id="print-btn" onclick="printBUCharging()" title="Click to Print PDF"><i class="mdi mdi-printer"></i> Print</button>
            </div>
        </div>
        <br>
        <form id="submitPaidForm" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <input value="<?php echo $charging_no; ?>" type="hidden" id="charging-no" name="charging-no">
            <input value="<?php echo $business_unit; ?>" type="hidden" id="business-unit">
            <input value="<?php echo $type; ?>" type="hidden" id="type-data">
            <div class="card bg-light">
                <div class="card-body">
                    <?php echo $table; ?>
                </div>
            </div>
           
            <?php include 'view_check_voucher.php'; ?>
        </form>
    </div> 
    <?php include 'view_charge_details.php'; ?>
</div>
<script>
    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function(){
      
    });

    const goback = () =>{
        window.history.back();
    }

    const printBUCharging = () => {
        const charging_no = document.querySelector('#charging-no').value;
        const b_units = document.querySelector('#business-unit').value;
        const type = document.querySelector('#type-data').value;

        var base_url = `${baseUrl}`;
        window.open(base_url + "printBUCharge/pdfReceivablesCharging/" + btoa(b_units) + "/" + btoa(charging_no) + "/" + btoa(type), '_blank');
    }

    const viewChargeDetails = (billing_id) => {
        $.ajax({
            url: `${baseUrl}head-office-iad/charging/view-details`,
            type: 'GET',
            data: {
                'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                'billing_id' : billing_id
            },
            success: function(data){
                const res = JSON.parse(data);
                const {
                    token, payment_no, billing_no, loa_noa_no, percentage, before_mbl, net_bill, company_charge, personal_charge, cash_advance, after_mbl, billed_on
                } = res;

                $('#viewDetailsModal').modal('show');
                $('#payment-no').html(payment_no);
                $('#billing-no').html(billing_no);
                $('#loa-noa-no').html(loa_noa_no);
                $('#percentage').html(percentage);
                $('#current-mbl').html(before_mbl);
                $('#hospital-bill').html(net_bill);
                $('#company-charge').html(company_charge);
                $('#personal-charge').html(personal_charge);
                $('#cash-advance').html(cash_advance);
                $('#remaining-mbl').html(after_mbl);
                $('#billed-on').html(billed_on);
            }
        });
    }

</script>