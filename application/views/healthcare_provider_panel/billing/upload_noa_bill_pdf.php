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
            <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search">
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

        <form  id="pdfBillingForm" enctype="multipart/form-data" class="needs-validation" novalidate>
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

                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle fw-bold animate__animated" data-bs-toggle="dropdown" aria-expanded="false">
                             Initial Billing
                        </button>
                        <ul class="dropdown-menu bg-white">
                            <li>
                                <a class="dropdown-item" href="#">Initial Billing</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Final Billing</a>
                            </li>
                        </ul>
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
                            <label class="form-label fs-5 ls-1" id="net_bill_label">Initial Bill</label>
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
                                <input type="file" class="form-control" name="Final-Diagnosis" id="Final-Diagnosis" accept="application/pdf" onchange="previewPdfFile('Final-Diagnosis')" required disabled>
                                <div class="invalid-feedback fs-6">
                                    PDF File is required
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                     Upload Medical Abstract(Optional)
                                </label>
                                <input type="file" class="form-control" name="Medical-Abstract" id="Medical-Abstract" accept="image/jpeg,image/png" onchange="previewPdfFile('Medical-Abstract')" disabled>
                
                            </div>

                            <div class="col-lg-6 mt-3" id="prescription-div">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                <input type="checkbox" id="take-home-checkbox">  Upload Prescription Image
                                </label>
                                <input type="file" class="form-control" name="Prescription" id="Prescription" accept="image/jpeg,image/png">
                                <div class="invalid-feedback fs-6">
                                    PDF File is required
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
                    
                    <!-- Modal -->
                    <!-- <div class="modal fade" id="viewPDFBillModal_initial" tabindex="-1" data-bs-backdrop="static" style="height: 100%;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Initial Bill</h5> -->
                                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cancel"></button> -->
                                <!-- </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <iframe id="pdf-viewer" style="width: 100%; height: 500px;"></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel">Cancel</button>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="row">
                        <div class="col-lg-12">
                           <div class="mt-3" id="pdf-preview"></div>
                        </div>
                    </div> -->
                </div>
                
            </div>
        </form>
        <div id="initial_bill_history">
        <label class="fw-bold fs-5 ls-1 ps-3" id="initial_btn_label">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Initial Billing History
                            </label>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="initial_bill_table">
                            <thead style="background-color: #00538C; color: white;">
                                <tr>
                                    <th class="fw-bold" style="color: white;">BILLING NO</th>
                                    <th class="fw-bold" style="color: white;">FILE NAME</th>
                                    <th class="fw-bold" style="color: white;">DATE UPLOADED</th>
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
        <?php include 'view_pdf_bill_modal.php'; ?>

    </div>
</div>
<script>
    const baseUrl = `<?php echo base_url(); ?>`;
    const mbl = parseFloat($('#remaining-balance').val().replace(/,/g, ''));
    let net_bill = 0;
    let pdfPreview = document.getElementById('pdf-preview');
    // const previewPdfFile = () => {
    //     let pdfFileInput = document.getElementById('pdf-file');
       
    //     let pdfFile = pdfFileInput.files[0];

    //     if (pdfFile.type === 'application/pdf') {
    //         $('#myModal').modal('show');
    //         let reader = new FileReader();
    //         reader.onload = function () {
    //             let pdfObject = "<object data='" + reader.result + "' type='application/pdf' width='100%' height='600px'>";
    //             pdfObject += "</object>";
    //             pdfPreview.innerHTML = pdfObject;
    //         }
    //         reader.readAsDataURL(pdfFile);
    //     } else {
    //         pdfPreview.innerHTML = "Please select a PDF file.";
    //     }
    // }
        let pdfinput = "";
    const previewPdfFile = (pdf_input) => {
        pdfinput = pdf_input;
        $('#viewPDFBillModal').modal('show');
        if(pdfinput==="pdf-file"){
            $('#billing_no').text('<?=$billing_no?>');
            $('#billing_no_holder').show();
        }else{
            $('#billing_no_holder').hide();
        }

        let pdfFileInput = document.getElementById(pdf_input);
        let pdfFile = pdfFileInput.files[0];
        let reader = new FileReader();

        reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-viewer');
            iframe.src = dataURL;
        };

        reader.readAsDataURL(pdfFile);
    };

    // $(document).ready(function() {
       
    // // $('#viewPDFBillModal').on('hidden.bs.modal', function() {
    // //     // Code to execute when the modal is closed
    // //     // console.log(pdfinput);
    // //     $('#'+pdfinput).val('');
    // //     // Add your custom logic here
    // //     });

    // });

    const form = document.querySelector('#pdfBillingForm');
    let hospital_charges ="";

    $(document).ready(function(){
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
            }else{
                $('#med-services').prop('disabled',true);
                $('#med-services').prop('required',false);
                $('#Prescription').prop('required',false);
                $('#Prescription').prop('disabled',true);
                $('#med-services-div').hide();
            }   
        });
        // $('#med-services-wrapper').on('change', function() {
        //     var prices = [];
            
        //     $('#med-services option:selected').each(function() {
        //       var price = $(this).data('price');
        //       var value = $(this).val();
        //       if (typeof price !== 'undefined') {
        //         prices.push(price);
        //         // console.log("price", price);
        //         console.log("value", value);
        //       }
        //     });

        //     total = prices.reduce(function(acc, val) {
        //       return acc + val;
        //     }, 0);
            
        //     $("#remaining_mbl").val(mbl);
        //     console.log("total", total);
            
        //     // if (total > mbl) {
        //     //   total = prices.reduce(function(acc, val) {
        //     //     return acc + val;
        //     //   }, 0);

        //       console.log("final", total);
        //       $('#net_bill').val(total);
        //       // }
        //   });

        var noa_id = "<?php echo $noa_id; ?>";
        $('#final_diagnosis').prop("hidden",true);
        $('#initial_bill_table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        order: [],
        ajax: {
            url: `${baseUrl}healthcare-provider/fetch_initial_billing/fetch/${noa_id}`,
            type: "POST",
            data: {
                token: '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },
        responsive: true,
        fixedHeader: true,
        initComplete: function () {
        var dataTable = $('#initial_bill_table').DataTable();
        var columnData = dataTable.column(3).data(); // Assuming column 4 is index 3
        var firstIndex = columnData[0];
        if (dataTable.rows().count() !== 0) {
            $('#net-bill').val(firstIndex);
        }
            
    }
    });

      
        
        var forms = document.getElementById('pdfBillingForm');
        
        var final_action = baseUrl + 'healthcare-provider/billing/bill-noa/upload-pdf/' + noa_id + '/submit';
        var initial_action = baseUrl + 'healthcare-provider/initial_billing/bill-noa/upload-pdf/' + noa_id + '/submit';
    
        forms.action = initial_action;

        $('.dropdown-item').click(function() {
            // location.reload();
            form.reset();
            var selectedText = $(this).text();
            $('.btn.dropdown-toggle').text(selectedText);
            $('#net_bill_label').text(selectedText);
            var div = document.getElementById("final_diagnosis");
            var inputs = div.getElementsByTagName("input");
            

            if(selectedText==="Initial Billing"){
                $('#initial_bill_holder').prop("hidden",false);
                $('#initial_bill_history').prop("hidden",false);
                $('#final_diagnosis').prop("hidden",true);
                $('#Rinal-diagnosis').prop("disabled",true);
                $('#Medical-Abstract').prop("disabled",true);
                $('#Operation').prop("disabled",true);
                
                for (var i = 0; i < inputs.length; i++) {
                inputs[i].disabled = true;
                }
                $('#initial_btn_label').html('<i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Initial Billing');
                forms.action = initial_action;
            }else{
                $('#initial_bill_holder').prop("hidden",true);
                $('#initial_bill_history').prop("hidden",true);
                $('#final_diagnosis').prop("hidden",false);
                $('#Rinal-diagnosis').prop("disabled",false);
                $('#Medical-Abstract').prop("disabled",false);
                $('#initial_btn_label').html('<i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Final Billing');
                forms.action = final_action;
                for (var i = 0; i < inputs.length; i++) {
                inputs[i].disabled = false;
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
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    const { token, status, initial, message, billing_id } = response;

                if(status == 'success'){
                    if(initial){
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
                            title: 'Success',
                            text: 'Final Bill Uploaded Successfully...',
                            timer: 1000,
                            showConfirmButton: false,
                            type: 'success'
                            }).then(function() {
                                window.location.href = `${baseUrl}healthcare-provider/billing/bill-noa/upload-pdf/${billing_id}/success`;
                        });
                    }

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

        $('#clear-btn').on('click', function(){
            let pdfPreview = document.getElementById('pdf_preview');
            $('#pdfBillingForm')[0].reset();
            pdfPreview.innerHTML = '';
        });

        //extract pdf text and git the net bill
        let pdfFileInput = document.getElementById('pdf-file');
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
                            const pattern = /hospital charges(.*?)please pay for this amount/si;
                        const matches = finalResult.match(pattern);
                        const result = matches ? matches[1] : null;
                        // console.log(result);
                        //get only the text between hospital charges and professional fee
                        hospital_charges = result;
                        // hospital_bills(finalResult);
                        
                        const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                        // const regex = /subtotal\s*\.{26}\s*\(([\d,\.]+)\)/i;
                            const match = finalResult.match(regex);
                            console.log("match",match);
                            if (match) {
                            subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                            net_bill=subtotalValue;
                            document.getElementsByName("net-bill")[0].value = match[1];
                           
                            } else {
                            console.log("please pay for this amount is not found");
                            $.alert({
                                    title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                                    content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that there was an issue with the uploaded PDF. Please review the PDF file and try again.</div>",
                                    type: "red",
                                    buttons: {
                                    ok: {
                                        text: "OK",
                                        btnClass: "btn-danger",
                                    },
                                },
                            });
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
                reader.readAsArrayBuffer(this.files[0]);
                });
                });

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


</script>