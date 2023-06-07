
      <!-- Start of Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Dashboard | <span class="text-danger"><i class="mdi mdi-hospital-building"></i> <?php echo $hp_name; ?></span></h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Provider</li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Dashboard
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
            <input id="hp-id" type="hidden" value="<?php echo $hp_id;?>">
            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-green">
                <div class="inner">
                  <h3><?php echo $bllled_count; ?></h3>
                  <p>Serviced</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-checkbox-marked" aria-hidden="true"></i>
                </div>
              </div>
            </div>


            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-orange">
                <div class="inner">
                  <h3><?php echo $loa_count; ?></h3>
                  <p>LOA Requests</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-document" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url() ?>healthcare-provider/loa-requests/approved" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            
            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-red">
                <div class="inner">
                  <h3><?php echo $noa_count; ?></h3>
                  <p>NOA Requests</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url() ?>healthcare-provider/noa-requests/approved" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-blue">
                <div class="inner">
                  <h3><?php echo $total_patient; ?></h3>
                  <p>Total Patient</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url() ?>healthcare-provider/patient/design" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
           
              <?php include "search_member_form.php"; ?>
              <div id="searchedMemberContainer">
                <?php include "searched_member_profile.php"; ?>
              </div>
              <?php include 'view_loa_history.php'; ?>
              <?php include 'view_noa_history.php'; ?>
              <?php include 'view_pdf_bill_modal.php'; ?>
              
          </div>
          
        <!-- End Container fluid  --> 
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>
    <script>
      const base_url = `<?php echo base_url(); ?>`;

      $(document).ready(function(){

        $('#viewLoaModal').on('hidden.bs.modal', function() {
          $('#services').empty(); // Remove all list items from the list
          $('#documents').empty(); 
          $('#loa_details_1').empty(); 
          $('#loa_details_2').empty(); 
          $('#physician').empty(); 
          // Additional reset logic if needed
        });

        $('#viewNoaModal').on('hidden.bs.modal', function() {
          $('#services-noa').empty(); // Remove all list items from the list
          $('#documents-noa').empty(); 
          $('#noa_details_1').empty(); 
          $('#noa_details_2').empty(); 
          $('#physician-noa').empty(); 
          // Additional reset logic if needed
        });
        /* This is a jQuery function that is used to hide and show the search form. */
            $("#search-form-1")[0].reset();
            $("#search-by-name").addClass('d-none');
            $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');
            $("#healthcard-no").focus();
        $("#search-select").on('change', function(){
          document.getElementById("searchedMemberContainer").innerHTML = "";
        
          if($(this).val() == "healthcard"){
            $("#search-form-1")[0].reset();
            $("#search-by-name").addClass('d-none');
            $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');
          } else if($(this).val() == "name"){
            $("#search-form-2")[0].reset();
            $("#search-by-healthcard").addClass('d-none');
            $("#search-by-name").removeClass('d-none is-invalid is-valid');
          }else{
            $("#search-by-healthcard").addClass('d-none');
            $("#search-by-name").addClass('d-none');
          }
        });

        $("#search-form-1").on('submit', function(event){
          event.preventDefault();
          $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
              if(res.status == 'error'){
                swal({
                  title: 'Error',
                  text: res.message,
                  timer: 3000,
                  showConfirmButton: false,
                  type: 'error'
                });
                $("#search-form-1")[0].reset();
                
              }else if(res.status == 'success'){
                $("#mbr-profile-div").removeClass('d-none');
                // if member exist populate member profile with dynamic data 
                let img_url = '';
                if(res.photo == '' || res.photo_status == 'Not Found'){
                  if(res.gender == 'Male' || res.gender == 'male'){
                    img_url = `${base_url}assets/images/male_avatar.svg`;
                  }else{
                    img_url = `${base_url}assets/images/female_avatar.svg`;
                  }
                }else{
                  img_url = `${base_url}uploads/profile_pics/${res.photo}`;
                }

                $("#mbr-photo").attr("src", img_url);
                $("#bus-unit").html(res.business_unit);
                $("#dept-name").html(res.dept_name);
                $("#position").html(res.position);
                $("#emp-type").html(res.emp_type);
                $("#cur-status").html(res.current_status);
                $("#pos-level").html(res.pos_level);
                $("#hcard-no").html(res.hcard_no);
                $("#mbr-mbl").html(res.mbr_mbl);
                $("#mbr-rmg-bal").html(res.mbr_rmg_bal);
                $("#mbr-fullname").html(res.fullname);
                $("#home-addr").html(res.home_address);
                $("#city-addr").html(res.city_address);
                $("#mbr-dob").html(res.date_of_birth);
                $("#mbr-age").html(res.age);
                $("#cvl-status").html(res.civil_status);
                $("#mbr-sex").html(res.gender);
                $("#contact-no").html(res.contact_no);
                $("#email-ad").html(res.email);
                // Show spouse div if res.spouse is not empty
                if(res.spouse != ''){
                  $("#spouse-div").removeClass('d-none');
                  $("#spouse-hr").removeClass('d-none');
                  $("#mbr-spouse").html(res.spouse);
                }else{
                  $("#spouse-div").addClass('d-none');
                  $("#spouse-hr").addClass('d-none');
                }
                $("#blood-type").html(res.blood_type);
                $("#mbr-height").html(res.height);
                $("#mbr-weight").html(res.weight);
                $("#cp-name").html(res.contact_person);
                $("#cp-addr").html(res.contact_person_addr);
                $("#cp-contact").html(res.contact_person_no);
                $("#s-emp-id").val(res.emp_id);
                
                get_loa();
                get_noa();
              }
            }
          });
        });

        $("#search-form-2").on('submit', function(event){
          event.preventDefault();
          $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
              if(res.status == 'error'){
                swal({
                  title: 'Error',
                  text: res.message,
                  timer: 3000,
                  showConfirmButton: false,
                  type: 'error'
                });
              }else if(res.status == 'success'){
                $("#mbr-profile-div").removeClass('d-none');
                // if member exist populate member profile with dynamic data 
                let img_url = '';
                if(res.photo == '' || res.photo_status == 'Not Found'){
                  if(res.gender == 'Male' || res.gender == 'male'){
                    img_url = `${base_url}assets/images/male_avatar.svg`;
                  }else{
                    img_url = `${base_url}assets/images/female_avatar.svg`;
                  }
                }else{
                  img_url = `${base_url}uploads/profile_pics/${res.photo}`;
                }

                $("#mbr-photo").attr("src", img_url);
                $("#bus-unit").html(res.business_unit);
                $("#dept-name").html(res.dept_name);
                $("#position").html(res.position);
                $("#emp-type").html(res.emp_type);
                $("#cur-status").html(res.current_status);
                $("#pos-level").html(res.pos_level);
                $("#hcard-no").html(res.hcard_no);
                $("#mbr-mbl").html(res.mbr_mbl);
                $("#mbr-rmg-bal").html(res.mbr_rmg_bal);
                $("#mbr-fullname").html(res.fullname);
                $("#home-addr").html(res.home_address);
                $("#city-addr").html(res.city_address);
                $("#mbr-dob").html(res.date_of_birth);
                $("#mbr-age").html(res.age);
                $("#cvl-status").html(res.civil_status);
                $("#mbr-sex").html(res.gender);
                $("#contact-no").html(res.contact_no);
                $("#email-ad").html(res.email);
                // Show spouse div if res.spouse is not empty
                if(res.spouse != ''){
                  $("#spouse-div").removeClass('d-none');
                  $("#spouse-hr").removeClass('d-none');
                  $("#mbr-spouse").html(res.spouse);
                }else{
                  $("#spouse-div").addClass('d-none');
                  $("#spouse-hr").addClass('d-none');
                }
                $("#blood-type").html(res.blood_type);
                $("#mbr-height").html(res.height);  
                $("#mbr-weight").html(res.weight);
                $("#cp-name").html(res.contact_person);
                $("#cp-addr").html(res.contact_person_addr);
                $("#cp-contact").html(res.contact_person_no);
                $("#s-emp-id").val(res.emp_id);
                get_loa();
                get_noa();
              }
            }
          });
        });

          const get_loa = () =>{
          const emp_id = $("#s-emp-id").val();
          const hp_id = document.querySelector('#hp-id').value;
          $('#loa_table').DataTable({ 
              lengthMenu: [5, 10, 25, 50],
              processing: true,
              serverSide: true,
              order: [],

              ajax: {
                url: `${base_url}healthcare-provider/patient/fetch_all_patient_loa`,
                type: "POST",
                data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'emp_id' :  emp_id,
                        'hp_id' :  hp_id}
              },

              responsive: true,
              fixedHeader: true,
            });  
          }
          
          const get_noa = () =>{
            const emp_id = $("#s-emp-id").val();
            const hp_id = document.querySelector('#hp-id').value;
            $('#noa_table').DataTable({ 
            lengthMenu: [5, 10, 25, 50],
            processing: true,
            serverSide: true,
            order: [],

            ajax: {
              url: `${base_url}healthcare-provider/patient/fetch_all_patient_noa`,
              type: "POST",
              data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                      'emp_id' :  emp_id,
                      'hp_id' :  hp_id}
            },

            responsive: true,
            fixedHeader: true,
            });   
  
          }

           Quagga.init({
          inputStream : {
              name : "Live",
              type : "LiveStream",
              target: document.querySelector('#healthcard-no')
            },
            decoder : {
                readers : ["ean_reader"]
            }
          }, function(err) {
              if (err) {
                  console.log(err);                                         
                  return;
              }
              Quagga.start();
          });

          Quagga.onDetected(function(data) {
              var code = data.codeResult.code;
              document.querySelector('#healthcard-no').value = code;
              document.querySelector('#search-form-1').submit();
          });

      });

      function viewImage(file,type) {
        let src = '';
        if(type == 'abstract'){
            src = `${base_url}uploads/medical_abstract/${file}`;
        }
        if(type == 'prescription'){
            src = `${base_url}uploads/prescription/${file}`;
        }
        if(type == 'rx'){
            src = `${base_url}uploads/loa_attachments/${file}`;
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
          pdfFile = `${base_url}uploads/pdf_bills/${pdf_bill}`;
        }
        if(type == "diagnosis"){
          pdfFile = `${base_url}uploads/final_diagnosis/${pdf_bill}`;
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
        url: `${base_url}healthcare-provider/patient_history/loa/${loa_no}`,
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
                    if(rx_file.length){$("#p-documents").hide();}
                break;
                case 'Approved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").hide();}
                break;
                case 'Disapproved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").hide();}
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
                case 'Refered':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                break;
                case 'Expired':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`);
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").hide();}
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
    function viewNoaHistoryInfo(noa_id) {
        $.ajax({
        url: `${base_url}healthcare-provider/patient_history/noa/${noa_id}`,
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
