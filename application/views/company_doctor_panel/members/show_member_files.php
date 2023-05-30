<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Diagnosis/Operation Files</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
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
              <a href="<?php echo base_url(); ?>company-doctor/member/view/files/" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                  <strong class="ls-2" style="vertical-align:middle">
                      <i class="mdi mdi-arrow-left-bold"></i> Go Back
                  </strong>
              </a>
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

          <!-- <div class="row col-md-1" style="justify-content:center">
            <div class="ps-3 pt-3 pb-4">
              <?php foreach($file as $files) : ?>
                <div class="col-md-1">
                  <a href="#" class="btn btn-primary btn-lg bg-light border border-light text-info">
                      <img src="<?php echo base_url(); ?>assets/images/pngegg.png" alt="Button Image" class="img-fluid">
                    <u><span class="fs-6" id="file-name" name="file-name[]"><?php echo $files['final_diagnosis_file']; ?></span></u>
                  </a>
                </div>
               
                <?php endforeach; ?>
            </div>
          </div> -->
          <div class="ps-4 pe-4 pt-4" style="justify-content:center">
            <table class="table table-bordered table-sm">
              <th class="fw-bold">File Name</th>
              <th class="fw-bold">Added On</th>
              <th class="fw-bold">View File</th>
              <tbody>
              <?php foreach($file as $files) : 
                if(!empty($files['final_diagnosis_file'])) : ?>
                <tr>
                  <td class=""><?php echo $files['final_diagnosis_file']; ?></td>
                  <td class=""><?php echo $files['billed_on']; ?></td>
                  <td class="">view</td>
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
  </div>
</div>

  <script>

  </script>