          <div class="modal fade" id="viewUserAccountModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">USER ACCOUNT DETAILS</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="d-flex justify-content-center align-items-center">
                          <img class="rounded-circle img-responsive my-3" id="view-user-img" alt="User Image" width="160" height="160">
                        </div>
                        <div class="text-center">
                          <strong class="mt-3" id="view-full-name"></strong>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <table class="table table-responsive table-sm">
                          <tr>
                            <td>Employee ID :</td>
                            <td id="view-emp-id"></td>
                          </tr>
                          <tr>
                            <td>User Role :</td>
                            <td id="view-user-role"></td>
                          </tr>
                          <tr>
                            <td>Designated Healthcare Provider :</td>
                            <td id="view-dsg-hcare-prov"></td>
                          </tr>
                          <tr>
                            <td>Username :</td>
                            <td id="view-username"></td>
                          </tr>
                          <tr>
                            <td>Added On :</td>
                            <td id="view-added-on"></td>
                          </tr>
                        </table>
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