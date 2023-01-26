<!-- Start of Page Wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2">NOA Requests</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Provider</li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Approved NOA
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
            <div class="col-lg-12">

                <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/pending"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link active"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/approved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/disapproved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
                    >
                </li>
                    <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/closed"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
                    >
                </li>
            </ul>


             <div class="card">
                <div class="card-body">
                    <div class="table-responsive">  
                        <table id="tbl-approved-noa" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Hosptial Name</th>
                                    <th>Admission Date</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (!empty($members)) {
                                    foreach ($members as $member) :
                                ?>
                                        <tr>
<<<<<<< HEAD
                                            <td><?php echo $member->noa_id ?></td>
                                            <td><?php echo $member->last_name . ', ' . $member->first_name . ' ' . $member->middle_name ?></td>
                                            <td><?php echo $member->hp_name ?></td>
                                            <td><?php echo $member->admission_date ?></td>
                                            <td><?php echo $member->request_date ?></td>
                                            <td><span class="badge rounded-pill bg-success"><?php echo $member->status ?></span></td>
=======
                                            <td><?= $member->noa_id ?></td>
                                            <td>
                                                <?= $member->first_name . ' ' . $member->middle_name . ' ' . $member->last_name ?>
                                            </td>
                                            <td><?= $member->hp_name ?></td>
                                            <td><?= $member->admission_date ?></td>
                                            <td><?= $member->request_date ?></td>
                                            <td>
                                                <span class="badge rounded-pill bg-success">
                                                    <?= $member->status ?>
                                                </span>
                                            </td>
>>>>>>> d9aa76d53d1e1dc7631e8a1f21d74e7831a85bc1
                                            <td>
                                                <a href="javascript:void(0)">
                                                    <i class="mdi mdi-information fs-2 text-info" data-toggle="tooltip" title="Click to view details"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    endforeach;
                                }
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
<script>
    $(document).ready(function() {
        $('#tbl-approved-noa').DataTable({
            responsive: true
        });

    });
</script>
