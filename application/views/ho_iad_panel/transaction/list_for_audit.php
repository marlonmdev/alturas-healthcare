<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title ls-2"><i class="mdi mdi-format-float-none"></i> For Audit [ <span class="text-info"><?php echo $payment_no; ?></span> ]</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">Head Office IAD</li>
                  <li class="breadcrumb-item active" aria-current="page">
                      For Audit
                  </li>
                </ol>
            </nav>
          </div>
      </div>
      </div>
  </div>
  <hr>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-6 pb-2">
          <div class="input-group">
              <a href="<?php echo base_url(); ?>head-office-iad/biling/audit" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                  <strong class="ls-2" style="vertical-align:middle">
                      <i class="mdi mdi-arrow-left-bold"></i> Go Back
                  </strong>
              </a>
          </div>
      </div>
    </div>
    <div class="row pt-3 pt-1">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="payment-no" id="payment-no" value="<?php echo $payment_no ?>">
            <div class="card shadow" style="background-color:">
                <div class="card-body">
                  <div class="table-responsive">
                    <i class="text-danger fw-bold">( Click LOA/NOA number to view details )</i>
                    <table class="table table-hover table-responsive" id="billedAuditTable">
                      <thead style="background-color:#eddcb7">
                        <tr>
                            <th class="fw-bold ls-2"><strong>#</strong></th>
                            <th class="fw-bold ls-2"><strong>Billing No</strong></th>
                            <th class="fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                            <th class="fw-bold ls-2"><strong>Patient Name</strong></th>
                            <th class="fw-bold ls-2"><strong>Business Unit</strong></th>
                            <th class="fw-bold ls-2"><strong>Current MBL</strong></th>
                            <th class="fw-bold ls-2"><strong>Percentage</strong></th>
                            <th class="fw-bold ls-2"><strong>Hospital Bill</strong></th>
                            <th class="fw-bold ls-2"><strong>Company Charge</strong></th>
                            <th class="fw-bold ls-2"><strong>Healthcare Advance</strong></th>
                            <th class="fw-bold ls-2"><strong>Total Payable</strong></th>
                            <th class="fw-bold ls-2"><strong>Personal Charge</strong></th>
                            <th class="fw-bold ls-2"><strong>Remaining MBL</strong></th>
                            <th class="fw-bold ls-2"><strong>SOA</strong></th>
                        </tr>
                      </thead>
                      <tbody id="billed-tbody">
                      </tbody>
                      <tfoot>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="fw-bold">TOTAL BILL </td>
                        <td><span class="text-danger fw-bold fs-5" id="mt-total-bill"></span></td>
                        <td></td>
                        <td><a class="btn btn-success" href="JavaScript:void(0)" onclick="tagDoneAudit('<?php echo $payment_no ?>')" title="tag as audited"><i class="mdi mdi-tag-plus"></i> Audited</a></td>
                        <td></td>
                      </tfoot>
                    </table>
                  </div>
                </div>
            </div>
      </div>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
    <?php include 'view_pdf_bill_modal.php'; ?>
  <!-- End Page wrapper  -->
  </div>
  <?php include 'view_loa_noa_details_modal.php';?>
<!-- End Wrapper -->

<script>

     const baseUrl = "<?php echo base_url(); ?>";
     const payment_no = document.querySelector('#payment-no').value;

 $(document).ready(function(){
    
    let billedTable = $('#billedAuditTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-iad/biling/audit/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.payment_no = payment_no;
        },
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
        let columnIdx = 10;
        let sum = 0;
        let rows = billedTable.rows().nodes();
        if ($('#billedAuditTable').DataTable().data().length > 0) {
            // The table is not empty
            rows.each(function(index, row) {
                let rowData = billedTable.row(row).data();
                let columnValue = rowData[columnIdx];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);
                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(',', '');
                    let floatValue = parseFloat(numberString);
                    sum += floatValue;
                }
            });
        }
        $('#mt-total-bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
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
                                    setTimeout(
                                    window.location.href = '<?php echo base_url();?>head-office-iad/biling/audited', 3000);
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

 const viewLOANOAdetails = (billing_id) => {
    $('#viewLOANOAdetailsModal').modal('show');
    $.ajax({
      url: `${baseUrl}head-office-iad/biling/loa-noa-details/fetch/${billing_id}`,
      data: `<?php echo $this->security->get_csrf_hash(); ?>`,
      type: 'GET',
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,
          loa_noa_no,
          fullname,
          business_unit,
          hp_name,
          requested_on,
          approved_on,
          approved_by,
          request_type,
          percentage,
          services,
          admission_date,
          billed_on,
          billed_by,
          billing_no,
          net_bill,
          personal_charge,
          company_charge,
          cash_advance,
          total_payable,
          before_remaining_bal,
          after_remaining_bal,
          hospitalized_date
        } = res;

        if(request_type == 'Diagnostic Test'){
          $('#cost-types').show();
        }else{
          $('#cost-types').hide();
        }
        if(request_type == 'NOA'){
          $('#admitted-on').show();
        }else{
          $('#admitted-on').hide();
        }
        if(request_type == 'Emergency'){
          $('#hospitalized-on').show();
        }else{
          $('#hospitalized-on').hide();
        }
        $('#hospitalized-date').html(hospitalized_date);
        $('#noa-loa-no').html(loa_noa_no);
        $('#member-fullname').html(fullname);
        $('#member-bu').html(business_unit);
        $('#hc-provider').html(hp_name);
        $('#request-date').html(requested_on);
        $('#approved-on').html(approved_on);
        $('#approved-by').html(approved_by);
        $('#request-type').html(request_type);
        $('#percentage-is').html(percentage);
        $('#med-services').html(services);
        $('#admission-date').html(admission_date);
        $('#billed-on').html(billed_on);
        $('#billed-by').html(billed_by);
        $('#billing-no').html(billing_no);
        $('#net-bill').html(net_bill);
        $('#personal-charge').html('-'+ personal_charge);
        $('#company-charge').html(company_charge);
        $('#cash-advance').html(cash_advance);
        $('#total-payable').html(total_payable);
        $('#totals-payable').html(total_payable);
        $('#max-benefit').html(before_remaining_bal);
        $('#remaining-mbl').html(after_remaining_bal);
      }
    });
 }

 const viewPDFBill = (pdf_bill,noa_no,loa_no) => {
      $('#viewPDFBillModal').modal('show');
      if(noa_no != ''){
        $('#pdf-loa-no').html(noa_no);
      }else{
        $('#pdf-loa-no').html(loa_no);
      }
      $('#cv-title').hide();
      $('#bill-title').show();

        let pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
        let fileExists = checkFileExists(pdfFile);

        if(fileExists){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', pdfFile, true);
        xhr.responseType = 'blob';

        xhr.onload = function(e) {
            if (this.status == 200) {
            let blob = this.response;
            let reader = new FileReader();

            reader.onload = function(event) {
                let dataURL = event.target.result;
                let iframe = document.querySelector('#pdf-viewer');
                iframe.src = dataURL;
            };
            reader.readAsDataURL(blob);
            }
        };
        xhr.send();
        }
    }

    const viewPDFBillReimburse = (pdf_bill,noa_no,loa_no) => {
      $('#viewPDFBillModal').modal('show');
      if(noa_no != ''){
        $('#pdf-loa-no').html(noa_no);
      }else{
        $('#pdf-loa-no').html(loa_no);
      }
      $('#cv-title').hide();
      $('#bill-title').show();

  
        let pdfFile = `${baseUrl}uploads/hospital_receipt/${pdf_bill}`;
        let fileExists = checkFileExists(pdfFile);

        if(fileExists){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', pdfFile, true);
        xhr.responseType = 'blob';

        xhr.onload = function(e) {
            if (this.status == 200) {
            let blob = this.response;
            let reader = new FileReader();

            reader.onload = function(event) {
                let dataURL = event.target.result;
                let iframe = document.querySelector('#pdf-viewer');
                iframe.src = dataURL;
            };
            reader.readAsDataURL(blob);
            }
        };
        xhr.send();
        }
    }

    const checkFileExists = (fileUrl) => {
        let xhr = new XMLHttpRequest();
        xhr.open('HEAD', fileUrl, false);
        xhr.send();

        return xhr.status == "200" ? true: false;
    }



</script>