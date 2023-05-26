
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
          // Additional reset logic if needed
        });

        $('#viewNoaModal').on('hidden.bs.modal', function() {
          $('#services-noa').empty(); // Remove all list items from the list
          $('#documents-noa').empty(); 
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

      function viewImage(rx_file) {
        let item = [{
            src: `${base_url}uploads/loa_attachments/${rx_file}`, // path to image
            title: 'Attached RX File' // If you skip it, there will display the original image name
        }];
        // define options (if needed)
        let options = {
            index: 0 // this option means you will start at first image
        };
        // Initialize the plugin
        let photoviewer = new PhotoViewer(item, options);
    }

    const viewPDFBill = (pdf_bill,loa_no) => {
      $('#viewPDFBillModal').modal('show');
      $('#pdf-loa-no').html(loa_no);

        let pdfFile = `${base_url}uploads/pdf_bills/${pdf_bill}`;
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
            request_date, chief_complaint, requesting_physician, attending_physician, rx_file,pdf_bill,
            req_status, work_related, approved_by, approved_on,expiration
            } = res;

            $("#viewLoaModal").modal("show");
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';

            $('#loa-no').html(loa_no);
            $('#status').html(`<strong class="text-success">[${req_status}]</strong>`);
            $('#approved-date').html(approved_on);
            $('#expire').html(expiration);

            $.each(med_services, function(index, item) {
              $('#services').append('<li>' + item + '</li>');
            });
            console.log("rxfile",rx_file);
            console.log("soafile",pdf_bill);
            if(rx_file.length){
              $('#documents').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+rx_file+'\')">Rx File</a></li>');
            }
            if(pdf_bill.length){
              $('#documents').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+pdf_bill+'\',\''+loa_no+'\')">Statement of Account (SOA)</a></li>');
            }
            // $('#soa').html();
            
            // $('#requesting-company').html(requesting_company);
            // $('#request-date').html(request_date);
            // $('#chief-complaint').html(chief_complaint);
            // $('#requesting-physician').html(requesting_physician);
            $('#physician').html(attending_physician);
            // $('#work-related').html(work_related);
          }

        });

    }
    function viewNoaHistoryInfo(noa_id) {
        $.ajax({
        url: `${base_url}healthcare-provider/patient_history/noa/${noa_id}`,
        type: "GET",
        success: function(response) {
            const res = JSON.parse(response);
            const base_url = window.location.origin;
            // Object Destructuring
            const { status, token, noa_no, member_mbl, remaining_mbl, first_name, middle_name,
            last_name, suffix, date_of_birth, age, gender, philhealth_no, blood_type, contact_no,
            home_address, city_address, email, contact_person, contact_person_addr, contact_person_no,
            healthcare_provider, loa_request_type, med_services, health_card_no, requesting_company,
            request_date, chief_complaint, requesting_physician, attending_physician, pdf_bill,
            req_status, work_related, approved_by, approved_on
            } = res;

            $("#viewNoaModal").modal("show");
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';

            $('#noa-no').html(noa_no);
            $('#status-noa').html(`<strong class="text-success">[${req_status}]</strong>`);
            $('#approved-date-noa').html(approved_on);
            $('#expire-noa').html(approved_on);

            $.each(med_services, function(index, item) {
              $('#services-noa').append('<li>' + item + '</li>');
            });
            // console.log("rxfile",rx_file);
            console.log("soafile",pdf_bill);
            // if(rx_file.length){
            //   $('#documents-noa').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+ +'\')">Rx File</a></li>');
            // }
            if(pdf_bill.length){
              $('#documents-noa').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+pdf_bill+'\',\''+noa_no+'\')">Statement of Account (SOA)</a></li>');
            }
            // $('#soa').html();
            
            // $('#requesting-company').html(requesting_company);
            // $('#request-date').html(request_date);
            // $('#chief-complaint').html(chief_complaint);
            // $('#requesting-physician').html(requesting_physician);
            $('#physician-noa').html(attending_physician);
            // $('#work-related').html(work_related);
          }

        });

    }
  
    </script>
