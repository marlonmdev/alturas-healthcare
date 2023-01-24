<!-- Page wrapper  -->
 <div class="page-wrapper">
    <!-- internal scripts -->
    <script src="<?php echo base_url(); ?>assets/js/lone/axios.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/vue3.js"></script>
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
                <form method="post" id="form-id" class="needs-validation" action="<?php echo base_url('healthcare-provider/billing/billing-person/finalBilling'); ?>" onsubmit="submitEquipment()">
                    <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <table id="mt" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Description</th>
                                <th scope="col">Charges</th>
                            </tr>
                        </thead>
                        <tbody id="survey_options">
                        </tbody>
                    </table>
                    <button type="submit" disabled id="submit-id" class="btn btn-info">
                        <i class="mdi mdi-content-save me-1"></i>Apply
                    </button>
                </form>

                <input type="text" id="myInput" onkeyup="searchFunction()" placeholder="Search for names.." title="Type in a name">
                <table id="myTable" class="table table-hover">
                    <tr class="header">
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                    </tr>
                    <?php
                    if (!empty($cost_type)) {
                        foreach ($cost_type as $ct) :
                    ?>
                            <tr>
                                <td>
                                    <?php echo $ct->cost_type; ?>
                                </td>
                                <td>
                                    <button class="btn btn-success" id="btn<?php echo $ct->ctype_id ?>" onclick="addEquipment('<?php echo $ct->ctype_id; ?>',' <?php echo $ct->cost_type ?>')">
                                        <i class="mdi mdi-plus-circle"></i> Add
                                    </button>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    }
                    ?>
                </table>
            </div>

        </div>
    </div>

</div>

<style>
    * {
        box-sizing: border-box;
    }

    #form-id {
        margin-bottom: 3%;
    }

    #myInput {
        background-image: url('/css/searchicon.png');
        background-position: 10px 10px;
        background-repeat: no-repeat;
        width: 100%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }

    #myTable {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
        font-size: 18px;
    }

    #myTable th,
    #myTable td {
        text-align: left;
        padding: 12px;
    }

    #myTable tr {
        border-bottom: 1px solid #ddd;
    }

    #myTable tr.header,
    #myTable tr:hover {
        background-color: #f1f1f1;
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

    function addEquipment(id, name) {
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
                $("#mt > tbody").append(
                    '<tr id="' + finalTrId + '" class="otherInput' + numberOfRow + '">\
                        <td>\
                            <span id="' + finalctName + '">' + name + '</span>\
                        </td>\
                        <td>\
                            <input id="' + finalInId + '" class="inputCT form-control" required type="number" >\
                        </td>\
                        <td>\
                            <button onclick="removeEquipment(' + id + ')" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Remove"><i class="mdi mdi-close "></i></button>\
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

    function removeEquipment(id) {
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
            $("#mt > tbody").append(row);
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