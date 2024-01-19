<?php 
    $headTitle = 'Home';
    include 'header.php'; 
?>

<section id="intro">
    <div class="d-flex align-items-center w-100" style="min-height: 60vh; background: var(--white);">
        <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between">
            <img src="MakeEvents.png" class="img-fluid px-5 mb-4 mb-lg-0" style="max-height: 30vh; object-fit: cover;"
                alt="...">

            <div class="text-center text-lg-start mx-lg-auto">
                <a href="register.php" class="btn btn-warning btn-lg fw-semibold px-5">REGISTER NOW!</a>
            </div>
        </div>
    </div>

</section>

<section id="divider" class="mb-5">
    <div class="d-flex align-items-center" style="height: 10vh; background: var(--blue);">
        <a class="mx-auto text-decoration-none" style="color: var(--yellow);" href="#events" id="zoom">View Latest
            Events</a>
    </div>
</section>
<section id="events" class="mb-5">
    <div class="container">
        <style>
        .card {
            max-height: 55vh;
            height: 55vh;
            overflow: auto;
        }

        .fst-italic {
            transition: all .5s ease;
            cursor: pointer;
        }

        .fst-italic:hover {
            color: var(--brown) !important;
            text-decoration: underline;
        }
        </style>
        <div class="row d-flex justify-content-center" id="eventDetails">
        </div>
    </div>
</section>


</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script src="js/getEvents.js"></script>

</html>