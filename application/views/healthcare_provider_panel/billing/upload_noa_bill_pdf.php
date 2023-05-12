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
                            <li><a class="dropdown-item">Initial Billing</a></li>
                            <li><a class="dropdown-item ">Final Billing</a></li>
                        </ul>
                        </div>

                        <div class="row pt-3">
                        <div class="col-lg-6">
                            <label class="fw-bold fs-5 ls-1">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Initial Billing 
                            </label>
                            <input type="file" class="form-control" name="pdf-file" id="pdf-file" accept="application/pdf" onchange="previewPdfFile()" required>
                            <div class="invalid-feedback fs-6">
                                PDF File is required
                            </div>
                        </div>

                        <div class="col-lg-3">
                        <label class="form-label fs-5 ls-1">Remaining MBL Balance</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="number" class="form-control fw-bold ls-1" id="remaining-balance" name="remaining-balance" value="<?= $remaining_balance ?>"  disabled>
                            </div>
                        </div>

                        <div class="col-lg-3">
                        <label class="form-label fs-5 ls-1" id="net_bill_label">Initial Bill</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="number" class="form-control fw-bold ls-1" id="net-bill" name="net-bill"  readonly>
                            </div>
                        </div>
                    </div>
             

                    <div class="row">
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-3" id="upload-btn">
                                <i class="mdi mdi-upload me-1"></i>UPLOAD
                            </button>
                            <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn">CLEAR</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                           <div class="mt-3" id="pdf-preview"></div>
                        </div>
                    </div>

                  <div id="initial_bill_holder">
                      <div class="ps-5 pe-5">
                              <h4 class="page-title ls-2  pb-2 ">Uploaded Initial Billing</h4>
                              <table class="table table-sm">
                                      <thead>
                                          <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Filename</th>
                                          <th scope="col">Subtotal</th>
                                          <th scope="col">View</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          
                                      </tbody>
                                      </table>
                              </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const baseUrl = `<?php echo base_url(); ?>`;

    const previewPdfFile = () => {
        let pdfFileInput = document.getElementById('pdf-file');
        let pdfPreview = document.getElementById('pdf-preview');
        let pdfFile = pdfFileInput.files[0];

        if (pdfFile.type === 'application/pdf') {
            let reader = new FileReader();
            reader.onload = function () {
                let pdfObject = "<object data='" + reader.result + "' type='application/pdf' width='100%' height='600px'>";
                pdfObject += "</object>";
                pdfPreview.innerHTML = pdfObject;
            }
            reader.readAsDataURL(pdfFile);
        } else {
            pdfPreview.innerHTML = "Please select a PDF file.";
        }
    }

    const form = document.querySelector('#pdfBillingForm');
    let hospital_charges ="";
    $(document).ready(function(){

        var base_url = "<?php echo base_url(); ?>";
        var noa_id = "<?php echo $noa_id; ?>";
        // Get the form element
        var forms = document.getElementById('pdfBillingForm');
        // Construct the new action URL
        var final_action = base_url + 'healthcare-provider/billing/bill-noa/upload-pdf/' + noa_id + '/submit';
        var initial_action = base_url + 'healthcare-provider/initial_billing/bill-noa/upload-pdf/' + noa_id + '/submit';
        // Change the action attribute
        forms.action = initial_action;

        $('.dropdown-item').click(function() {
        var selectedText = $(this).text();
        $('.btn.dropdown-toggle').text(selectedText);
        $('#net_bill_label').text(selectedText);
        if(selectedText==="Initial Billing"){
            $('#initial_bill_holder').prop("hidden",false);
            forms.action = initial_action;
        }else{
            $('#initial_bill_holder').prop("hidden",true);
            forms.action = final_action;
        }
        });

        $('#pdfBillingForm').submit(function(event){
            event.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            let formData = new FormData($(this)[0]);

            // if (hospital_charges != null) {
            //     const hospitalBillJSON = JSON.stringify(hospital_charges);
            //     formData.append('hospital_bill_data', hospitalBillJSON);
            // }
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
                        $.alert({
                            title: "<h3 style='font-weight: bold; color: #28a745; margin-top: 0;'>Uploaded Successfully</h3>",
                            content: "<div style='font-size: 16px; color: #333;'>Initial Bill uploaded successfully</div>",
                            type: "green",
                            buttons: {
                            ok: {
                                text: "OK",
                                btnClass: "btn-success",
                                action: function () {
                                let pdfPreview = document.getElementById('pdf-preview');
                                $('#pdfBillingForm')[0].reset();
                                pdfPreview.innerHTML = "";
                                },
                            },
                            },
                        
                        });
                    }else{
                        setTimeout(function() {
                            window.location.href = `${baseUrl}healthcare-provider/billing/bill-noa/upload-pdf/${billing_id}/success`;
                        }, 300);
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

        //extract pdf text 
        let pdfFileInput = document.getElementById('pdf-file');

        pdfFileInput.addEventListener('change', function() {
        let reader = new FileReader();
        reader.onload = function() {
            let typedarray = new Uint8Array(this.result);
            pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
            let numPages = pdf.numPages;
            let pageNum = 1;
            pdf.getPage(pageNum).then(function(page) {
                page.getTextContent().then(function(textContent) {
                let sortedItems = textContent.items
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

                    let finalResult = sortedItems.reduce(function(result, item) {
                    //remove all the dots. that not used in group text
                    const pattern = /\.{2,}(?!\.)/g;
                    return result = result + '\n' + item.text.replace(pattern, '');
                    }, '').trim();
                            
                        console.log(finalResult);
                        const pattern = /hospital charges(.*?)please pay for this amount/si;
                        const matches = finalResult.match(pattern);
                        const result = matches ? matches[1] : null;
                        console.log(result);
                        //get only the text between hospital charges and professional fee
                        hospital_charges = result;
                        // hospital_bills(finalResult);
                        
                        const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                        // const regex = /subtotal\s*\.{26}\s*\(([\d,\.]+)\)/i;
                            const match = finalResult.match(regex);
                            console.log("match",match);
                            if (match) {
                            const subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                            document.getElementsByName("net-bill")[0].value = subtotalValue;
                            console.log(subtotalValue);
                            } else {
                            console.log("please pay for this amount is not found");
                            $.alert({
                                    title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'></h3>`,
                                    content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it looks like your uploaded pdf is already . Thank you for your understanding.</div>",
                                    type: "red",
                                    buttons: {
                                    ok: {
                                        text: "OK",
                                        btnClass: "btn-danger",
                                    },
                                },
                            });
                            }
                        }); 
                    });
                    }, function(error) {
                    console.error(error);
                    });
                };
                reader.readAsArrayBuffer(this.files[0]);
                });
                });

        //         function hospital_bills(finalResult){
        //         const pattern = /hospital charges(.*?)please pay for this amount/si;
        //         const matches = finalResult.match(pattern);
        //         const result = matches ? matches[1] : null;
        //         console.log(result);

        //         let bills = [];
        //         const lines = result.split("\n");
        //         for (let i = 0; i < lines.length; i++) {
        //             const line = lines[i];
        //             const matches = line.match(/^(.*?)(\s+\S+(?=\s|$))?$/);

        //             if (matches !== null) {
        //             let beforeLastGroup = matches[1] || "";
        //             let lastGroup = matches[2] ? matches[2].trim() : '0';

        //             // let suffix = 1;
        //             // while (bills.some(item => item.text === beforeLastGroup)) {
        //             //   beforeLastGroup = `${beforeLastGroup}_${suffix}`;
        //             //   suffix++;
        //             // }
        //             lastGroup = lastGroup.replace(/[^0-9.-]/g, '');
        //             if (/\S/.test(beforeLastGroup)) {
        //                 console.log(`Line ${i + 1}:`);
        //                 console.log(`Before last group: ${beforeLastGroup}`);
        //                 console.log(`Last group: ${lastGroup}`);
        //                 console.log("");
        //                 bills.push({ beforeLastGroup,lastGroup});
        //             }
        //             }
        //         }
        //         console.log(bills);   
        //         hospital_charges = bills;  
        // }


</script>