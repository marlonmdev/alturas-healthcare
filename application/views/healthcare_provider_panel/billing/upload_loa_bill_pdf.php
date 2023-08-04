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
      <div id="progress-bar"></div>
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
                                <input type="text" class="form-control fw-bold ls-1" id="remaining-balance" name="remaining-balance" value="<?= number_format($remaining_balance,2) ?>"  readonly>
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

                    <div id = "itemized_holder" class="col-lg-6">
                                    <label class="fw-bold fs-5 ls-1" id="">
                                        <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Itemized Billing 
                                    </label>
                                    <input type="file" class="form-control" name="itemize-pdf-file" id="itemize-pdf-file" accept="application/pdf">
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
  let valid_services = true;
  let pdf_id = "pdf-file";
  const pdfs = ['pdf-file','itemize-pdf-file'];
  const services = <?php echo json_encode($services); ?>;

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
  "particulars",
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

      const get_ph_deduction = (text) => {

        // var lines = text.split("\n");
        const final_text = text.replace(/(\([\d,.]+\))/g, match => match.replace(/[()]/g, ''));
        console.log('final deduction',final_text);
        let include = false;
        const regex = /subtotal([\s\S]*?)total/gi;
        const matches = final_text.match(regex);

        const payregex = /payment([\s\S]*?)subtotal/gi;
        const paymatches = final_text.match(payregex);
        let data = [];
        let ptexts = [];
        var paydeductions = paymatches.join("\n").split("\n");
        console.log("payment matches",deductions);
        if(paymatches!==null){
          ptexts = paydeductions.filter(line => {

            if (/\bpayment\b/i.test(line)) {
              include = true;
            }

            if (/\bsubtotal\b/i.test(line)) {
              include = false;
              return false;
            }

            if (include) {
              return true;
            }

            return false;

          });
        }

        if(matches!==null){
          var deductions = matches.join("\n").split("\n");
          console.log("ph matches",deductions);
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
          data = texts .map(line => {
            const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g, '');
            const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
            return splitLine;
          });

          const pdata = ptexts .map(line => {
            const amount = line.split(/\s{2,}/).pop();
            return amount;
          });

          console.log('pdata',pdata);
          let ph_counter = 0;
          const outputArray = data.map((arr, index) => {
            if(arr.length === 1){ 
              if(paymatches!==null){
                const py_amount = pdata[ph_counter];
                ph_counter++; 
                return [...arr,py_amount];
              }
            }else{
              return [...arr];
            }
          });

          console.log("ph deductions",outputArray);
          return (outputArray.length > 1)?outputArray:[];
        }
        return null;
      };

      const get_selfpay_deduction = (text) => {

        // var lines = text.split("\n");
        const final_text = text.replace(/(\([\d,.]+\))/g, match => match.replace(/[()]/g, ''));
        console.log('final deduction',final_text);
        let include = false;
        const regex = /subtotal([\s\S]*?)total/gi;
        const matches = final_text.match(regex);

        let data = [];
        if(matches!==null){
          var deductions = matches.join("\n").split("\n");
          // console.log("se",deductions);
          const texts = deductions.filter(line => {

            if (/\bsubtotal\b/i.test(line)) {
              include = true;
              return false;
            }
            if (/\btotal\b/i.test(line)) {
              include = false;
            // return false;
            }

            if (include) {
              return true;
            }

            return false;
          });
          console.log('self text',texts);
          // data = texts.map(line => line.split(/\s{2,}/));
          data = texts .map(line => {
            const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g, '');
            const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
            return splitLine;
          });
          
          const outputArray = data.map((arr, index) => {
            if(arr.length > 1){   
              return [...arr];
            }
          });

          console.log("self pay deductions",(outputArray.length > 1)? outputArray : []);
          return (outputArray.length > 1)?outputArray:[];
        }
        return null;
      };

      // const get_hospital_charges = (text) => {

      //   let include = true;
      
      // const lin = text.split("\n");

      // const texts = lin.filter(line => {
      //   if(include){

      //         if (/\bsubtotal\b/i.test(line)) {
      //           include = false;
      //             return false;
      //         }

      //         return true;
      //   }
      //     });

      //   console.log('hospital text',texts);
      //   // data = texts.map(line => line.split(/\s{2,}/));
      //   data = texts.map(line => {
      //     const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g, '');
      //     const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
      //     return splitLine;
      //   });
        
      //   const outputArray = data.map((arr, index) => {
      //     if(arr.length === 1){   
      //       return [...arr, '0'];
      //     }else{
      //       return [...arr];
      //     }
      //   });
      //   console.log("final charges",outputArray);
      //   return outputArray;
      // };

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

                            const variableValue = arr[1]; // Assuming arr[1] is the variable you want to add
                            const pattern = new RegExp(`\\b${variableValue}\\b`, "i");
                            if(services !== 'Consultation' && services !== 'Emergency'){
                              const filteredLines = services.map((line) => {
                                
                                if (!pattern.test(line.toLowerCase())) {
                                  valid_services = false;
                                }
                              });
                            }

                            console.log("arr", arr[1]);
                          } else if (currentLength === 4) {
                            appendedArray = [data[id_length_1][0],data[id_length_5][0], ...arr];

                            const variableValue = arr[0]; // Assuming arr[1] is the variable you want to add
                            const pattern = new RegExp(`\\b${variableValue}\\b`, "i");
                            if(services !== 'Consultation' && services !== 'Emergency'){
                              const filteredLines = services.map((line) => {
                                
                                if (!pattern.test(line.toLowerCase())) {
                                  valid_services = false;
                                }
                              });
                            }
                            console.log("arr",arr[0]);
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
    console.log("services",services);
    $('#itemize-pdf-file').prop('required',true);
    if(services === 'Consultation'){
      $('#itemized_holder').hide();
      $('#itemize-pdf-file').prop('required',false);
    }
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
                              // window.location.href = `${baseUrl}healthcare-provider/billing`;
                              window.history.replaceState({}, document.title, `${baseUrl}healthcare-provider/billing`);
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

// pdfs.forEach(async function(pdfid) {
//   let pdfFileInput = document.getElementById(pdfid);
  
//   pdfFileInput.addEventListener('change', async function() {
//     let reader = new FileReader();

//     reader.onload = async function() {
//       let typedarray = new Uint8Array(this.result);
//       try {
//         let pdf = await pdfjsLib.getDocument(typedarray).promise;
//         let numPages = pdf.numPages;
//         let promises = [];
      
//         console.log("number of pages", numPages);

//         for (let page = 1; page <= numPages; page++) {
//           let currentPage = await pdf.getPage(page);
//           let textContent = await currentPage.getTextContent();

//           const sortedItems = textContent.items
//             .map(function(item) {
//               return { text: item.str.toLowerCase(), x: item.transform[4], y: item.transform[5] };
//             })
//             .sort(function(a, b) {
//               if (Math.abs(a.y - b.y) < 5) {
//                 return a.x - b.x;
//               } else {
//                 return b.y - a.y;
//               }
//             })
//             .reduce(function(groups, item) {
//               const lastGroup = groups[groups.length - 1];
//               if (lastGroup && Math.abs(lastGroup.y - item.y) < 5) {
//                 lastGroup.text += ' ' + item.text;
//               } else {
//                 groups.push({ text: item.text, x: item.x, y: item.y });
//               }
//               return groups;
//             }, []);

        

//           promises.push(sortedItems);
//         }

//         let results = await Promise.all(promises);
//         let finalItems = results.flat();
//         console.log(finalItems);

//         let finalResult = finalItems.reduce(function(result, item) {
//           const pattern = /\.{2,}(?!\.)/g;
//           return (result = result + '\n' + item.text.replace(pattern, ''));
//         }, '').trim();
//         // Add conditions for extracted text
//         // console.log(finalResult);
//                       //validate loa 
//                       const valid_loa = /registry\s{1,}no:/i;
//                       if(!finalResult.match(valid_loa)){
//                         $('#upload-btn').prop('disabled',true);
//                         setTimeout(function() {
//                             $.alert({
//                                           title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>ERROR</h3>`,
//                                           content:`<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused and would like to inform you that the uploaded PDF does not meet the criteria of being an ${(pdfid !== 'pdf-file'?'itemized bill of':'')} LOA (Letter of Authorization). We highly value your understanding in this matter and extend our heartfelt gratitude for your unwavering cooperation.</div>`,
//                                           buttons: {
//                                               ok: {
//                                                   text: "OK",
//                                                   btnClass: "btn-danger",
//                                                   // window.location.reload();
//                                                   action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
//                                               },
//                                           },
//                                       });
//                                   }, 1000); // Delay of 2000 milliseconds (2 seconds)
//                       }else{
//                         const patient_pattern = /patient name:\s(.*?)\sregistry/si;
//                         const matches_3 = finalResult.match(patient_pattern);
//                         const result_3 = matches_3 ? matches_3[1] : null;
//                         const doctor_pattern = /attending doctor\(s\):\s(.*?)\sregistry/si;
//                         const matches_doc = finalResult.match(doctor_pattern);
//                         const result_doc = (matches_doc[1].length>3) ? matches_doc[1] : null;
                        
//                         console.log("final result",finalResult);
//                         console.log("doctor result",result_doc);
//                         console.log("patient name", result_3);
//                         console.log('final text',final_text(finalResult));

//                         if (patient_name.length) {

//                         const names = patient_name.toLowerCase().split(' ').filter(Boolean);

//                         let removedElement ="";

//                         if(names[names.length-1] === ".jr"){
//                             removedElement = names.splice(names.length-2, 1);
//                         }else{
//                             removedElement = names.splice(names.length-1, 1);
//                         }
//                             const mem_name = removedElement + ", " + names.join(' ');

//                         if(!validate_name(result_3,mem_name)){
                        
//                             $('#upload-btn').prop('disabled',true);

//                             $.alert({
//                                     title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
//                                     content: `<div style='font-size: 16px; color: #333;'>The uploaded PDF bill does not match the member's name. Please ensure that you have uploaded the correct PDF bill.</div>`,
//                                     type: "red",
//                                     buttons: {
//                                     ok: {
//                                         text: "OK",
//                                         btnClass: "btn-danger",
//                                         action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
//                                     },
//                                 },
//                             });
//                         }

//                        else{
                         
//                           if(pdfid === 'pdf-file'){
//                                 var itemsPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;
//                                 const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
//                                 const hosp_plan = /hospitalization plan:\s(.*?)\sage/si;
//                                 const hpmatch = finalResult.match(hosp_plan);
//                                 const match = finalResult.match(regex);
                                
//                                 console.log("match",hpmatch[1]);
                                
//                                 if (match) {
//                                   const doc_pattern = /hospital charges(.*?)please pay for this amount/si;
//                                   const matches_2 = finalResult.match(doc_pattern);
//                                   const result_2 = matches_2 ? matches_2[1] : null;
//                                   hospital_charges = result_2;

//                                   if(hpmatch[1].replace(/\s/g, "")!=='self-pay'){
//                                     benefits_deductions = JSON.stringify(get_ph_deduction(final_text(finalResult)));
//                                   }else{
//                                     benefits_deductions = JSON.stringify(get_selfpay_deduction(final_text(finalResult)));
//                                   }
                              
//                                   attending_doctors = (result_doc !== null) ? get_doctors(final_text(finalResult)) : null;
//                                   console.log("doctors", attending_doctors);
//                                   console.log("hospital charges", hospital_charges);
//                                   console.log("JSON deduction",benefits_deductions);
//                                   subtotalValue = parseFloat(match[1].replace(/,/g, ""));
//                                   net_bill=subtotalValue;
                                 
//                                   document.getElementsByName("net-bill")[0].value = match[1];
//                                   $('#upload-btn').prop('disabled',false);
                                  
//                                   if(parseFloat(net_bill)>mbl){
//                                   // $('#upload-btn').prop('disabled',true);
//                                                 setTimeout(function() {
//                                                 $.alert({
//                                                     title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Warning</h3>`,
//                                                     content: "<div style='font-size: 16px; color: #333;'>The uploaded PDF Bill exceeds the patient's MBL balance.</div>",
//                                                     type: "red",
//                                                     buttons: {
//                                                         ok: {
//                                                             text: "OK",
//                                                             btnClass: "btn-danger",
//                                                             // window.location.reload();
//                                                         },
//                                                     },
//                                                 });
//                                             }, 1000); // Delay of 2000 milliseconds (2 seconds)
//                                     }

//                                 } else {
                        
//                                 console.log("please pay for this amount is not found");
//                                 $('#upload-btn').prop('disabled',true);
//                                 $.alert({
//                                         title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
//                                         content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that there was an issue with the uploaded PDF. Please review the PDF file and try again.</div>",
//                                         type: "red",
//                                         buttons: {
//                                         ok: {
//                                             text: "OK",
//                                             btnClass: "btn-danger",
//                                             action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
//                                         },
//                                     },
//                                 });
//                                 }
//                               }else{
//                                 var itemPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;
                                
//                                 if (itemPattern.test(finalResult)) {
//                                   $('#upload-btn').prop('disabled',false);
//                                   get_all_item(final_text(finalResult));
//                                   json_final_charges = JSON.stringify(get_all_item(final_text(finalResult)));
//                                   console.log("JSON item",json_final_charges);
//                                   if(!valid_services){
//                                     $.alert({
//                                                       title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>WARNING</h3>`,
//                                                       content: "<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused. It has come to our attention that there may be discrepancies or possible overages in the hospital charges. We deeply appreciate your patience and understanding as we address this matter promptly.</div>",
//                                                       type: "red",
//                                                       buttons: {
//                                                           ok: {
//                                                               text: "OK",
//                                                               btnClass: "btn-danger",
                                                
//                                                               // window.location.reload();
//                                                           },
//                                                       },
//                                                   });
//                                   }
//                                 } else {
//                                   $('#upload-btn').prop('disabled',true);
//                                   $.alert({
//                                           title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
//                                           content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that the uploaded pdf is not an itemized bill. Please review the PDF file and try again.</div>",
//                                           type: "red",
//                                           buttons: {
//                                           ok: {
//                                               text: "OK",
//                                               btnClass: "btn-danger",
//                                                 action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
//                                           },
//                                       },
//                                   });
//                                 }
                                
//                               }
//                             }
//                             }
//                           }
                  
//                       console.log("netbill",net_bill);
//                       console.log("mbl",mbl);
//       } catch (error) {
//         console.error(error);
//                                   $.alert({
//                                           title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
//                                           content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience. The system encountered an error. Please refresh current window. Thank you for your understanding.</div>",
//                                           type: "red",
//                                           buttons: {
//                                           ok: {
//                                               text: "OK",
//                                               btnClass: "btn-danger",
//                                           },
//                                       },
//                                   });
//       }
//     };
//     //end of async function
//     if (this.files[0]) {
//       reader.readAsArrayBuffer(this.files[0]);
//     }
//   });
// });
// end of the text extraction
      // Assuming pdfs is an array of PDF IDs
    pdfs.forEach((pdfid) => handlePDFChange(pdfid));
  });

  async function processPDF(pdfFileInput) {
  return new Promise((resolve, reject) => {
    let reader = new FileReader();

    reader.onload = async function() {
      let typedarray = new Uint8Array(this.result);
      try {
        let pdf = await pdfjsLib.getDocument(typedarray).promise;
        let numPages = pdf.numPages;
        let promises = [];

        console.log("number of pages", numPages);

        for (let page = 1; page <= numPages; page++) {
          let currentPage = await pdf.getPage(page);
          let textContent = await currentPage.getTextContent();

          const sortedItems = textContent.items
            .map(function(item) {
              return { text: item.str.toLowerCase(), x: item.transform[4], y: item.transform[5] };
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
                groups.push({ text: item.text, x: item.x, y: item.y });
              }
              return groups;
            }, []);

        

          promises.push(sortedItems);
        }

        

        resolve(promises); // Resolve the promise with the collected results
      } catch (error) {
        reject(error); // Reject the promise with the encountered error
      }
    };

    if (pdfFileInput.files[0]) {
      reader.readAsArrayBuffer(pdfFileInput.files[0]);
    }
  });
}

async function handlePDFChange(pdfid) {
  let pdfFileInput = document.getElementById(pdfid);

  pdfFileInput.addEventListener('change', async function() {
    try {
      let promises = await processPDF(pdfFileInput);
      // let results = await Promise.all(promises);
        let finalItems = promises.flat();
        console.log(finalItems);

        let finalResult = finalItems.reduce(function(result, item) {
          const pattern = /\.{2,}(?!\.)/g;
          return (result = result + '\n' + item.text.replace(pattern, ''));
        }, '').trim();
        // Add conditions for extracted text
        // console.log(finalResult);
                      //validate loa 
                      const valid_loa = /registry\s{1,}no:/i;
                      if(!finalResult.match(valid_loa)){
                        $('#upload-btn').prop('disabled',true);
                        setTimeout(function() {
                            $.alert({
                                          title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>ERROR</h3>`,
                                          content:`<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused and would like to inform you that the uploaded PDF does not meet the criteria of being an ${(pdfid !== 'pdf-file'?'itemized bill of':'')} LOA (Letter of Authorization). We highly value your understanding in this matter and extend our heartfelt gratitude for your unwavering cooperation.</div>`,
                                          buttons: {
                                              ok: {
                                                  text: "OK",
                                                  btnClass: "btn-danger",
                                                  // window.location.reload();
                                                  action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
                                              },
                                          },
                                      });
                                  }, 1000); // Delay of 2000 milliseconds (2 seconds)
                      }else{
                        const patient_pattern = /patient name:\s(.*?)\sregistry/si;
                        const matches_3 = finalResult.match(patient_pattern);
                        const result_3 = matches_3 ? matches_3[1] : null;
                        const doctor_pattern = /attending doctor\(s\):\s(.*?)\sregistry/si;
                        const matches_doc = finalResult.match(doctor_pattern);
                        const result_doc = (matches_doc[1].length>3) ? matches_doc[1] : null;
                        
                        console.log("final result",finalResult);
                        console.log("doctor result",result_doc);
                        console.log("patient name", result_3);
                        console.log('final text',final_text(finalResult));

                        if (patient_name.length) {

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
                                        action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
                                    },
                                },
                            });
                        }

                       else{
                         
                          if(pdfid === 'pdf-file'){
                                var itemsPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;
                                const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                                const hosp_plan = /hospitalization plan:\s(.*?)\sage/si;
                                const hpmatch = finalResult.match(hosp_plan);
                                const match = finalResult.match(regex);
                                let subtotalValue = 0;
                                console.log("match",hpmatch[1]);
                                
                                if (match) {
                                  const doc_pattern = /hospital charges(.*?)please pay for this amount/si;
                                  const matches_2 = finalResult.match(doc_pattern);
                                  const result_2 = matches_2 ? matches_2[1] : null;
                                  hospital_charges = result_2;

                                  if(hpmatch[1].replace(/\s/g, "")!=='self-pay'){
                                    benefits_deductions = JSON.stringify(get_ph_deduction(final_text(finalResult)));
                                  }else{
                                    benefits_deductions = JSON.stringify(get_selfpay_deduction(final_text(finalResult)));
                                  }
                              
                                  attending_doctors = (result_doc !== null) ? get_doctors(final_text(finalResult)) : null;
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
                                            action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
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
                                  if(!valid_services){
                                    $.alert({
                                                      title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>WARNING</h3>`,
                                                      content: "<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused. It has come to our attention that there may be discrepancies or possible overages in the hospital charges. We deeply appreciate your patience and understanding as we address this matter promptly.</div>",
                                                      type: "red",
                                                      buttons: {
                                                          ok: {
                                                              text: "OK",
                                                              btnClass: "btn-danger",
                                                
                                                              // window.location.reload();
                                                          },
                                                      },
                                                  });
                                  }
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
                                                action : function(){(pdfid === 'pdf-file')?$('#pdf-file').val(''):$('#itemize-pdf-file').val('')}
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
      console.log('promises',promises);
    } catch (error) {
      console.error(error);
      // Handle the error appropriately
    }
  });
}


</script>