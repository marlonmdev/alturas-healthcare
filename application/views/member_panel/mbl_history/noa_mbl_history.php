<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">MBL HISTORY</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item">NOA</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>member/mbl-history/loa" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">LOA</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>member/mbl-history/noa" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">NOA</span>
            </a>
          </li>
          <li>
            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <div class="dropdown">
                                <input type="text" class="form-control dropdown-toggle" id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false"  readonly>
                                  <ul class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="yearDropdown" id ="yearList">

                                  </ul>
                                </div>
                                
          </div>
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class=" table-responsive">
              <table class="table table-striped table-hover" id="memberPersonalCharges">
                <thead style="background-color:#00538C">
                  <tr>
                  <th class="fw-bold" style="color: white">#</th>
                    <th class="fw-bold" style="color: white">REQUEST DATE</th>
                    <th class="fw-bold" style="color: white">NOA #</th>
                    <th class="fw-bold" style="color: white">TYPE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">STATUS</th>
                    <th class="fw-bold" style="color: white">HOSPITAL BILL</th> 
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_noa_history.php'; ?>
        <?php include 'view_pdf_bill_modal.php'; ?>
      </div>
    </div>
  </div>
</div>
<style>
  .dropdown-menu-scrollable {
    max-height: 200px;
    overflow-y: auto;
  }
</style>
<script>
  const baseUrl = "<?= base_url() ?>";
  const emp_id = "<?= $emp_id ?>";
  const startYear = '2000';
  // const startYear = '<?= $start_date ?>';
  const yearList = document.getElementById('yearList');
  const currentYear = new Date().getFullYear();
  $(document).ready(function() {
    // $("#memberPersonalCharges").DataTable({
    //   processing: true, //Feature control the processing indicator.
    //   serverSide: true, //Feature control DataTables' server-side processing mode.
    //   order: [], //Initial no order.

    //   // Load data for the table's content from an Ajax source
    //   ajax: {
    //     url: `${baseUrl}member/mbl-history/noa/fetch`,
    //     type: "POST",
    //     // passing the token as data so that requests will be allowed
    //     data: {
    //       'token': '<?php echo $this->security->get_csrf_hash(); ?>',
    //       'emp_id' :  emp_id
    //     }
    //   },

    //   //Set column definition initialisation properties.
    //   columnDefs: [{
    //     "targets": [4, 5], // numbering column
    //     "orderable": false, //set not orderable
    //   }, ],
    //   responsive: true,
    //   fixedHeader: true,
    // });
    $('#yearDropdown').val(currentYear);
     mbl_datatable();
    yearList.addEventListener('click', function(event) {
          const selectedYear = event.target.textContent;
          yearDropdown.value = selectedYear;
          mbl_datatable();
        });

        for (let year = currentYear; year >= startYear; year--) {
          const listItem = document.createElement('li');
          const link = document.createElement('a');
          link.classList.add('dropdown-item');
          link.href = '#';
          link.textContent = year;
          listItem.appendChild(link);
          yearList.appendChild(listItem);
        }

    $('#viewNoaModal').on('hidden.bs.modal', function() {
          $('#services-noa').empty(); // Remove all list items from the list
          $('#documents-noa').empty(); 
          $('#noa_details_1').empty(); 
          $('#noa_details_2').empty(); 
          $('#physician-noa').empty(); 
          // Additional reset logic if needed
        });
  });

  const mbl_datatable = () => {
         // Check if the DataTable already exists
         if ($.fn.DataTable.isDataTable("#memberPersonalCharges")) {
              // Destroy the DataTable
              $("#memberPersonalCharges").DataTable().destroy();
            }
            
            $("#memberPersonalCharges").DataTable({
              processing: true, //Feature control the processing indicator. 
              serverSide: true, //Feature control DataTables' server-side processing mode.
              order: [], //Initial no order.
              columnDefs: [
            {
                "targets": [2], // Replace 0 with the index of the column you want to bold (zero-based index)
                "render": function (data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                }
            }],
              // Load data for the table's content from an Ajax source
              ajax: {
                url: `${baseUrl}member/mbl-history/noa/fetch`,
                type: "POST",
                // passing the token as data so that requests will be allowed
                data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                                'emp_id' :  emp_id,
                                'start_date' : $('#yearDropdown').val(),
                               
                      }
              },

              responsive: true,
              fixedHeader: true,

            });

      }

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
  function viewNoaHistoryInfo(noa_id) {
        $.ajax({
        url: `${baseUrl}member/mbl-history/noa/${noa_id}`,
        type: "GET",
        success: function(response) {
            const res = JSON.parse(response);
            // console.log(response);
            const base_url = window.location.origin;
            // Object Destructuring
            const { status, token, noa_no, member_mbl, remaining_mbl, first_name, middle_name,
            last_name, suffix, date_of_birth, age, gender, philhealth_no, blood_type, contact_no,
            home_address, city_address, email, contact_person, contact_person_addr, contact_person_no,
            healthcare_provider, loa_request_type, med_services, health_card_no, requesting_company,
            request_date,complaints, requesting_physician, attending_physician, pdf_bill,final_diagnosis,medical_abstract,
            req_status, work_related, approved_by, approved_on,billed_on,paid_on,net_bill,paid_amount,expiration,prescription,
            disapproved_on,attending_doctors,disapprove_reason,disapproved_by
            } = res;
            // console.log("complaints",complaints);
            $("#viewNoaModal").modal("show");
            $("#p_disaproved").hide();
            $("#p_documents").hide();
            $("#p_physician").hide();
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#noa_details_1').append(`<h6>NOA #: <strong><span class="text-primary">${noa_no}</span></strong></h6>`); 
            // $('#loa-no').html(loa_no);
            $('#nstatus').html(`<strong class="text-success">[${req_status}]</strong>`);
            // $('#noa-no').html(noa_no);
            // $('#status-noa').html(`<strong class="text-success">[${req_status}]</strong>`);
            // $('#approved-date-noa').html(approved_on);
            // $('#expire-noa').html(approved_on);
            $('#complaint-noa').text(complaints);
            // console.log(disapprove_reason);
            switch(req_status){
                case 'Pending':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                break;
                case 'Approved':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                break;
                case 'Disapproved':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`);
                    $('#noa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`);
                    $("#p_disaproved").show();
                    $('#disaproved-noa').text(disapprove_reason);
                break;
                case 'Expired':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>EXPIRED DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                break;
                case 'Billed' || 'Payment' || 'Payable':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $("#p_documents").show();
                    $("#p_physician").show();
                break;
                case 'Paid':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>DATE PAID: <strong><span class="text-primary">${paid_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>PAID AMOUNT: <strong><span class="text-primary">${paid_amount}</span></strong></h6>`); 
                    $("#p_documents").show();
                    $("#p_physician").show();
                break;
                
            }
            
          
            if(pdf_bill.length){
              $('#documents-noa').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+pdf_bill+'\',\''+noa_no+'\',\''+'pdf_bill'+'\')">Statement of Account (SOA)</a></li>');
            }
            if(final_diagnosis.length){
              $('#documents-noa').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+final_diagnosis+'\',\''+noa_no+'\',\''+'diagnosis'+'\')">Final Diagnosis</a></li>');
            }
            if(medical_abstract.length){
              $('#documents-noa').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+medical_abstract+'\',\''+'abstract'+'\')">Medical Abstract File</a></li>');
            }
            if(prescription.length){
              $('#documents-noa').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+prescription+'\',\''+'prescription'+'\')">Prescription File</a></li>');
            }
            // $('#soa').html();                                                                                               
            
            // $('#requesting-company').html(requesting_company);
            // $('#request-date').html(request_date);
            // $('#chief-complaint').html(chief_complaint);
            // $('#requesting-physician').html(requesting_physician);
            if(attending_doctors.length){
              $.each(attending_doctors, function(index, item) {
                if(item.length > 1){
                  $('#physician-noa').append('<li>' +'Dr. '+ item + '</li>');
                }
                
              });
            }
            
            // $('#work-related').html(work_related);
          }

        });
    }
</script>