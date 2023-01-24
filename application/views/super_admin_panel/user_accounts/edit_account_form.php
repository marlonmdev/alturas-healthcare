<main id="main" class="main">

  <div class="pagetitle">
    <h1>Accounts</h1>
  </div>
  <br>
  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">

        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>super-admin/accounts">Accounts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">Edit User Account</a>
          </li>
        </ul>
        <br>

        <div class="card">
          <div class="card-body">
            <!-- Start of Form -->
            <form method="post" action="<?= base_url() ?>super-admin/accounts/update/<?= $user['user_id'] ?>" class="mt-4" id="editAccountForm">
              <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="member-id" value="<?= $user['member_id'] ?>">
              <input type="hidden" name="emp-id" value="<?= $user['emp_id'] ?>">
              <div class="form-group row">
                <div class="col-lg-4 mt-3 mb-3">
                  <select class="form-select" name="user-role" id="user-role" onchange="showDesignatedHospital()">
                    <option value="" selected>Select User Role</option>
                    <?php
                    if ($user['user_role'] === "healthcare-coordinator") :
                    ?>
                      <option value="healthcare-coordinator" selected>HealthCare Coordinator</option>
                      <option value="company-doctor">Company Doctor</option>
                      <option value="healthcare-provider">HealthCare Provider</option>
                      <option value="head-office-accounting">Head Office Accounting</option>
                    <?php
                    elseif ($user['user_role'] === "company-doctor") :
                    ?>
                      <option value="healthcare-coordinator">HealthCare Coordinator</option>
                      <option value="company-doctor" selected>Company Doctor</option>
                      <option value="healthcare-provider">HealthCare Provider</option>
                      <option value="head-office-accounting">Head Office Accounting</option>
                    <?php
                    elseif ($user['user_role'] === "healthcare-provider") :
                    ?>
                      <option value="healthcare-coordinator">HealthCare Coordinator</option>
                      <option value="company-doctor">Company Doctor</option>
                      <option value="healthcare-provider" selected>HealthCare Provider</option>
                      <option value="head-office-accounting">Head Office Accounting</option>
                    <?php
                    elseif ($user['user_role'] === "head-office-accounting") :
                    ?>
                      <option value="healthcare-coordinator">HealthCare Coordinator</option>
                      <option value="company-doctor">Company Doctor</option>
                      <option value="healthcare-provider">HealthCare Provider</option>
                      <option value="head-office-accounting" selected>Head Office Accounting</option>
                    <?php
                    endif;
                    ?>
                  </select>
                  <em id="user-role-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-4">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> First Name</label>
                  <input type="text" class="form-control" name="first-name" id="first-name" value="<?= $user['first_name'] ?>">
                  <em id="first-name-error" class="text-danger"></em>
                </div>
                <div class="col-sm-4 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Middle Name</label>
                  <input type="text" class="form-control" name="middle-name" id="middle-name" value="<?= $user['middle_name'] ?>">
                  <em id="middle-name-error" class="text-danger"></em>
                </div>
                <div class="col-sm-4 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Last Name</label>
                  <input type="text" class="form-control" name="last-name" id="last-name" value="<?= $user['last_name'] ?>">
                  <em id="last-name-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Suffix (Jr./Sr.)</label>
                  <select class="form-select" name="suffix" id="suffix">
                    <option value="" selected></option>
                    <option value="Jr.">Jr.</option>
                    <option value="Sr.">Sr.</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-7 mb-3 <?php echo $user['user_role'] !== 'healthcare-provider' ? 'd-none' : ''; ?>" id="dsg-hospital-div">
                  <select class="form-select" name="dsg-hcare-prov" id="dsg-hcare-prov">
                    <option value="" selected>Select HealthCare Provider</option>
                    <?php
                    if (!empty($hcproviders)) {
                      foreach ($hcproviders as $hcprovider) :
                        if ($user['dsg_hcare_prov'] === $hcprovider['hp_id']) {
                    ?>
                          <option value="<?= $hcprovider['hp_id']; ?>" selected><?= $hcprovider['hp_name']; ?></option>
                        <?php
                        } else {
                        ?>
                          <option value="<?= $hcprovider['hp_id']; ?>"><?= $hcprovider['hp_name']; ?></option>
                    <?php
                        }
                      endforeach;
                    }
                    ?>
                  </select>
                  <em id="dsg-hcare-prov-error" class="text-danger"></em>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-end">
                  <button type="submit" class="btn btn-success">
                    UPDATE
                  </button>
                  &nbsp;&nbsp;
                  <a href="#" class="btn btn-danger" onclick="window.history.back()">GO BACK</a>
                </div>
              </div>
            </form>
            <!-- End of Form -->
          </div>
        </div>


      </div>
    </div>
  </section>

</main>
<script>
  const baseUrl = '<?= base_url() ?>';

  function showDesignatedHospital() {
    const role_input = document.querySelector('#user-role').value;
    const search_input = document.querySelector('#search-member-div');
    const username = document.querySelector('#username');
    const dsg_hospital = document.querySelector('#dsg-hospital-div');
    if (role_input === "") {
      dsg_hospital.className = "d-none";
    } else if (role_input === "healthcare-provider") {
      dsg_hospital.className = "col-lg-7 mb-3 d-block";
    } else {
      dsg_hospital.className = "d-none";
    }
  }

  $(document).ready(function() {

    $("#editAccountForm").submit(function(event) {
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr("action"),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            first_name_error,
            middle_name_error,
            last_name_error,
            user_role_error,
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (first_name_error !== "") {
                $("#first-name-error").html(first_name_error);
                $("#first-name").addClass("is-invalid");
              } else {
                $("#first-name-error").html("");
                $("#first-name").removeClass("is-invalid");
              }

              if (middle_name_error !== "") {
                $("#middle-name-error").html(middle_name_error);
                $("#middle-name").addClass("is-invalid");
              } else {
                $("#middle-name-error").html("");
                $("#middle-name").removeClass("is-invalid");
              }

              if (last_name_error !== "") {
                $("#last-name-error").html(last_name_error);
                $("#last-name").addClass("is-invalid");
              } else {
                $("#last-name-error").html("");
                $("#last-name").removeClass("is-invalid");
              }

              if (user_role_error !== "") {
                $("#user-role-error").html(user_role_error);
                $("#user-role").addClass("is-invalid");
              } else {
                $("#user-role-error").html("");
                $("#user-role").removeClass("is-invalid");
              }

              break;
            case 'save-error':
              swal({
                title: "Failed",
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: "error",
              });
              break;
            case 'success':
              swal({
                title: "Success",
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: "success",
              });
              setTimeout(function() {
                window.location.href = `${baseUrl}healthcare-coordinator/accounts`;
              }, 3200);
              break;
          }
        },
      });
    });
  }); // End of document ready
</script>