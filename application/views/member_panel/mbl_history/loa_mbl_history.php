<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">MBL HISTORY</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item">LOA</li>
              <li class="breadcrumb-item active" aria-current="page">MBL-History</li>
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
            <a class="nav-link active" href="<?php echo base_url(); ?>member/mbl-history/loa" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">LOA</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>member/mbl-history/noa" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">NOA</span>
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
                    <th class="fw-bold" style="color: white">LOA #</th>
                    <th class="fw-bold" style="color: white">STATUS</th>
                    <th class="fw-bold" style="color: white">REQUEST DATE</th>
                    <th class="fw-bold" style="color: white">HOSPITAL BILL</th>
                    <th class="fw-bold" style="color: white">VIEW</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_loa_history.php'; ?>
        <?php include 'view_pdf_bill_modal.php'; ?>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = "<?= base_url() ?>";
  const emp_id = "<?= $emp_id ?>";

  $(document).ready(function() {
    $("#memberPersonalCharges").DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}member/mbl-history/loa/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'emp_id' :  emp_id
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

    $('#viewLoaModal').on('hidden.bs.modal', function() {
          $('#services').empty(); // Remove all list items from the list
          $('#documents').empty(); 
          $('#loa_details_1').empty(); 
          $('#loa_details_2').empty(); 
          $('#physician').empty(); 
          // Additional reset logic if needed
        });
  });

  function viewImage(file,type) {
        let src = '';
        if(type == 'abstract'){
            src = `${baseUrl}uploads/medical_abstract/${file}`;
        }
        if(type == 'prescription'){
            src = `${baseUrl}uploads/prescription/${file}`;
        }
        if(type == 'rx'){
            src = `${baseUrl}uploads/loa_attachments/${file}`;
        }
        let item = [{
            src: src , // path to image
            title: 'Attached RX File' // If you skip it, there will display the original image name
        }];
        // define options (if needed)
        let options = {
            index: 0 // this option means you will start at first image
        };
        // Initialize the plugin
        let photoviewer = new PhotoViewer(item, options);
    }

    const viewPDFBill = (pdf_bill,loa_no,type) => {
      $('#viewPDFBillModal').modal('show');
      $('#pdf-loa-no').html(loa_no);

        let pdfFile = "";
        let fileExists = checkFileExists(pdfFile);
        if(type == "pdf_bill"){
          pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
        }
        if(type == "diagnosis"){
          pdfFile = `${baseUrl}uploads/final_diagnosis/${pdf_bill}`;
        }
    
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

    function viewLoaHistoryInfo(loa_no) {
        $.ajax({
        url: `${baseUrl}member/mbl-history/loa/${loa_no}`,
        type: "GET",
        success: function(response) {
            const res = JSON.parse(response);
            const base_url = window.location.origin;
            // Object Destructuring
            const { status, token, loa_no, member_mbl, remaining_mbl, first_name, middle_name,
            last_name, suffix, date_of_birth, age, gender, philhealth_no, blood_type, contact_no,
            home_address, city_address, email, contact_person, contact_person_addr, contact_person_no,
            healthcare_provider, loa_request_type, med_services, health_card_no, requesting_company,
            request_date, complaint, requesting_physician, attending_physician, rx_file,pdf_bill,
            req_status, work_related, approved_by, approved_on,expiration,billed_on,paid_on,net_bill,paid_amount,
            disapproved_on,date_perform,attending_doctors,disapprove_reason,complaints,disapproved_by
            } = res;

            $("#viewLoaModal").modal("show");
            $("#p-disaproved").hide();
            $("#p-documents").hide();
            $("#p-physician").hide();
           
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#loa_details_1').append(`<h6>LOA #: <strong><span class="text-primary">${loa_no}</span></strong></h6>`); 
            // $('#loa-no').html(loa_no);
            $('#status').html(`<strong class="text-success">[${req_status}]</strong>`);
            $('#complaint').text(complaints);
            // $('#approved-date').html(approved_on);
            // $('#expire').html(expiration);
            switch(req_status){
                case 'Pending':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                break;
                case 'Approved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                    if(attending_doctors.length != 0 || attending_physician.length != 0){ $("#p-physician").show();}
                break;
                case 'Disapproved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                    $("#p-disaproved").show();
                    $('#disaproved').text(disapprove_reason);
                break;
                case 'Completed':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`);
                    $("#p-documents").show();
                    $("#p-physician").show(); 
                break;
                case 'Reffered':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();} 
                    if(attending_doctors.length != 0 || attending_physician.length != 0){ $("#p-physician").show();}
                break;
                case 'Expired':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`);
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                break;
                case 'Billed' || 'Payment' || 'Payable':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $("#p-documents").show();
                    $("#p-physician").show();
                break;
                case 'Paid':
                  $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>PAID AMOUNT: <strong><span class="text-primary">${paid_amount}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DATE PAID: <strong><span class="text-primary">${paid_on}</span></strong></h6>`); 
                    $("#p-documents").show();
                    $("#p-physician").show();
                break;
                
            }
            
            console.log("rxfile",rx_file);
            console.log("soafile",pdf_bill);
            if(rx_file.length){
              $('#documents').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+rx_file+'\',\''+'rx'+'\')">Rx File</a></li>');
            }
            if(pdf_bill.length){
              $('#documents').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+pdf_bill+'\',\''+loa_no+'\',\''+'pdf_bill'+'\')">Statement of Account (SOA)</a></li>');
            }

            $.each(med_services, function(index, item) {
                console.log(item);
                $('#services').append('<li>' + item + '</li>');
            });
           
            if(attending_physician.length){
              //console.log("physician",attending_physician);
              $.each(attending_physician, function(index, item) {
                console.log("physician",item);
                $('#physician').append('<li>' + item + '</li>');
            });
            }
            
            if(attending_doctors.length){
              $.each(attending_doctors, function(index, item) {
                if(item.length > 1){
                  $('#physician').append('<li>' +'Dr. '+ item + '</li>');
                }
              });
            }
           
          }

        });

    }
    
</script>