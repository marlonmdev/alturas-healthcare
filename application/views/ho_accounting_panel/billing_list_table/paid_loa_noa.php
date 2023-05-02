<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Billing</h4>
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">
                  Paid
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
              href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/billed-loa-noa"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/paid-loa-noa"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Paid</span></a
            >
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-dark text-white">
                    <i class="mdi mdi-filter"></i>
                    </span>
                </div>
                <select class="form-select fw-bold" name="matched-hospital-filter" id="matched-hospital-filter">
                        <option value="">Select Hospital</option>
                        <?php foreach($hc_provider as $option) : ?>
                        <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                        <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="matchedLoaTable">
                <thead class="fs-5">
                  <tr>
                    <th class="fw-bold"></th>
                    <th class="fw-bold">Healthcare Provider</th>
                    <th class="fw-bold">Status</th>
                    <th class="fw-bold">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- End Row  -->  
      </div>
      <?php include 'view_check_details_model.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = "<?php echo base_url(); ?>";

  $(document).ready(function() {

    let matchedTable = $('#matchedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source  
      ajax: {
        url: `${baseUrl}head-office-accounting/bill/paid-list/fetch`,
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
  
  const viewPaymentDetails = (details_no) => {
      $.ajax({
        url: `${baseUrl}head-office-accounting/bill/payment-details/fetch`,
        type: 'GET',
        data: {
          'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
          'details_no' : details_no,
        },
        success : function(check) {
          $('#viewCheckDetailsModal').modal('show');

          let checktbody = '';
          
        $.each(check, function(index, item){
          checktbody += '<tr class="border-secondary border-2 border-0 border-top border-bottom">'+
                                '<td class="text-center fw-bold ls-2">Account Number</td>'+
                                '<td class="text-center fw-bold ls-2">'+item.acc_number+'</td>'+
                           '</tr>'+
                           '<tr class="border-secondary border-2 border-0 border-top border-bottom">'+
                                '<td class="text-center fw-bold ls-2">Account Name</td>'+
                                '<td class="text-center fw-bold ls-2">'+item.acc_name+'</td>'+
                           '</tr>'+
                           '<tr class="border-secondary border-2 border-0 border-top border-bottom">'+
                                '<td class="text-center fw-bold ls-2">Check Number</td>'+
                                '<td class="text-center fw-bold ls-2">'+item.check_num+'</td>'+
                           '</tr>'+
                           '<tr class="border-secondary border-2 border-0 border-top border-bottom">'+
                                '<td class="text-center fw-bold ls-2">Check Date</td>'+
                                '<td class="text-center fw-bold ls-2">'+item.check_date+'</td>'+
                           '</tr>'+
                           '<tr class="border-secondary border-2 border-0 border-top border-bottom">'+
                                '<td class="text-center fw-bold ls-2">Bank </td>'+
                                '<td class="text-center fw-bold ls-2">'+item.bank+'</td>'+
                           '</tr>'+
                           '<tr class="border-secondary border-2 border-0 border-top border-bottom">'+
                                '<td class="text-center fw-bold ls-2">Amount Paid</td>'+
                                '<td class="text-center fw-bold ls-2">'+item.amount_paid+'</td>'+
                           '</tr>';

        });
        $('#check-table').html(checktbody);
        }

      });
    }


</script>