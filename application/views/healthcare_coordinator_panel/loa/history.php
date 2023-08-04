<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">History of Billing</li>
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
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link " href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">BILLING STATEMENT</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/history" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">HISTORY</span>
            </a>
          </li>
        </ul>

        <div class="row">
          <div class="col-lg-6 d-flex justify-content-end align-items-center">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
              </div>
              <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter">
                <option value="">Select Hospital</option>
                <?php foreach($hcproviders as $option) : ?>
                  <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-lg-6 d-flex justify-content-start align-items-center">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
              </div>
              <input type="date" class="form-control" name="start-date" id="start-date" placeholder="Start Date" disabled>
              <div class="input-group-append">
                <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
              </div>
              <input type="date" class="form-control" name="end-date" id="end-date" placeholder="End Date" disabled>
            </div>
          </div>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="historytable">
                <thead class="fs-6" style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">ACCOUNT #</th>
                    <th class="fw-bold" style="color: white;">CHEQUE NUMBER</th>
                    <th class="fw-bold" style="color: white;">CHEQUE DATE</th>
                    <th class="fw-bold" style="color: white;">BANK</th>
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


<script>
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {
    const startDateInput = $('#start-date');
    const endDateInput = $('#end-date');

    function toggleDateInputs(disabled) {
      startDateInput.prop('disabled', disabled);
      endDateInput.prop('disabled', disabled);
    }
    toggleDateInputs(true);

    let table = $('#historytable').DataTable({
      processing: true,
      serverSide: true,
      order: [],
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/history`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#hospital-filter').val();
          data.endDate = $('#end-date').val();
          data.startDate = $('#start-date').val();
        }
      },
      columnDefs: [{
        "targets": [], // numbering column
        "orderable": false, //set not orderable
      }],
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    function filterByDateRange() {
      let startDate = $('#start-date').val();
      let endDate = $('#end-date').val();
      table.columns(3).search(startDate + ' - ' + endDate).draw();
    }

    function filterByDateRange() {
      let startDate = startDateInput.val();
      let endDate = endDateInput.val();
      table.columns(3).search(startDate + ' - ' + endDate).draw();
    }

    $('#hospital-filter').change(function() {
      let selectedHospital = $(this).val();
      if (selectedHospital !== '') {
        toggleDateInputs(false); // Enable date inputs
        filterByDateRange();
      } else {
        toggleDateInputs(true); // Disable date inputs
        table.columns(3).search('').draw(); // Clear the date filter
      }
    });

    $('#start-date, #end-date').change(function() {
      filterByDateRange();
    });

  });
</script>