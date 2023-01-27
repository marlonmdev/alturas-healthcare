<script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
<main id="main" class="main">
    <div class="card">
        <div class="container">
            <h1> <i class="bx bxs-receipt align-middle bx-icon icon-dark"></i> Billing </h1>
        </div>

    </div>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>No Member Found!!</strong>
        </div>
    <?php endif; ?>
    <?php
        $totalBill = 0;
        foreach ($this->cart->contents() as $row) {
            $totalBill += $row['price'];
        } 
    ?>
    <div class="card">
        <div class="cantainer">
            <form method="post" action="<?php echo base_url(); ?>healthcare-provider/billing/billing-person/finish" onsubmit="finalSubmit()" class="needs-validation" novalidate>
                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="hp_id" value="<?= $payload['hp_id'] ?>">
                <input type="hidden" name="member_id" value="<?= $payload['member_id'] ?>">
                <input type="hidden" name="noa_id" value="<?= $payload['noa_id'] ?>">
                <!-- <input type="hidden" name="mbr_remaining_bal" value=" max($totalBill - $payload["remaining_balance"], 0); ?>"> -->
                <?php print_r($payload["remaining_balance"]); ?>
                <input type="hidden" name="total_bill" value="<?= $totalBill  ?>">
                <!-- <input type="hidden" name="personal_charges" value=" max($totalBill - $payload["remaining_balance"], 0) ?>"> -->
                <input type="hidden" name="equipment_array_string" value='<?= strval($payload['equipment_array_string']) ?> '>

                <div class="row" style="margin-left: 7%; margin-right: 7%; margin-top:3%">
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputFirstName" class="form-label"><b>Hospital</b></label>
                            <input type="text" class="form-control" name="hospital_name" readonly value="<?php echo $payload["hospital_name"] ?>" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputLastName" class="form-label"><b>Date of Service</b></label>
                            <input type="date" class="form-control" name="date_service" readonly value="<?php echo $payload["date_service"]; ?>">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-left: 7%; margin-right: 7%;">
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputFirstName" class="form-label"><b>Employer Name</b></label>
                            <input type="text" name="requesting_company" class="form-control" readonly value="Alturas Supermarket Corp." aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputLastName" class="form-label"><b>Billing #</b></label>
                            <input type="text" name="billing_number" class="form-control" readonly value="<?php echo $payload["billing_number"] ?>">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-left: 7%; margin-right: 7%;">
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputFirstName" class="form-label"><b>Member Name</b></label>
                            <input type="text" class="form-control" name="full_name" readonly value="<?php echo $payload["full_name"] ?>" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputLastName" class="form-label"><b>Health Card #</b></label>
                            <input type="text" class="form-control" name="health_card_no" readonly value="<?php echo $payload["health_card_no"] ?>">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-left: 7%; margin-right: 7%;">
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputFirstName" class="form-label"><b>Type of Member</b></label>
                            <input type="text" class="form-control" name="emp_type" readonly value="<?php echo $payload["emp_type"] ?>" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="mb-3">
                            <label for="inputLastName" class="form-label"><b>Remaining MBL</b></label>

                            <input type="text" class="form-control" name="remaining_balance" value="₱ <?php echo $this->cart->format_number(max($payload["remaining_balance"] - $totalBill, 0)); ?>" readonly>
                        </div>
                    </div>
                </div>


                <div class="container" style="padding-left: 8%;margin-top:3%; padding-right:8%;">
                    <!-- <div class="card" style="background-color:#E7E9EB; box-shadow: 10px 10px 10px 5px lightblue; padding:25px">
                        <div class="container">
                            <div class="col-sm">
                                <div class="mb-3">
                                    <label for="inputLastName" class="form-label">
                                        <h3>PhilHealth</h3>
                                    </label>
                                    <input type="text" class="form-control" style=" border: 2px solid black;" name="philHealth" value="">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <table id="myTable" class="table table-hover">
                        <tr class="header">
                            <th scope="col">Name</th>
                            <th scope="col">Cost</th>
                            <!-- <th scope="col">Action</th> -->
                        </tr>
                        <?php
                        foreach ($this->cart->contents() as $row) {
                        ?>
                            <tr>
                                <th><?php echo $row['name']; ?></th>
                                <th>₱ <?php echo $this->cart->format_number($row['price']); ?></th>
                                <!-- <th>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clickModal('<?php echo $row['name']; ?>','<?php echo $row['price']; ?>')">
                                        Edit
                                    </button>
                                </th> -->
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="row" style="margin-left: 2%; margin-bottom:2%; margin-right: 7%;">
                    <div class="col-sm">
                        <div class="row">
                            <div class="col">
                                <h1>Total Bill :<span class="badge rounded-pill bg-primary"> ₱ <?php echo $this->cart->format_number($totalBill); ?></span></h1>
                                <button class="btn btn-success" id="btn-submit" style="margin-left: 20%;" type="submit">Billing</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nameEquipment">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="">
                                <div class="row" style="margin-left: 7%; margin-right: 7%;">
                                    <div class="col-sm">
                                        <div class="mb-3">
                                            <label for="inputLastName" class="form-label"><b>Cost</b></label>
                                            <input type="text" class="form-control" id="nameCostType" name="nameCostType" value="">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>




<style>
    input[type=text] {
        border: none;
        border-bottom: 2px solid gray;
    }

    * {
        box-sizing: border-box;
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
    function finalSubmit() {
        swal({
            title: "Billing Confirmation",
            text: "You will not be able to redo after it sent",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) form.submit();
        });
    }

    function clickModal(name, cost) {
        document.getElementById('nameEquipment').innerHTML = name;
        document.getElementById('nameCostType').value = cost;
    }

    $(function() {
        $.ajax({
            type: "GET",
            url: "<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/showAllEquipment",
            dataType: "json",
            success: function(response) {

            }
        })
    });
</script>