<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">MBL LEDGER</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">MBL-Ledger</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

                <div class="row pt-2">
                <div class="col-lg-4  pb-2 pt-1">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-dark fw-bold">
                                Filter : 
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="filter" id="filter">
                                <option value="">All</option>
                                <option value="LOA">LOA</option>
                                <option value="NOA">NOA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-1 offset- pb-2">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2" title="Year History List">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <div class="dropdown">
                                <input type="text" class="form-control dropdown-toggle" id="yearDropdown" title="Year History List" data-bs-toggle="dropdown" aria-expanded="false" value="Select Year" readonly>
                                  <ul class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="yearDropdown" id ="yearList">

                                  </ul>
                                </div>
                                
                              </div>
                        </div>
                    </div>
                      
                <div class="card shadow">
                    <div class="card-body">
                        <div class=" table-responsive">
                          <table class="table table-striped table-hover w-100" id="mbl_history_table">
                            <thead style="background-color:#00538C">
                              <tr>
                                <th class="fw-bold" style="color: white">#</th>
                                <th class="fw-bold" style="color: white">REQUEST DATE</th>
                                <th class="fw-bold" style="color: white">REQUEST TYPE</th>
                                <th class="fw-bold" style="color: white">BILLING #</th>
                                <th class="fw-bold" style="color: white">STATUS</th>
                                <th class="fw-bold" style="color: white">HOSPITAL BILL</th>
                                <th class="fw-bold" style="color: white">COMPANY CHARGE</th>
                                <th class="fw-bold" style="color: white">PERSONAL CHARGE</th>
                                <th class="fw-bold" style="color: white">CASH ADVANCE</th>
                                <th class="fw-bold" style="color: white">REMAINING MBL</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>

                          </table>
                        </div>
                 
                    <!-- <div class="mt-2 p-2 text-end" >
                      <label for="remaining_mbl">Remaining MBL</label>
                      <input type="text" name="remaining_mbl" id="remaining_mbl" disabled>
                    </div> -->
            </div>
          </div>
        </div>

        <?php include 'view_loa_history.php'; ?>
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
  const currentYear = new Date().getFullYear();
  const startYear = '2023';
  // const startYear = '<?= $start_date ?>';
  const yearList = document.getElementById('yearList');

  

  $(document).ready(function() {
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

        $('#filter').change(function(){
          mbl_datatable();
        });


    $('#viewLoaModal').on('hidden.bs.modal', function() {
          $('#services').empty(); // Remove all list items from the list
          $('#documents').empty(); 
          $('#loa_details_1').empty(); 
          $('#loa_details_2').empty(); 
          $('#physician').empty(); 
          // Additional reset logic if needed
        });

        $('#viewNoaModal').on('hidden.bs.modal', function() {
              $('#p_services').empty(); // Remove all list items from the list
              $('#documents-noa').empty(); 
              $('#noa_details_1').empty(); 
              $('#noa_details_2').empty(); 
              $('#physician-noa').empty(); 
              // Additional reset logic if needed
            });
  });

  const mbl_datatable = () => {
         // Check if the DataTable already exists
         if ($.fn.DataTable.isDataTable("#mbl_history_table")) {
              // Destroy the DataTable
              $("#mbl_history_table").DataTable().destroy();
            }
            
            $("#mbl_history_table").DataTable({
              processing: true, //Feature control the processing indicator. 
              serverSide: true, //Feature control DataTables' server-side processing mode.
              order: [], //Initial no order.
              "columnDefs": [
            {
                "targets": [8,9], // Replace 0 with the index of the column you want to bold (zero-based index)
                "render": function (data, type, row, meta) {
                    if (meta.row === 0) {
                        return '<strong>' + data + '</strong>';
                    } else {
                        return data;
                    }
                }
            }],
              // Load data for the table's content from an Ajax source
              ajax: {
                url: `${baseUrl}member/mbl-history/loa-noa/billed/fetch`,
                type: "POST",
                // passing the token as data so that requests will be allowed
                data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                                'emp_id' :  emp_id,
                                'start_date' : $('#yearDropdown').val(),
                                'loa_noa' :  $('#filter').val(),
                      }
              },

              responsive: true,
              fixedHeader: true,

            });

            // $('#mbl_history_table').on('draw.dt', function() {
            //   var dataTable = $('#mbl_history_table').DataTable();
            //   defaultData = ['','Beginning MBL','','','','','','','2000',''];
            //   dataTable.row.add(defaultData);
            // });

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
        if(type == 'h_bill'){
            src = `${baseUrl}uploads/hospital_receipt/${file}`;
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

    function viewLoaHistoryInfo(loa_id) {
        $.ajax({
        url: `${baseUrl}member/mbl-history/loa/${loa_id}`,
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
            disapproved_on,date_perform,attending_doctors,disapprove_reason,complaints,disapproved_by,hospital_receipt,hospital_bill
            } = res;
            console.log('rx_file',rx_file);
            $("#viewLoaModal").modal("show");
            $("#p-disaproved").hide();
            $("#p-documents").hide();
            $("#p-physician").hide();
            $("#p-services").hide();
           
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#loa_details_1').append(`<h6>LOA #: <strong><span class="text-primary">${loa_no}</span></strong></h6>`); 
            $('#loa_details_2').append(`<h6>HOSPITAL NAME: <strong><span class="text-primary">${healthcare_provider}</span></strong></h6>`); 
            // $('#loa-no').html(loa_no);
            $('#status').html(`<strong class="text-success">[${req_status}]</strong>`);
            $('#complaint').text(complaints);
            console.log('hospital_receipt',hospital_receipt);
            // $('#approved-date').html(approved_on);
            // $('#expire').html(expiration);
            switch(req_status){
                case 'Pending':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show()}
                    if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                break;
                case 'Approved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                     if(rx_file.length){$("#p-documents").show()}
                     if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                    if(attending_doctors.length != 0 || attending_physician.length != 0){ $("#p-physician").show();}
                break;
                case 'Disapproved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`); 
                     if(rx_file.length){$("#p-documents").show()}
                   if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
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
                     if(rx_file.length){$("#p-documents").show()}
                   if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                    if(attending_doctors.length != 0 || attending_physician.length != 0){ $("#p-physician").show();}
                break;
                case 'Expired':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`);
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                     if(rx_file.length){$("#p-documents").show()}
                   if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                break;
                case 'Billed': case 'Payment': case 'Payable':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                  
                   if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }else{
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    }
                    $("#p-documents").show();
                    $("#p-physician").show();
                break;
                case 'Paid':
                  $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                  
                    $('#loa_details_1').append(`<h6>PAID AMOUNT: <strong><span class="text-primary">${paid_amount}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DATE PAID: <strong><span class="text-primary">${paid_on}</span></strong></h6>`); 
                   if(hospital_receipt.length){$("#p-documents").show();$('#p-services').show();
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }else{
                      $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    }
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
            if(hospital_receipt.length){
              $('#documents').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewImage(\''+hospital_receipt+'\',\''+'h_bill'+'\')">Hospital Bill</a></li>');
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
            disapproved_on,attending_doctors,disapprove_reason,disapproved_by,hospital_receipt,hospital_name,hospital_bill
            } = res;
            // console.log("complaints",complaints);
            $("#viewNoaModal").modal("show");
            $("#p_disaproved").hide();
            $("#p_documents").hide();
            $("#p_physician").hide();
            $("#p_services").hide();
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#noa_details_1').append(`<h6>NOA #: <strong><span class="text-primary">${noa_no}</span></strong></h6>`); 
            $('#noa_details_2').append(`<h6>HOSPITAL NAME: <strong><span class="text-primary">${hospital_name}</span></strong></h6>`); 
            // $('#loa-no').html(loa_no);
            $('#nstatus').html(`<strong class="text-success">[${req_status}]</strong>`);
            // $('#noa-no').html(noa_no);
            // $('#status-noa').html(`<strong class="text-success">[${req_status}]</strong>`);
            // $('#approved-date-noa').html(approved_on);
            // $('#expire-noa').html(approved_on);
            $('#complaint-noa').text(complaints);
            // console.log(disapprove_reason);
            console.log('med_services',med_services);
            console.log('hospital_receipt',hospital_receipt);
            switch(req_status){
                case 'Pending':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    if(hospital_receipt.length){$("#p_documents").show(); $('#p_services').show()
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                break;
                case 'Approved':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(hospital_receipt.length){$("#p_documents").show(); $('#p_services').show()
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                break;
                case 'Disapproved':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`);
                    $('#noa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`);
                   if(hospital_receipt.length){$("#p_documents").show(); $('#p_services').show()
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                    $("#p_disaproved").show();
                    $('#disaproved-noa').text(disapprove_reason);
                break;
                case 'Expired':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>EXPIRED DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                   if(hospital_receipt.length){$("#p_documents").show(); $('#p_services').show()
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }
                break;
                case 'Billed' || 'Payment' || 'Payable':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    if(hospital_receipt.length){$("#p_documents").show(); $('#p_services').show()
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }else{
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    }
                    if(hospital_receipt.length){$('#p_services').show()};
                    $("#p_documents").show();
                    $("#p_physician").show();
                break;
                case 'Paid':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    if(hospital_receipt.length){$("#p_documents").show(); $('#p_services').show()
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${hospital_bill}</span></strong></h6>`); 
                    }else{
                      $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    }
                    $('#noa_details_2').append(`<h6>DATE PAID: <strong><span class="text-primary">${paid_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>PAID AMOUNT: <strong><span class="text-primary">${paid_amount}</span></strong></h6>`); 
                    if(hospital_receipt.length){$('#p_services').show()};
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
            if(hospital_receipt.length){
              $('#documents-noa').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewImage(\''+hospital_receipt+'\',\''+'h_bill'+'\')">Hospital Bill</a></li>');
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

            $.each(med_services, function(index, item) {
                console.log(item);
                $('#services').append('<li>' + item + '</li>');
            });
            
            // $('#work-related').html(work_related);
          }

        });
    }

    const validateDateRange = () => {
        const startDateInput = document.querySelector('#start-date');
        const endDateInput = document.querySelector('#end-date');
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        if(startDateInput !== '' && endDateInput !== ''){
          $('#print').prop('disabled',false);
        }
        if (startDateInput.value === '' || endDateInput.value === '') {
          $('#print').prop('disabled',true);
            return; // Don't do anything if either input is empty
        }

        if (endDate < startDate) {
            // alert('End date must be greater than or equal to the start date');
            $('#print').prop('disabled',true);
            swal({
                title: 'Failed',
                text: 'End date must be greater than or equal to the start date',
                showConfirmButton: true,
                type: 'error'
            });
            endDateInput.value = '';
            return;
        }          
    }
    
</script>