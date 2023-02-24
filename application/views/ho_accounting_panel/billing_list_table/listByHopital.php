<main id="main" class="main">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="container">

        <div class="col-12 col-lg-12 mb-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-1">
                            <div class="text-center px-xl-3">
                                <a href="<?= base_url(); ?>head-office-accounting/dashboard" class="btn btn-primary btn-block">Back</a>
                            </div>
                            <div class="img-holder mr-md-4 mb-md-0 mb-4 mx-auto mx-md-0 d-md-none d-lg-flex" style="margin-top: 30%;">
                                <img class="img-responsive" width="80" height="70" src="<?php echo base_url(); ?>uploads/ramiro-removebg-preview.png" alt="">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="e-navlist e-navlist--active-bold">
                                <ul class="nav">
                                    <li class="nav-item active"><a href="" class="nav-link"><span>All</span>&nbsp;<small>/&nbsp;32</small></a></li>
                                    <li class="nav-item"><a href="" class="nav-link"><span>Active</span>&nbsp;<small>/&nbsp;16</small></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-7">
                            <div>
                                <div class="form-group">
                                    <label>Date from - to:</label>
                                    <div>
                                        <input id="dates-range" class="form-control flatpickr-input" placeholder="01 Dec 17 - 27 Jan 18" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Search by Name:</label>
                                    <div><input class="form-control w-100" type="text" placeholder="Name" value=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row flex-lg-nowrap">
            <div class="col mb-3">
                <div class="e-panel card">
                    <div class="card-body">

                        <div class="e-table">
                            <div class="table-responsive table-lg mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="align-top">
                                                <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                                                    <input type="checkbox" class="custom-control-input" id="all-items">
                                                    <label class="custom-control-label" for="all-items"></label>
                                                </div>
                                            </th>
                                            <th>Photo</th>
                                            <th class="max-width">Name</th>
                                            <th class="sortable">Date</th>
                                            <th> </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php
                                        if (!empty($payloadBilling)) {
                                            foreach ($payloadBilling as $billing) :
                                        ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                                                            <input type="checkbox" class="custom-control-input" id="item-4">
                                                            <label class="custom-control-label" for="item-4"></label>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <img style="width:50px;height:50px;" src="<?php echo base_url() ?>uploads/profile_pics/<?= $billing['photo'] ?>" alt="">
                                                    </td>
                                                    <td class="text-nowrap align-middle"><?php echo ($billing['first_name'] . ' ' . $billing['last_name']) ?></td>
                                                    <td class="text-nowrap align-middle"><span><?php echo ($billing['billing_date']) ?></span></td>
                                                    <td class="text-center align-middle"><i class="fa fa-fw text-secondary cursor-pointer fa-toggle-on"></i></td>
                                                    <td class="text-center align-middle">
                                                        <div class="btn-group align-top">
                                                            <a href="<?= base_url(); ?>head-office-accounting/list/summary/1" class="btn btn-primary btn-block">View</a>
                                                        </div>
                                                    </td>
                                                </tr>

                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                <ul class="pagination mt-3 mb-0">
                                    <li class="disabled page-item"><a href="#" class="page-link">‹</a></li>
                                    <li class="active page-item"><a href="#" class="page-link">1</a></li>
                                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                                    <li class="page-item"><a href="#" class="page-link">›</a></li>
                                    <li class="page-item"><a href="#" class="page-link">»</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<style>
    body {
        margin-top: 20px;
        background: #f8f8f8
    }
</style>