<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <!-- <h4 class="page-title ls-2">For Payment LOA</h4> -->
        <div class="ms-auto text-end">
         <!--  <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Billing Statement</li>
            </ol>
          </nav> -->
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FOR PAYMENT</span>
            </a>
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="matched-hospital-filter" id="matched-hospital-filter">
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
              <table class="table table-hover table-responsive" id="matchedLoaTable">
                <thead class="fs-6" style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">PAYMENT #</th>
                    <th class="fw-bold" style="color: white;">CONSOLIDATED BILLING</th>
                    <th class="fw-bold" style="color: white;">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_completed_loa_details.php'; ?>
      </div>
    </div>
    <?php include 'performed_loa_info_modal.php'; ?>
  </div>
  <?php include 'view_performed_consult_loa.php'; ?>
</div>
<?php include 'view_charging.php'; ?>






<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {
    let matchedTable = $('#matchedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      // Load data for the table's content from an Ajax source  
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/requests-list/for-charging/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#matched-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [], // numbering column
        "orderable": false, //set not orderable
      }, ],
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    $('#matched-hospital-filter').change(function(){
      matchedTable.draw();
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
        link.download = `loa_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }

  function viewCompletedLoaInfo(req_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/completed/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          loa_no,
          member_mbl,
          remaining_mbl,
          work_related,
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
          approved_by,
          approved_on
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
          case 'Closed':
            $('#loa-status').html(`<strong class="text-info">[${req_status}]</strong>`);
            break;
        }
        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';
        $('#loa-no').html(loa_no);
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
        $('#work-related').html(work_related);
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

  
  const viewPerformedLoaInfo = (loa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/performed-loa-info/view/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(response){
        
        $('#pfLoaInfoModal').modal('show');

        let tbody = '';
        
        $.each(response, function(index, item){
          
          tbody += '<tr><td>'+ item.item_description +'</td><td>'+ item.status + '</td><td>' + item.date_performed +' '+ item.time_performed +'</td><td>'+ item.physician_fname +' '+ item.physician_mname + ' ' + item.physician_lname +'</td><td>'+ item.reason_cancellation +'</td></tr>';

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

  const viewLoaCharging = (loa_id) => {
   
    let converted_percent = 0;
    let company_charge = 0;
    let personal_charge = 0;
    let final_company_charge = 0;
    let final_personal_charge = 0;
    let result = 0;

    const company_charge_input = document.querySelector('#m-company-charge');
    const personal_charge_input = document.querySelector('#m-personal-charge');
    const before_remaining_mbl = document.querySelector('#before-remaining-mbl');
    const after_remaining_mbl = document.querySelector('#after-remaining-mbl');
          
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/charging/fetch/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(data){
        $('#viewLoaChargingModal').modal('show');
        if(data){
          cbody = '';

          cbody += '<div class="row"><div class="col-lg-6"><input class="form-control fw-bold" value="'+ data.first_name +' '+data.middle_name+ ' '+data.last_name+' '+data.suffix+'" readonly></div><div class="col-lg-6"><input class="form-control fw-bold" value="'+data.hp_name+'" readonly></div></div>';

          let net_bill = parseFloat(data.net_bill);
          let remaining_mbl = parseFloat(data.remaining_balance);
          before_remaining_mbl.value = remaining_mbl.toLocaleString('PHP', {minimumFractionDigits: 2});

          if(data.work_related == 'Yes'){
            if(data.percentage == ''){
              cbody += '<div class="row"><span class="pt-4 fw-bold text-danger fs-5">100 &percnt;  Work Related</span></div>';

            }else{
              cbody += '<div class="row"><span class="pt-4 fw-bold text-danger fs-5">'+data.percentage+'&percnt;  Work Related</span></div>';
            }

            cbody += '<div class="row"><div class="col-lg-6 pt-3"><label class="fw-bold">Total Net Bill: </label><input class="form-control text-danger" id="m-net-bill" value="'+net_bill.toLocaleString('PHP', {minimumFractionDigits: 2})+'" readonly></div></div>';
         
          }else if(data.work_related == 'No'){
            if(data.percentage == ''){
              cbody += '<div class="row"><span class="pt-4 fw-bold text-danger fs-5">100 &percnt;  Non Work-Related</span></div>';
            }else{
              cbody += '<div class="row"><span class="pt-4 fw-bold text-danger fs-5">'+data.percentage+'&percnt;  Non Work-Related</span></div>';
            }

            cbody += '<div class="row"><div class="col-lg-6 pt-3"><label class="fw-bold">Total Net Bill: </label><input class="form-control text-danger" id="m-net-bill" value="'+net_bill.toLocaleString('PHP', {minimumFractionDigits: 2})+'" readonly></div></div>';

          }

          if(data.work_related == 'Yes'){

            let net_bill = parseFloat(data.net_bill);
            let remaining_mbl = parseFloat(data.remaining_balance);
            let deducted_mbl = 0;

            if(data.percentage == ''){
              company_charge_input.value = net_bill.toLocaleString('PHP', { minimumFractionDigits: 2 });
              personal_charge_input.value = '0';

              if(net_bill >= remaining_mbl){
                after_remaining_mbl.value = 0;
              }else if(net_bill < remaining_mbl){
                deducted_mbl = remaining_mbl - net_bill;
                after_remaining_mbl.value = deducted_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });
              }

            }else if(data.percentage != ''){

              if(net_bill <= remaining_mbl){
                company_charge_input.value = net_bill.toLocaleString('PHP', { minimumFractionDigits: 2 });
                personal_charge_input.value = '0';
                deducted_mbl = remaining_mbl - net_bill;
                after_remaining_mbl.value = deducted_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });

              }else if(net_bill > remaining_mbl){
                converted_percent = data.percentage/100;
                company_charge = parseFloat(converted_percent) * parseFloat(data.net_bill);

                if(company_charge <= remaining_mbl){
                  company_charge_input.value = remaining_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  result = parseFloat(data.remaining_balance) - parseFloat(company_charge);
                  personal_charge = parseFloat(data.net_bill) - parseFloat(company_charge);
                  final_personal_charge = parseFloat(personal_charge) - result;
                  personal_charge_input.value = final_personal_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  after_remaining_mbl.value = 0;

                  // if(final_personal_charge < 0){
                  //   let company_charged = 0;
                  //   company_charged = company_charge + personal_charge;
                  //   company_charge_input.value = company_charged.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  //   personal_charge_input.value = 0;
                  //   after_remaining_mbl.value = remaining_mbl - company_charged;

                  // }else if(final_personal_charge >= 0){
                  //   company_charge_input.value = remaining_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  //   personal_charge_input.value = 0;
                  //   after_remaining_mbl.value = 0;
                  // }

                }else if(company_charge > data.remaining_balance){
                  personal_charge = parseFloat(data.net_bill) - parseFloat(company_charge);
                  company_charge_input.value = company_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  personal_charge_input.value = personal_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  after_remaining_mbl.value = 0;
                }
              }
            }

          }else if(data.work_related == 'No'){

            let net_bill = parseFloat(data.net_bill);
            let remaining_mbl = parseFloat(data.remaining_balance);
            let deducted_mbl = 0;

            if(data.percentage == ''){
             
              if(net_bill <= remaining_mbl){
                company_charge_input.value = net_bill.toLocaleString('PHP', { minimumFractionDigits: 2 });
                personal_charge_input.value = '0';
                deducted_mbl = remaining_mbl - net_bill;
                after_remaining_mbl.value = deducted_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });

              }else if(net_bill > remaining_mbl){
                company_charge_input.value = remaining_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });
                personal_charge = data.net_bill - data.remaining_balance;
                personal_charge_input.value = personal_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                after_remaining_mbl.value = 0;

              }
             
            }else if(data.percentage != ''){

              if(net_bill <= remaining_mbl){
                company_charge_input.value = net_bill.toLocaleString('PHP', { minimumFractionDigits: 2 });
                personal_charge_input.value = '0';
                deducted_mbl = remaining_mbl - net_bill;
                after_remaining_mbl.value = deducted_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });

              }else if(net_bill > remaining_mbl){
              
                converted_percent = data.percentage/100;
                personal_charge = parseFloat(converted_percent) * parseFloat(data.net_bill);
                company_charge = parseFloat(data.net_bill) - parseFloat(personal_charge);

                if(company_charge <= remaining_mbl){
                  company_charge_input.value = remaining_mbl.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  result = remaining_mbl - parseFloat(company_charge);
                  final_personal_charge = parseFloat(personal_charge) - result;
                  personal_charge_input.value = final_personal_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  after_remaining_mbl.value = 0;

                }else if(company_charge > remaining_mbl){
                  company_charge_input.value = company_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  personal_charge_input.value = personal_charge.toLocaleString('PHP', { minimumFractionDigits: 2 });
                  after_remaining_mbl.value = 0;
                }
              }
            }
          }
          $('#matched-emp-id').val(data.emp_id);
          $('#matched-loa-id').val(data.loa_id);
          $('#matched-billed-loa-no').html(data.loa_no);
          $('#matched-loa-status').html(data.status);
          $('#matched-container').html(cbody);
        }
       
      }
    });
  }

  const submitCharging = () => {
    const data = $('#confirmChargingForm').serialize();
      $.ajax({
        url: `${baseUrl}healthcare-coordinator/loa/charging/confirm`,
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(response){
          const { token, status, message } = response;

          if(status = 'success'){
            swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
            });
            window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/for-charging';
          }else{
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
</script>