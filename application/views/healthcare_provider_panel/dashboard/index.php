
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

            <?php include "searched_member_profile.php"; ?>

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
        /* This is a jQuery function that is used to hide and show the search form. */
            $("#search-form-1")[0].reset();
            $("#search-by-name").addClass('d-none');
            $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');
            $("#healthcard-no").focus();
        $("#search-select").on('change', function(){
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
              }
            }
          });
        });

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

      
    </script>
