document.addEventListener("DOMContentLoaded", function () {
	fetchEvents();
});

document.addEventListener("click", function (e) {
	const alertMessage = document.getElementById("cancelStatus");
	if (e.target.classList.contains("cancel-attendance-btn")) {
		handleCancelAttendanceClick(e);
	}
});

function handleCancelAttendanceClick(e) {
	e.preventDefault();

	const target = e.target;
	const eventId = target.getAttribute("data-event-id");
	const confirmation = confirm(
		"Are you sure you want to cancel your attendance for this event?"
	);

	if (confirmation) {
		fetch("assets/join-api.php", {
			method: "DELETE",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: `event_id=${eventId}`,
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.status === "success") {
					console.log(data.message);
					fetchEvents();
					if (data.status === "success") {
						showBootstrapAlert("success", data.message);
					} else {
						showBootstrapAlert("danger", data.message);
					}
					// You may want to update the UI or reload the page after canceling attendance
				} else {
					console.error("Error:", data.message);
					showBootstrapAlert("danger", "Error canceling attendance.");
				}
			})
			.catch((error) => {
				console.error("Error:", error);
				alert("An error occurred. Please try again.");
			});
	}
}

function showBootstrapAlert(type, message) {
	const alertMessage = document.getElementById("cancelStatus");
	alertMessage.innerHTML = "";

	const alert = document.createElement("div");
	alert.className = `alert alert-${type} alert-dismissible fade show`;

	alert.innerHTML = `
		<strong>${message}</strong>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	`;

	alertMessage.appendChild(alert);
}

function fetchEvents() {
	fetch("assets/join-api.php", {
		method: "GET",
	})
		.then((response) => response.json())
		.then((data) => {
			console.log(data);
			if (data.length > 0) {
				displayEvents(data);
			} else {
				displayNoEventMessage();
			}
		})
		.catch((error) => console.error("Error fetching events:", error));
}

function toggleDescription(index, description) {
	var descriptionText = document.getElementById(`descriptionText_${index}`);
	var seeMore = document.getElementById(`seeMore_${index}`);

	const limitedDescription =
		description.length > 150
			? description.substring(0, 150) + " ..."
			: description;

	if (descriptionText.classList.contains("expanded")) {
		descriptionText.textContent = limitedDescription;
		seeMore.innerText = "See more";
		descriptionText.classList.remove("expanded");
	} else {
		descriptionText.textContent = description;
		seeMore.innerText = "See less";
		descriptionText.classList.add("expanded");
	}
}

function displayEvents(events) {
	const joinedEvents = document.getElementById("joinedEvents");

	joinedEvents.innerHTML = "";

	events.forEach((event, index) => {
		const dateObject = new Date(event.date_time);

		const formattedDate = new Intl.DateTimeFormat("en-PH", {
			year: "numeric",
			month: "short",
			day: "numeric",
			weekday: "short",
			hour: "numeric",
			minute: "numeric",
			hour12: true,
		}).format(dateObject);

		const description = event.description;

		const limitedDescription =
			description.length > 150
				? description.substring(0, 150) + " ..."
				: description;

		const seeMoreLink =
			description.length > 150
				? `<span id="seeMore_${index}" class="text-primary fst-italic" onclick="toggleDescription(${index}, '${description}')">See more</span>`
				: "";
		var participants = event.participants_count;
		var participantsText =
			participants < 1
				? "No one has joined your event yet."
				: participants + " people joined your event.";

		const imageSource = event.image
			? event.image
			: "https://cdn.head-fi.org/assets/classifieds/hf-classifieds_no-image-available_2.jpg";

		const eventHTML = `
        <div class="d-lg-inline-flex w-100 d-block justify-content-start align-items-center p-3 mb-2 position-relative"
        style="background: var(--white); max-height: 42vh; overflow: auto;">
            <div class="text-center">
                <img src="${imageSource}"
                style="height: 22vh; object-fit: cover; object-position: 50% 50%;" alt="">
				
            </div>
            <div class="d-block ms-md-5 w-100">
                <p class="fw-semibold mb-2">${formattedDate}</p>
                <h3 class="fw-bold mb-2">${event.title}</h3>
                <p class="fw-semibold mb-2 expanded" style="max-height: 10vh; overflow: auto;">
                    <span id="descriptionText_${index}">${limitedDescription}</span>
                    ${seeMoreLink}
                </p>
                <p class="fw-semibold mb-0 fw-bold">Event Manager: <span class="fw-semibold">${
					event.event_manager
				}</span></p>
                <div class="d-flex justify-content-between">
					<p class="fw-semibold mb-2 text-body-tertiary">${participantsText} </p>
					<button class="btn btn-sm btn-danger cancel-attendance-btn" data-event-id="${
						event.id
					}" onclick="handleCancelAttendanceClick">Cancel Attendance</button>
				</div>
            </div>
            <div class="position-absolute top-0 end-0 px-2" style="background:var(--red)">
            <span class="fw-semibold" style="color: var(--white);"> ${
				index + 1
			}</span></div>
        </div>
        `;

		joinedEvents.innerHTML += eventHTML;
	});
}

function displayNoEventMessage() {
	const joinedEvents = document.getElementById("joinedEvents");
	joinedEvents.innerHTML = `<h5 class="text-center">You didn't participate to any event.</h5>`;
}
