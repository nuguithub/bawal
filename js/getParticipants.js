document.addEventListener("DOMContentLoaded", function () {
	const urlParams = new URLSearchParams(window.location.search);
	const eventId = urlParams.get("event_id");

	if (eventId) {
		fetchParticipants(eventId);
	} else {
		// Handle the case where event_id is not present in the URL
		console.error("Event ID not found in the URL");
	}
});

function fetchParticipants(eventId) {
	fetch(`assets/getParticipants.php?event_id=${eventId}`, {
		method: "GET",
	})
		.then((response) => response.json())
		.then((participants) => {
			console.log(participants);
			if (participants.length > 0) {
				displayParticipants(participants);
			} else {
				window.location.href = "my-events.php";
			}
		})
		.catch((error) => console.error("Error fetching participants:", error));
}

function displayParticipants(participants) {
	const participantsTable = document.getElementById("participants");
	participantsTable.innerHTML = "";

	participants.forEach((participant, index) => {
		const participantHTML = `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${participant.firstname}</td>
                <td>${participant.lastname}</td>
                <td>${participant.email}</td>
            </tr>
        `;

		participantsTable.innerHTML += participantHTML;
	});
}
