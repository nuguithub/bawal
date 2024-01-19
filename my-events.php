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
    $headTitle = 'My Events';
    include 'header.php'; 
?>
<style>
.fst-italic {
    cursor: pointer;
}

.fst-italic:hover {
    cursor: pointer;
    text-decoration: underline;
}
</style>
<div class="container p-4">
    <h3 class="fw-bolder mb-3">My Events</h3>
    <div id="eventStat"></div>
    <div id="myEvents">

    </div>
</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script src="js/idle.js"></script>
<script src="js/getMyEvents.js"></script>

</html>