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

$username_valid = true; // This varaiable denotes whether the submitted username was invalid or not.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // The user has submitted the registration form, which must be processed.
    if (!preg_match("/^[0-9a-zA-Z_|.£¬ ]*$/",$_POST['username'])) {
        // If the inputted username does not match the above regular expression, then it is invalid.
        $username_valid = false;
    } else {
        // If the inputted username is valid, then create a profile for the user

        // First set session variables, to provide all pages on the site info about the user's profile
        $_SESSION['registered'] = true;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['avatar_skin'] = $_POST['skinChoice'];
        $_SESSION['avatar_eyes'] = $_POST['eyesChoice'];
        $_SESSION['avatar_mouth'] = $_POST['mouthChoice'];

        // Then set cookies, so that the user can return to their session even when closing the website
        setcookie('username', $_POST['username'], time() + (86400 * 30), "/");
        setcookie('avatar_skin', $_POST['skinChoice'], time() + (86400 * 30), "/");
        setcookie('avatar_eyes', $_POST['eyesChoice'], time() + (86400 * 30), "/");
        setcookie('avatar_mouth', $_POST['mouthChoice'], time() + (86400 * 30), "/");
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Registration Page</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!--Invokes the Content Delivery Network allowing the use of Bootstrap's CSS and JavaScript features-->

        <link rel=stylesheet type='text/css' href='./Protected/wg_styles.css'>
        
        <style type="text/css">
            #main > p {
                color: aliceblue;
                font-family: Verdana, Tahoma, sans-serif !important;
                text-align: center !important;
                margin: 10px 0px 20px;
            }

            form {
                background-color: rgba(0,0,0,0.8);
                width: 80%;
                max-width: 640px;
                padding: 20px;
                border-radius: 20px;
            }

            label {
                color: aliceblue;
                font-family: Verdana, Tahoma, sans-serif !important;
            }

            #error-message {
                font-family: Verdana, Tahoma, sans-serif !important;
            }

            .avatar-button {
                width: 40px;
                height: 40px;
                margin: 8px 5px;
                text-align: center;
                font-size: 20px;
                padding: 0px !important;
                color: white;
                border: 3px solid white;
                background-color: rgb(91, 118, 255);
            }
            .avatar-button:hover {
                color: grey;
                border: 3px solid grey;
                background-color: rgb(68, 94, 224);
            }

            /* These styles affect the avatar selection images */
            #avatar-images {
                margin: 0px 15px;
            }
            #avatar-images, #avatar-images > img:first-child {
                position: relative;
            }
            #avatar-images > img:not(:first-child) {
                position: absolute;
                /* For images to be positioned directly on top of each other, the parent element and the first child
                must have their position property set to 'relative', while other children must have it set to 'absolute' */
            }
            #avatar-images, #avatar-images > img {
                top: 0;
                left: 0;
            }
            #avatar-images > img {
                width: 150px;
                height: 150px;
                margin-top: 10px;
            }
        </style>
    </head>

    <body>
        <?php include 'Protected/navbar.php';?> <!-- This line of PHP code adds in the navbar from navbar.php -->

        <div id="main" class="d-flex flex-column">
            <p class='h2 text-center fw-bold'>Registration Page<p>

            <?php
            if (isset($_POST['username']) && $username_valid == true) {
                /* This message will show when the user registers with a valid username */
                echo "<p class='text-warning mb-3 fs-5 fw-bold'>Successfully registered as {$_POST['username']}!</p>";
            }
            ?>

            <form class="mx-auto" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <!--This is the form where the user enters a username and selects their avatar.-->
                <!--The form is submitted via the HTTP POST method to this same page-->

                <div class="mx-auto mb-4">
                    <label for="username-input" class="form-label fs-6 fw-bold">Username:</label>
                    <input type="text" class="form-control" id="username-input" name="username" aria-describedby="error-message" maxlength="40" required>
                    <!--The field for inputting the username-->

                    <div id="error-message" class="form-text text-danger">
                        <!--This error message shown will be shown if an invalid username was inputted-->
                        <?php
                        if ($username_valid == false) {
                            echo "The characters ” ! @ # % & ^ * ( ) + = { } [ ] — ; : “ ' < > ? / cannot be used.";
                        }
                        ?>
                    </div>
                </div>

                <div id="avatar-selector" class="mb-3 mx-auto d-flex justify-content-center"> 
                    <!--The container for the avatar selection elements-->

                    <div class="d-flex flex-column"> <!--Container for the left buttons--> 
                        <button class="avatar-button btn h1 fw-bold" type="button" onclick="avatarButtonClicked('left', 'skin')"><</button>
                        <button class="avatar-button btn h1 fw-bold" type="button" onclick="avatarButtonClicked('left', 'eyes')"><</button>
                        <button class="avatar-button btn h1 fw-bold" type="button" onclick="avatarButtonClicked('left', 'mouth')"><</button>
                    </div>

                    <div id="avatar-images"> <!--Container for the avatar imnages-->
                        <!--Avatar skin-->
                        <img id="skinImage" src='Images/Avatars/skin/red.png'> 
                        <input type="hidden" id="skinChoice" name="skinChoice" value="red.png">
                        <!-- Avatar eyes-->
                        <img id="eyesImage" src='Images/Avatars/eyes/normal.png'> 
                        <input type="hidden" id="eyesChoice" name="eyesChoice" value="normal.png">
                        <!--Avatar mouth -->
                        <img id="mouthImage" src='Images/Avatars/mouth/open.png'> 
                        <input type="hidden" id="mouthChoice" name="mouthChoice" value="open.png">
                    </div>

                    <div class="d-flex flex-column"> <!--Container for the right buttons--> 
                        <button class="avatar-button btn fw-bold" type="button" onclick="avatarButtonClicked('right', 'skin')">></button>
                        <button class="avatar-button btn fw-bold" type="button" onclick="avatarButtonClicked('right', 'eyes')">></button>
                        <button class="avatar-button btn fw-bold" type="button" onclick="avatarButtonClicked('right', 'mouth')">></button>
                    </div>
                </div>

                <div class="mx-auto mb-4">
                    <p class="text-white mb-3 fs-6"><small>
                        <?php
                        /* This PHP code will alter the message shown depending on if the user is registered or not */
                        if ($_SESSION['registered'] == true) {
                            echo "Because you are already registered, registering under a different username will create 
                            a new profile. Alternatively, you can use your current username to change your avatar.";
                        } else {
                            echo "Click 'Register' to create a new profile.";
                        }
                        ?>
                        
                    </small></p>
                </div>

                <div class="mx-auto d-flex justify-content-center">
                    <!--The sumbit button that submits the form-->
                    <input type="submit" value="Register" class="btn btn-primary rounded-pill">
                    
                </div>
            </form>
        </div>


        <script>
            // This script contains the JavaScript code which controls what the left/right avatar buttons do.

            // These lists hold the filenames of the avatar component images
            const skinList = ['red.png', 'orange.png', 'yellow.png', 'green.png', 'blue.png', 'purple.png', 'white.png'];
            const eyesList = ['normal.png', 'closed.png', 'laughing.png', 'long.png', 'rolling.png', 'winking.png'];
            const mouthList = ['open.png', 'sad.png', 'smiling.png', 'straight.png', 'surprise.png', 'teeth.png'];
            // The index
            var skinIndex = 0; var eyesIndex = 0; var mouthIndex = 0;

            function avatarButtonClicked(side, component) {
                // This function is called whenever any of the avatar selection buttons are clicked.

                if (component == 'skin') {
                    if (side == 'left') {
                        skinIndex = skinIndex == 0 ? 6 : skinIndex - 1;
                    } else {
                        skinIndex = skinIndex == 6 ? 0 : skinIndex + 1;
                    }                
                    document.getElementById('skinImage').src = 'Images/Avatars/skin/' + skinList[skinIndex];
                    document.getElementById('skinChoice').value = skinList[skinIndex];
                    console.log("Index: " + skinIndex + " , Current skin: " + skinList[skinIndex]);

                } else if (component == 'eyes') {
                    if (side == 'left') {
                        eyesIndex = eyesIndex == 0 ? 5 : eyesIndex - 1;
                    } else {
                        eyesIndex = eyesIndex == 5 ? 0 : eyesIndex + 1;
                    }                
                    document.getElementById('eyesImage').src = 'Images/Avatars/eyes/' + eyesList[eyesIndex];
                    document.getElementById('eyesChoice').value = eyesList[eyesIndex];
                    console.log("Index: " + eyesIndex + " , Current skin: " + eyesList[eyesIndex]);

                } else {
                    if (side == 'left') {
                        mouthIndex = mouthIndex == 0 ? 5 : mouthIndex - 1;
                    } else {
                        mouthIndex = mouthIndex == 5 ? 0 : mouthIndex + 1;
                    }                
                    document.getElementById('mouthImage').src = 'Images/Avatars/mouth/' + mouthList[mouthIndex];
                    document.getElementById('mouthChoice').value = mouthList[mouthIndex];
                    console.log("Index: " + mouthIndex + " , Current skin: " + mouthList[mouthIndex]);
                }
            }
        </script>
    </body>
</html>


<?php
// session_destroy();
// Uncomment this to manually remove the current session
?>
