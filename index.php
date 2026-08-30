<?php
session_name("WebGame");
session_start(); // Starts the PHP session

if (!(isset($_SESSION['registered']))) {
    // If the user is not currently in a registered session
    
    if(isset($_COOKIE['username'])) {
        // If valid (non-expired) cookies are present, set the session variables using the cookies
        $_SESSION['registered'] = true;
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['avatar_skin'] = $_COOKIE['avatar_skin'];
        $_SESSION['avatar_eyes'] = $_COOKIE['avatar_eyes'];
        $_SESSION['avatar_mouth'] = $_COOKIE['avatar_mouth'];
    } else {
        // If cookies are either not present or expired
        $_SESSION['registered'] = false;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Landing Page</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!--Invokes the Content Delivery Network allowing the use of Bootstrap's CSS and JavaScript features-->

        <link rel=stylesheet type='text/css' href='./Protected/wg_styles.css'>
        
        <style type="text/css">
            /* These styles affect the text, links and buttons on this page */
            #main p, a {
                color: aliceblue;
                font-family: Verdana, Tahoma, sans-serif !important;
                text-align: center !important;
                text-decoration: none; /* Removes the underline from the link by default */
                margin: 10px 0px;
                transition-duration: 0.1s;
            }
            #main a:hover {
                text-decoration: underline; /* Adds the underline back when hovered over */
            }

        </style>
    </head>

    <body>
        <?php include 'Protected/navbar.php';?> <!-- This line of PHP code adds in the navbar from navbar.php -->

        <div id="main" class="d-flex flex-column">
            <?php
                if ($_SESSION['registered'] == true) {
                    // If the user is currently registered
                    echo "
                    <p class='h4 fw-bold'>Welcome to Pairs<p>
                    <a class='h4 fw-bold btn btn-primary my-2' href='pairs.php' style='border: 3px solid white'>Click here to play</a>
                    ";
                } else {
                    // If the user is not currently registered
                    echo "
                    <p class='h4 fw-bold'>You're not using a registered session?<p>
                    <a class='h3 fw-bold my-2' href='registration.php'>-- Register now --</a>
                    ";
                }
            ?>
        </div>
    </body>
</html>