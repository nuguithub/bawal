<div class="container p-4">
    <h3 class="fw-bolder mb-5">My Profile</h3>
    <div id="alertMessage"></div>
    <div class="mb-3">
        <form id="saveProfile">

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="fname">First Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rounded-0" id="fname" name="fname" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="lname">Last Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rounded-0" id="lname" name="lname" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="email">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control rounded-0" id="email" name="email" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="currentPass" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="password" class="form-control rounded-0 z-0 aa" id="password" name="password"
                            aria-describedby="eye" required>
                        <span class="z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eye1"
                            onclick="togglePasswordVisibility()">
                            <i id="pass-toggle" class='bx bxs-show'></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <input class="btn btn-primary mt-3 mb-3 px-3" type="submit" name="saveProfile" value="Save Profile">
            </div>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var passwordToggleIcon = document.getElementById("pass-toggle");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordToggleIcon.classList.remove("bxs-show");
        passwordToggleIcon.classList.add("bxs-hide");
        passwordToggleIcon.style.color = "var(--blue)";
    } else {
        passwordInput.type = "password";
        passwordToggleIcon.classList.remove("bxs-hide");
        passwordToggleIcon.classList.add("bxs-show");
        passwordToggleIcon.style.color = "black";
    }
}
</script>