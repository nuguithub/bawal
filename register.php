<?php
    include 'connectDB.php';
    $headTitle = 'Register';
    include 'header.php'; 
?>

<section>
    <div class="p-5">
        <div class="container">
            <div id="alertMessage"></div>
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h3 class="card-title fw-bolder">REGISTER</h3>
                    <hr>
                    <form id="registerForm">

                        <div class="">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" class="form-control rounded-0" id="username" name="username" required>
                        </div>

                        <div class="">
                            <label for="password" class="form-label">
                                Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control rounded-0 z-0 aa" id="password"
                                    name="password" required>
                                <i class="bx bxs-show z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eye2"
                                    onclick="showPass('password', 'eye2')"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm
                                Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control rounded-0 z-0 aa" id="confirm_password"
                                    name="confirm_password" required>
                                <i class="bx bxs-show z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eye3"
                                    onclick="showPass('confirm_password', 'eye3')"></i>
                            </div>
                        </div>

                        <hr>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-5">REGISTER</button>
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
<script>
document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById('registerForm');
    const alertMessage = document.getElementById('alertMessage');

    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(registerForm);

        fetch('assets/register_act.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                registerForm.reset();
                window.scrollTo(0, 0);

                if (data.status === 'success') {
                    showBootstrapAlert('success', data.message);
                } else {
                    showBootstrapAlert('danger', data.message);
                }
            })
            .catch(error => {
                registerForm.reset();
                window.scrollTo(0, 0);
                showBootstrapAlert('danger', 'Register failed. Please try again.');
                console.error('Error creating event:', error);
            });

        function showBootstrapAlert(type, message) {
            alertMessage.innerHTML = '';
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;

            alert.innerHTML = `
                <strong>${message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            alertMessage.appendChild(alert);
        }
    });
});

function showPass(inputId, bxId) {
    const passwordInput = document.getElementById(inputId);
    const bx = document.getElementById(bxId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        bx.style.color = "var(--blue)";
        bx.classList.remove("bxs-show");
        bx.classList.add("bxs-hide");
    } else {
        passwordInput.type = "password"
        bx.style.color = "black";
        bx.classList.remove("bxs-hide");
        bx.classList.add("bxs-show");
    }
}
</script>

</html>