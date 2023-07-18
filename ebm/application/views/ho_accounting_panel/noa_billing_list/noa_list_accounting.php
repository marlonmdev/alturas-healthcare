<main id="main" class="main">

    <div class="pagetitle">
        <h1>LOA Requests List</h1>
    </div>
    <br>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-provider/loa-request-list/loa-closed">Closed</a>
                    </li>
                </ul>
                <br>

                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Req. No.</th>
                            <th>Name</th>
                            <th>Req. Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (!empty($members)) {
                            foreach ($members as $member) :
                        ?>
                                <tr>
                                    <td><?php echo $member->noa_no ?></td>
                                    <td><?php echo $member->first_name . ',' . $member->middle_name . ' ' . $member->last_name ?></td>
                                    <td><?php echo $member->request_date ?></td>
                                    <td><span class="badge rounded-pill bg-primary"><?php echo $member->status ?></span></td>
                                    <!-- <td><button class="btn btn-primary" type="submit"> <i class="bi alarm-fille"></i> View</button></td> -->
                                </tr>
                        <?php
                            endforeach;
                        }
                        ?>

                    </tbody>

                </table>

                <!-- View Pending LOA Modal  -->
                <div class="modal fade" id="view-loa-details-modal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">LOA Request <span class="text-warning">[Pending]</span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="container-fluid">
                                    <h6 class="text-center font-weight-bold text-primary mb-3"><strong>Patient Details</strong></h6>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="fullname-label">Fullname:</span>
                                                <input type="text" class="form-control disabled" id="full-name" aria-describedby="fullname-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="birthdate-label">Date of Birth:</span>
                                                <input type="text" class="form-control disabled" id="date-of-birth" aria-describedby="birthdate-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="gender-label">Gender:</span>
                                                <input type="text" class="form-control disabled" id="gender" aria-describedby="gender-label" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="mobile-label">Mobile Number:</span>
                                                <input type="text" class="form-control disabled" id="patient-mobile-number" aria-describedby="mobile-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="address-label">Address:</span>
                                                <input type="text" class="form-control disabled" id="patient-address" aria-describedby="address-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-group input-group-md mb-2">
                                            <span class="input-group-text" id="email-label">Email Address:</span>
                                            <input type="text" class="form-control disabled" id="patient-email" aria-describedby="email-label" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <h6 class="text-center font-weight-bold text-primary my-3">
                                        <strong>Contact Person Details</strong>
                                    </h6>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="contact-person-label">Fullname:</span>
                                                <input type="text" class="form-control disabled" id="contact-person" aria-describedby="contact-person-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="cp-address-label">Address:</span>
                                                <input type="text" class="form-control disabled" id="contact-person-address" aria-describedby="cp-address-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="cp-mobile-label">Mobile Number:</span>
                                                <input type="text" class="form-control disabled" id="contact-person-number" aria-describedby="cp-mobile-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div class="container">
                                    <h6 class="text-center font-weight-bold text-primary my-3"><strong>HMO/LOA Request</strong></h6>
                                    <div class="row d-flex">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="hospital-name-label">Name of Hospital:</span>
                                                <input type="text" class="form-control disabled" id="name-of-hospital" aria-describedby="hospital-name-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="loa-type-label">LOA Type:</span>
                                                <input type="text" class="form-control disabled" id="loa-request-type" aria-describedby="loa-type-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 mb-2">
                                            <label><strong>Services:</strong></label>
                                            <textarea class="form-control disabled" id="loa-med-services" aria-label="With textarea" rows="3" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="hc-number-label">Health Card Number:</span>
                                                <input type="text" class="form-control disabled" id="health-card-number" aria-describedby="hc-number-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="insurance-company-label">Insurance Company:</span>
                                                <input type="text" class="form-control disabled" id="insurance-company" aria-describedby="insurance-company-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="request-date-label">Availment Request Date:</span>
                                                <input type="text" class="form-control disabled" id="availment-request-date" aria-describedby="request-date-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <strong class="mb-2">Chief Complaint: </strong>
                                            <textarea class="form-control disabled" id="chief-complaint" aria-label="With textarea" rows="6" disabled></textarea><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-md mb-2">
                                                <span class="input-group-text" id="attending-physician-label">Attending Physician:</span>
                                                <input type="text" class="form-control disabled" id="attending-physician" aria-describedby="attending-physician-label" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of View Pending LOA Modal -->

            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true
            });

        });
    </script>
</main>