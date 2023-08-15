<div class="modal fade" id="viewMBLModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span class="fw-bold fs-4">Used MBL of   <span class="text-info fw-bold ps-1" id="mbl-fullname"></span></span> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="mbl-emp-id">
                <input type="text" id="mbl-filtered-year">
                <div class="offset-9" id="year-div">
                    <span class="fw-bold fs-5">Year : <span class="text-info fw-bold" id="mbl-year"></span></span>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover table-stripped"  id="mbltablemodal" style="width: 100%;">
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">No.</th>
                        <th class="fw-bold">Date Used</th>
                        <th class="fw-bold">Used MBL</th>
                        <th class="fw-bold">Remaining MBL</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // const BaseUrl = "<?php echo base_url(); ?>";
   
    // $(document).ready(function(){

    // });
</script>