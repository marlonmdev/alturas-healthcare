      <!-- Start of Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Dashboard</h4>
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
          <div class="row">
            <div class="row shadow">
              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-cyan">
                  <div class="inner">
                    <h3><?php echo $billed_count; ?></h3>
                    <p>Billed</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-document" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/bill/billing-list/billed-loa-noa" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-green">
                  <div class="inner">
                    <h3><?php echo $payment_count; ?></h3>
                    <p>Payment History</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-document" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/payment_history" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-orange">
                  <div class="inner">
                    <h3><?php echo $loa_count; ?></h3>
                    <p>Approved LOA Requests</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-document" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/loa-request-list/loa-approved" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              
              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-red">
                  <div class="inner">
                    <h3><?php echo $noa_count; ?></h3>
                    <p>Approved NOA Requests</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/noa-request-list/noa-approved" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            </div>
           
            <div class="row pt-3 pb-3">
              <div class="col-lg-6 col-sm-6 pb-2 shadow"><br>
                <div class="pt-3" id="chartContainer" style="height: 370px; width: 100%;"></div>
              </div>
              
              <div class="col-lg-6 col-sm-6 shadow"><br>
                <div class="pt-3" id="chartBarContainer" style="height: 370px; width: 100%;"></div>
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
      <?php
        // foreach($paid_count as $bill){

            $dataPoints = array( 
              // array("label"=>,"sg", "y"=>23.3),
              array("label"=>$hp_name, "y"=>$paid_count),
              array("label"=>"IE", "y"=>8.47),
              array("label"=>"Safari", "y"=>6.08),
              array("label"=>"Edge", "y"=>4.29),
              array("label"=>"Others", "y"=>4.59)
            );
            ?>
     

      window.onload = function() {
      
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
          text: "Paid Bill in every Healthcare Provider"
        },
        subtitles: [{
          text: "   "
        }],
        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();

      var chartCol = new CanvasJS.Chart("chartBarContainer", {
      theme: "light1", // "light2", "dark1", "dark2"
      animationEnabled: false, // change to true		
      title:{
        text: "Basic Column Chart"
      },
      data: [
      {
          // Change type to "bar", "area", "spline", "pie",etc.
          type: "column",
          dataPoints: [
            { label: "apple",  y: 10  },
            { label: "orange", y: 15  },
            { label: "banana", y: 25  },
            { label: "mango",  y: 30  },
            { label: "grape",  y: 28  }
          ]
        }
        ]
      });
      chartCol.render();
        
      }
</script>