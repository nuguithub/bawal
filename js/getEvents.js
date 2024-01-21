document.addEventListener("DOMContentLoaded", function () {
	fetchEvents();
});

document.addEventListener("click", function (e) {
	if (e.target.classList.contains("join-btn")) {
		handleJoinClick(e);
	}
});

function handleJoinClick(e) {
	e.preventDefault();

	const target = e.target;
	const eventId = target.getAttribute("data-event-id");
	const confirmation = confirm("Are you sure you want to join this event?");

	if (confirmation) {
		fetch("assets/join-api.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: `event_id=${eventId}`,
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.status === "success") {
					console.log(data.message);
					alert(data.message);
				} else {
					console.error("Error:", data.message);
					alert(data.message);
				}
				if (data.redirect) {
					window.location.href = data.redirect;
				}
			})
			.catch((error) => {
				console.error("Error:", error);
				alert("An error occurred. Please try again.");
			});
	}
}

function fetchEvents() {
	fetch("assets/api.php", {
		method: "GET",
	})
		.then((response) => response.json())
		.then((data) => {
			console.log(data); // Add this line for debugging
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
		description.length > 40
			? description.substring(0, 40) + " ..."
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
	const eventDetails = document.getElementById("eventDetails");

	eventDetails.innerHTML = "";

	events.forEach((event, index) => {
		const dateObject = new Date(event.date_time);

		const description = event.description;

		const limitedDescription =
			description.length > 40
				? description.substring(0, 40) + " ..."
				: description;

		const seeMoreLink =
			description.length > 40
				? `<span id="seeMore_${index}" class="text-primary fst-italic" onclick="toggleDescription(${index}, '${description}')">See more</span>`
				: "";

		const formattedDate = new Intl.DateTimeFormat("en-PH", {
			year: "numeric",
			month: "short",
			day: "numeric",
			weekday: "short",
			hour: "numeric",
			minute: "numeric",
			hour12: true,
		}).format(dateObject);

		const imageSource = event.image
			? "images/" + event.image
			: "https://cdn.head-fi.org/assets/classifieds/hf-classifieds_no-image-available_2.jpg";

		const eventHTML = `
			<div class="col-lg-4 col-10 mb-2">
				<div class="card mb-2" id="eventCard">
					<h3 class="mx-auto px-3 fw-semibold mt-4">${event.title}</h3>
					<img src="${imageSource}" class="px-5" style="height: 22vh; object-fit: cover; object-position: 50% 50%;" alt="...">
					<div class="card-body text-center">
						<p class="card-text fw-semibold mb-2 expanded" style="max-height: 8vh; overflow: auto;">
							<span id="descriptionText_${index}">${limitedDescription}</span>
							${seeMoreLink}
						</p>
						<p class="card-text">${formattedDate}</p>
						<p class="card-title mx-auto text-secondary">${event.event_manager}</p>
						<button class="btn btn-sm btn-primary px-5 join-btn" data-event-id="${event.id}" onclick="handleJoinClick">Join</button>

					</div>
				</div>
			</div>
		`;

		eventDetails.innerHTML += eventHTML;
	});
}

function displayNoEventMessage() {
	const eventDetails = document.getElementById("eventDetails");
	eventDetails.innerHTML = '<h5 class="text-center">No recent event</h5>';
}
