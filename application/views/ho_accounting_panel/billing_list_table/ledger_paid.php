<!-- Start of Page Wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title"><i class="mdi mdi-file-document-box"></i> LEDGER [Paid Bill]</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                       Paid bill
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
      <div class="col-lg-12">
        <div class="row pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="row pb-2 gap-2">
              <div class="col-lg-2 ps-5 pb-4">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text bg-info text-white">
                          <i class="mdi mdi-calendar"></i>
                          </span>
                      </div>
                      <select class="form-select fw-bold" id="selectedYear" name="selectedYear">
                          <option value="">Select Year</option>
                          <?php
                          $displayedYears = array(); // Array to store already displayed years

                          foreach ($paid as $date) :
                              $year = date('Y', strtotime($date['date_add']));

                              // Check if the year has already been displayed
                              if (!in_array($year, $displayedYears)) {
                                  array_push($displayedYears, $year); // Add the year to the displayed years array

                                  // Display the option with the year
                                  ?>
                                  <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                  <?php
                              }
                          endforeach;
                          ?>
                      </select>

                  </div>
              </div>
              <div class="col-lg-2 ps-5 pb-2">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text bg-info text-white">
                          <i class="mdi mdi-calendar"></i>
                          </span>
                      </div>
                      <select class="form-select fw-bold" id="selectedMonth" name="selectedMonth">
                          <option value="">Select Month</option>
                          <option value="01">January</option>
                          <option value="02">February</option>
                          <option value="03">March</option>
                          <option value="04">April</option>
                          <option value="05">May</option>
                          <option value="06">June</option>
                          <option value="07">July</option>
                          <option value="08">August</option>
                          <option value="09">September</option>
                          <option value="10">October</option>
                          <option value="11">November</option>
                          <option value="12">December</option>
                      </select>

                  </div>
              </div>
              <div class="col-lg-4 ps-5 pb-2">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text bg-info text-white">
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
            </div>
            <div class="card shadow" style="background-color:">
                <div class="table-responsive pt-2">
                  <table class="table table-hover table-responsive table-stripped"  id="ledgertbody">
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">DATE PAID</th>
                        <th class="fw-bold">MEMBER NAME</th>
                        <th class="fw-bold">BUSINESS UNIT</th>
                        <th class="fw-bold">DEBIT</th>
                        <th class="fw-bold">CREDIT</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                          <td></td>
                          <td></td>
                          <td class="fw-bold">TOTALS</td>
                          <td class="fw-bold">&#8369; <span id="total-debit"></span></td>
                          <td class="fw-bold">&#8369; <span id="total-credit"></span></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
      </div>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>

<!-- End Wrapper -->

<script>
     const baseUrl = "<?php echo base_url(); ?>";

    $(document).ready(function(){
      let ledgerTable = $('#ledgertbody').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/ledger/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.year = $('#selectedYear').val();
            data.bu_filter = $('#bu-filter').val();
            data.month = $('#selectedMonth').val();
        },
      },
      //Set column definition initialisation properties.
      columnDefs: [{
        "orderable": false, //set not orderable
      }, ],
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

    $('#selectedMonth').change(function(){
      ledgerTable.draw();
    });

    ledgerTable.on('draw.dt', function() {
            let columnIndices = [3, 4]; // Array of column indices to calculate sum
            let sums = [0, 0]; // Array to store the sums for each column

            if ($('#ledgertbody').DataTable().data().length > 0) {
                // The table is not empty
                ledgerTable.rows().nodes().each(function(index, row) {
                let rowData = ledgerTable.row(row).data();

                columnIndices.forEach(function(columnIdx, idx) {
                    let columnValue = rowData[columnIdx];
                    let pattern = /-?[\d,]+(\.\d+)?/g;
                    let matches = columnValue.match(pattern);

                    if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(/,/g, '');
                    let floatValue = parseFloat(numberString);
                    sums[idx] += floatValue;
                    }
                });
                });
            }

            let sumColumn1 = sums[0];
            let sumColumn2 = sums[1];

            $('#total-debit').html(sumColumn1.toLocaleString('PHP', { minimumFractionDigits: 2 }));
            $('#total-credit').html(sumColumn2.toLocaleString('PHP', { minimumFractionDigits: 2 }));
        });

    });


</script>