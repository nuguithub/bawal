document.addEventListener("DOMContentLoaded", function () {
	fetchEvents();
});

document.addEventListener("click", function (e) {
	const alertMessage = document.getElementById("eventStat");
	if (e.target.classList.contains("del-event-btn")) {
		deleteEventClick(e);
	}
	if (e.target.classList.contains("edit-event-btn")) {
		editEventClick(e);
	}
});

function fetchEvents() {
	fetch("assets/myEvents-api.php", {
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
	const myEvents = document.getElementById("myEvents");

	myEvents.innerHTML = "";

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
        style="background: var(--skin); max-height: 42vh; overflow: auto;">
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
				<div class="d-flex justify-content-between">
					<p class="fw-semibold mb-2 text-body-tertiary">${participantsText}</p>
					<div class="d-flex">
						<button class="btn btn-sm btn-primary me-1 edit-event-btn" data-event-id="${
							event.id
						}" onclick="editEventClick">Edit</button>
						<button class="btn btn-sm btn-danger del-event-btn" data-event-id="${
							event.id
						}" onclick="deleteEventClick">Delete</button>
					</div>
				</div>
            </div>
            <div class="position-absolute top-0 end-0 px-2" style="background:var(--red)">
            <span class="fw-semibold" style="color: var(--white);"> ${
				index + 1
			}</span></div>
        </div>
        `;

		myEvents.innerHTML += eventHTML;
	});
}

function displayNoEventMessage() {
	const myEvents = document.getElementById("myEvents");
	myEvents.innerHTML =
		'<h5 class="text-center">You have no events. Click <a href="make-event.php#">here</a> to make an event.</h5>';
}

function deleteEventClick(e) {
	e.preventDefault();

	const target = e.target;
	const eventId = target.getAttribute("data-event-id");
	const confirmation = confirm("Are you sure you want to delete this event?");

	if (confirmation) {
		fetch("assets/myEvents-api.php", {
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
						fetchEvents();
					} else {
						showBootstrapAlert("danger", data.message);
					}
				} else {
					console.error("Error:", data.message);
					showBootstrapAlert("danger", "Error deleting event.");
				}
			})
			.catch((error) => {
				console.error("Error:", error);
				showBootstrapAlert("An error occurred. Please try again.");
			});
	}
}

function showBootstrapAlert(type, message) {
	const alertMessage = document.getElementById("eventStat");
	alertMessage.innerHTML = "";

	const alert = document.createElement("div");
	alert.className = `alert alert-${type} alert-dismissible fade show`;

	alert.innerHTML = `
		<strong>${message}</strong>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	`;

	alertMessage.appendChild(alert);
}

function editEventClick(e) {
	e.preventDefault();

	const target = e.target;
	const eventId = target.getAttribute("data-event-id");

	editEvent(eventId);
}

function editEvent(eventId) {
	window.location.href = `editEvent.php?eventId=${eventId}`;
}
