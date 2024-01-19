<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>YGEOM - <?php echo $headTitle;?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
    :root {
        --brown: #864f34;
        --skin: #EAAB66;
        --red: #D04427;
        --yellow: #F7C026;
        --white: #F0F6F0;
        --blue: #3170A9;
    }

    body {
        background: var(--white);
        font-family: 'Montserrat', sans-serif;
    }

    h1,
    h2,
    h3,
    p {
        font-family: 'Montserrat', sans-serif;
    }

    .logout {
        color: var(--red);
    }

    #hover {
        position: relative;
        text-transform: uppercase;
        transition: .8s ease;
    }

    #hover::after {
        content: '';
        transform: scaleX(0);
        transition: all 0.5s;
        height: 3px;
        width: 100%;
        background-color: var(--blue);
        position: absolute;
        bottom: 0;
        left: 0;
    }

    #hover:hover::after {
        transform: scaleX(1);
    }

    #hover:hover {
        color: var(--blue);
    }

    .logout:hover {
        color: var(--red) !important;
    }

    .logout::after {
        background-color: var(--red) !important;
    }

    #zoom:hover {
        font-weight: 600;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top" style="background: var(--yellow) !important;">
        <div class="container py-2">
            <a class="navbar-brand" href="index.php#">
                <img src="YGEOM2.png" alt="Bootstrap" height="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav fw-semibold justify-content-center ms-auto mb-2 mb-lg-0 grid">
                    <li class="nav-item me-4">
                        <a class="nav-link m-0" href="index.php#" id="hover">Home</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link" href="make-event.php#" id="hover">Create Events</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link" href="my-events.php#" id="hover">My Events</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link" href="profile.php#" id="hover">Profile</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($isLoggedIn) : ?>
                        <!-- If logged in, show Logout link -->
                        <a class="nav-link logout" href="#" id="hover" data-bs-toggle="modal"
                            data-bs-target="#confirmationModal">Logout</a>
                        <?php else : ?>
                        <!-- If not logged in, show Login link -->
                        <a class="nav-link" href="login.php#" id="hover">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php  include 'confirmModal.php';?>