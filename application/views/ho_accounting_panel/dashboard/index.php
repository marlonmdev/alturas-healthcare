      <!-- Start of Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"><i class="mdi mdi-view-dashboard"></i> Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Dashboard
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
          <div class="row ps-3">
            <div class="row">
              <div class="col-lg-3">
                <div class="card-box bg-cyan">
                  <div class="inner">
                    <h3><?php echo $billed_count ?></h3>
                    <p>Billed</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-check" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/bill/billing-list/billed-loa-noa" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card-box bg-green">
                  <div class="inner">
                    <h3><?php echo $payment_count ?></h3>
                    <p>Payment History</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-document" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/payment_history" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card-box bg-orange">
                  <div class="inner">
                    <h3><?php echo $bu_charge_count ?></h3>
                    <p>Business Unit Charging</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/charging/business-unit" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="card-box bg-red">
                  <div class="inner">
                    <h3><i class="mdi mdi-view-dashboard"></i></h3>
                    <p>Ledger</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/ledger" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            </div>
            <div class="row pt-4">
              <div class="col-6">
                  <div id="chartContainer" style="height: 370px; width:auto;"></div>
              </div>
              <div class="col-6">
                  <div id="chartDNContainer" style="height: 370px; width:auto;"></div>
              </div>
            </div>
          </div>
        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>
    <script>
      window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
          animationEnabled: true,
          theme: "light2",
          title:{
            text: "Line Chart"
          },
          data: [{        
            type: "line",
                indexLabelFontSize: 16,
            dataPoints: [
              { y: 450 },
              { y: 414},
              { y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
              { y: 460 },
              { y: 450 },
              { y: 500 },
              { y: 480 },
              { y: 480 },
              { y: 410 , indexLabel: "\u2193 lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
              { y: 500 },
              { y: 480 },
              { y: 510 }
            ]
          }]
        });

        var chartDN = new CanvasJS.Chart("chartDNContainer", {
              theme: "light2",
              exportFileName: "Doughnut Chart",
              exportEnabled: true,
              animationEnabled: true,
              title:{
                text: "Monthly Expense"
              },
              legend:{
                cursor: "pointer",
                itemclick: explodePie
              },
              data: [{
                type: "doughnut",
                innerRadius: 90,
                showInLegend: true,
                toolTipContent: "<b>{name}</b>: ${y} (#percent%)",
                indexLabel: "{name} - #percent%",
                dataPoints: [
                  { y: 450, name: "Food" },
                  { y: 120, name: "Insurance" },
                  { y: 300, name: "Travelling" },
                  { y: 800, name: "Housing" },
                  { y: 150, name: "Education" },
                  { y: 150, name: "Shopping"},
                  { y: 250, name: "Others" }
                ]
              }]
            });
        chart.render();
        chartDN.render();

        function explodePie (e) {
          if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
          } else {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
          }
          e.chart.render();
        }

      }

      
      
    </script>