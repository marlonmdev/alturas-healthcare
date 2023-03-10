<div class="modal fade" id="viewChargeTypeModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title ls-2">Charge Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/set-charge-type" id="formUpdateChargeType">

                    <div class="row mb-3">
                      <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="hidden" name="loa-id" id="loa-id">
                      <select class="chargetype fs-5" name="charge-type">
                        <option value="">Please Select...</option>
                        <option value="Yes">Work related</option>
                        <option value="No">Non-work related</option>
                      </select>

		                  <div class="form-group row pt-4">
		                    <div class="wr" id="percentage">
		                      <div id="med-services-wrapper">
		                        <div class="mb-2 fs-5">
		                          <label class="colored-label">Enter Percentage (work-related)</label>
		                          <input type="number" class="form-control" name="wr_percentage">
		                        </div>
		                      </div>
		                      <em id="wpercentage-error" class="text-danger"></em>
		                    </div>
		                  </div>

	                    <div class="form-group row">
	                      <div class="nwr" id="percentage">
	                        <div id="med-services-wrapper">
	                          <div class="mb-2 fs-5">
	                            <label class="colored-label">Enter Percentage (nonwork-related)</label>
	                            <input type="number" class="form-control" name="nwr_percentage">
	                          </div>
	                        </div>
	                        <em id="nwpercentage-error" class="text-danger"></em>
	                      </div>
	                    </div>
                    </div>
                    

                    <div class="row mb-2">
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

    $('.chargetype').on('change',function(){
      var value = $(this).val();
      if(value == "Yes"){
        $( ".wr" ).show();
        $( ".nwr" ).hide();

      }else if(value == "No"){
        $( ".wr" ).hide();
        $( ".nwr" ).show();
      }else if(value == ""){
        $( ".wr" ).hide();
        $( ".nwr" ).hide();
      }
    });
  });

</script>
