<div class="modal fade" id="viewCoordinatorBillModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <span class="fw-bold fs-4">Coordinator's Bill of LOA # : [ <span class="text-info fw-bold" id="bill-loa-no"></span> ]</span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <ul class="list-unstyled">
            <li class="text-secondary">
              <span class="ls-1 fs-5">Patient Name: <span class="fw-bold text-dark fs-5" id="bill-fullname"></span>
            </li>

            <li class="text-secondary">
              <span class="ls-1 fs-5">HealthCare Provider: <span class="fw-bold text-dark fs-5" id="bill-hp-name"></span>
            </li>
          </ul>
        </div>

        <div class="row justify-content-center pt-2">
          <h6 class="text-center ls-1">CHARGES</h6>
          <table class="table table-sm">
            <thead>
              <tr class="border-secondary border-2 border-0 border-top border-bottom">
                <th class="text-center fw-bold ls-2">Service</th>
                <th class="text-center fw-bold ls-2">Amount</th>
              </tr>
            </thead>
            <tbody id="service-table" class="text-center"></tbody>
          </table>

          <h6 class="text-center ls-1">BILLING DEDUCTION/S</h6>
          <table class="table table-sm">
            <thead>
              <tr class="border-secondary border-2 border-0 border-top border-bottom">
                <th class="text-center fw-bold ls-2">Deduction Name</th>
                <th class="text-center fw-bold ls-2">Deduction Amount</th>
              </tr>
            </thead>
            <tbody id="deduction-table"></tbody>
          </table>
        </div>  
      </div>
    </div>
  </div>
</div>