document.addEventListener("DOMContentLoaded", function () {
	document
		.getElementById("changePassButton")
		.addEventListener("click", function () {
			changePassword();
		});
});

function showBootstrapAlert(type, message) {
	const alertMessage = document.getElementById("accAlertMessage");
	alertMessage.innerHTML = "";
	const alert = document.createElement("div");
	alert.className = `alert alert-${type} fw-bold alert-dismissible fade show`;

	alert.innerHTML = `
		<strong>${message}</strong>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	`;

	alertMessage.appendChild(alert);
}

function changePassword() {
	const currentPass = document.getElementById("currentPass").value;
	const newPass = document.getElementById("newPass").value;
	const confirmPass = document.getElementById("confirmPass").value;

	const data = {
		changePass: true,
		currentPass: currentPass,
		newPass: newPass,
		confirmPass: confirmPass,
	};

	fetch("assets/changePass.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: new URLSearchParams(data),
	})
		.then((response) => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then((data) => {
			if (data.success) {
				showBootstrapAlert("success", data.message);
				// Optionally redirect to another page after successful password change
				// window.location.href = "profile.php";
			} else {
				showBootstrapAlert("danger", data.error);
			}
		})
		.catch((error) => {
			console.error("Error:", error);
		});
}
