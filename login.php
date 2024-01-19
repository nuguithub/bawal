<?php
    include 'connectDB.php';
    $headTitle = 'Login';
    include 'header.php'; 
?>

<section>
    <div class="p-5">
        <div class="container">
            <div id="alertMessage">
                <?php
            if (isset($_SESSION['auth-error'])) {
                echo $_SESSION['auth-error'];
                unset($_SESSION['auth-error']);
            }
            ?></div>
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h3 class="card-title fw-bolder">LOGIN</h3>
                    <hr>

                    <form id="loginForm">

                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label" for="username">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control rounded-0" id="username" name="username"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label" for="password">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-0 z-0 aa" id="password"
                                        name="password" aria-describedby="eye" required>
                                    <span class="z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eye1"
                                        onclick="togglePasswordVisibility()">
                                        <i id="password-toggle-icon" class='bx bxs-show'></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p class="text-secondary" style="font-size: 14px;">Don't have account? <a
                                href="register.php">Register
                                here.</a>
                        </p>
                        <hr>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-5">LOGIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<!-- Add this script to your HTML -->
<script>
document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const formData = new FormData(event.target);

    fetch("assets/login_process.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "profile.php";
            } else {
                displayError(data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            displayError("An unexpected error occurred.");
        });
});

function displayError(message) {
    const alertContainer = document.getElementById("alertMessage");
    alertContainer.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>${message}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
}

function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var passwordToggleIcon = document.getElementById("password-toggle-icon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordToggleIcon.classList.remove('bxs-show');
        passwordToggleIcon.classList.add('bxs-hide');
        passwordToggleIcon.style.color = "var(--blue)";
    } else {
        passwordInput.type = "password";
        passwordToggleIcon.classList.remove('bxs-hide');
        passwordToggleIcon.classList.add('bxs-show');
        passwordToggleIcon.style.color = "black";
    }
}
</script>

</html>