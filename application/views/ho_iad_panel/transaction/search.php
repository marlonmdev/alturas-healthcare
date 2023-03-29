<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title">Transaction Record</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Internal Audit Department</li>
                <li class="breadcrumb-item active" aria-current="page">Billing</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
    <div class="container-fluid">
      <div class="row">
        <?php if ($this->session->flashdata('error')): ?>
          <script>
            Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'No Record Found!'
            })
          </script>
        <?php endif; ?>

        <div class="col-12">
          <div class="card shadow">
            <div class="border border-2 border-dark"></div>
              <div class="card-body">
                <h4 class="card-title text-center ls-1">Search to View Record</h4>
                <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 mt-3 mb-5">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter me-2"></i>Search By :</span>
                    </div>
                    <select class="form-select" name="search_select" id="search-select">
											<option value="">Select Search Method</option>
											<option value="ID">ID No.</option>
                      <option value="healthcard">HealthCard No.</option>
											<option value="name">Employee Name</option>
                    </select>
                  </div>
                </div>

                <div class="col-6 offset-3 mb-5 d-none" id="search-by-id">
                  <form method="POST" action="<?php echo base_url(); ?>head-office-iad/transaction/search_by_id" id="search-form-1" class="needs-validation" novalidate>
                    <div class="input-group">
											<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
											<input type="text" class="form-control" name="employee_id" placeholder="Enter ID No."  aria-describedby="btn-search" required>
                      <button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
                    </div>
                   </form>
                </div>

                <div class="col-6 offset-3 mb-5 d-none" id="search-by-healthcard">
                  <form method="POST" action="<?php echo base_url(); ?>head-office-iad/transaction/search_by_healthcard" id="search-form-3" class="needs-validation" novalidate>
                    <div class="input-group">
											<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
											<input type="text" class="form-control" name="employee_healthcard" placeholder="Enter HealthCard No."  aria-describedby="btn-search" required>
                      <button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
                    </div>
                   </form>
                </div>

								<div class="col-sm-12 col-md-10 offset-md-1 text-center mb-5 d-none" id="search-by-name">
									<form method="POST" action="<?php echo base_url(); ?>head-office-iad/transaction/search_by_name" id="search-form-2" class="needs-validation" novalidate>
										<div class="input-group">
											<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">

											<span class="input-group-text bg-dark text-white">
                        <i class="mdi mdi-filter"></i>
                      </span>

											<input type="text" name="first_name" class="form-control" placeholder="Enter Firstname" required>

											<input type="text" name="middle_name" class="form-control" placeholder="Enter Middlename">
                      
											<input type="text" name="last_name" class="form-control" placeholder="Enter Lastname" required>

											<button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
										</div>
									</form>
								</div>
              </div>
            </div>
          </div>
        </div>
    	</div>
		</div>
	</div>
</div>


<script>
  onload = (event) => {
    searchMethods();
  };
    
  $(document).ready(function(){
    $("#search-select").on('change', function(){
      searchMethods();
    });
  });    

  const searchMethods = () => {
		if($('#search-select').val() == "ID"){
			$("#search-form-1")[0].reset();
			$("#search-by-name").addClass('d-none');
      $("#search-by-healthcard").addClass('d-none');
			$("#search-by-id").removeClass('d-none is-invalid is-valid');
    }else if($('#search-select').val() == "healthcard"){
      $("#search-form-3")[0].reset();
      $("#search-by-name").addClass('d-none');
      $("#search-by-id").addClass('d-none');
      $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');
		}else if($('#search-select').val() == "name"){
			$("#search-form-2")[0].reset();
			$("#search-by-id").addClass('d-none');
      $("#search-by-healthcard").addClass('d-none');
			$("#search-by-name").removeClass('d-none is-invalid is-valid');
		}else{
			$("#search-by-id").addClass('d-none');
			$("#search-by-healthcard").addClass('d-none');
      $("#search-by-name").addClass('d-none');
		}
  }

  (function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>