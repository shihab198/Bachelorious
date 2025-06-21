<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bachelorious - House Rental for Bachelors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/responsive.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>">
                    <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Bachelorious Logo">
                    <span>Bachelorious</span>
                </a>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>properties/search.php">Properties</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>profile.php">Profile</a></li>
                        <?php if($_SESSION['user_type'] == 'owner'): ?>
                            <li><a href="<?php echo BASE_URL; ?>properties/add.php">Add Property</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>auth/login.php">Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>auth/register.php">Register</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <div class="mobile-nav">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
            <li><a href="<?php echo BASE_URL; ?>properties/search.php">Properties</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo BASE_URL; ?>profile.php">Profile</a></li>
                <?php if($_SESSION['user_type'] == 'owner'): ?>
                    <li><a href="<?php echo BASE_URL; ?>properties/add.php">Add Property</a></li>
                <?php endif; ?>
                <li><a href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo BASE_URL; ?>auth/login.php">Login</a></li>
                <li><a href="<?php echo BASE_URL; ?>auth/register.php">Register</a></li>
            <?php endif; ?>
            <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
        </ul>
    </div>