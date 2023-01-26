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
                    Pending NOA
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
                    class="nav-link active"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-request-list/noa-pending"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-request-list/noa-approved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-request-list/noa-disapproved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
                    >
                </li>
                    <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-request-list/noa-closed"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
                    >
                </li>
            </ul>


             <div class="card">
                <div class="card-body">
                    <div class="table-responsive">  
                        <table id="tbl-pending-noa" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Req. No.</th>
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
                                            <td><?php echo $member->noa_id ?></td>
                                            <td><?php echo $member->last_name . ', ' . $member->first_name . ' ' . $member->middle_name?></td>
                                            <td><?php echo $member->hp_name ?></td>
                                            <td><?php echo $member->admission_date ?></td>
                                            <td><?php echo $member->request_date ?></td>
                                            <td><span class="badge rounded-pill bg-warning"><?php echo $member->status ?></span></td>
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
<script>
    $(document).ready(function() {
        $('#tbl-pending-noa').DataTable({
            responsive: true
        });

    });
</script>
