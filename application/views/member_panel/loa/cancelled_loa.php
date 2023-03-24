
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Requested LOA</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
                Cancelled LOA
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

      <div class="col-lg-12">

          <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
              <a
                class="nav-link"
                href="<?php echo base_url(); ?>member/requested-loa/pending"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                href="<?php echo base_url(); ?>member/requested-loa/approved"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                href="<?php echo base_url(); ?>member/requested-loa/disapproved"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
              >
            </li>
             <li class="nav-item">
              <a
                class="nav-link"
                href="<?php echo base_url(); ?>member/requested-loa/completed"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Completed</span></a
              >
            </li>
            <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>member/requested-loa/cancelled"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Cancelled</span></a
            >
          </li>
          </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="cancelledLoaTable">
                <thead>
                  <tr>
                    <th class="fw-bold">LOA No.</th>
                    <th class="fw-bold">Requested on</th>
                    <th class="fw-bold">Reason</th>
                    <th class="fw-bold">Confirmed on</th>
                    <th class="fw-bold">Confirmed by</th>
                    <th class="fw-bold">Status</th>
                    <th class="fw-bold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php include 'view_loa_details.php'; ?>
      </div>
      <!-- End Row  -->  
      </div>
      <?php include 'view_cancel_reason.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
</div>

<script>
  const baseUrl = `<?php echo base_url(); ?>`;

  $(document).ready(function() {
    $('#cancelledLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}member/requested-loa/cancelled/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [2, 4, 5, 6], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });
  })

  const viewReasonModal = (reason) => {
        $("#viewCancelReasonModal").modal("show");
        $("#reason").val(reason);
  }

  const viewLoaInfo = (loa_id) => {
    $.ajax({
      url: `${baseUrl}member/requested-loa/view/${loa_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          loa_no,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          gender,
          philhealth_no,
          blood_type,
          contact_no,
          home_address,
          city_address,
          email,
          contact_person,
          contact_person_addr,
          contact_person_no,
          healthcare_provider,
          loa_request_type,
          med_services,
          health_card_no,
          requesting_company,
          request_date,
          chief_complaint,
          requesting_physician,
          attending_physician,
          rx_file,
          req_status,
          work_related
        } = res;

        $("#viewLoaModal").modal("show");
        $('#saveImage-btn').hide();
        let rstat = '';
        if(req_status == 'Pending'){
          req_stat = `<strong class="text-warning">[${req_status}]</strong>`;
        }else{
          req_stat = `<strong class="text-cyan">[${req_status}]</strong>`;
        }

        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';

        $('#loa-no').html(loa_no);
        $('#loa-status').html(req_stat);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#gender').html(gender);
        $('#philhealth-no').html(philhealth_no);
        $('#blood-type').html(blood_type);
        $('#contact-no').html(contact_no);
        $('#home-address').html(home_address);
        $('#city-address').html(city_address);
        $('#email').html(email);
        $('#contact-person').html(contact_person);
        $('#contact-person-addr').html(contact_person_addr);
        $('#contact-person-no').html(contact_person_no);
        $('#healthcare-provider').html(healthcare_provider);
        $('#loa-request-type').html(loa_request_type);
        $('#loa-med-services').html(med_serv);
        $('#health-card-no').html(health_card_no);
        $('#requesting-company').html(requesting_company);
        $('#request-date').html(request_date);
        $('#chief-complaint').html(chief_complaint);
        $('#requesting-physician').html(requesting_physician);
        $('#attending-physician').html(at_physician);
        if(work_related != ''){
          $('#work-related-info').removeClass('d-none');
          $('#work-related-val').html(work_related);
        }else{
          $('#work-related-info').addClass('d-none');
          $('#work-related-val').html('');
        }
      }
    });
  }
</script>