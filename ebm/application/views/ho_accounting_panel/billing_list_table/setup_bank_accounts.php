<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
            <h4 class="page-title"><i class="mdi mdi-settings"></i> Bank Setup</h4>
            <div class="ms-auto text-end order-first order-sm-last">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Setup
                    </li>
                    </ol>
                </nav>
            </div>
        </div>
        </div>
    </div><hr>
    <!-- End Bread crumb and right sidebar toggle -->
    <div class="container-fluid">
        <form method="POST" id="submitBankForm">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" id="bank-id">
            <div class="row">
                <div class="col-lg-4 shadow" style="width:500px;height:500px;">
                    <br>
                    <i class="mdi mdi-table-edit fs-5 ps-2"></i> <span class="fs-5 fw-bold text-info">Setup Healthcare Provider Bank Accounts</span>
                    <div class="w-100 ps-2 pe-4 pb-3 pt-5 pb-5">
                        <div class="input-group">
                            <span class="text-danger fw-bold fs-5 pe-2">* </span>
                            <div class="input-group-prepend">
                                <span class="input-group-text text-dark fw-bold">
                                    <i class="mdi mdi-hospital-building"></i>
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="b-hospital-filter" id="b-hospital-filter">
                                <option value="">Select Hospital...</option>
                                <?php foreach($hc_provider as $hospital) : ?>
                                <option value="<?php echo $hospital['hp_id']; ?>"><?php echo $hospital['hp_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-6"><span class="text-danger">*</span> Bank Name: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="bank-name" id="bank-name" oninput="convertToUppercase(this)">
                            <div id="suggestions" style=""></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-6"><span class="text-danger">*</span> Account Name: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="acc-name" id="acc-name" oninput="convertToUppercase(this)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-6"><span class="text-danger">*</span> Account Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="acc-number" id="acc-number">
                            <small><span id="charCount" class="text-info"></span></small>
                        </div>
                    </div>
                    <div id="submitBtn" class="offset-9 col-md-3 pt-4">
                            <a type="button" href="JavaScript:void(0)" onclick="submitBankAccount()" data-bs-toggle="tooltip" class="btn btn-success px-3 ls-2 fs-5"><i class="mdi mdi-plus"></i> ADD</a>
                        </div>
                    <div id="updateBtn" class="offset-9 col-md-4 pt-4 d-none">
                        <a type="button" href="JavaScript:void(0)" onclick="updateBankAccount()" data-bs-toggle="tooltip" class="btn btn-info px-3 ls-2 fs-5"><i class="mdi mdi-grease-pencil"></i> Update</a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ps-1 pb-4 table-responsive">
                        <table class="table border" id="bankAccountTable">
                            <thead style="background-color:#00538C">
                                <tr class="border-secondary border-2 border-0 border-top border-bottom">
                                    <th class="text-white">#</th>
                                    <th class="text-white">Hospital</th>
                                    <th class="text-white" style="width:100px">Bank Name</th>
                                    <th class="text-white">Account Name</th>
                                    <th class="text-white">Account Number</th>
                                    <th class="text-white">Added On</th>
                                    <th class="text-white">Updated On</th>
                                    <th class="text-white">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div> 
</div>
<style>
    #suggestions {
        max-height: 100px;
        overflow-y: auto;
    }

    #suggestions div {
        padding: 5px;
        cursor: pointer;
    }

    #suggestions div:hover {
        background-color: #f2f2f2;
    }
</style>
<script>
    const baseUrl = '<?php echo base_url(); ?>';
    
    const bankNameToLengthMap = {
    'PNB': 12,
    'PHILIPPINE NATIONAL BANK (PNB)': 12,
    'PHILIPPINE NATIONAL BANK': 12,
    'LANDBANK': 10,
    'LBP': 10,
    'LANDBANK OF THE PHILIPPINES': 10,
    'BPI': 10,
    'BANK OF THE PHILIPPINES ISLANDS (BPI)': 10,
    'BANK OF THE PHILIPPINES ISLANDS': 10,
    'BANCO DE ORO': 12,
    'BANCO DE ORO (BDO)': 12,
    'BDO': 12,
    'METROBANK': 13,
    'UNIONBANK OF THE PHILIPPINES': 12,
    'BANK OF COMMERCE': 12,
    'CHINA BANK': 12,
    'RIZAL COMMERCIAL BANKING CORPORATION': 10,
    'RIZAL COMMERCIAL BANKING CORPORATION (RCBC)': 10,
    'RCBC': 10,
    'SECURITY BANK': 13,
    'CITIBANK': 16,
    'FIRST CONSOLIDATED BANK': 14,
    'FIRST CONSOLIDATED BANK (FCB)': 14,
    'FCB': 14,
    };

    const bankNameToMinLengthMap = {
    'PNB': 10,
    'PHILIPPINE NATIONAL BANK (PNB)': 10,
    'PHILIPPINE NATIONAL BANK': 10,
    'LANDBANK': 10,
    'LBP': 10,
    'LANDBANK OF THE PHILIPPINES': 10,
    'BPI': 10,
    'BANK OF THE PHILIPPINES ISLANDS (BPI)': 10,
    'BANK OF THE PHILIPPINES ISLANDS': 10,
    'BANCO DE ORO': 10,
    'BANCO DE ORO (BDO)': 10,
    'BDO': 10,
    'METROBANK': 10,
    'UNIONBANK OF THE PHILIPPINES': 12,
    'BANK OF COMMERCE': 12,
    'CHINA BANK': 10,
    'RIZAL COMMERCIAL BANKING CORPORATION': 10,
    'RIZAL COMMERCIAL BANKING CORPORATION (RCBC)': 10,
    'RCBC': 10,
    'SECURITY BANK': 13,
    'CITIBANK': 10,
    'FIRST CONSOLIDATED BANK': 14,
    'FIRST CONSOLIDATED BANK (FCB)': 14,
    'FCB': 14,
    };

    $(document).ready(function(){

    let bankAccountTable = $('#bankAccountTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/setup/bank-accounts/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
           data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          
        },
      },
      //Set column definition initialisation properties.
      columnDefs: [{
        "orderable": false, //set not orderable
      }, ],
      data: [],  // Empty data array
      deferRender: true,  // Enable deferred rendering
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    const input = document.getElementById('acc-number');
    const charCount = document.getElementById('charCount');
    const bankNameSelect = document.getElementById('bank-name');

    // Function to handle the input and bank-name change events
    const handleInputChange = () => {
        const bankName = bankNameSelect.value;
        const maxLength = bankNameToLengthMap[bankName] || 16;

        const remaining = maxLength - input.value.length;
        charCount.textContent = `Characters remaining: ${Math.max(0, remaining)}`;

        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }
    };

    // Attach the input event listener
    input.addEventListener('input', handleInputChange);

    // Attach the bank-name change event listener
    bankNameSelect.addEventListener('change', handleInputChange);

    window.onload = function() {
        handleInputChange();
    }

    var suggestions = ["PNB", "PHILIPPINE NATIONAL BANK (PNB)", "PHILIPPINE NATIONAL BANK", "LANDBANK", "LBP", "LANDBANK OF THE PHILIPPINES", "BPI", "BANK OF THE PHILIPPINES ISLANDS (BPI)", "BANK OF THE PHILIPPINES ISLANDS", "BANCO DE ORO", "BDO", "BANCO DE ORO (BDO)", "METROBANK", "UNIONBANK OF THE PHILIPPINES", "BANK OF COMMERCE", "CHINA BANK", "RIZAL COMMERCIAL BANKING CORPORATION", "RIZAL COMMERCIAL BANKING CORPORATION (RCBC)", "RCBC", "SECURITY BANK", "CITIBANK", "FIRST CONSOLIDATED BANK", "FIRST CONSOLIDATED BANK (FCB)", "FCB"];

    bankNameSelect.addEventListener('keyup', function() {
        var inputbank = document.getElementById("bank-name");
        var filter = inputbank.value.toUpperCase();
        var suggestionContainer = document.getElementById("suggestions");
        suggestionContainer.innerHTML = ""; // Clear previous suggestions

        // Filter the suggestions based on user inputbank
        var filteredSuggestions = suggestions.filter(function (item) {
            return item.toUpperCase().indexOf(filter) > -1;
        });

        // Display the filtered suggestions
        filteredSuggestions.forEach(function (item) {
            var suggestion = document.createElement("div");
            suggestion.textContent = item;
            suggestion.addEventListener("click", function () {
            // Set the selected suggestion as the inputbank value
            inputbank.value = item;
            suggestionContainer.innerHTML = ""; // Clear the suggestions
            });
            suggestionContainer.appendChild(suggestion);
        });
    });

    });

    

    const submitBankAccount = () => {
        const input = document.getElementById('acc-number');
        const bankNameSelect = document.getElementById('bank-name');
        const bankName = bankNameSelect.value;
        const minLength = bankNameToMinLengthMap[bankName] || 8;
        let dialog;

         if (input.value.length < minLength) {
            // Show notification for character count below minimum length
            // Replace the following line with your own notification logic
            alert(`Account Number minimum length should be ${minLength} characters.`);
            dialog.close();
        }

        dialog  = $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: 'Are you sure? Please review before you proceed.',
            type: 'green',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function(){
                        $.ajax({
                            url: `${baseUrl}head-office-accounting/setup/submit-bank-account`,
                            type: "POST",
                            data: $('#submitBankForm').serialize(), 
                            dataType: "json",
                                success: function(response){
                                const {
                                    token, status, message, hp_error, bank_error, acc_name_error, acc_num_error
                                } = response;

                                if(status == 'validation-error'){
                                    if(hp_error != ''){
                                        $("#b-hospital-filter").addClass('is-invalid');
                                    }else{
                                        $("#b-hospital-filter").removeClass('is-invalid');
                                    }

                                    if(bank_error != ''){
                                        $("#bank-name").addClass('is-invalid');
                                    }else{
                                        $("#bank-name").removeClass('is-invalid');
                                    }

                                    if(acc_name_error != ''){
                                        $("#acc-name").addClass('is-invalid');
                                    }else{
                                        $("#acc-name").removeClass('is-invalid');
                                    }

                                    if(acc_num_error != ''){
                                        $("#acc-number").addClass('is-invalid');
                                    }else{
                                        $("#acc-number").removeClass('is-invalid');
                                    }
                                }
                                if(status == 'success'){
                                    $('#submitBankForm')[0].reset();
                                    swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 5000,
                                        showConfirmButton: false,
                                        type: 'success'
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                }else if(status == 'error'){
                                    swal({
                                        title: 'Error',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: true,
                                        type: 'error'
                                    });
                                }
                            }
                        }); 
                    },
                },
                cancel: {
                    btnClass: 'btn-dark',
                    action: function() {
                        // close dialog
                    }
                },
            }
        });
    }
    const convertToUppercase = (input) => {
        input.value = input.value.toUpperCase();
    }

    const deleteRow = (bank_id) => {
        $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: 'Delete Bank Account?',
            type: 'red',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function(){
                        $.ajax({
                            url: `${baseUrl}head-office-accounting/setup/delete-bank-account`,
                            type: "POST",
                            data: {
                                'token' : '<?php echo $this->security->get_csrf_hash();?>',
                                'bank_id' : bank_id,
                            },
                            dataType: "json",
                                success: function(response){
                                const {
                                    token, status, message
                                } = response;

                                if(status == 'success'){
                                    swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 5000,
                                        showConfirmButton: false,
                                        type: 'success'
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                }else if(status == 'error'){
                                    swal({
                                        title: 'Error',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: true,
                                        type: 'error'
                                    });
                                }
                            }
                        }); 
                    },
                },
                cancel: {
                    btnClass: 'btn-dark',
                    action: function() {
                        // close dialog
                    }
                },
            }
        });
    }

    const updateBankAccount = () => {
        const hp_id = document.querySelector('#b-hospital-filter').value;
        const bank_name = document.querySelector('#bank-name').value;
        const account_name = document.querySelector('#acc-name').value;
        const account_num = document.querySelector('#acc-number').value;
        const bank_id = document.querySelector('#bank-id').value;
        let dialog;

        const minLength = bankNameToMinLengthMap[bank_name] || 8;

         if (account_num.length < minLength) {
            // Show notification for character count below minimum length
            // Replace the following line with your own notification logic
            alert(`Account Number minimum length should be ${minLength} characters.`);
            dialog.close();

        }
   
        dialog = $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: 'Are you sure? Please review before you proceed.',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function(){
                        $.ajax({
                            url: `${baseUrl}head-office-accounting/setup/update-bank-account`,
                            type: "POST",
                            data:  {
                                'token' : '<?php echo $this->security->get_csrf_hash();?>',
                                'hp_id' : hp_id,
                                'bank_name' : bank_name,
                                'account_name' : account_name,
                                'account_num' : account_num,
                                'bank_id' : bank_id
                            },
                            dataType: "json",
                                success: function(response){
                                const {
                                    token, status, message
                                } = response;

                                if(status == 'success'){
                                    $('#submitBankForm')[0].reset();
                                    $('#submitBtn').show();
                                    $('#updateBtn').addClass('d-none');
                                    swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 5000,
                                        showConfirmButton: false,
                                        type: 'success'
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);

                                }else if(status == 'error'){
                                    swal({
                                        title: 'Error',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: true,
                                        type: 'error'
                                    });
                                }
                            }
                        }); 
                    },
                },
                cancel: {
                    btnClass: 'btn-dark',
                    action: function() {
                        // close dialog
                    }
                },
            }
        });
    }

    const editRow = (bank_id, hp_id, hp_name, bank_name, account_name, account_number) => {
        $('#submitBtn').hide();
        $('#updateBtn').removeClass('d-none');

        // Clear the select options first
        $('#b-hospital-filter').empty();

        // Append a new option with the hp_name
        $('#b-hospital-filter').append($('<option>', {
            value: hp_id,
            text: hp_name
        }));

        $('#bank-name').val(bank_name);
        $('#acc-name').val(account_name);
        $('#acc-number').val(account_number);
        $('#bank-id').val(bank_id);
    }

   


</script>