let idleTimeout;

function resetIdleTimeout() {
	clearTimeout(idleTimeout);
	idleTimeout = setTimeout(logoutUser, 15 * 60 * 1000);
}

function logoutUser() {
	fetch("logout.php", {
		method: "POST",
	})
		.then((response) => {
			alert("You've been idle for 15 minutes. You will be logged out.");
			window.location.href = "login.php";
		})
		.catch((error) => console.error("Error logging out:", error));
}

document.addEventListener("mousemove", resetIdleTimeout);
document.addEventListener("keypress", resetIdleTimeout);

resetIdleTimeout();
