const base_url = $('#base_url').val();

$(document).ready(function () {
	  
	// Dropify Plugin
	$(".dropify").dropify({
		messages: {
			default: "Drop files here or click to browse",
			replace: "Drag and drop a image or file here or click to replace",
		},
	});

	// initialize Tagify plugin on the above input node reference
	const tags_input = document.querySelector("#tags-input");
	new Tagify(tags_input, {
		pattern: /^[A-Za-z\s\.]*$/, // this regex pattern only accepts letters, spaces and period
		duplicate: "Already exists",
	});

	$('#managersKeyReqLOANOAForm').submit(function(event){
		event.preventDefault();
		
		  $.ajax({
		  type: "post",
			url: base_url+'company-doctor/overide/get-mgr-key-loa',
			data: $(this).serialize(),
			dataType: "json",
			success: function(res) {
			  const { status, message, mgr_username_error, mgr_password_error, company_doctor } = res;
	
			  if (status == "error") {
				if (mgr_username_error !== '') {
				  $('#mgr-username-error-req-loa').html(mgr_username_error);
				  $('#mgr-username-req-loa').addClass('is-invalid');
				} else {
				  $('#mgr-username-error-req-loa').html('');
				  $('#mgr-username-req-loa').removeClass('is-invalid');
				}
	
				if (mgr_password_error !== '') {
				  $('#mgr-password-error-req-loa').html(mgr_password_error);
				  $('#mgr-password-req-loa').addClass('is-invalid');
				} else {
				  $('#mgr-password-error-req-loa').html('');
				  $('#mgr-password-req-loa').removeClass('is-invalid');
				}
	
				if (message !== '') {
				  $('#msg-error-req-loa').html(message);
				  $('#mgr-username-req-loa').addClass('is-invalid');
				  $('#mgr-password-req-loa').addClass('is-invalid');
				} else {
				  $('#msg-error-req-loa').html('');
				  $('#mgr-username-req-loa').removeClass('is-invalid');
				  $('#mgr-password-req-loa').removeClass('is-invalid');
				}
	
			  } else {
				// console.log('result',res);
				// const baseUrl = `<?php echo base_url()?>`;
				$('#LOAMngKeyModal').modal('hide');
				const type = document.querySelector('#req-type-key').value;
				if(type == 'loa'){
				  window.location.href = `${base_url}company-doctor/override/loa-request/${company_doctor}`;
				  $('#req-type-key').val('');
	
				}else if(type == 'noa'){
				  window.location.href = `${base_url}company-doctor/override/noa-request/${company_doctor}`;
				  $('#req-type-key').val('');
	
				}
			  }
			}
		  });
		});

});

const LOAManagersKey = () => {
	$('#LOAMngKeyModal').modal('show');
	$('#req-type-key').val('loa');
	$('#mgr-username-req-loa').val('');
	$('#mgr-password-req-loa').val('');

  }

  const NOAManagersKey = () => {
	$('#LOAMngKeyModal').modal('show');
	$('#req-type-key').val('noa');
	$('#mgr-username-req-loa').val('');
	$('#mgr-password-req-loa').val('');
  }