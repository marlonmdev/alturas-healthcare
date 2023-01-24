<!-- Start of Page Wrapper -->
<div class="page-wrapper">
<!-- Bread crumb and right sidebar toggle -->
<div class="page-breadcrumb">
<div class="row">
    <div class="col-12 d-flex no-block align-items-center">
    <h4 class="page-title ls-2">LOA Requests</h4>
    <div class="ms-auto text-end">
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Healthcare Provider</li>
            <li class="breadcrumb-item active" aria-current="page">
            Pending LOA
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
                    href="<?php echo base_url(); ?>healthcare-provider/loa-request-list/loa-pending"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/loa-request-list/loa-approved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/loa-request-list/loa-disapproved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
                    >
                </li>
                    <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/loa-request-list/loa-closed"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
                    >
                </li>
            </ul>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-pending-loa" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>LOA Type</th>
                                    <th>Service Type</th>
                                    <th>RX File</th>
                                    <th>Req. Date</th>
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
                                    <td><?php echo $member->loa_no ?></td>
                                    <td>
                                        <?php echo $member->first_name . ',' . $member->middle_name . ' ' . $member->last_name ?>
                                    </td>
                                    <td><?php echo $member->loa_request_type ?></td>
                                    <td>

                                        <?php foreach ($member->med_services as $ct) :  ?>
                                            <?php if (isset($ct[0])) { ?>
                                                <span class="badge rounded-pill bg-primary">
                                                    <?php echo $ct[0]->cost_type ?></span>
                                            <?php } ?>
                                        <?php endforeach ?>

                                    </td>
                                    <td>
                                        <?php if ($member->loa_request_type == 'Diagnostic Test') { ?>
                                            <a href="javascript:void(0)" onclick="viewImage('<?= base_url() . 'uploads/loa_attachments/' . $member->rx_file ?>')"><strong>View</strong></a>
                                        <?php } else { ?>
                                            None
                                        <?php } ?>

                                    </td>
                                    <td><?php echo $member->request_date ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-warning"><?php echo $member->status ?></span>
                                    </td>
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
        $('#tbl-pending-loa').DataTable({
            responsive: true
        });

    });

    function viewImage(path) {
        let item = [{
            src: path, // path to image
            title: 'Attached RX File' // If you skip it, there will display the original image name
        }];
        // define options (if needed)
        let options = {
            index: 0 // this option means you will start at first image
        };
        // Initialize the plugin
        let photoviewer = new PhotoViewer(item, options);
    }
</script>
