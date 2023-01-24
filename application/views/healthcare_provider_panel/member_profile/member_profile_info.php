<script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Member Information</h1>
    </div>
    <br>
    <?php if ($member->current_status == "Resigned") { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h1 style="margin-left: 35%;">Resigned Member</h1>
        </div>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Resigned Member!'
            })
        </script>
    <?php } ?>
    <section class="section dashboard" <?php if ($member->current_status == "Resigned") { ?>id="sectionDashboard" <?php } ?>>
        <div class="row">
            <div class="col-lg-12">

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-provider/profile/view">Profile</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link " href="<?php echo base_url(); ?>healthcare-provider/profile/view/mbl">MBL Monitoring</a>
                    </li> -->
                </ul>
                <br>

            </div>
        </div>
        <div class="container">
            <div class="container-xl px-4 mt-4">
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Profile picture card-->
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header">Profile Picture</div>
                            <div class="card-body text-center">
                                <!-- Profile picture image-->
                                <img class="img-account-profile rounded-circle mb-2 mt-3" src="<?= base_url(); ?>assets/images/user.svg" alt="">
                                <!-- Profile picture help block-->
                                <div class="small font-italic text-muted mb-4"></div>
                                <!-- Profile picture upload button-->
                                <button class="btn" type="button" style="background-color:#9ACD32;">Active</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Account Details</div>
                            <div class="card-body">
                                <form>

                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3 mt-4">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>First Name</b></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" id="inputLocation" type="text" disabled value="<?php echo $member->first_name ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-4 col-form-label"><b>Last Name</b></label>
                                                <div class="col-sm-8">
                                                    <input disabled class="form-control" id="inputLocation" type="text" readonly value="<?php echo $member->last_name ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Group (username)-->
                                    <div class="mb-3">
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-2 col-form-label"><b>Home Address</b></label>
                                            <div class="col-sm-10">
                                                <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->home_address ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Group (username)-->
                                    <div class="mb-3">
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-2 col-form-label"><b>City Address</b></label>
                                            <div class="col-sm-10">
                                                <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->city_address ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Row        -->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Birth</b></label>
                                                <div class="col-sm-9">
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->date_of_birth ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Status</b></label>
                                                <div class="col-sm-9">
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->civil_status ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Cell #</b></label>
                                                <div class="col-sm-9">
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->contact_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                    <hr>

                                    <!-- Form Group (username)-->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Sex</b></label>
                                                <div class="col-sm-9">
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->gender ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Age</b></label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $dateOfBirth = $member->date_of_birth;
                                                    $today = date("Y-m-d");
                                                    $diff = date_diff(date_create($dateOfBirth), date_create($today));
                                                    $age = $diff->format('%y') . ' years old';
                                                    ?>
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $age ?>
                                                    " />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Group (username)-->
                                    <div class=" row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Blood Type</b></label>
                                                <div class="col-sm-9">
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->blood_type ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-3 col-form-label"><b>Height</b></label>
                                                <div class="col-sm-9">
                                                    <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->height ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Group (username)-->
                                    <div class="mb-3">
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-2 col-form-label"><b>Weight</b> </label>
                                            <div class="col-sm-10">
                                                <input disabled class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="<?php echo $member->weight ?>">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<style>
    #sectionDashboard {
        -webkit-filter: blur(5px);
        -moz-filter: blur(5px);
        -o-filter: blur(5px);
        -ms-filter: blur(5px);
        filter: blur(5px);
        background-color: #ccc;

    }

    input[type=text] {
        border: none;
        border-bottom: 2px solid gray;
    }

    body {
        margin-top: 20px;
        background-color: #f2f6fc;
        color: #69707a;
    }

    .img-account-profile {
        height: 10rem;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
    }

    .card .card-header {
        font-weight: 500;
    }

    .card-header:first-child {
        border-radius: 0.35rem 0.35rem 0 0;
    }

    .card-header {
        padding: 1rem 1.35rem;
        margin-bottom: 0;
        background-color: rgba(33, 40, 50, 0.03);
        border-bottom: 1px solid rgba(33, 40, 50, 0.125);
    }

    .form-control,
    .dataTable-input {
        display: block;
        width: 100%;
        padding: 0.875rem 1.125rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #c5ccd6;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .nav-borders .nav-link.active {
        color: #0061f2;
        border-bottom-color: #0061f2;
    }

    .nav-borders .nav-link {
        color: #69707a;
        border-bottom-width: 0.125rem;
        border-bottom-style: solid;
        border-bottom-color: transparent;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0;
        padding-right: 0;
        margin-left: 1rem;
        margin-right: 1rem;
    }
</style>