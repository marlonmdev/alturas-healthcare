<!-- Start of Page Wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div  class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
            <h4 class="page-title"><i class="mdi mdi-file-document-box"></i> LEDGER [ <span class="text-info">Paid Bill</span> ]</h4>
            <div class="ms-auto text-end order-first order-sm-last">
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
      <div class="col-md-12">
        <div class="row pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="row pb-4 gap-2">
              <div class="col-md-2 ps-2 pb-2">
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
              <div class="col-md-3 ps-5 pb-2">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text text-info">
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
              <div class="col-md-1">
                <button class="btn btn-danger rounded-pill" type="button" id="print-btn" onclick="printpaidledger()"><i class="mdi mdi-printer"></i> Print</button>
              </div>
            </div>
            
            <div class="card shadow">
                <div class="table-responsive pt-2 ps-4 pe-5 pb-2">
                  <table class="table table-hover table-sm table-responsive table-stripped"  id="ledgertbody">
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">No.</th>
                        <th class="fw-bold">DATE PAID</th>
                        <th class="fw-bold">MEMBER NAME</th>
                        <th class="fw-bold">BUSINESS UNIT</th>
                        <th class="fw-bold">DEBIT</th>
                        <th class="fw-bold">CREDIT</th>
                        <!-- <th class="fw-bold">BALANCE</th> -->
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                          <td></td>
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
      $("#print-btn").animate({
        width: "100px",
        opacity: 0.9
      }, 1000);

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
      columnDefs: [
            { targets: 4, className: 'text-end' },
            { targets: 5, className: 'text-end' },
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
            let columnIndices = [4, 5]; // Array of column indices to calculate sum
            let sums = [0, 0]; // Array to store the sums for each column

            if ($('#ledgertbody').DataTable().data().length > 0) {
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

    const printpaidledger = () => {
      const years = document.querySelector('#selectedYear').value;
      const months = document.querySelector('#selectedMonth').value;
      const bu_filters = document.querySelector('#bu-filter').value;

      if(years == ''){
        year = 'none';
      }else{
        year = years;
      }
      if(months == ''){
        month = 'none';
      }else{
        month = months;
      }
      if(bu_filters == ''){
        bu_filter = 'none';
      }else{
        bu_filter = bu_filters;
      }

      const base_url = `${baseUrl}`;
        window.open(base_url + "printledger/ledgerpaid/" +  btoa(month) + "/" + btoa(year) + "/" + btoa(bu_filter), '_blank');
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