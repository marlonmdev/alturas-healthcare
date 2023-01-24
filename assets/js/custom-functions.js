$(document).ready(function () {
	//LOA Request SelectBox Events
	/* The above code is checking to see if the value of the select element is not empty. If it is not
empty, then the med-services element is enabled. If it is empty, then the med-services element is
disabled. */
	$("#loa-request-type").change(function () {
		const select_loa_request = document.querySelector("#loa-request-type");
		const med_services = document.querySelector("#med-services");
		const selected_value =
			select_loa_request.options[select_loa_request.selectedIndex].value;
		if (selected_value !== "") {
			med_services.disabled = false;
		} else {
			med_services.disabled = true;
		}
	});
});
