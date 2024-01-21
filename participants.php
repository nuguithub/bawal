<?php
    session_start();

    if (!isset($_SESSION['loggedIn'])) {
        $_SESSION['auth-error'] = '
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bx bxs-error pe-1"></i><strong>You need to login first!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        header('Location: login.php');
        exit();
    }

    include 'connectDB.php';
    $headTitle = 'Participants';
    include 'header.php'; 
?>

</body>

<div class="container">
    <div class="p-5 mt-5 shadow">
        <h2 class="fw-bold mb-3">Participants of <span class="text-secondary fst-italic" id="title"></span></h2>
        <table class="table table-striped table-hover shadow">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                </tr>
            </thead>
            <tbody id="participants">

            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script src="js/getParticipants.js"></script>

</html>