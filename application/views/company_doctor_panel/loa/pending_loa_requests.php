
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">LOA Requests</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">
                Pending LOA
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
              class="nav-link active"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
            <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/closed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
            >
          </li>
        </ul>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="pendingLoaTable">
                <thead>
                  <tr>
                    <th class="fw-bold">LOA No.</th>
                    <th class="fw-bold">Name</th>
                    <th class="fw-bold">LOA Type</th>
                    <th class="fw-bold">Healthcare Provider</th>
                    <th class="fw-bold">RX File</th>
                    <th class="fw-bold">Request Date</th>
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
        <?php include 'loa_disapprove_reason.php'; ?>
       <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?= base_url() ?>`;
  $(document).ready(function() {

    $('#pendingLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}company-doctor/loa/requests-list/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [4, 6, 7], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

  });

  function viewImage(path) {
    let item = [{
      src: path, // path to image
      title: 'Attached RX File' // If you skip it, there will display the original image name
    }];
    // define options (if needed)
    let options = {
      index: 0 // this option means you will start at first image
    };
    // Initialize the plugin
    let photoviewer = new PhotoViewer(item, options);
  }

  function viewPendingLoaInfo(req_id) {
    $.ajax({
      url: `${baseUrl}company-doctor/loa/requests-list/view/${req_id}`,
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
          member_mbl,
          remaining_mbl
        } = res;

        $("#viewLoaModal").modal("show");
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
        }
        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';
        $('#loa-no').html(loa_no);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
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
      }
    });
  }

  // $.confirm is a syntax of Jquery Confirm plugin
  function approveLoaRequest(loa_id) {
    const nextPage = `${baseUrl}company-doctor/loa/requests-list/approved`;
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to Approve LOA Request?',
      type: 'green',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-green',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}company-doctor/loa/requests-list/approve/${loa_id}`,
              data: {
                loa_id: loa_id
              },
              dataType: "json",
              success: function(response) {
                const {
                  token,
                  status,
                  message
                } = response;
                if (status === 'success') {
                  swal({
                    title: 'Success',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'success'
                  });

                  setTimeout(function() {
                    window.location.href = nextPage;
                  }, 3200);

                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                }
              }
            });
          }
        },
        cancel: {
          btnClass: 'btn-dark',
          action: function() {
            // close dialog
          }
        },

      }
    });
  }

  function disapproveLoaRequest(loa_id) {
    $('#loaDisapprovedReasonModal').modal('show');
    $('#disapprove-reason-error').html('');
    $('#disapprove-reason').removeClass('is-invalid');
    $("#loaDisapproveForm").attr("action", `${baseUrl}company-doctor/loa/requests-list/disapprove/${loa_id}`);
    $('#loa-id').val(loa_id);
  }

  $(document).ready(function() {

    $('#loaDisapproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/loa/requests-list/disapproved`;
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            disapprove_reason_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (disapprove_reason_error !== '') {
                $('#disapprove-reason-error').html(disapprove_reason_error);
                $('#disapprove-reason').addClass('is-invalid');
              } else {
                $('#disapprove-reason-error').html('');
                $('#disapprove-reason').removeClass('is-invalid');
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
              $('#loaDisapprovedReasonModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });
  });
</script>