<div class="modal fade" id="tagPersonalChargeModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <form id="tagHealthcareAdvanceForm">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row text-center">
                                <h4><strong>REQUEST FOR HEALTHCARE ADVANCE</strong></h4>
                                </div>
                            <hr>
                            <input id="tag-billing-id" type="hidden">
                            <div class="row pt-3 pb-3" style="justify-content:center">
                                <div class="col-3 pt-1">
                                    <span class="fw-bold fs-5">Personal Charge : </span>
                                </div>
                                
                                <div class="col-4">
                                    <input class="form-control fs-5 fw-bold" id="tag-personal-charge" readonly >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="mdi mdi-send"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End of View LOA -->