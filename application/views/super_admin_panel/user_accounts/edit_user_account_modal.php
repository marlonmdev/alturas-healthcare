          <div class="modal fade" id="editUserAccountModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">EDIT USER DETAILS</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container-fluid">
                    <form method="POST" action="<?= base_url() ?>super-admin/accounts/update" class="mt-3" id="editAccountForm">
                      <input type="hidden" name="token" id="edit-token" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="hidden" name="user-id" id="user-id">
                      <div class="row mb-3">
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> User Role</label><br>
                          <select class="form-select" name="user-role" id="edit-user-role" onchange="showEditHcareProv()">
                            <option value="">Select User Role</option>
                            <option value="healthcare-coordinator">HealthCare Coordinator</option>
                            <option value="company-doctor">Company Doctor</option>
                            <option value="healthcare-provider">HealthCare Provider</option>
                            <option value="head-office-accounting">Head Office - Accounting</option>
                            <option value="head-office-iad">Head Office - IAD</option>
                          </select>
                          <em id="edit-user-role-error" class="text-danger"></em>
                        </div>
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> Full Name</label><br>
                          <input type="text" class="form-control" name="full-name" id="edit-full-name">
                          <em id="edit-full-name-error" class="text-danger"></em>
                        </div>
                      </div>
                      <div class="row mb-3 d-none" id="editDsgHcareProvDiv">
                        <div class="col-sm-8 offset-sm-2">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> HealthCare Provider</label><br>
                          <select class="form-select" name="dsg-hcare-prov" id="edit-dsg-hcare-prov">
                            <option value="" selected>Select HealthCare Provider</option>
                            <?php
                            if (!empty($hcproviders)) :
                              foreach ($hcproviders as $hcprovider) :
                            ?>
                                <option value="<?= $hcprovider['hp_id']; ?>"><?= $hcprovider['hp_name']; ?></option>
                            <?php
                              endforeach;
                            endif;
                            ?>
                          </select>
                          <em id="edit-dsg-hcare-prov-error" class="text-danger"></em>
                        </div>
                      </div>
                      <!-- <div class="row mb-3">
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> Mobile Number</label><br>
                          <input type="text" class="form-control" name="mobile-no" id="edit-mobile-no">
                          <em id="edit-mobile-no-error" class="text-danger"></em>
                        </div>
                        <div class="col-sm-6">
                          <label class="colored-label">Email</label><br>
                          <input type="text" class="form-control" name="email" id="edit-email">
                          <em id="edit-email-error" class="text-danger"></em>
                        </div>
                      </div> -->
                      <div class="row">
                        <div class="col-sm-12 mb-2 d-flex justify-content-end">
                          <button type="submit" class="btn btn-success"><i class="mdi mdi-content-save-settings"></i> UPDATE</button>
                          &nbsp;&nbsp;
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> Cancel</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
