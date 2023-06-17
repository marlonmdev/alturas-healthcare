<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Upload PDF Billing</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Provider</li>
              <li class="breadcrumb-item active" aria-current="page">Upload PDF</li>
             </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid" id="container-div">
      <div class="col-12 mb-4 mt-0">
        <form method="POST" id="go-back" action="<?php echo base_url(); ?>healthcare-provider/billing/search">
          <div class="input-group">
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="healthcard_no" value="<?= $healthcard_no ?>">
            <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
              <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Go Back</strong>
            </button>
          </div>
        </form>
      </div>

      <form action="<?php echo base_url();?>healthcare-provider/billing/bill-loa/upload-pdf/<?= $loa_id ?>/submit" id="pdfBillingForm" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
        <input type="hidden" name="billing-no" value="<?= $billing_no ?>">
        <!-- <input type="hidden" name="net-bill" value="0"> -->
        <div class="card">
          <div class="card-body shadow">
            <div class="row mt-3">
              <div class="col-12">
                <table class="table table-bordered">
                  <tr>
                    <td>
                      <span class="fw-bold text-secondary fs-5 ls-1">Patient's Name: <span class="text-info"><?= $patient_name ?></span></span>
                    </td>

                    <td>
                      <span class="fw-bold text-secondary fs-5 ls-1">LOA No. : <span class="text-info"><?= $loa_no ?></span>
                    </td>

                    <td>
                      <span class="fw-bold text-secondary fs-5 ls-1">Billing No. : <span class="text-info"><?= $billing_no ?></span></span> 
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row pt-3">
                        <div class="col-lg-6">
                            <label class="fw-bold fs-5 ls-1">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload PDF Bill 
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
                                <input type="text" class="form-control fw-bold ls-1" id="remaining-balance" name="remaining-balance" value="<?= number_format($remaining_balance) ?>"  readonly>
                            </div>
                        </div>

                        <div class="col-lg-3">
                        <label class="form-label fs-5 ls-1">Net Bill</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill"  value="0.00"  readonly>
                            </div>
                        </div>
                    </div>

            <div class="row">
              <div class="d-flex justify-content-center align-items-center mt-2">
                <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-3" id="upload-btn"><i class="mdi mdi-upload me-1"></i>UPLOAD</button>
                <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn">CLEAR</button>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <div class="mt-3" id="pdf-preview"></div>
                <!-- <iframe id="view_pdf" style="width:100%;height:580px;"></iframe><br> -->
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const mbl = parseFloat($('#remaining-balance').val().replace(/,/g, ''));
  let net_bill = 0;
  var re_upload = '<?= isset($re_upload)?$re_upload : ""?>';
  var prev_billing = '<?= isset($prev_billing)?$prev_billing:"" ?>';
  var loa_no = '<?= $loa_no ?>';
  var patient_name ="<?= $patient_name ?>";
  const form = document.querySelector('#pdfBillingForm');
  let hospital_charges ="";
  let attending_doctors ="";
  let is_valid_name = true;
  let is_valid_noa = true;

  const previewPdfFile = () => {
    let pdfFileInput = document.getElementById('pdf-file');
    let pdfPreview = document.getElementById('pdf-preview');
    let pdfFile = pdfFileInput.files[0];
    if(pdfFile){
      if (pdfFile.type === 'application/pdf') {
        let reader = new FileReader();
        reader.onload = function () {
          let pdfObject = "<object data='" + reader.result + "' type='application/pdf' width='100%' height='600px'>";
          pdfObject += "</object>";
          pdfPreview.innerHTML = pdfObject;
        }
        reader.readAsDataURL(pdfFile);
      }else{
        pdfPreview.innerHTML = "Please select a PDF file.";
      }
    }
  }
  
  $(document).ready(function(){
    $('#pdfBillingForm').submit(function(event){
      event.preventDefault();

      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }
      let formData = new FormData($(this)[0]);
    
      // if (hospital_charges != null) {
      //   const hospitalBillJSON = JSON.stringify(hospital_charges);
      //   formData.append('hospital_bill_data', hospitalBillJSON);
      // }
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
                              // window.location.href = `${baseUrl}healthcare-provider/billing/search`;
                              // window.location.href = `${baseUrl}healthcare-provider/billing/bill-loa/upload-pdf/${billing_id}/success`;
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

    $('#clear-btn').on('click', function(){
      let pdfPreview = document.getElementById('pdf-preview');
      $('#pdfBillingForm')[0].reset();
      pdfPreview.innerHTML = "";
    });   

      //extract pdf text 
      let pdfFileInput = document.getElementById('pdf-file');

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
                            const pattern = /attending doctor\(s\):\s(.*?)\sregistry date:/si;
                            const patient_pattern = /patient name:\s(.*?)\admission no:/si;
                            const doc_pattern = /hospital charges(.*?)please pay for this amount/si;

                            // const matches_1 = finalResult.match(pattern); 
                            // const result_1 = matches_1 ? matches_1[1] : null;

                            const matches_2 = finalResult.match(doc_pattern);
                            const result_2 = matches_2 ? matches_2[1] : null;

                            hospital_charges = result_2;
                            attending_doctors = get_doctors(finalResult);
                            
                            const matches_3 = finalResult.match(patient_pattern);
                            const result_3 = matches_3 ? matches_3[1] : null;

                            console.log("doctors", attending_doctors);
                            console.log("hospital charges", hospital_charges);

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
                                  is_valid_name = false;
                                    // $('#upload-btn').prop('disabled',true);
                                    $.alert({
                                            title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                                            content: `<div style='font-size: 16px; color: #333;'>The uploaded PDF bill does not match the member's name. Please ensure that you have uploaded the correct PDF bill.</div>`,
                                            type: "red",
                                            buttons: {
                                            ok: {
                                                text: "OK",
                                                btnClass: "btn-danger",
                                            },
                                        },
                                    });
                                }else{
                                  is_valid_name = true;
                                }
                            }

                        //validate noa 
                        const valid_loa = /registry no:/i;
                            const invalid_loa = /admission no:/i;
                            if(finalResult.match(invalid_loa) && !finalResult.match(valid_loa)){
                              is_valid_noa = false;
                              $('#upload-btn').prop('disabled',true);
                              setTimeout(function() {
                                  $.alert({
                                                title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>ERROR</h3>`,
                                                content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that your uploaded PDF is an NOA (Notice of Admission) instead of  an LOA (Letter of Authorization). Thank you for your understanding.</div>",
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
                              is_valid_noa = true;
                            }

                        const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                        // const regex = /subtotal\s*\.{26}\s*\(([\d,\.]+)\)/i;
                            const match = finalResult.match(regex);
                            console.log("match",match);
                            if (match) {
                              subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                              net_bill=subtotalValue;
                              document.getElementsByName("net-bill")[0].value = match[1];

                              if(is_valid_name && is_valid_noa){
                                    $('#upload-btn').prop('disabled',false);
                                }

                              if(parseFloat(net_bill)>mbl){
                              // $('#upload-btn').prop('disabled',true);
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

                            } else {
                            console.log("please pay for this amount is not found");
                            $('#upload-btn').prop('disabled',true);
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
                        });
                        
                    }, function(error) {
                    console.error(error);
                    });
      };
        if(this.files[0])
        reader.readAsArrayBuffer(this.files[0]);
      });

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
      // const viewPDFBill = (pdf_bill) => {

      //               let pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
      //               let fileExists = checkFileExists(pdfFile);
      //               if(fileExists){
      //               let xhr = new XMLHttpRequest();
      //               xhr.open('GET', pdfFile, true);
      //               xhr.responseType = 'blob';

      //               xhr.onload = function(e) {
      //                   if (this.status == 200) {
      //                   let blob = this.response;
      //                   let reader = new FileReader();

      //                   reader.onload = function(event) {
      //                       let dataURL = event.target.result;
      //                       let iframe = document.querySelector('#view_pdf');
      //                       iframe.src = dataURL;
      //                   };
      //                   reader.readAsDataURL(blob);
      //                   }
      //               };
      //               xhr.send();
      //               }
      //           }

      //           const checkFileExists = (fileUrl) => {
      //               let xhr = new XMLHttpRequest();
      //               xhr.open('HEAD', fileUrl, false);
      //               xhr.send();

      //               return xhr.status == "200" ? true: false;
      //           }

      //           if(re_upload){
      //               viewPDFBill(prev_billing);
      //               $('#pdf-file').val("asdfdfas");
      //           }
  });

  // function hospital_bills(finalResult){
  //       const pattern = /hospital charges(.*?)please pay for this amount/si;
  //       const matches = finalResult.match(pattern);
  //       const result = matches ? matches[1] : null;
  //       console.log(result);

        // let bills = [];
        // const lines = result.split("\n");
        // for (let i = 0; i < lines.length; i++) {
        //   const line = lines[i];
        //   const matches = line.match(/^(.*?)(\s+\S+(?=\s|$))?$/);

        //   if (matches !== null) {
        //     let beforeLastGroup = matches[1] || "";
        //     let lastGroup = matches[2] ? matches[2].trim() : '0';

        //     // let suffix = 1;
        //     // while (bills.some(item => item.text === beforeLastGroup)) {
        //     //   beforeLastGroup = `${beforeLastGroup}_${suffix}`;
        //     //   suffix++;
        //     // }
        //     lastGroup = lastGroup.replace(/[^0-9.-]/g, '');
        //     if (/\S/.test(beforeLastGroup)) {
        //       console.log(`Line ${i + 1}:`);
        //       console.log(`Before last group: ${beforeLastGroup}`);
        //       console.log(`Last group: ${lastGroup}`);
        //       console.log("");
        //       bills.push({ beforeLastGroup,lastGroup});
        //     }
        //   }
        // }
        // console.log(bills);   
        // hospital_charges = bills;  
  // }

</script>