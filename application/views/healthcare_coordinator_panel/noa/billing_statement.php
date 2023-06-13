<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2" style="color:red"><?php echo $status; ?></h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Billing Statement</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/for_payment" role="tab">
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
                <thead class="" style="background-color:#00538C">
                  <tr>
                    <th style="color: white">Billing No.</th>
                    <th style="color: white"></th>
                    <th style="color: white">HEALTHCARE PROVIDER</th>
                    <th style="color: white">STATUS</th>
                    <th style="color: white">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>







<script>
  const baseUrl = "<?php echo base_url(); ?>";

  $(document).ready(function() {

    let matchedTable = $('#matchedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source  
      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/requests-list/payable/fetch`,
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