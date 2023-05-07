  <!-- Start of Search Form -->
  <div class="col-12 mt-2">
    <div class="card shadow">
      <div class="border border-2 border-dark"></div>
      <div class="card-body">
        <h4 class="card-title">Search Member</h4>
        <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 mt-3 mb-5">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white">
                <i class="mdi mdi-filter me-2"></i>Search By
              </span>
            </div>
            <select class="form-select" name="search_select" id="search-select">
              <option value="healthcard">Healthcard Number</option>
              <option value="">Select Search Method</option>
              <option value="name">Patient Name</option>
            </select>
          </div>
        </div>

        <div class="col-6 offset-3 mb-5 d-none" id="search-by-healthcard">
          <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/search-member/healthcard" id="search-form-1" class="needs-validation" novalidate>
            <div class="input-group">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
              <input type="text" class="form-control" id="healthcard-no" name="healthcard_no" placeholder="Search Healthcard Number"  aria-describedby="btn-search" required>
              <button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
            </div>
          </form>
        </div>
                                      

        <div class="col-sm-12 col-md-10 offset-md-1 text-center mb-5 d-none" id="search-by-name">
          <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/search-member/name" id="search-form-2" class="needs-validation" novalidate>
            <div class="input-group">
                <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <span class="input-group-text bg-dark text-white">Name :</span>
                <input type="text" name="first_name" class="form-control" placeholder="Enter Firstname" required>
                <input type="text" name="last_name" class="form-control" placeholder="Enter Lastname" required>
                <span class="input-group-text bg-dark text-white">Birthday :</span>
                <input type="date" name="date_of_birth" class="form-control" required>
                <button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
            </div>
          </form>
        </div>
        
      </div>
    </div>
  </div>
  <!-- End of Search Form -->