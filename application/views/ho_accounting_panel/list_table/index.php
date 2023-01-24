<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Billing List</h4>
            <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item">Head Office Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">
                    Billing List
                </li>
                </ol>
            </nav>
            </div>
        </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="career-search mb-60">

                    <form action="<?php echo base_url(); ?>head-office-accounting/search-table-list" method="post" class="career-form mb-60">
                        <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="row">
                            <div class="col-md-6 col-lg-9 my-3">
                                <div class="input-group position-relative">
                                    <input type="text" class="form-control" name="search" placeholder="Enter Billing Number here .." id="keywords">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3 my-3">
                                <button type="submit" class="searchButton" id="contact-submit">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="filter-result">
                        <p class="mb-30 ff-montserrat">Total : 89</p>
                        <?php foreach ($payloadLoa as $key => $value) { ?>

                            <div class="job-box d-md-flex align-items-center justify-content-between mb-30">
                                <div class="job-left my-4 d-md-flex align-items-center flex-wrap">
                                    <div class="img-holder mr-md-4 mb-md-0 mb-4 mx-auto mx-md-0 d-md-none d-lg-flex">
                                        <img class="img-responsive" width="80" height="70" src="<?php echo base_url(); ?>uploads/ramiro-removebg-preview.png" alt="">
                                    </div>
                                    <div class="job-content">
                                        <h5 class="text-center text-md-left"><?php echo "{$value['first_name']} {$value['last_name']}" ?></h5>
                                        <ul class="d-md-flex flex-wrap text-capitalize ff-open-sans">
                                            <li class="mr-md-4">
                                                <?php print_r($value['billing_no']) ?>
                                            </li>
                                            <li class="mr-md-4">
                                                <span class="badge rounded-pill bg-primary" style="margin-left: 70%;"> <i class="zmdi zmdi-time mr-2"></i> <?php print_r($value['billing_date']) ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="job-right my-4 flex-shrink-0">
                                    <a href="<?= base_url(); ?>head-office-accounting/list/summary/1" class="editButton" id="contact-submit">
                                        View
                                    </a>
                                </div>
                            </div>


                        <?php  } ?>


                    </div>
                </div>

                <!-- START Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-reset justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="mdi mdi-arrow-left-bold"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item d-none d-md-inline-block"><a class="page-link" href="#">2</a></li>
                        <li class="page-item d-none d-md-inline-block"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">8</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="mdi mdi-arrow-right-bold"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- END Pagination -->
            </div>
        </div>
    </div>
</div>

<style>
    .editButton {
        background-color: #4154f1;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .searchButton {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    option {
        color: #4e63d7;
    }

    /* ===== Career ===== */
    .career-form {
        background-color: #4e63d7;
        border-radius: 5px;
        padding: 0 16px;
    }

    .career-form .form-control {
        background-color: rgba(255, 255, 255, 0.2);
        border: 0;
        padding: 12px 15px;
        color: #fff;
    }

    .career-form .form-control::-webkit-input-placeholder {
        /* Chrome/Opera/Safari */
        color: #fff;
    }

    .career-form .form-control::-moz-placeholder {
        /* Firefox 19+ */
        color: #fff;
    }

    .career-form .form-control:-ms-input-placeholder {
        /* IE 10+ */
        color: #fff;
    }

    .career-form .form-control:-moz-placeholder {
        /* Firefox 18- */
        color: #fff;
    }

    .career-form .custom-select {
        background-color: rgba(255, 255, 255, 0.2);
        border: 0;
        padding: 12px 15px;
        color: #fff;
        width: 100%;
        border-radius: 5px;
        text-align: left;
        height: auto;
        background-image: none;
    }

    .career-form .custom-select:focus {
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .career-form .select-container {
        position: relative;
    }

    .career-form .select-container:before {
        position: absolute;
        right: 15px;
        top: calc(50% - 14px);
        font-size: 18px;
        color: #ffffff;
        content: '\F2F9';
        font-family: "Material-Design-Iconic-Font";
    }

    .filter-result .job-box {
        -webkit-box-shadow: 0 0 35px 0 rgba(130, 130, 130, 0.2);
        box-shadow: 0 0 35px 0 rgba(130, 130, 130, 0.2);
        border-radius: 10px;
        padding: 10px 35px;
    }

    ul {
        list-style: none;
    }

    .list-disk li {
        list-style: none;
        margin-bottom: 12px;
    }

    .list-disk li:last-child {
        margin-bottom: 0;
    }

    .job-box .img-holder {
        height: 65px;
        width: 65px;
        background-color: #4e63d7;
        background-image: -webkit-gradient(linear, left top, right top, from(rgba(78, 99, 215, 0.9)), to(#5a85dd));
        background-image: linear-gradient(to right, rgba(78, 99, 215, 0.9) 0%, #5a85dd 100%);
        font-family: "Open Sans", sans-serif;
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border-radius: 65px;
    }

    .career-title {
        background-color: #4e63d7;
        color: #fff;
        padding: 15px;
        text-align: center;
        border-radius: 10px 10px 0 0;
        background-image: -webkit-gradient(linear, left top, right top, from(rgba(78, 99, 215, 0.9)), to(#5a85dd));
        background-image: linear-gradient(to right, rgba(78, 99, 215, 0.9) 0%, #5a85dd 100%);
    }

    .job-overview {
        -webkit-box-shadow: 0 0 35px 0 rgba(130, 130, 130, 0.2);
        box-shadow: 0 0 35px 0 rgba(130, 130, 130, 0.2);
        border-radius: 10px;
    }

    @media (min-width: 992px) {
        .job-overview {
            position: -webkit-sticky;
            position: sticky;
            top: 70px;
        }
    }

    .job-overview .job-detail ul {
        margin-bottom: 28px;
    }

    .job-overview .job-detail ul li {
        opacity: 0.75;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .job-overview .job-detail ul li i {
        font-size: 20px;
        position: relative;
        top: 1px;
    }

    .job-overview .overview-bottom,
    .job-overview .overview-top {
        padding: 35px;
    }

    .job-content ul li {
        font-weight: 600;
        opacity: 0.75;
        border-bottom: 1px solid #ccc;
        padding: 10px 5px;
    }

    @media (min-width: 768px) {
        .job-content ul li {
            border-bottom: 0;
            padding: 0;
        }
    }

    .job-content ul li i {
        font-size: 20px;
        position: relative;
        top: 1px;
    }

    .mb-30 {
        margin-bottom: 30px;
    }
</style>
