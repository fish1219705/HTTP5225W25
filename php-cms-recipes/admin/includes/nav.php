<?php
session_start(); // Start the session at the very top of the file
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Toggle button on the right -->
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- Centered navigation items -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav text-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>


                 <!-- Check if the user is logged in (no need to check role if only logged-in state matters) -->
                <?php if (isset($_SESSION['id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- For users not logged in, show Login button -->
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/index.php">Admin Panel</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>