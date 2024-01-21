document
	.getElementById("createEventForm")
	.addEventListener("submit", function (event) {
		event.preventDefault();
		saveToDatabase();
	});

function saveToDatabase() {
	var form = document.getElementById("createEventForm");
	var formData = new FormData(form);
	const alertMessage = document.getElementById("alertMessage");

	fetch("assets/api.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			createEventForm.reset();
			window.scrollTo(0, 0);

			if (data.status === "success") {
				showBootstrapAlert("success", data.message);
			} else {
				showBootstrapAlert("danger", data.message);
			}
		})
		.catch((error) => {
			createEventForm.reset();
			window.scrollTo(0, 0);
			showBootstrapAlert("danger", "Error creating event.");
			console.error("Error creating event:", error);
		});

	function showBootstrapAlert(type, message) {
		alertMessage.innerHTML = "";
		const alert = document.createElement("div");
		alert.className = `alert alert-${type} alert-dismissible fade show`;

		alert.innerHTML = `
                <strong>${message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

		alertMessage.appendChild(alert);
	}
}
