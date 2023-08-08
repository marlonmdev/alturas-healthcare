<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2" style="font-size:14px">DISAPPROVED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Disapproved</li>
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
          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-12 font-bold"><i class="pending mdi mdi-dots-horizontal"></i> PENDING</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-12 font-bold"><i class="approved mdi mdi-thumb-up"></i> APPROVED</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1 active" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-12 font-bold"><i class="disapproved mdi mdi-thumb-down"></i> DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-12 font-bold"><i class="expired mdi mdi-calendar-clock"></i> EXPIRED</span>
            </a>
          </li>
        </ul>

        <!-- <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="disapproved-hospital-filter" id="disapproved-hospital-filter">
              <option value="">Select Hospital</option>
              <?php foreach($hcproviders as $option) : ?>
                <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div> -->

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="disapprovedNoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;font-size:12px">NOA NO.</th>
                    <th class="fw-bold" style="color: white;font-size:12px">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;font-size:12px">DATE OF ADMISSION</th>
                    <th class="fw-bold" style="color: white;font-size:12px">NAME OF HOSPITAL</th>
                    <th class="fw-bold" style="color: white;font-size:12px">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white;font-size:12px">STATUS</th>
                    <th class="fw-bold" style="color: white;font-size:12px">ACTION</th>
                </thead>
                  </tr>
                <tbody style="color:black;font-size:12px">
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Disapproved Modal-->
        <div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <section id="printableDiv">
                <div class="modal-header" style="background-color:#00538c">
                  <h4 class="modal-title ls-2" style="color:#fff">NOA #: <span id="noa_no" class="text-warning"></span> <span id="noa_status"></span></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row text-center">
                      <h4><strong>PATIENT DETAILS</strong></h4>
                    </div>
                    <div class="row">
                      <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                        <tr>
                          <td class="fw-bold ls-1">Requested On :</td>
                          <td class="fw-bold ls-1" id="request_date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Admission Date :</td>
                          <td class="fw-bold ls-1" id="admission_date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Disapproved On :</td>
                          <td class="fw-bold ls-1" id="disapproved_on"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Disapproved By :</td>
                          <td class="fw-bold ls-1" id="disapproved_by"></td>
                        </tr>
                        
                        <tr>
                          <td class="fw-bold ls-1">Percentage :</td>
                          <td class="fw-bold ls-1" id="percentage"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Maximum Benefit Limit :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Remaining MBL :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="remaining_mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Healthcard No. :</td>
                          <td class="fw-bold ls-1" id="healthcard_no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Full Name :</td>
                          <td class="fw-bold ls-1" id="full_name"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Date of Birth :</td>
                          <td class="fw-bold ls-1" id="date_of_birth"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Age :</td>
                          <td class="fw-bold ls-1" id="age"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Gender :</td>
                          <td class="fw-bold ls-1" id="gender"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Blood Type :</td>
                          <td class="fw-bold ls-1" id="blood_type"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Philhealth No. :</td>
                          <td class="fw-bold ls-1" id="philhealth_no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Home Address :</td>
                          <td class="fw-bold ls-1" id="home_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">City Address :</td>
                          <td class="fw-bold ls-1" id="city_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Number :</td>
                          <td class="fw-bold ls-1" id="contact_no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Email Address :</td>
                          <td class="fw-bold ls-1" id="email_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Name :</td>
                          <td class="fw-bold ls-1" id="contact_person_name"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Address :</td>
                          <td class="fw-bold ls-1" id="contact_person_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Number :</td>
                          <td class="fw-bold ls-1" id="contact_person_number"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Healthcare Provider :</td>
                          <td class="fw-bold ls-1" id="healthcare_provider"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Chief Complaint :</td>
                          <td class="fw-bold ls-1" id="chief_complaint"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Reason for Disapproval :</td>
                          <td class="fw-bold ls-1" id="disapprove_reason"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Services :</td>
                          <td class="fw-bold ls-1" id="services"></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </section>
              <div class="modal-footer">
                <button class="btn btn-dark ls-1 me-2" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of View NOA -->

      </div>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let disapprovedTable = $('#disapprovedNoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/requests-list/disapproved/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#disapproved-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [5, 6], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

    $('#disapproved-hospital-filter').change(function(){
      disapprovedTable.draw();
    });


  });


  const saveAsImage = () => {
    // Get the div element you want to save as an image
    const element = document.querySelector("#printableDiv");
    // Use html2canvas to take a screenshot of the element
    html2canvas(element)
      .then(function(canvas) {
        // Convert the canvas to an image data URL
        const imgData = canvas.toDataURL("image/png");
        // Create a temporary link element to download the image
        const link = document.createElement("a");
        link.download = `noa_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }

  const viewDisapprovedNoaInfo = (req_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/noa/disapproved/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          noa_no,req_status,request_date,admission_date,disapproved_on,disapproved_by,disapprove_reason,work_related,percentage,mbl,remaining_mbl,health_card_no,first_name,middle_name,last_name,suffix,date_of_birth,age,gender,blood_type,philhealth_no,home_address,city_address,contact_no,email,contact_person,contact_person_addr,contact_person_no,hospital_name,chief_complaint,medical_services
        } = res;

        $("#viewNoaModal").modal("show");

        const dob = date_of_birth !== '' ? date_of_birth : 'None';
        const ag = age !== '' ? age : 'None';
        const gndr = gender !== '' ? gender : 'None';
        const bt = blood_type !== '' ? blood_type : 'None';
        const pn = philhealth_no !== '' ? philhealth_no : 'None';
        const ha = home_address !== '' ? home_address : 'None';
        const ca = city_address !== '' ? city_address : 'None';
        const cn = contact_no !== '' ? contact_no : 'None';
        const em = email !== '' ? email : 'None';
        const cp = contact_person !== '' ? contact_person : 'None';
        const cpa = contact_person_addr !== '' ? contact_person_addr : 'None';
        const cpn = contact_person_no !== '' ? contact_person_no : 'None';
        const serv = medical_services !== '' ? medical_services : 'None';

        switch (req_status) {
          case 'Pending':
            $('#noa_status').html('<strong class="text-warning">[' + req_status + ']</strong>');
            break;
          case 'Approved':
            $('#noa_status').html('<strong class="text-success">[' + req_status + ']</strong>');
            break;
          case 'Disapproved':
            $('#noa_status').html('<strong class="text-danger">[' + req_status + ']</strong>');
            break;
        }
        $('#noa_no').html(noa_no);
        $('#request_date').html(request_date);
        $('#admission_date').html(admission_date);
        $('#disapproved_on').html(disapproved_on);
        $('#disapproved_by').html(disapproved_by);
        $('#disapprove_reason').html('<strong class="text-danger">' + disapprove_reason + '</strong>');
        if(work_related == 'Yes'){ 
          if(percentage == ''){
            wpercent = '100% W-R';
            nwpercent = '';
          }else{
            wpercent = percentage+'%  W-R';
            result = 100 - parseFloat(percentage);
            if(percentage == '100'){
              nwpercent = '';
            }else{
              nwpercent = result+'% Non W-R';
            } 
          } 
        }else if(work_related == 'No'){
          if(percentage == ''){
            wpercent = '';
            nwpercent = '100% Non W-R';
          }else{
            nwpercent = percentage+'% Non W-R';
            result = 100 - parseFloat(percentage);
            if(percentage == '100'){
              wpercent = '';
            }else{
              wpercent = result+'%  W-R';
            }
          }
        }
        $('#percentage').html(wpercent+', '+nwpercent); if(work_related == 'Yes'){ 
          if(percentage == ''){
            wpercent = '100% W-R';
            nwpercent = '';
          }else{
            wpercent = percentage+'%  W-R';
            result = 100 - parseFloat(percentage);
            if(percentage == '100'){
              nwpercent = '';
            }else{
              nwpercent = result+'% Non W-R';
            }
          } 
        }else if(work_related == 'No'){
          if(percentage == ''){
            wpercent = '';
            nwpercent = '100% Non W-R';
          }else{
            nwpercent = percentage+'% Non W-R';
            result = 100 - parseFloat(percentage);
            if(percentage == '100'){
              wpercent = '';
            }else{
              wpercent = result+'%  W-R';
            } 
          }
        }
        $('#percentage').html(wpercent+', '+nwpercent);

        $('#mbl').html(mbl);
        $('#remaining_mbl').html(remaining_mbl);
        $('#healthcard_no').html(health_card_no);
        $('#full_name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date_of_birth').html(dob);
        $('#age').html(ag);
        $('#gender').html(gndr);
        $('#blood_type').html(bt);
        $('#philhealth_no').html(pn);
        $('#home_address').html(ha);
        $('#city_address').html(ca);
        $('#contact_no').html(cn);
        $('#email_address').html(em);
        $('#contact_person_name').html(cp);
        $('#contact_person_address').html(cpa);
        $('#contact_person_number').html(cpn);
        $('#healthcare_provider').html(hospital_name);
        $('#chief_complaint').html(chief_complaint);
        $('#services').html(serv);
      }
    });
  }
</script>

<style>
  .nav-item1 {
    list-style-type: none;
  }

  .nav-link1 {
    display: inline-block;
    padding: 10px;
    padding-top:1px;
    padding-bottom:1px;
    text-decoration: none;
    background-color: #e6e6e6;
    color: #000;
    border: 1px solid gray;
    border-bottom: 3px solid gray;
    border-radius: 15px;
  }

  .nav-link1:hover {
    background-color: #002244;
    color: #fff;
    border: 1px solid #000;
  }

  .font-bold {
    font-weight: bold;
  }

  .hidden-xs-down {
    display: inline-block;
  }

  .fs-5 {
    font-size: 1.2rem;
  }
  .pending{
    color:red
  }
  .approved{
    color:green
  }
  .disapproved{
    color:red
  }
  .completed{
    color:green
  }
  .referral{
    color:orange
  }
  .expired{
    color:#a32cc4
  }
  .cancelled{
    color:red
  }
</style>