<div class="page-wrapper">
  <div class="container-fluid">
    <div class="col-sm-12 col-md-5 offset-md-0" >
      <div class="input-group" id="search-member-div">
        <span class="mdi mdi-magnify input-group-text bg-dark text-white"> Search:</span>
        <input type="text" class="form-control" name="search-member" id="input-search-member" onkeyup="searchHmoMember()" placeholder="Type Patient Name Here..." />
      </div>
      <div id="member-search-div">
        <div id="search-results" class="border-top-0"></div>
      </div>
      <input type="text" name="emp-id" id="emp-id" hidden>
    </div><br>

    <div class="col-lg-12">
      <div class="card shadow">
        <div class="card-body">
          <div class="col-12  pt-2">
            <h5 style="text-align:center;color:black;font-size:20px;letter-spacing:4px">PATIENT INFORMATION</h5>
          </div><hr><br>

          <div class="form-group row">
            <div class="col-sm-6 mb-2">
              <label class="colored-label">Full Name</label>
              <input type="text" class="form-control" name="full-name" id="full-name" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Date of Birth</label>
              <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Age:</label>
              <input type="text" class="form-control" name="age" id="age" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6 mb-2">
              <label class="colored-label">Gender</label>
              <input type="text" class="form-control" id="gender" name="gender" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">PhilHealth Number</label>
              <input type="text" class="form-control" id="philhealth_no" name="philhealth_no" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Blood Type</label>
              <input type="text" class="form-control" id="blood_type" name="blood_type" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6 mb-2">
              <label class="colored-label">Home Address</label>
              <input type="text" class="form-control" id="home_address" name="home_address" disabled>
            </div>
            <div class="col-sm-6 mb-2">
              <label class="colored-label">City Address</label>
              <input type="text" class="form-control" id="city_address" name="city_address" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6 mb-2">
              <label class="colored-label">Contact Number</label>
              <input type="text" class="form-control" id="contact_no" name="contact_no" disabled>
            </div>
            <div class="col-sm-6">
              <label class="colored-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6 mb-2">
              <label class="colored-label">Contact Person Name</label>
              <input type="text" class="form-control" id="contact_person" name="contact_person" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Contact Number</label>
              <input type="text" class="form-control" id="contact_person_no" name="contact_person_no" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Address</label>
              <input type="text" class="form-control" id="contact_person_addr" name="contact_person_addr" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6 mb-2">
              <label class="colored-label">Company</label>
              <input type="text" class="form-control" id="requesting_company" name="requesting_company" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Healthcard Number</label>
              <input type="text" class="form-control" id="health_card_no" name="health_card_no" disabled>
            </div>
            <div class="col-sm-3 mb-2">
              <label class="colored-label">Remaining MBL</label>
              <input type="text" class="form-control" id="remaining_mbl" name="remaining_mbl" disabled>
            </div>
          </div><br><br>

          <div class="row mt-2">
            <div class="col-sm-12 mb-2 d-flex justify-content-center">
              <button type="submit" class="btn btn-info me-3" id="submit" onclick="proceedToRoute()">Proceed <i class="mdi mdi mdi-arrow-right-bold" style="color:#80ff00"></i></button>  
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"><br><br><br><br><br><br>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel" style="color:red">Warning!</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p style="font-size:17px;color:black" id="modalMessage"></p>
      </div>

    </div>
  </div>
</div>
<!-- End -->

<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const member_name = $('#full-name').val();
  

  function proceedToRoute() {
    const remainingMbl = parseFloat($('#remaining_mbl').val().replace(/[^0-9.-]+/g,""));
    const fullName = $('#full-name').val().trim();
    const dateOfBirth = $('#date-of-birth').val().trim();
    const age = $('#age').val().trim();
    const mbl = $('#remaining_mbl').val().trim();
    const emp_id = $('#emp-id').val();
    if (fullName === '' || dateOfBirth === '' || age === '' || mbl === '') {
      $('#modalMessage').text("To proceed, please search patient's name to fill in the fields. Thank you!");
      $('#myModal').modal('show');
      return;
    }

    if (remainingMbl === 0) {
      window.location.href = baseUrl+"healthcare-coordinator/emergency_loa/managers_key/"+emp_id;
    } else {
      window.location.href = baseUrl+"healthcare-coordinator/emergency_loa/emergency_form/"+emp_id;
    }
  }

  function searchHmoMember() {
    const token = "<?= $this->security->get_csrf_hash() ?>";
    const search_input = document.querySelector('#input-search-member');
    const result_div = document.querySelector('#search-results');
    var search = search_input.value;
    if (search !== '') {
      load_member_data(token, search);
    } else {
      result_div.innerHTML = '';
    }
  } 

  function load_member_data(token, search) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/member/search`,
      method: "POST",
      data: {
        token: token,
        search: search
      },
      success: function(data) {
        $('#search-results').removeClass('d-none');
        $('#search-results').html(data);
      }
    });
  }

  function getMemberValues(member_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/member/search/${member_id}`,
      method: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,member_id,first_name,middle_name,last_name,suffix,date_of_birth,age,gender,philhealth_no,blood_type,home_address,city_address,contact_no,email,contact_person,contact_person_no,contact_person_addr,health_card_no,requesting_company,mbl,emp_id
        } = res;
        $('#search-results').addClass('d-none');
        $('#input-search-member').val('');
        $('#member-id').val(member_id);
        $('#emp-id').val(emp_id);
        $('#full-name').val(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').val(date_of_birth);
        $('#age').val(age);
        $('#gender').val(gender);
        $('#philhealth_no').val(philhealth_no);
        $('#blood_type').val(blood_type);
        $('#home_address').val(home_address);
        $('#city_address').val(city_address);
        $('#contact_no').val(contact_no);
        $('#email').val(email);
        $('#contact_person').val(contact_person);
        $('#contact_person_no').val(contact_person_no);
        $('#contact_person_addr').val(contact_person_addr);
        $('#health_card_no').val(health_card_no);
        $('#requesting_company').val(requesting_company);
        const formattedMbl = number_format(parseFloat(mbl), 2, '.', ',');
        $('#remaining_mbl').val(`₱ ${formattedMbl}`);
      }
    });
  }

  function number_format(number, decimals, decimalSeparator, thousandsSeparator) {
    decimals = typeof decimals !== 'undefined' ? decimals : 2;
    decimalSeparator = typeof decimalSeparator !== 'undefined' ? decimalSeparator : '.';
    thousandsSeparator = typeof thousandsSeparator !== 'undefined' ? thousandsSeparator : ',';
    var parts = number.toFixed(decimals).toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
    return parts.join(decimalSeparator);
  }
  
</script>