
      <!-- Start of Page Wrapper -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title ls-2">Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Member</li>
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
          <div class="row mb-2">

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-blue">
                <div class="inner">  
                  <h3><?php echo $pending_loa_count; ?></h3>
                  <p>Pending LOA</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-hospital-building" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>member/requested-loa/pending" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-green">
                <div class="inner">
                  <h3><?php echo $pending_noa_count; ?></h3>
                  <p>Pending NOA</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-document" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>member/requested-noa/pending" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-orange">
                <div class="inner">
                  <h3>&#8369;<?php echo number_format($mbl['max_benefit_limit'], 2); ?></h3>
                  <p>Maximum Benefit Limit</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-wallet" aria-hidden="true"></i>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-red">
                <div class="inner">
                  <h3>&#8369;<?php echo number_format($mbl['remaining_balance'], 2); ?></h3>
                  <p>Remaining Balance</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-coin" aria-hidden="true"></i>
                </div>
                <a class="card-box-footer" id = "view_mbl" >View MBL History <i class="fa fa-arrow-circle-right"></i></a>
                <!-- <a href="<?php echo base_url(); ?>member/mbl-history/loa" class="card-box-footer">View MBL History <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="border border-2 border-secondary"></div>
              <h4 class="page-title ls-2 mt-3 mb-4">Doctor's Availability</h4>
              <div class="row">
                <?php if (!empty($doctors)) : ?>
                  <?php foreach ($doctors as $doc) : ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-3">
                      <div class="p-30 text-white text-center shadow">
                        <img src="<?php echo base_url(); ?>assets/images/company-doctor.svg" class="card-img-top img-responsive mb-3" alt="User Image" style="width:80px;height:auto;">
                  
                        <h5 class="text-dark mb-0 mt-1">
                          <?php echo $doc['doctor_name']; ?>
                        </h5>
                        <strong style="letter-spacing:2px">
                          <?php echo ($doc['online'] == 1) ? '<span class="text-success">Online</span>' : '<span class="text-warning">Offline</span>'; ?>
                        </strong>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
           <!-- End Row  -->  
          </div>
        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>
 <!-- Modal -->
 <div class="modal fade show animate__animated animate__fadeOut" id="termModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Terms and Conditions</h3> 
                </div>
                <div class="modal-body">
                <h3 class="mt-2 mb-2 text-center">DATA PRIVACY POLICY</h3>

              <p class="fs-5 "><strong>Effectivity Date:</strong> MMDDYYYY<br>
              <strong>Last Modified:</strong> MMDDYYYY</p>

              <p class="fs-5">Alturas Supermarket Corporation (ASC) including its parent company, subsidiaries, affiliates, and related companies, collectively known as “Alturas Group of Companies (AGC)”, is committed to protecting your right to privacy. We ensure that our activities involving the collection and/or use of personal data are performed in accordance with the Data Privacy Act of 2012 (“the Act”), its Implementing Rules and Regulations (“IRR”), and other relevant policies, including issuances of the National Privacy Commission.</p>

              <p class="fs-5">This Data Privacy Policy is to serve you better when you avail of our services and inform you of how your personal data is collected, used, and secured by AGC. Please read our Data Privacy Policy carefully.</p>

              <h4 class="mt-4 fs-5">PERSONAL DATA COLLECTED</h4>

              <p class="fs-5">We may collect, store, and use your personal data directly when you provide it in instances when you register, apply, create or modify any account in any of AGC’s website, mobile applications, customer support, customer surveys, apply for employment, or in connection with any other activities, services, features, or resources we make available on this app via any means (e.g., tablet, cellphone, etc.).</p>

              <ol>
                <li class="fs-5">Collected Personal Information (PI) thru this app:
                  <ul>
                    <li>Name</li>
                    <li>Birthday</li>
                    <li>Mobile number</li>
                    <li>Email address</li>
                    <li>Home Address</li>
                    <li>Individual’s photograph</li>
                    <li>Login Credentials such as username and password</li>
                  </ul>
                </li>
                <li class="fs-5">Collected Sensitive Personal Information (SPI) thru this website / app
                  <ul>
                    <li>Gender</li>
                    <li>Civil Status</li>
                    <li>Government Issued ID</li>
                  </ul>
                </li>
              </ol>

              <p class="fs-5">AGC uses cookies to ensure a consistent and efficient experience for customers or users and performs essential functions such as allowing users to register and remain logged in.</p>

              <h4 class="mt-4">USE AND DISCLOSURE</h4>

              <p class="fs-5">When you use or avail of any of the Services of AGC and/or participate in any of its events or activities provided by or held by AGC, we may use and process the personal data collected about you including the processing of your application, publication of your name should you win something, or avail or purchase something through the e-commerce platform or when you avail of other discounts, promos, sales, advertisements, marketing activities, and commercial communications of AGC or any of its stores or malls whether online or not. At all times, your personal data shall not be used or processed for any purpose that is contrary to law, morals, or public policy.</p>

              <h4 class="mt-4">SHARING OF PERSONAL DATA</h4>

              <p class="fs-5">We may share your Personal Data internally between and among the entities comprising AGC and to individuals that will handle and process your application and that will enable us to provide you with personalized or desired services.</p>

              <p class="fs-5">We may also share personal data with vendors, consultants, marketing partners, and other service providers who need access to such information to carry our work on behalf of AGV.</p>

              <p class="fs-5">If and when necessary, a data sharing agreement shall cover any sharing of data between and among AGC and/or its vendors, consultants, marketing partners, and other service providers. AGC may also share information in accordance with any order from any relevant government agency, in response to legal proceedings or when ordered by the competent court of jurisdiction, or as when required by any applicable law.</p>

              <h4 class="mt-4">STORAGE, TRANSMISSION, AND RETENTION</h4>

              <p class="fs-5">We take appropriate measures to keep your personal data secured. The collected personal data from you and/or data which we continue to collect are retained or stored in a secured network location and is accessible by a limited number of persons who have special access rights to such systems and are required to keep the personal data confidential. Its protection may also include securing any document containing personal data, requiring the execution of non-disclosure agreements, confidentiality agreement by its employees, consultants, vendors, suppliers, and contractors among others.</p>

              <p class="fs-5">While there is a risk of unauthorized disclosure of personal data, AGC believes that the foregoing safeguards are sufficient to prevent the occurrence of such unauthorized disclosure and is committed to improving such safeguards in order to protect your rights and in compliance with the Data Privacy Act. When your personal data is no longer required, we will ensure secure deletion from our system or anonymization of your data by our technical experts.</p>

              <h4 class="mt-4">YOUR RIGHTS</h4>

              <p class="fs-5">AGC allows you to exercise your data subject rights in accordance with the Act.</p>

              <ol class="fs-5 ms-3">
                <li>Right to be informed through this privacy notice and other announcements by AGC about the updates on collection, use, storage, and disclosure of your personal data;</li>
                <li>Right to access and data portability by requesting a physical or electronic copy of your personal data through a request and approval process. You may send your requests anytime;</li>
                <li>Right to rectify any inaccuracy or error in your personal data being maintained by AGC;</li>
                <li>Right to object or withhold your consent in the processing of your personal data;</li>
                <li>Right to withdraw or order blocking of your personal data from AGC’s records upon discovery or substantial proof that your personal data is incomplete, outdated, false, or unlawfully obtained;</li>
                <li>Right to damages, if upon justification and full investigation, it was established that your personal data was breached through us, we will face full responsibility and will indemnify you as may be required by Law; and</li>
                <li>Right to file a complaint with the National Privacy Commission if you feel that your personal information has been misused, maliciously disclosed, or improperly disposed of, or that any of your data privacy rights have been violated, you have a right to file a complaint with the NPC.</li>
              </ol>

              <h4 class="mt-4">CHANGES TO THE PRIVACY POLICY</h4>

              <p class="fs-5">AGC reserves the right to modify this Privacy Policy at any time, so please review it frequently. Changes and clarifications will take effect immediately upon their posting on the website.</p>

              <p class="fs-5">If we make material changes to this policy, we will notify you here that it has been updated, so that you are aware of what information we collect, how we use it, and under what circumstances, if any, we use and/or disclose it.</p>

              <p class="fs-5">If our store is acquired or merged with another company, your information may be transferred to the new owners so that we may continue to sell products to you.</p>

              <h4 class="mt-4">CONTACT US</h4>

              <p class="fs-5">If you have any questions or concerns about this Privacy Policy, please contact us:</p>

              <ul class="fs-5 ms-3">
                <li>Email: privacy@alturasgroup.com</li>
                <li>Address: Alturas Corporate Center, Sta. Catalina St., Tagbilaran City, Bohol</li>
                <li>Contact Number: (123) 456-7890</li>
              </ul>
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="agreement">
                    <label class="form-check-label" for="defaultCheck1">
                        I agree to the terms and conditions
                    </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="agreed">Agree</button>
                </div>
                </div>
            </div>
            </div>

        <!-- mbl history modal -->
        <div class="modal fade show animate__animated animate__fadeOut" id="mbl_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">MBL History</h3> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                <!-- <h4 class="page-title ls-2 ">Patient History</h4> -->
                  <!-- patient history Loa-->
                  <div class="card shadow mt-2 p-2" >
                    <table class="table table-hover table-responsive" id="mbl_history_table">
                        <thead >
                          <tr>
                            <th > <span style="font-weight:600;" class="colored-label">BILLING #</span></th>
                            <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                            <th > <span style="font-weight:600;" class="colored-label">REQUEST DATE</span></th>
                            <th > <span style="font-weight:600;" class="colored-label">NET BILL</span></th>
                            <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                  </div>
                  <div class="mt-2 p-2 text-end" >
                    <label for="remaining_mbl">Remaining MBL</label>
                    <input type="text" name="remaining_mbl" id="remaining_mbl" disabled>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger rounded-pill" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <?php include 'view_loa_history.php'; ?>
        <?php include 'view_pdf_bill_modal.php'; ?>
    <script>
      const baseUrl = `<?php echo base_url(); ?>`;
      $(document).ready(()=>{
        $('#agreed').prop('disabled', true);
        read_tnc();
          // add a change event listener to the agreement checkbox
          $('#agreement').on('change', function() {
              // enable/disable the agreed button based on the checked state of the agreement checkbox
              $('#agreed').prop('disabled', !$(this).prop('checked'));
              // enable/disable the login button based on the checked state of the agreement checkbox
          }); 
          $('#agreed').on('click',function(){
              update_read_tnc();
          });

          $('#view_mbl').on('click',function(){

            $('#mbl_modal').modal('show');
            $("#mbl_history_table").DataTable({
              processing: true, //Feature control the processing indicator. 
              serverSide: true, //Feature control DataTables' server-side processing mode.
              order: [], //Initial no order.

              // Load data for the table's content from an Ajax source
              ajax: {
                url: `${baseUrl}member/mbl-history/loa/billed/fetch`,
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
      });
      
     
      const update_read_tnc = () => {
            $.ajax({
                url: `${baseUrl}update-member-tnc`,
                type: "post",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                    },
                dataType: "json",
                success: function (res) {
                   
                },
            });
        }
        

          const read_tnc = () => {
          $.ajax({
              url: `${baseUrl}read-member-tnc`,
              type: "post",
              data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                    },
              dataType: "json",
              success: function (res) {
                console.log(res);
                 if(res.modal_display ==  true){
                  $('#termModal').modal('show');
                 }else{
                  $('#termModal').modal('hide');
                 }
                //  if(res.csrf_hash){
                //   crsf_token = res.csrf_hash;
                // }
                console.log("success");
              },
              error: function (e) {
                console.log("error");
              }
              
          });
      }


    </script>