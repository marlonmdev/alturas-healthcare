<script type="text/javascript">

    function check_login(){
      $.ajax({
        url: "check-login",
        data: $('#login-form').serialize(),
        type: "post",
        dataType: "json",
        success: function(response){
          const { status, message, username, password } = response;
          const input_username = document.querySelector('#input-username');
          const input_password = document.querySelector('#input-password');
          if(status == "error"){
            toastr.options = {
              "closeButton": true,
              "preventDuplicates": true
            }
            input_username.value = username;
            input_password.value = password;
            input_username.classList.add('is-invalid', 'text-danger');
            input_password.classList.add('is-invalid', 'text-danger');
            toastr["error"](message);
          }else{
            const { user_id, fullname, email, user_role, logged, next_route, next_page } = response;
            toastr.options = {
              "showDuration": "800",
              "timeOut": "1500",
              "progressBar": true,
              "preventDuplicates": true
            }
            toastr["success"](message);
            setTimeout(function () {
              window.location.href = next_page;
            }, 2000);
            login_validated(user_id, fullname, user_role, logged, next_route, next_page);
          }     
        }
      });
    }

    function login_validated(user_id, fullname, user_role, logged, next_route, next_page){
      $.ajax({
        url: next_route,
        data: {
          user_id: user_id,
          fullname: fullname,
          user_role: user_role,
          logged: logged
        },
        type: "post",
        dataType: "json",
        success: function(response){
          console.log(response);
          const { status, message } = response;
          if(status == "success"){
            toastr.options = {
              "showDuration": "800",
              "timeOut": "1500",
              "progressBar": true,
              "preventDuplicates": true
            }
            toastr["success"](message);
            setTimeout(function () {
              window.location.href = next_page;
            }, 2000);
          }else{
            toastr.options = {
              "closeButton": true,
              "preventDuplicates": true
            }
            toastr["error"](message);
          }
        }
      });
    }

</script>