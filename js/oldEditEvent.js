document.addEventListener("DOMContentLoaded", function () {
	// Function to extract query parameters from the URL
	function getQueryParameter(name) {
		const urlParams = new URLSearchParams(window.location.search);
		return urlParams.get(name);
	}

	const eventId = getQueryParameter("eventId");

	if (eventId) {
		fetchEventDetails(eventId)
			.then((eventDetails) => {
				// Populate the form fields with the retrieved event details
				document.getElementById("title").value = eventDetails.title;
				document.getElementById("description").value =
					eventDetails.description;
				const dateTimeParts = eventDetails.date_time.split(" ");
				document.getElementById("date").value = dateTimeParts[0];
				document.getElementById("time").value = dateTimeParts[1];
			})
			.catch((error) => {
				console.error("Error fetching event details:", error);
			});
	}

	function fetchEventDetails(eventId) {
		// Replace this with your actual logic to fetch event details from the server
		return fetch(`assets/save-event.php?eventId=${eventId}`)
			.then((response) => response.json())
			.then((data) => data.eventDetails) // Adjust here if needed
			.catch((error) => {
				console.error("Error fetching event details:", error);
			});
	}

	const editEventForm = document.getElementById("editEventForm");

	if (editEventForm) {
		editEventForm.addEventListener("submit", function (event) {
			event.preventDefault();

			const formData = new FormData(editEventForm);

			fetch(`assets/save-event.php?eventId=${eventId}`, {
				method: "PUT",
				body: formData,
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.status === "success") {
						showBootstrapAlert("success", data.message);
						fetchEventDetails(eventId);
					} else {
						showBootstrapAlert("danger", data.message);
					}
				})
				.catch((error) => {
					showBootstrapAlert(
						"danger",
						"An error occurred while updating the event."
					);
					console.error("Error updating event:", error);
				});
		});
	}

	function showBootstrapAlert(type, message) {
		const alertMessage = document.getElementById("editStat");
		alertMessage.innerHTML = "";

		const alert = document.createElement("div");
		alert.className = `alert alert-${type} alert-dismissible fade show`;

		alert.innerHTML = `
            <strong>${message}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

		alertMessage.appendChild(alert);
	}
});
