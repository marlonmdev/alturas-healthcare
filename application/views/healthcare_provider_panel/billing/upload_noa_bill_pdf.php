<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">Upload PDF Billing</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Healthcare Provider</li>
                            <li class="breadcrumb-item active" aria-current="page">
                            Upload PDF
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div> 
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <hr>
    <div class="container-fluid" id="container-div">
        <!-- Go Back to Previous Page -->
        <div class="col-12 mb-4 mt-0">
            <form method="POST" id="go-back" action="<?php echo base_url(); ?>healthcare-provider/billing/search">
                <div class="input-group">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="healthcard_no" value="<?= $healthcard_no ?>">
                    <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </button>
                </div>
            </form> 
        </div>
        
        <div class="row">
            <div class="col-12 mb-3">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="final_tab" data-bs-toggle="tab" data-bs-target="#final_bill" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>Final Bill</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="initial_tab" data-bs-toggle="tab" data-bs-target="#initial_bill" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>Initial Bill</strong></button>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="final_bill" role="tabpanel">
                    <!-- Content for the "final_bill" tab pane -->
                    <form action="<?php echo base_url();?>healthcare-provider/billing/bill-noa/upload-pdf/<?= $noa_id ?>/submit" id="pdfBillingForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="billing-no" value="<?= $billing_no ?>">
                        <div class="card">
                            <div class="card-body shadow">
                                <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-secondary fs-5 ls-1">
                                                Patient's Name: <span class="text-info"><?= $patient_name ?></span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-secondary fs-5 ls-1">
                                                NOA No. : <span class="text-info"><?= $noa_no ?></span>
                                            </span>  
                                        </td>
                                        <td>
                                            <span class="fw-bold text-secondary fs-5 ls-1">
                                                Billing No. : <span class="text-info"><?= $billing_no ?></span>
                                            </span> 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                            <div class="row pt-3">
                                <div class="col-lg-6">
                                    <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                        <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Initial Billing 
                                    </label>
                                    <input type="file" class="form-control" name="pdf-file" id="pdf-file" accept="application/pdf" onchange="previewPdfFile('pdf-file')" required>
                                    <div class="invalid-feedback fs-6">
                                        PDF File is required
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                <label class="form-label fs-5 ls-1">Remaining MBL Balance</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                        <input type="text" class="form-control fw-bold ls-1" id="remaining-balance" name="remaining-balance" value="<?= number_format($remaining_balance) ?>"  readonly>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                <label class="form-label fs-5 ls-1" id="net_bill_label">Final Bill</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                        <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row " id="final_diagnosis">
                                <div class="col-lg-6">
                                    <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                        <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Final Diagnosis/Operation 
                                    </label>
                                    <input type="file" class="form-control" name="Final-Diagnosis" id="Final-Diagnosis" accept="application/pdf" onchange="previewPdfFile('Final-Diagnosis')"  >
                                    <div class="invalid-feedback fs-6">
                                        PDF File is required
                                    </div>
                                </div>

                                <div class="col-lg-6 ">
                                    <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                        Upload Medical Abstract(Optional)
                                    </label>
                                    <input type="file" class="form-control" name="Medical-Abstract" id="Medical-Abstract" accept="image/jpeg,image/png" onchange="previewPdfFile('Medical-Abstract')">
                    
                                </div>

                                <div class="col-lg-6 mt-3" id="prescription-div">
                                    <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    <input type="checkbox" id="take-home-checkbox">  Upload Prescription Image
                                    </label>
                                    <input type="file" class="form-control" name="Prescription" id="Prescription" accept="image/jpeg,image/png">
                                    <div class="invalid-feedback fs-6">
                                    Prescription Image is required
                                    </div>
                                </div>

                                <div class="col-lg-6 mt-3" id="med-services-div">
                                    <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    Select Take Home Medicines
                                    </label>
                                    <div id="med-services-wrapper"></div>
                                    <em id="med-services-error" class="text-danger"></em>
                                </div>
                                
                            </div>
                

                        <div class="row mt-3">
                            <div class="d-flex justify-content-center align-items-center mt-2">
                                <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-3" id="upload-btn">
                                    <i class="mdi mdi-upload me-1"></i>UPLOAD
                                </button>
                                <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn">CLEAR</button>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </form> 
            </div>
            <div class="tab-pane fade" id="initial_bill" role="tabpanel">
            <input type="hidden" id="i_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <!-- Content for the "initial_bill" tab pane -->
            <form  action="<?php echo base_url();?>healthcare-provider/initial_billing/bill-noa/upload-pdf/<?= $noa_id ?>/submit" id="initialpdfBillingForm" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="billing-no" value="<?= $billing_no ?>">
            <div class="row">
            <div class="col-lg-8"></div> <!-- Empty column to create space on the left side -->
            <div class="col-lg-4">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text text-white bg-dark ls-1 ms-2" title="Initial bill As Of">
                            <i class="mdi mdi-calendar-range"></i>
                        </span>
                    </div>
                    <input type="date" class="form-control" name="initial-date" id="initial-date" title="Initial bill As Of" onchange="validateDateRange()" placeholder="Billing Date"  required>
                </div>
            </div>
        </div>

            <div class="card">
                <div class="card-body shadow">
                    <div class="row mt-3">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <span class="fw-bold text-secondary fs-5 ls-1">
                                            Patient's Name: <span class="text-info"><?= $patient_name ?></span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-secondary fs-5 ls-1">
                                            NOA No. : <span class="text-info"><?= $noa_no ?></span>
                                        </span>  
                                    </td>
                                    <td>
                                        <span class="fw-bold text-secondary fs-5 ls-1">
                                            Billing No. : <span class="text-info"><?= $billing_no ?></span>
                                        </span> 
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                        <div class="row pt-3">
                            <div class="col-lg-6">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Initial Billing 
                                </label>
                                <input type="file" class="form-control" name="pdf-file-initial" id="pdf-file-initial" accept="application/pdf" onchange="previewPdfFile('pdf-file-initial')" required>
                                <div class="invalid-feedback fs-6">
                                    PDF File is required
                                </div>
                            </div>

                            <div class="col-lg-3">
                            <label class="form-label fs-5 ls-1">Remaining MBL Balance</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="remaining-balance" name="remaining-balance" value="<?= number_format($remaining_balance) ?>"  readonly>
                                </div>
                            </div>

                            <div class="col-lg-3">
                            <label class="form-label fs-5 ls-1" id="net_bill_label">Initial Bill</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="initial-net-bill" name="initial-net-bill" value="0.00" readonly>
                                </div>
                            </div>
                        </div>

                    <div class="row mt-3">
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-3" id="initial-upload-btn">
                                <i class="mdi mdi-upload me-1"></i>UPLOAD
                            </button>
                            <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn-initial">CLEAR</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>

                    <div class="col-lg-12" id="initial_bill_history">
                        <label class="fw-bold fs-5 ls-1 ps-3" id="initial_btn_label">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Initial Billing History
                            </label>
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover w-100" id="initial_bill_table">
                                            <thead style="background-color: #00538C; color: white;">
                                                <tr>
                                                    <th class="fw-bold" style="color: white;">BILLING NO</th>
                                                    <th class="fw-bold" style="color: white;">FILE NAME</th>
                                                    <th class="fw-bold" style="color: white;">BILLING DATE</th>
                                                    <th class="fw-bold" style="color: white;">INITIAL  BILL</th>
                                                    <th class="fw-bold" style="color: white;">VIEW</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Add your table rows dynamically here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        
        <?php include 'view_pdf_bill_modal.php'; ?>

    </div>
</div>

<script>
    const baseUrl = `<?php echo base_url(); ?>`;
    const admission_date = '<?= isset($admission_date) ? $admission_date : null ?>';
    var re_upload = '<?= isset($re_upload)?$re_upload : false?>';
    var prev_billing = '<?= isset($prev_billing) ? $prev_billing : null ?>';
    var noa_no = '<?= $noa_no ?>';
    var noa_id = "<?php echo $noa_id; ?>";  
    var initial_net_bill = "";
    var initial_net_bill_date = 0;
    var patient_name ="<?= $patient_name ?>";
    // console.log("admission_date",admission_date);
    const mbl = parseFloat($('#remaining-balance').val().replace(/,/g, ''));
    let net_bill = 0;
    let pdfPreview = document.getElementById('pdf-viewer');
    const form = document.querySelector('#pdfBillingForm');
    let hospital_charges ="";
    let attending_doctors ="";
    
    if(re_upload){
        $('#initial_tab').hide();
        $('#final_diagnosis').hide();
        $('#Final-Diagnosis').prop('required',false);
    }else{
        $('#Final-Diagnosis').prop('required',true);
    }

    let pdfinput = "";
    const  previewPdfFile = (pdf_input) => {
        pdfinput = pdf_input;
        let pdfFileInput = document.getElementById(pdf_input);
        let pdfFile = pdfFileInput.files[0];
        let reader = new FileReader();
        if(pdfFile){
            $('#viewPDFBillModal').modal('show');
            if(pdfinput==="pdf-file" || pdfinput ==="pdf-file-initial"){
                $('#billing_no').text('<?=$billing_no?>');
                $('#billing_no_holder').show();
            }else{
                $('#billing_no_holder').hide();
            }
            reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-viewer');
            iframe.src = dataURL;
        };
            reader.readAsDataURL(pdfFile);
        }

    };

    $(document).ready(function(){

        read_pdf(true);
        $('#Prescription').hide();
        $('#final_tab').on('click',function(){
            $('#pdfBillingForm')[0].reset();
            read_pdf(true);
           
        });
        $('#initial_tab').on('click',function(){
            $('#initialpdfBillingForm')[0].reset();
            $('#initial-net-bill').val(initial_net_bill);
            read_pdf(false);
        });

        $('#clear-btn').on('click', function(){
            $('#pdfBillingForm')[0].reset();
        });
        $('#clear-btn-initial').on('click', function(){
            $('#initialpdfBillingForm')[0].reset();
        });
        $('#pdfBillingForm').on('reset',function(){
            $('#upload-btn').prop('disabled',false);
        });
        $('#initialpdfBillingForm').on('reset',function(){
            $('#initial-upload-btn').prop('disabled',false);
        });
        // $('#Operation').prop("disabled",true);
        $('#med-services-div').hide();
        $.ajax({
                    url: `${baseUrl}healthcare-provider/patient/get_takehome_meds`,
                    type: "GET",
                    data: {token:'<?php echo $this->security->get_csrf_hash(); ?>'},
                    dataType: "json",
                    success:function(response){
                        $('#med-services-wrapper').empty();                
                        $('#med-services-wrapper').append(response);
                        $(".chosen-select").chosen({
                        width: "100%",
                        no_results_text: "Oops, nothing found!"
                        }); 
                    }
                    });

        $('#cancel').on('click',function(){
           $('#'+pdfinput).val('');
        });

        $('#take-home-checkbox').on('change',function(){
            if(this.checked){
                $('#med-services').prop('disabled',false);
                $('#med-services').prop('required',true);
                $('#Prescription').prop('required',true);
                $('#Prescription').prop('disabled',false);
                $('#med-services-div').show();
                $('#Prescription').show();
            }else{
                $('#med-services').prop('disabled',true);
                $('#med-services').prop('required',false);
                $('#Prescription').prop('required',false);
                $('#Prescription').prop('disabled',true);
                $('#med-services-div').hide();
                $('#Prescription').hide();
            }   
        });
       
        
        
        // $('#final_diagnosis').prop("hidden",true);
        $('#initial_bill_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [],
            ajax: {
                url: `${baseUrl}healthcare-provider/fetch_initial_billing/fetch/${noa_id}`,
                type: "POST",
                data: function (d) {
                    d.token = '<?php echo $this->security->get_csrf_hash(); ?>';
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("AJAX Error: " + textStatus + "\n" + errorThrown);
            },
            responsive: true,
            fixedHeader: true,
            initComplete: function () {
                var dataTable = $('#initial_bill_table').DataTable();
                var columnData = dataTable.column(3).data(); // Assuming column 4 is index 3
                var firstIndex = columnData[0];

                var initial_date = dataTable.column(2).data(); // Assuming column 4 is index 3
                var date = initial_date[0];
                if (dataTable.rows().count() !== 0) {

                    const b_Date = new Date(date);
                    const year = b_Date.getFullYear();
                    const month = String(b_Date.getMonth() + 1).padStart(2, '0');
                    const day = String(b_Date.getDate()).padStart(2, '0');

                    initial_net_bill = firstIndex;
                    initial_net_bill_date = year+month+day;
                    console.log("initial date",year+month+day);

                }
            }
        });

        //submit the form
        $('#pdfBillingForm').submit(function(event){
            event.preventDefault();
            console.log('medicines',$('#med-services').val());
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            let formData = new FormData($(this)[0]);
            formData.append('hospital_bill_data', hospital_charges);
            formData.append('attending_doctors', attending_doctors);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    const { token, status, message, billing_id } = response;

                if(status == 'success'){
                    
                        swal({
                            title: 'Success',
                            text: 'Final Bill Uploaded Successfully...',
                            timer: 1000,
                            showConfirmButton: false,
                            type: 'success'
                            }).then(function() {
                                $('#go-back').submit();
                                // window.location.href = `${baseUrl}healthcare-provider/billing/bill-noa/upload-pdf/${billing_id}/success`;
                                //window.location.href = `${baseUrl}healthcare-provider/billing/search`;
                        });
                  
                    }else{
                        swal({
                            title: 'Failed',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'error'
                        }); 
                    }
                }
            });

        });

        $('#initialpdfBillingForm').submit(function(event){
            event.preventDefault();

            let formData = new FormData($(this)[0]);
            formData.append('hospital_bill_data', hospital_charges);
            formData.append('attending_doctors', attending_doctors);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    const { token, status, message, billing_id } = response;

                if(status == 'success'){
                   
                        swal({
                            title: 'Success',
                            text: 'Initial Bill Uploaded Successfully...',
                            timer: 1000,
                            showConfirmButton: false,
                            type: 'success'
                            }).then(function() {
                                location.reload();
                        });
                
                    }else{
                        swal({
                            title: 'Failed',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'error'
                        }); 
                    }
                }
            });

        });
        });

        const read_pdf = (is_final) =>{
       // console.log("is_final",is_final);
        //extract pdf text and git the net bill
        let pdfFileInput = (is_final) ? document.getElementById('pdf-file') : document.getElementById('pdf-file-initial');
        let subtotalValue = 0;
        pdfFileInput.addEventListener('change', function() {
        let reader = new FileReader();
        reader.onload = function() {
            let typedarray = new Uint8Array(this.result);
            pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                let numPages = pdf.numPages;
                    let promises = [];
                    console.log("number of pages",numPages);
                    for (let page = 1; page <= numPages ; page++) {
                    let promise = pdf.getPage(page)
                        .then(function(page) {
                        return page.getTextContent();
                        })
                        .then(function(textContent) {
                        const sortedItems = textContent.items
                            .map(function(item) {
                                return {text: item.str.toLowerCase(), x: item.transform[4], y: item.transform[5]};
                            })
                            .sort(function(a, b) {
                                if (Math.abs(a.y - b.y) < 5) {
                                    return a.x - b.x;
                                } else {
                                    return b.y - a.y;
                                }
                                })
                            .reduce(function(groups, item) {
                                const lastGroup = groups[groups.length - 1];
                                if (lastGroup && Math.abs(lastGroup.y - item.y) < 5) {
                                    lastGroup.text += ' ' + item.text;
                                } else {
                                    groups.push({text: item.text, x: item.x, y: item.y});
                                }
                                return groups;
                            }, []);

                        return sortedItems;
                        })
                        .catch(function(error) {
                        console.log(error);
                        });

                    promises.push(promise);
                    }
                    
                    Promise.all(promises)
                        .then(function(results) {
                            let finalItems = results.flat();
                            console.log(finalItems);
                            return finalItems;
                        })
                        .then(function(finalItems) {
                            let finalResult = finalItems.reduce(function(result, item) {
                            // Remove all the dots that are not used in group text
                            const pattern = /\.{2,}(?!\.)/g;
                            return (result = result + '\n' + item.text.replace(pattern, ''));
                            }, '').trim();

                            console.log(finalResult);
                            console.log("final result",finalResult);
                            const pattern = /attending doctor\(s\):\s(.*?)\admission date:/si;
                            const patient_pattern = /patient name:\s(.*?)\admission no:/si;
                            const doc_pattern = /hospital charges(.*?)please pay for this amount/si;

                            const matches_1 = finalResult.match(pattern);
                            const result_1 = matches_1 ? matches_1[1] : null;

                            const matches_2 = finalResult.match(doc_pattern);
                            const result_2 = matches_2 ? matches_2[1] : null;

                            const matches_3 = finalResult.match(patient_pattern);
                            const result_3 = matches_3 ? matches_3[1] : null;

                            hospital_charges = result_2;
                            attending_doctors = get_doctors(finalResult);
                           
                            console.log("doctors",attending_doctors);
                            // console.log("final doctors", result_3);
                            console.log("hospital charges", hospital_charges);
                            console.log("patient name", result_3);
                            //this check if the patient name is equal to the member name
                            if (patient_name.length) {
                                console.log("member name", patient_name);
                                const names = patient_name.toLowerCase().split(' ').filter(Boolean);

                                let removedElement ="";
                                
                                if(names[names.length-1] === ".jr"){
                                   removedElement = names.splice(names.length-2, 1);
                                }else{
                                    removedElement = names.splice(names.length-1, 1);
                                }
                                const mem_name = removedElement + ", " + names.join(' ');
                                console.log("final name",mem_name);
                                if(mem_name !== result_3){
                                    $('#upload-btn').prop('disabled',true);
                                    $.alert({
                                            title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                                            content: `<div style='font-size: 16px; color: #333;'>The uploaded PDF bill does not match the member's name. Please ensure that you have uploaded the correct PDF bill for your account.</div>`,
                                            type: "red",
                                            buttons: {
                                            ok: {
                                                text: "OK",
                                                btnClass: "btn-danger",
                                            },
                                        },
                                    });
                                }
                            }

                            const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                        // const regex = /subtotal\s*\.{26}\s*\(([\d,\.]+)\)/i;
                            const match = finalResult.match(regex);
                            console.log("match",match);
                            if (match) {
                                subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                                net_bill=subtotalValue;
                                if(is_final){
                                    document.getElementsByName("net-bill")[0].value = match[1];
                                }else{
                                    document.getElementsByName("initial-net-bill")[0]   .value = match[1];
                                    //console.log('initil',match[1]);
                                }
                
                            } else {
                                console.log("please pay for this amount is not found");
                                $('#upload-btn').prop('disabled',true);
                                setTimeout(function() {
                                            $.alert({
                                                title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Warning</h3>`,
                                                content: "<div style='font-size: 16px; color: #333;'>The uploaded PDF Bill name is not the same to the members name.</div>",
                                                type: "red",
                                                buttons: {
                                                    ok: {
                                                        text: "OK",
                                                        btnClass: "btn-danger",
                                                    },
                                                },
                                            });
                                        }, 1000); // Delay of 2000 milliseconds (2 seconds)
                            }

                            console.log("netbill",net_bill);
                            console.log("mbl",mbl);

                            const invalid_noa = /registry no:/i;
                            const valid_noa = /admission no:/i;
                            if(finalResult.match(invalid_noa) && !finalResult.match(valid_noa)){
                            $('#upload-btn').prop('disabled',true);
                            setTimeout(function() {
                                $.alert({
                                                title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>ERROR</h3>`,
                                                content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that your uploaded PDF is an LOA (Letter of Authorization) instead of  an NOA (Notice of Admission). Thank you for your understanding.</div>",
                                                type: "red",
                                                buttons: {
                                                    ok: {
                                                        text: "OK",
                                                        btnClass: "btn-danger",
                                                    },
                                                },
                                            });
                                        }, 1000); // Delay of 2000 milliseconds (2 seconds)
                            }else{
                            $('#upload-btn').prop('disabled',false);
                            if(parseFloat(net_bill)>mbl){
                                            setTimeout(function() {
                                            $.alert({
                                                title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Warning</h3>`,
                                                content: "<div style='font-size: 16px; color: #333;'>The uploaded PDF Bill exceeds the patient's MBL balance.</div>",
                                                type: "red",
                                                buttons: {
                                                    ok: {
                                                        text: "OK",
                                                        btnClass: "btn-danger",
                                                    },
                                                },
                                            });
                                        }, 1000); // Delay of 2000 milliseconds (2 seconds)
                                }
                            }
                        });
                        
                    }, function(error) {
                    console.error(error);
                    });
                };
                if(this.files[0]){
                    reader.readAsArrayBuffer(this.files[0]);
                }
                
                });

            }

            const get_doctors = (lines) => {
                let include = false;
                let include2 = true;

                const liness = lines.split("\n");
                const filteredLines = liness.filter((line) => {
                    if (include) {
                    return true;
                    }
                    if (/\btotal\b/i.test(line)) {
                    include = true;
                    }
                    return false;
                });

                const result = filteredLines.join("\n");

                if(result){
                    const lin = result.split("\n");
              
                    const doctors = lin.filter(line => {
                        if (include2) {
                            if (/\bsubtotal\b/i.test(line)) {
                                include2 = false;
                                return false;
                            }
                            return true;
                        }
                        return false;
                        });

                        const doc = doctors.join("\n");
                        
                        const excludedTerms = ["gross", "discount", "vat", "professional fee"];

                        const pattern = new RegExp("\\b(" + excludedTerms.join("|") + "|\\d{1,3}(?:,\\d{3})*(?:\\.\\d+)?)\\b", "gi");
                        const excludedDoc = doc.replace(pattern, "");
                        
                        const pattern1 = /\n(\S+)/g;
                        const modifiedDoc1 = excludedDoc.replace(pattern1, ' $1');

                        const pattern2 = /^(.*\S)(\s*)$/gm;
                        const modifiedDoc2 = modifiedDoc1.replace(pattern2, '$1;$2');
                        
                        return modifiedDoc2.replace(/\s+/g, ' ');;
                }

            };

                const viewPDFBill = (pdf_bill,noa_no) => {
                $('#viewPDFBillModal').modal('show');
                $('#billing_no').html(noa_no);

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
            
                const showMedServices = () => {
                    const medServices = document.querySelector('#med-services-div');

                    if (loaType === "Consultation" || loaType === ""){
                    medServices.className = "d-none";
                    fileAttachment.className = "d-none";
                    } else if (loaType === "Diagnostic Test") {
                    medServices.className = "col-lg-7 col-sm-12 mb-2 d-block";
                    fileAttachment.className = "form-group row d-block";
                    }
                }
            
                const validateDateRange = () => {

                    const billed_date = document.querySelector('#initial-date');
                    const endDateInput =Date('m-y-d');
                    const b_Date = new Date(billed_date.value);
                    const year = b_Date.getFullYear();
                    const month = String(b_Date.getMonth() + 1).padStart(2, '0');
                    const day = String(b_Date.getDate()).padStart(2, '0');

                    const final_date = year+month+day;
                    // console.log('final date',final_date-admission_date);
                    if(initial_net_bill_date){
                        console.log(final_date-initial_net_bill_date);
                        if( final_date-initial_net_bill_date == 1 ){
                        return; // Don't do anything if either input is empty
                        }
                        else{
                            // alert('End date must be greater than or equal to the start date');
                            swal({
                                title: 'Failed',
                                text: 'Please be aware that the selected date may be either before or one day after the admission date. Take this into consideration when choosing the date.',
                                showConfirmButton: true,
                                type: 'error'
                            });
                            billed_date.value = '';
                            return;
                        }   
                    }else{
                        console.log(final_date-admission_date);
                        if( final_date-admission_date == 0 ){
                           
                            return; // Don't do anything if either input is empty
                        }
                        else{
                                // alert('End date must be greater than or equal to the start date');
                                swal({
                                    title: 'Failed',
                                    text: 'Please be aware that the selected date may be either before or one day after the admission date. Take this into consideration when choosing the date.',
                                    showConfirmButton: true,
                                    type: 'error'
                                });
                                billed_date.value = '';
                                return;
                            }
                    }

                       
                       
                }

                                
                // const displayValue = () => {

                // const billed_date = new Date(document.querySelector('#initial-date').value);
                // const options = { month: 'long', day: '2-digit', year: 'numeric' };
                // const formattedStartDate = billed_date.toLocaleDateString('en-US', options);
            
                // const bDate = document.querySelector('#b-date');

                // if(document.querySelector('#initial-date').value){
                //     }else{
                //         bDate.textContent = '';
                //     }
                // }


</script>