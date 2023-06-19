<div class="modal fade" id="viewChargeTypeModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title ls-2">Charge Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/set-charge-type" id="formUpdateChargeType">

                    <div class="row mb-3">
                      <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="hidden" name="noa-id" id="noa-id">
                      <div class="col-6 pt-3">
                        <label class="colored-label fs-5">Select :</label>
                        <select class="form-select chargetype fs-5" name="charge-type">
                          <option value="">Select Charge Type...</option>
                          <option value="Yes">Work related</option>
                          <option value="No">Non-work related</option>
                        </select>
                        <em id="charge-type-error" class="text-danger"></em>
                      </div>
                      <div class="mb-2 pt-3 fs-5 ls-1 col-6">
                        <label class="colored-label">Enter Percentage</label>
                        <div class="input-group">
                          <input type="number" class="form-control" name="percentage" min="0" max="100" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2">
                          <div class="input-group-append">
                            <span class="input-group-text">%</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col">
                      <label class="fs-5 colored-label">Spot Report :</label>
                      <input type="file" accept=".pdf, image/*" class="form-control" name="spot-report"  id="spot-report" onchange="previewFile('spot-report')">
                    </div>   
                    <div class="col pt-3 pb-3">
                      <label class="fs-5 colored-label">Incident Report :</label>
                        <input type="file" accept=".pdf, image/*" class="form-control" name="incident-report" id="incident-report" onchange="previewFile('incident-report')">
                    </div> 

                    <div class="row mb-2 pt-3">
                      <div class="col-12 text-center">
                        <button type="submit" class="btn btn-info ls-1 me-2">SUBMIT</button>
                        <button type="button" class="btn btn-danger ls-1" data-bs-dismiss="modal">CANCEL</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End of View LOA -->

<script>
  $(document).ready(function(){

    // $('.chargetype').on('change',function(){
    //   var value = $(this).val();
    //   if(value == "Yes"){
    //     $( ".wr" ).show();
    //     $( ".nwr" ).hide();

    //   }else if(value == "No"){
    //     $( ".wr" ).hide();
    //     $( ".nwr" ).show();
    //   }else if(value == ""){
    //     $( ".wr" ).hide();
    //     $( ".nwr" ).hide();
    //   }
    // });
  });

</script>