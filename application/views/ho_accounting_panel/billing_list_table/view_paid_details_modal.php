<div class="modal fade" id="viewpaidLOANOAdetailsModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span class="fw-bold fs-4">[ <span class="text-info fw-bold fs-4" id="noa-loa-no"></span> ]</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Member's Fullname : <span class="fw-bold fs-5" id="members-fullname"></span></span></td>
                        <td><span class="">Business Unit : <span class="fw-bold fs-5" id="member-bu"></span></span></td>
                        
                    </tr>
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Healthcare Provider : <span class="fw-bold fs-5" id="hc-provider"></span></span></td>
                        <td><span class="">Requested on : <span class="fw-bold fs-5" id="request-date"></span></span></td>
                        
                    </tr>
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Approved by : <span class="fw-bold fs-5" id="approved-by"></span></span></td>
                        <td><span class="">Approved on : <span class="fw-bold fs-5" id="approved-on"></span></span></td>
                        
                    </tr>
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Type of Request : <span class="fw-bold fs-5" id="request-type"></span></span></td>
                        <td id="hospitalized-on">Date Hospitalized : <span class="fw-bold fs-5" id="hospitalized-date"></span></td>
                    </tr>  
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Percentage : <span class="fw-bold fs-5" id="percentage-is"></span></span></td>
                    </tr>
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Billing No : <span class="fw-bold fs-5" id="billing-no"></span></span></td>
                        <td><span class="">Billed on : <span class="fw-bold fs-5" id="billed-on"></span></span></td>
                    </tr>
                </table>
                <!-- <table class="table table-sm">
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td id="cost-types"><span class="">Services : <span class="fw-bold fs-5" id="med-services"></span></span></td>
                    </tr>
                </table> -->
                <table class="table table-sm">
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Hospital Bill : <span class="fw-bold fs-5" id="hp-bill"></span></span></td>
                        <td><span class="">Company Charge : <span class="fw-bold fs-5" id="company-chrg-bill"></span></span></td>
                        <td><span class="">Personal Charge : <span class="fw-bold fs-5" id="personal-chrg-bill"></span></span></td>
                    </tr>
                    <tr class="border-secondary border-2 border-0 border-top border-bottom">
                        <td><span class="">Current MBL : <span class="fw-bold fs-5" id="current-mbl"></span></span></td>
                        <td><span class="">Remaining MBL <small>(* as of billing)</small> : <span class="fw-bold fs-5" id="remaining-mbl"></span></span></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>