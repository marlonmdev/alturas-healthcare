 <!-- Start of Alert notification when patient't total bill exceeds remaining MBL balance -->
  <div class="row my-3 d-none" id="charge-alert-div">
      <div class="col-12">
          <div class="alert alert-cyan text-center" role="alert">
              <em class="fw-bold ls-1">The Net Bill exceeds the patient's <span class="text-danger fs-4"><?= '&#8369;'.number_format($remaining_balance, 2) ?></span> Remaining MBL Balance. The <span class="text-danger fs-4" id="charge-amount"></span> excess amount will be added to his/her Personal Charges.
              </em>
          </div>
      </div>
  </div>
  <!-- End of Alert notification when patient't total bill exceeds remaining MBL balance -->