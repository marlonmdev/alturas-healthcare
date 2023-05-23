
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

          const baseurl = '<?php echo base_url();?>';
          
          const get_loa = () =>{
          const emp_id = $("#s-emp-id").val();
          const hp_id = document.querySelector('#hp-id').value;
              $('#loa_table').DataTable({ 
              processing: true,
              serverSide: true,
              order: [],

              ajax: {
                url: `${baseurl}healthcare-provider/patient/fetch_all_patient_loa`,
                type: "POST",
                data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'emp_id' :  emp_id,
                        'hp_id' :  hp_id}
              },

              // columnDefs: [{ 
              //   "targets": [6], // 6th and 7th column / numbering column
              //   "orderable": false,
              // },
              // ],
              responsive: true,
              fixedHeader: true,
            });   
          }
          
          const get_noa = () =>{
            const emp_id = $("#s-emp-id").val();
            const hp_id = document.querySelector('#hp-id').value;
            $('#noa_table').DataTable({ 
            processing: true,
            serverSide: true,
            order: [],

            ajax: {
              url: `${baseurl}healthcare-provider/patient/fetch_all_patient_noa`,
              type: "POST",
              data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                      'emp_id' :  emp_id,
                      'hp_id' :  hp_id}
            },

            // columnDefs: [{ 
            //   "targets": [6], // 6th and 7th column / numbering column
            //   "orderable": false,
            // },
            // ],
            responsive: true,
            fixedHeader: true,
            });   
          }
          

      });

      // window.onload = function() {
      //   get_loa_noa();
      // };

    

      // const get_loa_noa = () => {
      //   const emp_id = document.querySelector('#s-emp-id').value;
      //   const hp_id = document.querySelector('#hp-id').value;

      //   $.ajax({
      //     url: '<?php echo base_url();?>healthcare-provider/history/get_loa_noa',
      //     type: 'get',
      //     dataType: 'json',
      //     data: {
      //       'token' : '<?php echo $this->security->get_csrf_hash();?>',
      //       'emp_id' : emp_id,
      //       'hp_id' : hp_id,
      //     }, 
      //     success: function(res){
      //       let data = '';
            
      //       let displayedLoaNos = []; // Array to store the displayed LOA numbers or NOA numbers

      //         if(res !== ""){
      //           $.each(res, function(index, loa_noa) {
      //             let loaNoa = '';

      //             if (loa_noa.loa_id !== '') {
      //               loaNoa = loa_noa.loa_no;
      //             }

      //             // Check if the LOA number or NOA number has already been displayed
      //             if (!displayedLoaNos.includes(loaNoa)) {
      //               let status = loa_noa.status;
      //               let approvedOn = loa_noa.approved_on;
      //               let output = '<div><span class="mb-0 text-secondary" style="font-weight:600;">' + loaNoa + '</span>' +
      //                 '<span class="mb-0 text-secondary ps-5 ms-4 pt-1" style="font-weight:600;">' + status + '</span>' +
      //                 '<span class="mb-0 text-secondary ps-5 ms-5 pt-1" style="font-weight:600;">' + approvedOn + '</span></div>';

      //               // Include the NOA number if available
      //               if (loa_noa.noa_id !== '' && loa_noa.noa_no !== '') {
      //                 let noaNo = loa_noa.noa_no;
      //                 output += '<div><span class="mb-0 text-secondary" style="font-weight:600;">' + noaNo + '</span>' +
      //                   '<span class="mb-0 text-secondary ps-5 ms-4 pt-1" style="font-weight:600;">' + status + '</span>' +
      //                   '<span class="mb-0 text-secondary ps-5 ms-5 pt-1" style="font-weight:600;">' + approvedOn + '</span></div>';
      //               }

      //               data += output;
      //               displayedLoaNos.push(loaNoa); // Add the LOA number or NOA number to the displayed array
      //             }
      //           });

      //         }else{
      //           data += 'No Histories';
      //         }
           
      //       $('#history').html(data);
      //     }
      //   });
      // }

      
    </script>
