function generatePassword() {
	const password = document.querySelector("#password");
	const current_year = new Date().getFullYear();
	password.value = generateRandomString(6);
}

function generateCredentials() {
	const username = document.querySelector("#username");
	const password = document.querySelector("#password");
	const current_year = new Date().getFullYear();
	username.value = "hmo-" + generateRandomString(4) + generateRandomNumber(4);
	password.value = generateRandomString(4) + current_year;
}

function generateRandomString(limit) {
	const characters = "0123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ";
	let result = "";
	const charactersLength = characters.length;
	for (let i = 0; i < limit; i++) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
}

function generateRandomNumber(limit) {
	const characters = "0123456789";
	let result = "";
	const charactersLength = characters.length;
	for (let i = 0; i < limit; i++) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
}

function showUserPassword() {
	const input_password = document.querySelector("#password");
	if (input_password.type === "password") {
		input_password.type = "text";
	} else {
		input_password.type = "password";
	}
}

function showUserNewPassword() {
	const input_password = document.querySelector("#new-password");
	if (input_password.type === "password") {
		input_password.type = "text";
	} else {
		input_password.type = "password";
	}
}

function showAccountSettingsModal() {
	$("#accountSettingsModal").modal("show");
}

function showDBBackupModal() {
	$("#dbBackupForm")[0].reset();
	$("#dbBackupModal").modal("show");
}

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
	"use strict";

	// Fetch all the forms we want to apply custom Bootstrap validation styles to
	var forms = document.querySelectorAll(".needs-validation");

	// Loop over them and prevent submission
	Array.prototype.slice.call(forms).forEach(function (form) {
		form.addEventListener(
			"submit",
			function (event) {
				if (!form.checkValidity()) {
					event.preventDefault();
					event.stopPropagation();
				}

				form.classList.add("was-validated");
			},
			false
		);
	});
})();
