<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Incident and Spot Reports</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Member's Files</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
 
  <div class="container-fluid">
    <div class="row">
        <div class="col-6 pb-2">
          <div class="input-group">
            <button onclick="goBack()" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
              <strong class="ls-2" style="vertical-align:middle">
                <i class="mdi mdi-arrow-left-bold"></i> Go Back
              </strong>
            </button>
          </div>
      </div>
    </div>
    <div class="row pt-2">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">
                <span class="fs-5">Member's Fullname : <span class="fw-bold fs-4"><?php echo $member['first_name'].' '. $member['middle_name'].' '.$member['last_name'].' '.$member['suffix'];?></span></span><br>
                <span class="fs-5 pt-1">Business Unit : <span class="fw-bold fs-4"><?php echo $member['business_unit'];?></span></span>
          </div>
            <div class="row">
                <i class="text-danger ps-4">(Click the file to view)</i>

                <div class="ps-3 pe-4 pt-3 pb-4" style="justify-content:center">
                    <table class="table table-bordered table-sm">
                    <th class="fw-bold">#</th>
                    <th class="fw-bold">LOA/NOA #</th>
                    <th class="fw-bold">Spot Report</th>
                    <th class="fw-bold">Incident Report</th>
                    <th class="fw-bold">Added On</th>
                    <tbody>
                  
                    <?php 
                        $number = 1;
                        foreach($file_loa as $files) : 
                        if(!empty($files['spot_report_file'] && $files['incident_report_file'])) : 
                        ?>
                        <tr>
                            <td><?php echo $number++; ?></td>
                            <td><?php echo $files['loa_no'] ?></td>
                            <td><a data-bs-toggle="tooltip" href="JavaScript:void(0)" onclick="viewSpotFile('<?php echo $files['spot_report_file']; ?>')" id="spot-report-file-up"><?php echo $files['spot_report_file']; ?></a></td>
                            <td><a data-bs-toggle="tooltip" href="JavaScript:void(0)" onclick="viewIncidentFile('<?php echo $files['incident_report_file']; ?>')" id="incident-report-file-up"><?php echo $files['incident_report_file']; ?></td>
                            <td><?php echo date('F d, Y', strtotime($files['date_uploaded'])); ?></td>
                        </tr>
                        <?php 
                        endif;
                    endforeach; ?>
                     <?php 
                        foreach($file_noa as $files) : 
                        if(!empty($files['spot_report_file'] && $files['incident_report_file'])) : 
                        ?>
                        <tr>
                            <td><?php echo $number++; ?></td>
                            <td><?php echo $files['noa_no'] ?></td>
                            <td><a data-bs-toggle="tooltip" href="JavaScript:void(0)" onclick="viewSpotFile('<?php echo $files['spot_report_file']; ?>')" id="spot-report-file-up"><?php echo $files['spot_report_file']; ?></a></td>
                            <td><a data-bs-toggle="tooltip" href="JavaScript:void(0)" onclick="viewIncidentFile('<?php echo $files['incident_report_file']; ?>')" id="incident-report-file-up"><?php echo $files['incident_report_file']; ?></td>
                            <td><?php echo date('F d, Y', strtotime($files['date_uploaded'])); ?></td>
                        </tr>
                        <?php 
                        endif;
                    endforeach; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <?php include 'view_pdf_bill_modal.php'; ?>
    </div>
  </div>
</div>

  <script>
    function goBack() {
      window.history.back();
    }

    const baseUrl = '<?php echo base_url();?>';

    const viewImage = (path) => {
        let item = [{
        src: path, // path to image
        title: 'Medical Abstract' // If you skip it, there will display the original image name
        }];
        // define options (if needed)
        let options = {
        index: 0 // this option means you will start at first image
        };
        // Initialize the plugin
        let photoviewer = new PhotoViewer(item, options);
    }

    const viewPDF = (pdf) => {
      $('#viewPDFBillModal').modal('show');
      $('#pdf-name').html('Medical Abstract File');

        let pdfFile = `${baseUrl}uploads/medical_abstract/${pdf}`;
        let fileExists = checkFileExists(pdfFile);

        if(fileExists){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', pdfFile, true);
        xhr.responseType = 'blob';

        xhr.onload = function(e) {
            if (this.status == 200) {
            let blob = this.response;
            let reader = new FileReader();

            reader.onload = function(event) {
                let dataURL = event.target.result;
                let iframe = document.querySelector('#pdf-viewer');
                iframe.src = dataURL;
            };
            reader.readAsDataURL(blob);
            }
        };
        xhr.send();
        }
    }

    const checkFileExists = (fileUrl) => {
        let xhr = new XMLHttpRequest();
        xhr.open('HEAD', fileUrl, false);
        xhr.send();

        return xhr.status == "200" ? true: false;
    }

    const viewSpotFile = (anchorText) => {

      $('#viewPDFBillModal').modal('show');
      $('#pdf-name').html('Uploaded Spot Report');

      let pdfFile = `${baseUrl}uploads/spot_reports/${anchorText}`;
      let fileExists = checkFileExists(pdfFile);

      if(fileExists){
      let xhr = new XMLHttpRequest();
      xhr.open('GET', pdfFile, true);
      xhr.responseType = 'blob';

      xhr.onload = function(e) {
          if (this.status == 200) {
          let blob = this.response;
          let reader = new FileReader();

          reader.onload = function(event) {
              let dataURL = event.target.result;
              let iframe = document.querySelector('#pdf-viewer');
              iframe.src = dataURL;
          };
          reader.readAsDataURL(blob);
          }
      };
      xhr.send();
      }
    }

    const viewIncidentFile = (anchorText) => {

      $('#viewPDFBillModal').modal('show');
      $('#pdf-name').html('Uploaded Incident Report');

      let pdfFile = `${baseUrl}uploads/incident_reports/${anchorText}`;
      let fileExists = checkFileExists(pdfFile);

      if(fileExists){
      let xhr = new XMLHttpRequest();
      xhr.open('GET', pdfFile, true);
      xhr.responseType = 'blob';

      xhr.onload = function(e) {
          if (this.status == 200) {
          let blob = this.response;
          let reader = new FileReader();

          reader.onload = function(event) {
              let dataURL = event.target.result;
              let iframe = document.querySelector('#pdf-viewer');
              iframe.src = dataURL;
          };
          reader.readAsDataURL(blob);
          }
      };
      xhr.send();
      }
    }

  </script>