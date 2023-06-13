<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">BILLED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Hc Provider Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">Billed</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>hc-provider-accounting/noa-requests/pending" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>hc-provider-accounting/noa-requests/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>hc-provider-accounting/noa-requests/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>hc-provider-accounting/noa-requests/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">BILLED</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="< echo base_url(); ?>hc-provider-accounting/noa-requests/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li> -->       
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">  
              <?php include 'view_billed_noa_details.php'; ?>
              <table id="billedNoaTable" class="table table-striped" style="width:100%">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">NOA NO.</th>
                    <th class="fw-bold" style="color: white">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white">NAME OF HOSPITAL</th>
                    <th class="fw-bold" style="color: white">DATE OF ADMISSION</th>
                    <th class="fw-bold" style="color: white">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">STATUS</th>
                    <th class="fw-bold" style="color: white">ACTION</th>
                  </tr>
                </thead>
                <tbody>
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
    const baseUrl = "<?php echo base_url(); ?>";
    $(document).ready(function() {

        $('#billedNoaTable').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}hc-provider-accounting/noa-requests/billed/fetch`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: {
            'token': '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        //Set column definition initialisation properties.
        columnDefs: [{
            "targets": [5, 6], // numbering column
            "orderable": false, //set not orderable
        }, ],
        responsive: true,
        fixedHeader: true,
        });

    });

    function viewNoaInfo(req_id) {
        $.ajax({
            url: `${baseUrl}hc-provider-accounting/noa-requests/view/${req_id}`,
            type: "GET",
            success: function(response) {
                const res = JSON.parse(response);
                const base_url = window.location.origin;
                const {
                status,
                token,
                noa_no,
                approved_by,
                approved_on,
                member_mbl,
                remaining_mbl,
                first_name,
                middle_name,
                last_name,
                suffix,
                date_of_birth,
                age,
                hospital_name,
                health_card_no,
                requesting_company,
                admission_date,
                chief_complaint,
                work_related,
                request_date,
                req_status,
                percentage
                } = res;

                $("#viewNoaModal").modal("show");

                $('#noa-no').html(noa_no);
                $('#noa-status').html('<strong class="text-cyan">[' + req_status + ']</strong>');
                $('#approved-by').html(approved_by);
                $('#approved-on').html(approved_on);
                $('#member-mbl').html(member_mbl);
                $('#remaining-mbl').html(remaining_mbl);
                $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
                $('#date-of-birth').html(date_of_birth);
                $('#age').html(age);
                $('#hospital-name').html(hospital_name);
                $('#admission-date').html(admission_date);
                $('#chief-complaint').html(chief_complaint);
                $('#request-date').html(request_date);
                if(work_related == 'Yes'){ 
              if(percentage == ''){
                wpercent = '100% W-R';
                nwpercent = '';
              }else{
                wpercent = percentage+'%  W-R';
                result = 100 - parseFloat(percentage);
                if(percentage == '100'){
                  nwpercent = '';
                }else{
                  nwpercent = result+'% Non W-R';
                }
                
              }	
            }else if(work_related == 'No'){
              if(percentage == ''){
                wpercent = '';
                nwpercent = '100% Non W-R';
              }else{
                nwpercent = percentage+'% Non W-R';
                result = 100 - parseFloat(percentage);
                if(percentage == '100'){
                  wpercent = '';
                }else{
                  wpercent = result+'%  W-R';
                }
              
              }
            }
            $('#percentage').html(wpercent+', '+nwpercent);
            }
        });
    }
    const viewPDFBill = (pdf_bill,noa_no) => {
      $('#viewPDFBillModal').modal('show');
      $('#pdf-noa-no').html(noa_no);

        let pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
        let fileExists = checkFileExists(pdfFile);
        console.log(pdf_bill);
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
</script>
