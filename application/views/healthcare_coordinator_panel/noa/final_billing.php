<div class="page-wrapper">
  <!-- <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Final Billing (Inpatient)</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Final Billing</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div> -->


  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/for_payment" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">HISTORY</span>
            </a>
          </li>
        </ul>

        <form id="billedTableLoaNoa" method="POST" action="<?php echo base_url(); ?>healthcare-coordinator/noa/matched-bill/submit">
          <input type="hidden" class="form-control" name="initial_status" id="initial_status" value="Payable">
          <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="col-lg-5 ps-5 pb-3 pt-1 pb-4">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
                </div>
                <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter" oninput="enableDate()">
                  <option value="">Select Hospital</option>
                  <?php foreach($hcproviders as $option) : ?>
                    <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

                
            <div class="col-lg-6 offset-1">
              <div class="input-group">
                <div class="input-group-append">
                  <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
                </div>
                <input type="date" class="form-control" name="start-date" id="start-date" oninput="validateDateRange()" placeholder="Start Date" disabled>

                <div class="input-group-append">
                  <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
                </div>
                <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange();enableProceedBtn()" placeholder="End Date" disabled>
              </div>
            </div>
          </div>
          
          <div class="card shadow" style="background-color:">
            <div class="card-body">
              <div class="">
                <table class="table table-hover table-responsive" id="billedLoaTable">
                  <thead style="background-color:#00538C">
                    <tr>
                      <th style="color: white">NOA NO.</th>
                      <th style="color: white">NAME OF PATIENT</th>
                      <th style="color: white">MBL REMAINING BALANCE</th>
                      <th style="color: white">WORK RELATED</th>
                      <th style="color: white">REQUEST DATE</th>
                      <th style="color: white">BILLED DATE</th>
                      <th style="color: white">COMPANY CHARGE</th>
                      <th style="color: white">PERSONAL CHARGE</th>
                      <th style="color: white">HEALTHCARE ADVANCE</th>
                      <th style="color: white">VIEW SOA</th>
                      <th style="color: white">HOSPITAL BILL</th>
                      <th style="color: white">STATUS</th>
                      <th style="color: white">ACTION</th>
                    </tr>
                  </thead>
                  <tbody id="billed-tbody">
                  </tbody>
                </table>
              </div>
              <div class="row pt-4">
                <div class="col-lg-2 offset-9">
                  <label>Total Hospital Bill : </label>
                  <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-center fw-bold" readonly>
                </div>
              </div>
            </div><br><br>
            <div class="offset-10 pt-2 pb-4">
              <button class="btn btn-info fw-bold fs-5 btn-lg" type="submit" id="proceed-btn" ><i class="mdi mdi-send"></i> Proceed</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
</div>



<!-- GUARANTEE LETTER -->
<div class="modal fade pt-4" id="GuaranteeLetter" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="submit"  id="Letter" class="btn btn-primary me-2" form="submitForm"><i class="mdi mdi-near-me"></i> Send</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CLOSE</button>
      </div>
    </div>
  </div>
</div>
<!-- END -->





<script>
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {

    let billedTable = $('#billedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/billed/final_billing`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#billed-hospital-filter').val();
          data.endDate = $('#end-date').val();
          data.startDate = $('#start-date').val();
        }
      },

      columnDefs: [{
        "orderable": false,
      }, ],
      data: [],
      deferRender: true,
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    billedTable.on('draw.dt', function() {
        let columnId = 9;
        let sum = 0;
        let rowss = billedTable.rows().nodes();

        if ($('#billedLoaTable').DataTable().data().length > 0) {
            // The table is not empty
            rowss.each(function(index, row) {
            let rowData = billedTable.row(row).data();
            let columnValue = rowData[columnId];
            let pattern = /-?[\d,]+(\.\d+)?/g;
            let matches = columnValue.match(pattern);

            if (matches && matches.length > 0) {
                let numberString = matches[0].replace(/,/g, ''); // Replace all commas
                let floatValue = parseFloat(numberString);
                sum += floatValue;
            }
            });
        }

        $('#total-hospital-bill').val(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });

    let columnIdx = 7;
    let rows = billedTable.rows().nodes();

    rows.each(function(index, row) {
      let rowData = billedTable.row(row).data();
      let columnValue = rowData[columnIdx];

      if (columnValue > 100) {
        $('#proceed-btn').prop('disabled', true); // disable the button
      }else{
        $('#proceed-btn').prop('disabled', false); // enable the button
      }
    });

    $('#billed-hospital-filter').change(function(){
      billedTable.draw();
    });

    $('#start-date').change(function(){
      billedTable.draw();
    });

    $('#end-date').change(function(){
      billedTable.draw();
    });

    $("#start-date").flatpickr({
      dateFormat: 'Y-m-d',
    });

    $('#end-date').flatpickr({
      dateFormat: 'Y-m-d',
    });

    //Submit Guarantee Letter
    // $('#Letter').submit(function(event) {
    //   event.preventDefault();

    //   const LetterForm = $('#Letter')[0];
    //   const formdata = new FormData(LetterForm);
    //   $.ajax({
    //     type: "post",
    //     url: $(this).attr('action'),
    //     data: formdata,
    //     dataType: "json",
    //     processData: false,
    //     contentType: false,
    //     success: function(response) {
    //       const {
    //         token,
    //         status,
    //         message,
            
    //       } = response;
    //       switch (status) {
    //         case 'error':
    //           // is-invalid class is a built in classname for errors in bootstrap
    //           if (charge_type_error !== '') {
    //             $('#charge-type-error').html(charge_type_error);
    //             $('#charge-type').addClass('is-invalid');
    //           } else {
    //             $('#charge-type-error').html('');
    //             $('#charge-type').removeClass('is-invalid');
    //           }
    //         break;
    //         case 'save-error':
    //           swal({
    //             title: 'Failed',
    //             text: message,
    //             timer: 3000,
    //             showConfirmButton: false,
    //             type: 'error'
    //           });
            
    //         break;
    //         case 'success':
    //           swal({
    //             title: 'Success',
    //             text: message,
    //             timer: 3000,
    //             showConfirmButton: false,
    //             type: 'success'
    //           });
    //         break;
    //       }
    //     },
    //   })
    // });
    //End

    //Submit Guarantee Letter
    $('#Letter').click(function(event) {
      $.ajax({
        type: "post",
        url: `<?php echo base_url(); ?>healthcare-coordinator/noa/billed/submit_letter`,
        data:{
          token :`<?= $this->security->get_csrf_hash() ?>`,
          pdf_file : $('#pdf_file').val(),
          billing_id : $('#billing_id').val(),
        } ,
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            
          } = response;
          switch (status) {
            case 'error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });

            break;
            case 'save-error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });
            
            break;
            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
            break;
          }
        },
      })
    });
    //End

    $(".generate_pdf").click(function() {
      // Send an AJAX request to the server to generate the PDF
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>healthcare-coordinator/noa/billed/guarantee_pdf",
        success: function(response) {
          // Open the generated PDF in a new tab or window
          window.open(response, "_blank");
        }
      });
    });
  });

  const enableProceedBtn = () => {
    const hp_name = document.querySelector('#billed-hospital-filter');
    const start_date = document.querySelector('#start-date');
    const end_date = document.querySelector('#end-date');
    const button = document.querySelector('#proceed-btn');

    if(end_date.value == ''){
      button.setAttribute('disabled', true);
    }else{
      button.removeAttribute('disabled');
    }
  }

  const viewPDFBill = (pdf_bill,loa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#pdf-loa-no').html(loa_no);

    let pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
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

  // const getTotalBill = () => {
  //   const hospital_bill = document.querySelector('#total-hospital-bill');
  //   const hp_filter = document.querySelector('#billed-hospital-filter').value;
  //   const end_date = document.querySelector('#end-date').value;
  //   const start_date = document.querySelector('#start-date').value;
  //   const button = document.querySelector('#proceed-btn');

  //   $.ajax({
  //     type: 'post',
  //     url: `${baseUrl}healthcare-coordinator/noa/total-bill/fetch`,
  //     dataType: "json",
  //     data: {
  //       'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
  //       'hp_id' : hp_filter,
  //       'startDate' : start_date,
  //       'endDate' : end_date,
  //     },
  //     success: function(response){
  //     hospital_bill.value = response.total_hospital_bill;
  //     },
  //   });
  // }

  const enableDate = () => {
    const hp_filter = document.querySelector('#billed-hospital-filter');
    const start_date = document.querySelector('#start-date');
    const end_date = document.querySelector('#end-date');
    if(hp_filter != ''){
      start_date.removeAttribute('disabled');
      start_date.style.backgroundColor = '#ffff';
      end_date.removeAttribute('disabled');
      end_date.style.backgroundColor = '#ffff';
    }else{
      start_date.setAttribute('disabled', true);
      start_date.value = '';
      end_date.setAttribute('disabled', true);
      end_date.value = '';
    }
  }

  const validateDateRange = () => {
    const startDateInput = document.querySelector('#start-date');
    const endDateInput = document.querySelector('#end-date');
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (startDateInput.value === '' || endDateInput.value === '') {
      return;
    }

    if (endDate < startDate) {
      swal({
        title: 'Failed',
        text: 'End date must be greater than or equal to the start date',
        showConfirmButton: true,
        type: 'error'
      });
      endDateInput.value = '';
      return;
    }          
  }


  // function GuaranteeLetter(billing_id) {
  //   $("#GuaranteeLetter").modal("show");
  //   $('#billing-id').val(billing_id);
  // }

  function showPreview(input) {
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('image-preview');
    const pdfPreview = document.getElementById('pdf-preview');

    if (input.files && input.files[0]) {
      const file = input.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        if (file.type.startsWith('image')) {
          // Display Image preview
          const imageUrl=URL.createObjectURL(file);
          imagePreview.innerHTML=`<a href="${imageUrl}" target="_blank">View Image</a>`;
          imagePreview.style.display = 'block';
          pdfPreview.style.display = 'none';
        } else if (file.type === 'application/pdf') {
          // Display PDF preview
          const pdfUrl = URL.createObjectURL(file);
          pdfPreview.innerHTML = `<a href="${pdfUrl}" target="_blank">View PDF</a>`;
          pdfPreview.style.display = 'block';
          imagePreview.style.display = 'none';
        }
      };

      reader.readAsDataURL(file);
      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
    }
  }

  function GuaranteeLetter(noa_id, billing_id) {
    console.log(billing_id);
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/noa/billed/guarantee_pdf/${noa_id}`,
      type: "GET",
      success: function (response) {
        const res = JSON.parse(response);
        const { status, filename } = res;
        console.log('filename',filename);
        console.log('status',status);
        const embedTag = `<embed src="${baseUrl}/uploads/guarantee_letter/${filename}" name="pdfEmbed" id="pdfEmbed" width="100%" height="100%" type="application/pdf" /> <input type = "hidden" name="pdf_file" id="pdf_file" value="${filename}" /> <input type = "hidden" name="billing_id" id="billing_id" value="${billing_id}" />`;
       
        $('#GuaranteeLetter .modal-body').html(embedTag);
        $('#GuaranteeLetter').modal('show');
        // console.log ($('#pdf_file').val());
      }
    });
  }
</script>