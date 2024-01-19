<?php
    session_start();
    
    include 'connectDB.php';
    $headTitle = 'Personal Information';
    include 'header.php'; 

    if (!isset($_SESSION['token'])) {
        echo '<script>alert("You are not allowed to access this page.");
            window.location.href = "profile.php";
        </script>';
    } 
?>

<section>
    <div class="p-5">
        <div class="container">
            <div id="alertMessage"></div>
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h3 class="card-title fw-bolder">Add Personal Information</h3>
                    <hr>
                    <form id="addInfoForm">

                        <div class="">
                            <label class="form-label" for="firstname">First Name</label>
                            <input type="text" class="form-control rounded-0" id="firstname" name="firstname" required>
                        </div>

                        <div class="">
                            <label class="form-label" for="lastname">Last Name</label>
                            <input type="text" class="form-control rounded-0" id="lastname" name="lastname" required>
                        </div>

                        <div class="">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control rounded-0" id="email" name="email" required>
                        </div>

                        <hr>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-5">Save</button>
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
    const addInfoForm = document.getElementById('addInfoForm');
    const alertMessage = document.getElementById('alertMessage');

    addInfoForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(addInfoForm);

        fetch('assets/save-info.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                addInfoForm.reset();
                window.scrollTo(0, 0);

                if (data.status === 'success') {
                    showBootstrapAlert('success', data.message);
                } else {
                    showBootstrapAlert('danger', data.message);
                }
            })
            .catch(error => {
                addInfoForm.reset();
                window.scrollTo(0, 0);
                showBootstrapAlert('danger', 'Unexpected error occur.');
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
</script>

</html>