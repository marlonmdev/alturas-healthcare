$(document).ready(function () {
	// Dropify Plugin
	$(".dropify").dropify({
		messages: {
			default: "Drop files here or click to browse",
			replace: "Drag and drop a image or file here or click to replace",
		},
	});

	// Multiselect
	// $("#med-services").easySelect({
	// 	selectColor: "#414c52",
	// 	placeholder: "",
	// 	showEachItem: true,
	// });

	// $(".chosen-select").chosen({
	// 	width: "100%",
	// 	no_results_text: "Oops, nothing found!"
	// }); 
   

	// initialize Tagify plugin on the above input node reference
	const tags_input = document.querySelector("#tags-input");
	const noa_med_services = document.querySelector("#noa-med-services");
	new Tagify(tags_input, {
		pattern: /^[A-Za-z\s\.]*$/, // this regex pattern only accepts letters, spaces and period
		duplicate: "Already exists",
	});
	new Tagify(noa_med_services, {
		// pattern: /^[A-Za-z\s\.]*$/, // this regex pattern only accepts letters, spaces and period
		duplicate: "Already exists",
	});

	
	// Input Mask Plugin
	// $("#hospital-phone-number").inputmask({"mask": "(999) 999 9999"});
});

