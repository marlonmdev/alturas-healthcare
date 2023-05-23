<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">LEDGER</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">History of Charges</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="ledgertable">
                <thead class="fs-6">
                  <tr>
                    <th style="background-color: #00538C; color: white;">NAME OF PATIENT</th>
                    <th style="background-color: #00538C; color: white;">TYPE OF EMPLOYMENT</th>
                    <th style="background-color: #00538C; color: white;">STATUS</th>
                    <th style="background-color: #00538C; color: white;">BUSINESS UNIT</th>
                    <th style="background-color: #00538C; color: white;">DEPARTMENT</th>
                    <th style="background-color: #00538C; color: white;">ACTION</th>
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
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function () {
    $('#ledgertable').DataTable({ 
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa_controller/fetch_datatable`,
        type: "POST",
        data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>' }
      },

      columnDefs: [{ 
        // "targets": [5],
        "orderable": false,
      },
      ],
      data: [],
      deferRender: true,
      info: false,
      paging: false,
      // filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });      
  });
</script>









