document.addEventListener("DOMContentLoaded", function () {
	fetchProfile();
});

document.addEventListener("DOMContentLoaded", function () {
	saveProfile();
});

function fetchProfile() {
	fetch("assets/getProfile.php")
		.then((response) => response.json())
		.then((profileData) => {
			// console.log(profileData);
			const imageSource = profileData.image
				? profileData.image
				: "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png";

			// Set image source dynamically
			const pictureElement = document.getElementById("profilePic");
			pictureElement.innerHTML = `
			<source srcset="${imageSource}" type="image/svg+xml" height="200" width="200">
			<img src="${imageSource}" class="img-fluid img-thumbnail rounded-5 object-fit-cover" alt="...">
			`;

			const fullName =
				(profileData.firstname || "") +
				" " +
				(profileData.lastname || "");

			// Set the full name dynamically
			const nameElement = document.getElementById("name");
			nameElement.textContent = fullName.trim() || "No Name";

			document.getElementById("fname").value =
				profileData.firstname || "";
			document.getElementById("lname").value = profileData.lastname || "";
			document.getElementById("email").value = profileData.email || "";
		})
		.catch((error) => console.error("Error:", error));
}

function saveProfile() {
	const saveProfile = document.getElementById("saveProfile");
	const alertMessage = document.getElementById("alertMessage");

	saveProfile.addEventListener("submit", function (event) {
		event.preventDefault();

		const formData = new FormData(saveProfile);

		fetch("assets/updInfo.php", {
			method: "POST",
			body: formData,
		})
			.then((response) => response.json())
			.then((data) => {
				document.getElementById("password").value = "";

				if (data.status === "success") {
					showBootstrapAlert("success", data.message);
					fetchProfile();
				} else {
					showBootstrapAlert("danger", data.message);
				}
			})
			.catch((error) => {
				document.getElementById("password").value = "";
				showBootstrapAlert("danger", "Unexpected error occur.");
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
	});
}
