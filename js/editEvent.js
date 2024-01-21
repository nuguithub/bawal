document.addEventListener("DOMContentLoaded", function () {
	const eventId = getQueryParameter("eventId");
	fetchEvent(eventId);
	saveEvent(eventId);
});

function getQueryParameter(name) {
	const urlParams = new URLSearchParams(window.location.search);
	return urlParams.get(name);
}

function fetchEvent(eventId) {
	fetch(`assets/save-event.php?eventId=${eventId}`)
		.then((response) => response.json())
		.then((eventDetails) => {
			console.log(eventDetails);
			document.getElementById("title").value = eventDetails.title;
			document.getElementById("description").value =
				eventDetails.description;
			document.getElementById("venue").value = eventDetails.venue;
			document.getElementById("imgPreview").src =
				"images/" + eventDetails.image;

			const dateTimeParts = eventDetails.date_time.split(" ");
			document.getElementById("date").value = dateTimeParts[0];
			document.getElementById("time").value = dateTimeParts[1];
		})
		.catch((error) => console.error("Error:", error));
}

function saveEvent(eventId) {
	const editEventForm = document.getElementById("editEventForm");
	const alertMessage = document.getElementById("editStat");

	editEventForm.addEventListener("submit", function (event) {
		event.preventDefault();

		const formData = new FormData(editEventForm);

		fetch(`assets/save-event.php?eventId=${eventId}`, {
			method: "POST",
			body: formData,
		})
			.then((response) => response.json())
			.then((eventDetails) => {
				console.log(eventDetails); // Log the response to the console
				if (eventDetails.status === "success") {
					showBootstrapAlert("success", eventDetails.message);
					fetchEvent(eventId);
				} else {
					showBootstrapAlert("danger", eventDetails.message);
				}
			})
			.catch((error) => {
				showBootstrapAlert("danger", "Unexpected error occur.");
				console.error("Error updating event:", error);
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
	});
}
