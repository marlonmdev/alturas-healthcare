<!-- Start of Page Wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
              <h4 class="page-title"><i class="mdi mdi-file-document-box"></i> LEDGER [ <span class="text-info">Max Benefit Limit</span> ]</h4>
              <div class="ms-auto text-end order-first order-sm-last">
                  <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item">Head Office Accounting</li>
                          <li class="breadcrumb-item active" aria-current="page">
                              MBL
                          </li>
                      </ol>
                  </nav>
              </div>
          </div>
        </div>
    </div>
    <hr>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="row pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="row pb-2 gap-2">
              <div class="col-md-2 ps-5 pb-4">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text text-info">
                          <i class="mdi mdi-calendar"></i>
                          </span>
                      </div>
                      <select class="form-select fw-bold" id="selectedYear" name="selectedYear">
                          <option value="">Select Year</option>
                          
                      </select>

                  </div>
              </div>
              <div class="col-md-5 ps-5 pb-2">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text text-info">
                          <i class="mdi mdi-filter"></i>
                          </span>
                      </div>
                      <select class="form-select fw-bold" name="bu-filter" id="bu-filter">
                          <option value="">Select Business Units</option>
                          <?php
                              // Sort the business units alphabetically
                              $sorted_bu = array_column($bu, 'business_unit');
                              asort($sorted_bu);
                              
                              foreach($sorted_bu as $bu) :
                          ?>
                          <option value="<?php echo $bu; ?>"><?php echo $bu; ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
              </div>
              <div class="col-md-2">
                <button class="btn btn-danger rounded-pill" type="button" id="print-btn" onclick="printMBLledger()"><i class="mdi mdi-printer"></i> Print</button>
              </div>
            </div>
            <div class="card shadow">
                <div class="table-responsive pt-2 ps-4 pe-4 pb-2">
                  <table class="table table-lg table-hover table-responsive table-stripped"  id="ledgermbl">
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">NO.</th>
                        <th class="fw-bold">HEALTHCARD NO.</th>
                        <th class="fw-bold">MEMBER NAME</th>
                        <th class="fw-bold">BUSINESS UNIT</th>
                        <th class="fw-bold">ACTION</th>
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
    <!-- End Container fluid  -->
    </div>
    <?php include 'view_mbl_modal.php'; ?>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->

<script>
     const baseUrl = "<?php echo base_url(); ?>";
 
    $(document).ready(function(){
      let ledgerTable = $('#ledgermbl').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/ledger/mbl/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.year = $('#selectedYear').val();
            data.bu_filter = $('#bu-filter').val();
        },
      },
      //Set column definition initialisation properties.
      columnDefs: [
          
        ],
      data: [],  // Empty data array
      deferRender: true,  // Enable deferred rendering
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

      MblLedgerTable = $('#mbltablemodal').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}head-office-accounting/ledger/mbl-details/fetch`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: function(data) {
                data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
                data.emp_id =  $('#mbl-emp-id').val();
                data.filteredYear = $('#mbl-filtered-year').val();
            },
        },
        //Set column definition initialisation properties.
        columnDefs: [],
        data: [],  // Empty data array
        deferRender: true,  // Enable deferred rendering
        info: false,
        paging: false,
        filter: false,
        lengthChange: false,
        responsive: true,
        fixedHeader: true,
      });
        
    $('#selectedYear').change(function(){
      ledgerTable.draw();
    });

    $('#bu-filter').change(function(){
      ledgerTable.draw();
    });

    $("#print-btn").animate({
      width: "100px",
      opacity: 0.9
    }, 1000);
    
    });
    
    const viewMBLDetails = (emp_id,filteredYear,fullname) => {
      $('#viewMBLModal').modal('show');
      $('#mbl-emp-id').val(emp_id);
      $('#mbl-filtered-year').val(filteredYear);
      $('#mbl-fullname').html(fullname);
      if(filteredYear != ''){
        $('#year-div').show();
      }else{
        $('#year-div').hide();
      }
      $('#mbl-year').html(filteredYear);
    }

    const printMBLledger = () => {
      const years = document.querySelector('#selectedYear').value;
      const bu_filters = document.querySelector('#bu-filter').value;

      if(years == ''){
        year = 'none';
      }else{
        year = years;
      }
      if(bu_filters == ''){
        bu_filter = 'none';
      }else{
        bu_filter = bu_filters;
      }

      const base_url = `${baseUrl}`;
        window.open(base_url + "printledger/ledgermbl/" + btoa(year) + "/" + btoa(bu_filter), '_blank');
    }

      var currentYear = new Date().getFullYear();
      var selectElement = document.getElementById('selectedYear');
      var startYear = 2022;
    for (var year = currentYear; year >= startYear; year--) {
      var option = document.createElement('option');
      option.value = year;
      option.text = year;
      selectElement.appendChild(option);
    }

   


</script>