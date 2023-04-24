<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">REFERRAL REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">Referral LOA</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active"  href="<?php echo base_url(); ?>company-doctor/loa/requests-list/referral" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">REFERRAL</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">EXPIRED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/cancelled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">CANCELLED</span>
            </a>
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white">
                <i class="mdi mdi-filter"></i>
              </span>
            </div>
            <select class="form-select fw-bold" name="referral-hospital-filter" id="referral-hospital-filter">
              <option value="">Select Hospital</option>
              <?php foreach($hcproviders as $option) : ?>
                <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="ReferralLoaTable">
                <thead>
                  <tr>
                    <th class="fw-bold">LOA NO.</th>
                    <th class="fw-bold">PATIENT'S NAME</th>
                    <th class="fw-bold">TYPE OF REQUEST</th>
                    <th class="fw-bold">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold">RX FILE</th>
                    <th class="fw-bold">DATE OF REQUEST</th>
                    <th class="fw-bold">STATUS</th>
                    <th class="fw-bold">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          <?php include 'back_date_modal.php'; ?>
        </div>
        <?php include 'view_referral_loa_details.php'; ?>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function(){
    let referraltable = $('#ReferralLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}company-doctor/loa/requests-list/referral/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#referral-hospital-filter').val();
        }
      },

      columnDefs: [{
        "targets": [4, 6, 7],//set data descending or ascending
        "orderable": false,
      }, ],
      responsive: true,
      fixedHeader: true,
    });

    $('#referral-hospital-filter').change(function(){
      referraltable.draw();
    });

    $('#backDateForm').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseUrl}company-doctor/loa/requests-list/expired/backdate_expired`,
        data: $(this).serialize(),
        dataType: "json",
        success: function (res) {
          const { status, message } = res;

          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (expiry_date_error !== '') {
                $('#expiry-date-error').html(expiry_date_error);
                $('#expiry-date').addClass('is-invalid');
              } else {
                $('#expiry-date-error').html('');
                $('#expiry-date').removeClass('is-invalid');
              }
            break;

            case 'save-error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });
            break;

            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
              $("#backDateModal").modal("hide");
              $("#expiredLoaTable").DataTable().ajax.reload();
            break;
          }
        },
      });
    });
  });

  const showBackDateForm = (loa_id, loa_no,expiry_date) => {
    $("#backDateModal").modal("show");
    $('#bd-loa-id').val(loa_id);
    $('#bd-loa-no').val(loa_no);
    $('#loa-no').html(loa_no);
    $('#expired-on').val(expiry_date);
  }

  //View Image display in table
  function viewImage(path) {
    let item = [{
      src: path,
      title: 'Attached RX File'
    }];

    let options = {
      index: 0 
    };
    let photoviewer = new PhotoViewer(item, options);
  }
  //end

  //To save image 
  const saveAsImage = () => {
    const element = document.querySelector("#printableDiv");
    html2canvas(element)
    .then(function(canvas) {
      const imgData = canvas.toDataURL("image/png");
      const link = document.createElement("a");
      link.download = `loa_${fileName}.png`;
      link.href = imgData;
      link.click();
    });
  }
  //end

  //Display Data in modal
  function viewReferralLoaInfo(req_id) {
    $.ajax({
      url: `${baseUrl}company-doctor/loa/requests-list/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,token,loa_no,req_status,request_date,requested_by,approved_on,approved_by,member_mbl,remaining_mbl,work_related,health_card_no,first_name,middle_name,last_name,suffix,date_of_birth,age,gender,blood_type,philhealth_no,home_address,city_address,contact_no,email,contact_person,contact_person_addr,contact_person_no,healthcare_provider,loa_request_type,med_services,requesting_company,chief_complaint,requesting_physician
        } = res;

        $("#viewLoaModal").modal("show");

        
        const med_serv = med_services !== '' ? med_services : 'None';

        $('#loa-no').html(loa_no);
        switch (req_status) {
          case 'Pending':
            $('#loa-status').html(`<strong class="text-warning">[${req_status}]</strong>`);
          break;
          case 'Approved':
            $('#loa-status').html(`<strong class="text-success">[${req_status}]</strong>`);
          break;
          case 'Disapproved':
            $('#loa-status').html(`<strong class="text-danger">[${req_status}]</strong>`);
          break;
          case 'Closed':
            $('#loa-status').html(`<strong class="text-info">[${req_status}]</strong>`);
          break;
          case 'Reffered':
            $('#loa-status').html(`<strong class="text-success">[${req_status}]</strong>`);
          break;
        }
        $('#request-date').html(request_date);
        $('#requested-by').html(requested_by);
        $('#approved-date').html(approved_on);
        $('#approved-by').html(approved_by);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
        $('#work-related').html(work_related);
        $('#health-card-no').html(health_card_no);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#gender').html(gender);
        $('#blood-type').html(blood_type);
        $('#philhealth-no').html(philhealth_no);
        $('#home-address').html(home_address);
        $('#city-address').html(city_address);
        $('#contact-no').html(contact_no);
        $('#email').html(email);
        $('#contact-person').html(contact_person);
        $('#contact-person-addr').html(contact_person_addr);
        $('#contact-person-no').html(contact_person_no);
        $('#healthcare-provider').html(healthcare_provider);
        $('#loa-request-type').html(loa_request_type);
        $('#loa-med-services').html(med_serv);
        $('#requesting-company').html(requesting_company);
        $('#chief-complaint').html(chief_complaint);
        $('#requesting-physician').html(requesting_physician);
      }
    });
  }
  //end
  
  const viewPerformedLoaInfo = (loa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/performed-loa-info/view/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(response){
        
        $('#pfLoaInfoModal').modal('show');

        let tbody = '';
        
        $.each(response, function(index, item){
          
          tbody += '<tr><td>'+ item.item_description +'</td><td>'+ item.status + '</td><td>' + item.date_performed +' '+ item.time_performed +'</td><td>'+ item.physician_fname +' '+ item.physician_mname + ' ' + item.physician_lname +'</td><td>'+ item.reschedule_on +'</td><td>'+ item.reason_cancellation +'</td></tr>';

        $('#pf-loa-no').html(item.loa_no);
        });
        $('#pf-tbody').html(tbody);
      
      }
    });
  }

  const viewPerformedLoaConsult = (loa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/performed-consult-loa-info/view/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(response){
      
        $('#consultLoaInfoModal').modal('show');

        let tbody = '';
        
        tbody += '<tr><td>'+ response.request_type +'</td><td>'+ response.status + '</td><td>' + response.date_performed + ' '+ response.time_performed +'</td><td>'+ response.physician_fname +' ' + response.physician_mname + ' ' + response.physician_lname +'</td></tr>';

        $('#pf-consult-tbody').html(tbody);
        $('#pf-consult-loa-no').html(response.loa_no);

      }

    });
  }
  //Trappings for Date Picker
  const dateValidity = () => {
    const expire_on = document.querySelectorAll('.expired-on'); 
    const date_performed = document.querySelectorAll('.input-date');
    const current_date = new Date();

    for (let i = 0; i < date_performed.length; i++){
      const date_performance = new Date(date_performed[i].value);
      const expired_date = new Date(expire_on[i].value);
      const min_valid_date = new Date(current_date.getTime() - 1 * 24 * 60 * 60 * 1000); // 1 day before current_date
      const max_valid_date = new Date(expired_date.getTime() + 14 * 24 * 60 * 60 * 1000); // 14 days after expired_date

      if(date_performance < min_valid_date || date_performance > max_valid_date){
        swal({
          title: 'Invalid Date',
          // text: 'It must be between '+ min_valid_date.toDateString() +' and '+ max_valid_date.toDateString(),
          text: 'It must be between '+ current_date.toDateString() +' and '+ max_valid_date.toDateString(),
          showConfirmButton: true,
          type: 'error'
        });
        date_performed[i].value = '';
        flatpickr(date_performed[i]).close();
        return;
      }
    }
  }
  //end
</script>