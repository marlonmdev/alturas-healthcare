          <div class="modal fade" id="addUserAccountModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">REGISTER USER ACCOUNT</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container-fluid">
                    <form method="POST" action="<?php echo base_url(); ?>healthcare-coordinator/accounts/register/submit" class="mt-3" id="registerAccountForm">
                      <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="hidden" name="emp-id" id="emp-id">
                      <div class="row mb-3">
                        <div class="col-sm-8 offset-sm-2">
                          <input type="text" class="form-control" name="search-member" id="input-search-member" onkeyup="searchMember()" placeholder="Search Employee Here..." />
                          <div id="member-search-div">
                            <div id="search-results" style="min-width:488px;" class="border-top-0"></div>
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> User Role</label><br>
                          <select class="form-select" name="user-role" id="user-role" onchange="showHcareProv()">
                            <option value="">Select User Role</option>
                            <option value="healthcare-coordinator">Healthcare Coordinator</option>
                            <option value="healthcare-provider">Healthcare Provider</option>
                            <option value="hc-provider-front-desk">Healthcare Provider Front Desk</option>
                            <option value="hc-provider-accounting">Healthcare Provider Accounting</option>
                            <option value="head-office-accounting">Head Office - Accounting</option>
                            <option value="head-office-iad">Head Office - IAD</option>
                           
                          </select>
                          <em id="user-role-error" class="text-danger"></em>
                        </div>
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> Full Name</label><br>
                          <input type="text" class="form-control" name="full-name" id="full-name">
                          <em id="full-name-error" class="text-danger"></em>
                        </div>
                      </div>
                      <div class="row mb-3 d-none" id="dsgHcareProvDiv">
                        <div class="col-sm-8 offset-sm-2">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> HealthCare Provider</label><br>
                          <select class="form-select" name="dsg-hcare-prov" id="dsg-hcare-prov">
                            <option value="" selected>Select Healthcare Provider</option>
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
                          <em id="dsg-hcare-prov-error" class="text-danger"></em>
                        </div>
                      </div>
    
                      <div class="form-group row">
                        <div class="col-sm-12 mb-3 d-flex justify-content-center">
                          <button type="button" class="btn btn-success" onclick="generateRandomCredentials()" data-bs-toggle="tooltip" title="Generate Random Username and Password"><i class="bx bxs-key"></i> Generate Credentials</button>
                          &nbsp;&nbsp;
                          <button type="button" class="btn btn-warning" onclick="setDefaultPassword()" data-bs-toggle="tooltip" title="Use Default Password"><i class="bx bxs-key"></i> Use Default Password</button>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> Username</label><br>
                          <input type="text" class="form-control" name="username" id="username">
                          <em id="username-error" class="text-danger"></em>
                        </div>
                        <div class="col-sm-6">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> Password</label>
                          <div class="main-password">
                            <input type="password" class="form-control input-password" name="password" id="password" aria-label="password">
                            <a href="JavaScript:void(0);" class="icon-view">
                              <i class="mdi mdi-eye" id="pwd-icon"></i>
                            </a>
                          </div>
                          <em id="password-error" class="text-danger"></em>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-12 mb-2 d-flex justify-content-end">
                          <button type="submit" class="btn btn-primary me-2"><i class="mdi mdi-content-save"></i> REGISTER</button>
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> Cancel</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>