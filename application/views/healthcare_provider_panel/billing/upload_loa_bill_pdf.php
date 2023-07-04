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
                            <input type="file" class="form-control" name="pdf-file" id="pdf-file" accept="application/pdf"  required>
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

                    <div class="col-lg-6">
                                    <label class="fw-bold fs-5 ls-1" id="">
                                        <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Itemized Billing 
                                    </label>
                                    <input type="file" class="form-control" name="itemize-pdf-file" id="itemize-pdf-file" accept="application/pdf"  required>
                                    <div class="invalid-feedback fs-6">
                                        PDF File is required
                                    </div>
                                </div>

            <div class="row">
              <div class="d-flex justify-content-center align-items-center mt-4">
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
  let benefits_deductions = {};
  let json_final_charges = {};
  let pdf_id = "pdf-file";
  const pdfs = ['pdf-file','itemize-pdf-file'];

  const previewPdfFile = (pdfid) => {
    let pdfFileInput = document.getElementById(pdfid);
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
    // text_extract(pdfid);
  }

      

const final_text = (text) => {

var searchTerms = [
  "ramiro community hospital",
  "0139 c. gallares street",
  "tel. no(s):",
  "patient name:",
  "hospitalization plan:",
  "attending doctor(s):",
  "patient address:",
  "room no.:",
  "date   description",
  "labesores, marian cacayan",
  "billing clerk",
  "please pay for",
  "important:",
  "statement",
  "hospital reserves",
  "incurred",
  "(philhealth and/or hmo)",
  "possible",
  "days upon receipt",
  "notice",
  "billed",
  "clave",
  "member",
  "page",
  // "please pay for",
  // "the statement of account",
  // "hospital reserves",
  // "incurred which",
  // "(philhealth and/or hmo)",
  // "hospital reserves",

  // Add more search terms as needed
];

var lines = text.split("\n");
var modifiedLines = lines.filter(function(line) {
  for (var i = 0; i < searchTerms.length; i++) {
    if (line.includes(searchTerms[i])) {
        return false;
      }
    // if (line.includes(searchTerms[11])) {
    //     break;
    //   }
    }
    return true;
  });

var modifiedText = modifiedLines.join("\n");
return modifiedText;
// console.log("final text",modifiedText);
}
  
function validate_name(patient, member) {
  // Remove leading and trailing spaces
  if(patient!=null && patient!=null){
    var trimmedStr1 = patient.replace(/\s/g, "");
    var trimmedStr2 = member.replace(/\s/g, "");
    console.log("trimmedStr1",trimmedStr1);
    console.log("trimmedStr2",trimmedStr2);
    return trimmedStr1 === trimmedStr2;
  }else{
    return false;
  }
  // Compare the strings
  
}

      const get_deduction = (text) => {

        // var lines = text.split("\n");
        const final_text = text.replace(/[()]/g, '');
        let include = false;
        const regex = /subtotal([\s\S]*?)total/gi;
        const matches = text.match(regex);
        let data = [];
        var deductions = matches.join("\n").split("\n");
        console.log("matches",deductions);
        const texts = deductions.filter(line => {
          if (/\bsubtotal\b/i.test(line)) {
            include = true;
            return false;
          }
          if (/\btotal\b/i.test(line)) {
            include = false;
            return false;
          }

          if (include) {
            return true;
          }

          return false;
        });

        // data = texts.map(line => line.split(/\s{2,}/));
        data = texts.map(line => {
          const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g, '');
          const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
          return splitLine;
        });
        
        const outputArray = data.map((arr, index) => {
          if(arr.length === 1){   
            return [...arr, '0'];
          }else{
            return [...arr];
          }
        });
        console.log("deductions",outputArray);
        return outputArray;
      };

      const get_all_item = (result) => {
                    // const line1 = result.split("\n"); // Split input into an array of lines
                    // const data1 = line1.map(line => line.split(/\s{3,}/)); 
                    // console.log("data",data1);
                    let include = true;
      
                    const lin = result.split("\n");
              
                    const texts = lin.filter(line => {

                        if (include) {

                            if (/\btotal\b/i.test(line)) {
                                return false;
                            }
                            return true;

                            }

                            return false;
                        });

                        const text = texts.join("\n");
                        let pushedArrays = [];
                        const liness = text.split("\n"); // Split input into an array of lines
                        const data = liness.map(line => line.split(/\s{3,}/)); // Split each line into an array of values
                        let x = 0;
      
                        let id_length_1 = 0;
                        let id_length_5 = 0;
                        const outputArray = data.map((arr, index) => {
                        const currentLength = arr.length;
                        const nextLength = index + 1 < data.length ? data[index + 1].length : 0;

                        if (index === 0) {
                          
                          id_length_1 = index;
                          return arr;
                        } else {

                          let appendedArray = [];

                          if(currentLength === 1) {
                              id_length_1 = index;
                          }

                        
                          if (currentLength === 5) {
                            appendedArray = [data[id_length_1][0], ...arr];
                            id_length_5 = index;
                           
                          } else if (currentLength === 4) {
                            appendedArray = [data[id_length_1][0],data[id_length_5][0], ...arr];
                            
                          } else {
                            appendedArray = arr;
                          }
                            return appendedArray;

                        }
                         
                      });
                        // console.log("medicine",outputArray.filter(arr => arr.length !== 1));
                        return outputArray.filter(arr => arr.length !== 1);
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

            }

    $(document).ready(function(){
    
    // text_extract('pdf-file');
    $('#pdf-file').on('change',function(){
      // pdf_id = 'pdf-file';
      previewPdfFile('pdf-file');
      // text_extract('pdf-file');
    });
    $('#itemize-pdf-file').on('change',function(){
      // pdf_id = 'itemize-pdf-file';
      previewPdfFile('itemize-pdf-file');
      // text_extract('itemize-pdf-file');
    });
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
      formData.append('json_final_charges', json_final_charges);
      formData.append('benefits_deductions', benefits_deductions);

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
// function  text_extract(pdf_id){
// console.log(pdf_id);
// let pdfFileInput = document.getElementById(pdfs);
// var pdfFileInputs = document.querySelectorAll(pdfs);
pdfs.forEach(function(pdfid) {
let pdfFileInput = document.getElementById(pdfid);
  // console.log('executed');
pdfFileInput.addEventListener('change', function() {
  // console.log('executed');
let reader = new FileReader();
reader.onload = function() {
  // console.log('executed',pdfid);
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

                      const patient_pattern = /patient name:\s(.*?)\sregistry/si;
                      const matches_3 = finalResult.match(patient_pattern);
                      const result_3 = matches_3 ? matches_3[1] : null;
                      console.log("final result",finalResult);
                      console.log("patient name", result_3);
                      console.log('final text',final_text(finalResult));

                      //validate loa 
                      const valid_loa = /registry\s{1,}no:/i;
                      const invalid_loa = /admission\s{1,}no:/i;
                      if(!finalResult.match(valid_loa)){
                       
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
                                                  // window.location.reload();
                                              },
                                          },
                                      });
                                  }, 1000); // Delay of 2000 milliseconds (2 seconds)
                      }else{ if (patient_name.length) {

                        const names = patient_name.toLowerCase().split(' ').filter(Boolean);

                        let removedElement ="";

                        if(names[names.length-1] === ".jr"){
                            removedElement = names.splice(names.length-2, 1);
                        }else{
                            removedElement = names.splice(names.length-1, 1);
                        }
                            const mem_name = removedElement + ", " + names.join(' ');

                        if(!validate_name(result_3,mem_name)){
                        
                            $('#upload-btn').prop('disabled',true);

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
                        }

                       else{
                         
                          if(pdfid === 'pdf-file'){
                                var itemsPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;
                                const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                                const match = finalResult.match(regex);
                                console.log("match",match);
                                if (match && !itemsPattern.test(finalResult)) {
                                  const doc_pattern = /hospital charges(.*?)please pay for this amount/si;
                                  const matches_2 = finalResult.match(doc_pattern);
                                  const result_2 = matches_2 ? matches_2[1] : null;
                                  hospital_charges = result_2;
                                  benefits_deductions = JSON.stringify(get_deduction(final_text(finalResult)));
                                  attending_doctors = get_doctors(finalResult);
                                  console.log("doctors", attending_doctors);
                                  console.log("hospital charges", hospital_charges);
                                  console.log("JSON deduction",benefits_deductions);
                                  subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                                  net_bill=subtotalValue;
                                  document.getElementsByName("net-bill")[0].value = match[1];
                                  $('#upload-btn').prop('disabled',false);

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
                                                            // window.location.reload();
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
                              }else{
                                var itemPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;
                                
                                if (itemPattern.test(finalResult)) {
                                  $('#upload-btn').prop('disabled',false);
                                  get_all_item(final_text(finalResult));
                                  json_final_charges = JSON.stringify(get_all_item(final_text(finalResult)));
                                  console.log("JSON item",json_final_charges);
                                } else {
                                  $('#upload-btn').prop('disabled',true);
                                  $.alert({
                                          title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                                          content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that the uploaded pdf is not an itemized bill. Please review the PDF file and try again.</div>",
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
                            }
                            }
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
});

// end of the text extraction


      // const get_all_services = (lines,pattern,obj_name) => {

      //           let include = false;
      //           let include2 = true;

      //           const liness = lines.split("\n");
      //           const filteredLines = liness.filter((line) => {
      //               if (include) {
      //               return true;
      //               }
      //               if (new RegExp("\\b" + pattern + "\\b", "i").test(line)) {
      //               include = true;
      //               }
      //               return false;
      //           });

      //           const result = filteredLines.join("\n");

      //           if(result){
      //               const lin = result.split("\n");
              
      //               const texts = lin.filter(line => {

      //                   if (include2) {

      //                       if (/\btotal\b/i.test(line)) {
      //                           return false;
      //                       }

      //                       if (!/\s{3,}/i.test(line)) {
      //                           include2 = false;
      //                           return false;
      //                       }
                            
      //                       return true;

      //                       }
                            
      //                       return false;
      //                   });

      //                   const text = texts.join("\n");
                        
      //                   console.log("results",text);

      //                   const liness = text.split("\n"); // Split input into an array of lines
      //                   const data = liness.map(line => line.split(/\s{3,}/)); // Split each line into an array of values
      //                   // const data = liness.map(line => line.split(/\s{3,}/)); // Split each line into an array of values

      //                   // console.log("data", data); // Output the result
      //                   // final_charges[obj_name] = data;
      //                   final_charges[obj_name] = data;
                      
      //           }
                     
      //     }

      // const get_services = (lines,pattern,obj_name) => {

      //           let include = false;
      //           let include2 = true;

      //           const liness = lines.split("\n");
      //           const filteredLines = liness.filter((line) => {
      //               if (include) {
      //               return true;
      //               }
      //               if (new RegExp("\\b" + pattern + "\\b", "i").test(line)) {
      //               include = true;
      //               }
      //               return false;
      //           });

      //           const result = filteredLines.join("\n");

      //           if(result){
      //               const lin = result.split("\n");
              
      //               const texts = lin.filter(line => {

      //                   if (include2) {

      //                       if (/\btotal\b/i.test(line)) {
      //                           include2 = false;
      //                           return false;
      //                       }
      //                       return true;

      //                       }
                            
      //                       return false;
      //                   });

      //                   const text = texts.join("\n");
                        
      //                   console.log("results",text);

      //                   const liness = text.split("\n"); // Split input into an array of lines
      //                   const data = liness.map(line => line.split(/\s{3,}/)); // Split each line into an array of values
      //                   // console.log("data", data); // Output the result
      //                   // final_charges[obj_name] = data;
      //                   final_charges[obj_name] = data;
                      
      //           }
      //       }
      
  });

 

</script>