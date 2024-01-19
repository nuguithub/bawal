<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['auth-error'] = '
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bx bxs-error pe-1"></i><strong>You need to login first!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    header('Location: login.php');
    exit();
}

// Include necessary files
include 'connectDB.php';
$headTitle = 'Create Event';
include 'header.php';
?>

<section>
    <div class="p-5">
        <div class="container">
            <div id="alertMessage"></div>
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h3 class="card-title fw-semibold">Edit Event</h3>
                    <div id="editStat"></div>
                    <hr>
                    <form method="POST" id="editEventForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="title" placeholder="Title" name="title">
                            <label for="title">Event Title</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea type="text" class="form-control" id="description" placeholder="Description"
                                style="resize: none;" name="description"></textarea>
                            <label for="description">Description</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control datepicker" id="date" placeholder="Event Date"
                                name="date" aria-label="Event Date" name="event_date">
                            <label for="date">Date</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control timepicker" id="time" aria-label="Event Time"
                                name="time" name="event_time">
                            <label for="time">Time</label>
                        </div>
                        <div class="form-floating mb-3">
                            <!-- Use htmlspecialchars to safely output session values -->
                            <input type="text" class="form-control" id="manager" name="event_manager"
                                value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" hidden>
                            <input type="text" class="form-control" id="display" name="display"
                                value="<?php echo htmlspecialchars($_SESSION['username']); ?>" disabled>
                            <label for="manager">Event Manager</label>
                        </div>
                        <hr>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-5">SAVE</button>
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
<script src="js/editEvent.js"></script>
<script src="js/idle.js"></script>

</html>