<!-- Page wrapper  -->
 <div class="page-wrapper">
    <!-- internal scripts -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/lone/axios.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/vue3.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2">Bill Services</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Healthcare Provider</li>
                        <li class="breadcrumb-item active" aria-current="page">
                        Bill Services
                        </li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->

    <!-- Container fluid  -->
    <div class="container-fluid">        
        <div class="row">
            <div class="col-12">
                <form method="post" id="form-med-services" class="needs-validation" action="<?php echo base_url(); ?>healthcare-provider/billing/billing-person/finalBilling" onsubmit="submitEquipment()">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-charges" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Cost Type</th>
                                            <th class="fw-bold">Quantity</th>
                                            <th class="fw-bold">Fee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" disabled id="submit-id" class="btn btn-info">
                                        <i class="mdi mdi-content-save me-1"></i>Apply
                                    </button>
                                </div> 
                            </div>
                        </div>
                    </div>                    
                </form>

                <div class="d-flex justify-content-center mx-5">
                    <input type="text" id="myInput" onkeyup="searchFunction()" placeholder="Search here for services to avail...">
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Name</th>
                                        <th class="fw-bold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($cost_type)):
                                        foreach ($cost_type as $ct) :
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?= $ct['cost_type']; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-success" id="btn<?= $ct['ctype_id'] ?>" onclick="addService('<?= $ct['ctype_id'] ?>',' <?= $ct['cost_type'] ?>')">
                                                        <i class="mdi mdi-plus-circle"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #myInput {
        width: 50%;
        font-size: 16px;
        padding: 10px;
        border: 1px solid #f1f1f1;
        margin-bottom: 20px;
        border-radius: 5px;
    }
</style>

<script>
    var numberOfRow = 0;
    var chargesEquip = [];
    var readySubmit = false;
    const baseUrlSubmit = "<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/addEquipments";
    const billingNumber = "<?php echo $member['billing_number'] ?>";
    const memberId = "<?php echo $member['member_id'] ?>";

    function submitEquipment() {

        $(".inputCT").each(function() {
            var input = $(this); // This is the jquery object of the input, do what you will

            if (!input.val()) {
                input.addClass("is-invalid")
            } else {
                input.removeClass("is-invalid");
                var idCostType = input.attr('id').substring(2);
                var finalIdCostType = "ct" + idCostType;
                var ctName = document.getElementById(finalIdCostType).innerText;

                console.log(baseUrlSubmit)
                $.ajax({
                    type: "post",
                    url: baseUrlSubmit,
                    data: {
                        token: "<?php echo $this->security->get_csrf_hash() ?>",
                        ctype_id: idCostType,
                        cost_type: ctName,
                        billingNumber: billingNumber,
                        emp_id: memberId,
                        amount: input.val()
                    },
                    dataType: "json",
                    success: function(res) {
                        console.log(res)
                    }
                })
                console.log({
                    token: "<?php echo $this->security->get_csrf_hash() ?>",
                    ctype_id: idCostType,
                    cost_type: ctName,
                    billingNumber: billingNumber,
                    emp_id: memberId,
                    amount: input.val()
                })
                chargesEquip.push({
                    token: "<?php echo $this->security->get_csrf_hash() ?>",
                    ctype_id: idCostType,
                    cost_type: ctName,
                    billingNumber: billingNumber,
                    emp_id: memberId,
                    amount: input.val()
                });
            }
        });
        $("#equipment_cost").val(JSON.stringify(chargesEquip));
    }


    function enableInput(x) {
        if (document.getElementById('cb' + x).checked) {
            $("." + x).prop("disabled", false);
        } else {
            $("." + x).prop("disabled", true);
            $("." + x).val(0);
        }
    }

    function addService(id, name) {
        numberOfRow++;

        var trId = "tr" + id;
        var finalTrId = trId.replace(/\s/g, '');

        var inId = "in" + id;
        var finalInId = inId.replace(/\s/g, '');

        var ctName = "ct" + id;
        var finalctName = ctName.replace(/\s/g, '');
        const baseUrl = "<?php echo base_url(); ?>healthcare-provider/reports/report-list/ajax/addEquip/" + id;
        console.log(baseUrl.replace(/\s/g, ''))
        $.ajax({
            type: "GET",
            url: baseUrl.replace(/\s/g, ''),
            dataType: "json",
            success: function(response) {
                $("#tbl-charges > tbody").append(
                    '<tr id="' + finalTrId + '" class="otherInput' + numberOfRow + '">\
                        <td class="fw-bold" style="width:50%">\
                            <span id="' + finalctName + '">' + name + '</span>\
                        </td>\
                        <td class="fw-bold" style="width:20%">\
                            <input type="number" id="' + finalInId + '" class="inputCT form-control" value="1" min="1" required>\
                        </td>\
                        <td class="fw-bold" style="width:30%">\
                            <input type="number" id="' + finalInId + '" class="inputCT form-control" required>\
                        </td>\
                        <td>\
                            <button onclick="removeService(' + id + ')" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Remove"><i class="mdi mdi-close "></i></button>\
                        </td>\
                    </tr>');
                var stringId = "#btn" + id;
                var stringRemove = stringId.replace(/\s/g, '');
                $("#submit-id").attr("disabled", false)
                $(stringRemove).attr("disabled", true);
            },
            error: function(data) {
                console.log("correct")
                console.log(data);
            }
        });
    }

    function removeService(id) {
        var idRemove = "#tr" + id
        numberOfRow--;
        //var finalidRemove = idRemove.replace(/\s/g, '')
        var stringId = "#btn" + id;
        var stringRemove = stringId.replace(/\s/g, '');
        $(stringRemove).attr("disabled", false);
        $(idRemove).remove();
        if (numberOfRow == 0) {
            $("#submit-id").attr("disabled", true)
        }
    }


    $(function() {
        $("#addRow").click(function(x) {
            var row = $('<tr class="otherInput' + number + '">' +
                '<td>Other</td><td><input type="text"  required></td><td></td></tr>');
            $("#tbl-charges > tbody").append(row);
            number++;

            $(".inputCT").each(function() {
                var input = $(this); // This is the jquery object of the input, do what you will
                if (input.val()) {
                    chargesEquip.push({
                        id: input.attr('id'),
                        name: "Test",
                        cost: input.val()
                    })
                }
            });
            console.log(chargesEquip)
        });
    });

    $(function() {
        $("#deleteRow").click(function() {
            number--;
            $(".otherInput" + number).remove();
        });
    });


    function searchFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>