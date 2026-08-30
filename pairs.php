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


// The scores that will be used as the initial values of the hidden fields in the HTML below
$importScores = array(0, 0, 0, 0, 0, 0, 0, 0);

if (isset($_SESSION['username'])) {
    // Retrieves the JSON file storing all of the user scores and converts it into an array
    $userScoresJSON = file_get_contents('Protected/storage.json');
    $userScoresAll = json_decode($userScoresJSON, true);

    if ($userScoresAll == null) {
        // If the JSON file is currently empty
        $userScoresAll = array();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // If the user has just submitted their score, update the array with said scores

        // Updates the array with the user's best scores
        $userScoreArray = array();
        $userScoreArray["l1_score"] = $_POST["level-1-score"];
        $userScoreArray["l2_score"] = $_POST["level-2-score"];
        $userScoreArray["l3_score"] = $_POST["level-3-score"];
        $userScoreArray["l4_score"] = $_POST["level-4-score"];
        $userScoreArray["l5_score"] = $_POST["level-5-score"];
        $userScoreArray["l6_score"] = $_POST["level-6-score"];
        $userScoreArray["l7_score"] = $_POST["level-7-score"];
        $userScoreArray["total_score"] = $_POST["total-score"];
        $userScoresAll[$_SESSION['username']] = $userScoreArray;
    }

    if (array_key_exists($_SESSION['username'],$userScoresAll)) {
        // Writes the user's scores to the HTML hidden fields, if they could be found in the file.
        $importScores[0] = $userScoresAll[$_SESSION['username']]["l1_score"];
        $importScores[1] = $userScoresAll[$_SESSION['username']]["l2_score"];
        $importScores[2] = $userScoresAll[$_SESSION['username']]["l3_score"];
        $importScores[3] = $userScoresAll[$_SESSION['username']]["l4_score"];
        $importScores[4] = $userScoresAll[$_SESSION['username']]["l5_score"];
        $importScores[5] = $userScoresAll[$_SESSION['username']]["l6_score"];
        $importScores[6] = $userScoresAll[$_SESSION['username']]["l7_score"];
        $importScores[7] = $userScoresAll[$_SESSION['username']]["total_score"];
    }
    
    // Converts the array back to JSON and stores it in the JSON file
    $userScoresJSON = json_encode($userScoresAll);
    file_put_contents("Protected/storage.json", $userScoresJSON);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Pairs Game Page</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!--Invokes the Content Delivery Network allowing the use of Bootstrap's CSS and JavaScript features-->

        <link rel=stylesheet type='text/css' href='./Protected/wg_styles.css'>

        <script type="text/javascript" src="./Protected/gameLogic.js"></script> <!--Imports the external JS file for the game logic-->
        
        <style type="text/css">
            #main > p {
                color: aliceblue;
                font-family: Verdana, Tahoma, sans-serif !important;
            }

            /* These styles affect the game container */
            #game-box {
                background-color: grey;
                border: 4px solid rgb(170, 205, 255);
                border-radius: 20px;
                width: 90vw;
                max-width: 900px;
                height: 85%;
                box-shadow: 5px 5px 5px 0px rgb(120, 165, 255);
                position: relative;
            }

            /* These are styles for the various game buttons */
            #startButton {
                display: block;
                margin: auto !important;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border: 3px solid white;
            }

            #nextLevelButton {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 65%;
                left: 50%;
                transform: translate(-50%, -65%);
                border: 3px solid white;
            }

            #submitScoreButton {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 65%;
                left: 50%;
                transform: translate(-50%, -65%);
                border: 3px solid white;
            }

            #playAgainButton {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 80%;
                left: 50%;
                transform: translate(-50%, -80%);
                border: 3px solid white;
            }

            /* These styles affect the text displaying the points, level and timer */
            #gameText {
                color: white;
                font-weight: bold;
                font-family: 'Trebuchet MS', sans-serif !important;
                display: none;
                font-size: 1.2rem;
            }

            /* These are styles for the other game text */
            #levelCompleteText {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 40%;
                left: 50%;
                transform: translate(-50%, -40%);
                font-family: 'Trebuchet MS', sans-serif !important;
                color: rgb(40, 40, 255);
            }

            #pointsText {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-family: 'Trebuchet MS', sans-serif !important;
                color: rgb(40, 40, 255);
            }
            
            #gameEndedTextA {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 30%;
                left: 50%;
                transform: translate(-50%, -30%);
                font-family: 'Trebuchet MS', sans-serif !important;
                color: rgb(255, 50, 50);
            }

            #gameEndedTextB {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 40%;
                left: 50%;
                transform: translate(-50%, -40%);
                width: 80%;
                font-family: 'Trebuchet MS', sans-serif !important;
                color: rgb(255, 50, 50);
            }
            
            #endScoreText {
                display: none;
                margin: auto !important;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-family: 'Trebuchet MS', sans-serif !important;
                color: rgb(255, 50, 50);
            }

            /* These styles affect the card conatiner */
            #card-container {
                display: none;
                grid-template-columns: auto auto auto auto;
                justify-content: center;
                align-items: center;

                width: calc(100% - 40px);
                height: calc(100% - 75px);
                background-color: rgb(100, 100, 100);
                border-radius: 20px;
                position: absolute;
                left: 20px;
            }

            /* These styles affect the game cards */
            .card {
                background-color: rgb(200,200,200);;
                border-radius: 8px;
                padding: 15px;
                border: 5px solid black;
                
                position: relative;
                aspect-ratio: 3 / 4;
                max-width: 80%;
                max-height: 80%;
            }
            .card:hover {
                border: 5px solid rgb(70, 110, 255);
            }
            .card > img {
                max-width: 60px;
                max-height: 60px;
                margin: auto;
            }
            .card > img:first-child {
                opacity: 0;
            }
            .card > img:not(:first-child) {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

        </style>
    </head>

    <body>
        <?php include 'Protected/navbar.php';?> <!-- This line of PHP code adds in the navbar from navbar.php -->

        <div id="main" class="d-flex flex-column">
            <p class="h3 text-center fw-bold my-2">Play Pairs</p>

            <div id="game-box" class="mx-auto p-2 my-3"> <!--The container in which the game will take place-->

                <button id="startButton" class="btn rounded-pill btn-lg btn-primary fw-bold text-white" onclick="startGame()">
                    Start The Game
                </button>

                <p id="gameText" class="mx-4 my-2 text-center">
                    <span id="movesText" style="float: left;">Moves Left: 30</span> <!--Shows the number of points-->
                    <span id="levelText">Level 1</span> <!--Shows the current game level-->
                    <span id="timerText" style="float: right;">Timer: 60s</span> <!--Shows the timer-->
                </p>

                <div id="card-container"> 
                    <div id="template-card" class="card" style="display: none;" onclick="cardClicked(0)">
                        <img class="dummyAlignment" src='Images/Emojis/skin/yellow.png'>
                        <img class="cardEmojiSkin" src='Images/Emojis/skin/yellow.png'>
                        <img class="cardEmojiEyes" src='Images/Emojis/eyes/normal.png'>
                        <img class="cardEmojiMouth" src='Images/Emojis/mouth/open.png'>
                    </div>
                    <!--This is the template for the game's cards-->
                    <!--The JavaScript code will clone it to make the actual cards that will be used in levels-->
                </div>

                <!--Level completion elements-->
                <p id="levelCompleteText" class="fw-bold text-center display-6">Level Complete!</p>
                <p id="pointsText" class="text-center h4">Points: 0</p>

                <button id="nextLevelButton" class="btn rounded-pill btn-lg btn-primary fw-bold text-white" onclick="nextLevel()">
                    Next Level
                </button>

                <!--Game end elemebnts-->
                <p id="gameEndedTextA" class="fw-bold text-center display-5">Game Over!</p>
                <p id="gameEndedTextB" class="text-center h4">You ran out of moves!</p>
                <p id="endScoreText" class="text-center h4">Final Score: 0</p>

                <button id="playAgainButton" class="btn rounded-pill btn-lg btn-danger fw-bold text-white" onclick="startGame()">
                    Play Again
                </button>

                <!--This is the form that will submit the user's game scores-->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <button id="submitScoreButton" class="btn rounded-pill btn-lg btn-danger fw-bold text-white" onclick="">
                        Submit Score
                    </button>
                    <!--A button inside a form is automatically treated as a submit button, unless 'type="submit"' is included-->

                    <!--The rest of these are hidden input fields used to submit the user's highscores-->
                    <input type="hidden" id="level-1-score" name="level-1-score" value=<?php echo $importScores[0]; ?>>
                    <input type="hidden" id="level-2-score" name="level-2-score" value=<?php echo $importScores[1]; ?>>
                    <input type="hidden" id="level-3-score" name="level-3-score" value=<?php echo $importScores[2]; ?>>
                    <input type="hidden" id="level-4-score" name="level-4-score" value=<?php echo $importScores[3]; ?>>
                    <input type="hidden" id="level-5-score" name="level-5-score" value=<?php echo $importScores[4]; ?>>
                    <input type="hidden" id="level-6-score" name="level-6-score" value=<?php echo $importScores[5]; ?>>
                    <input type="hidden" id="level-7-score" name="level-7-score" value=<?php echo $importScores[6]; ?>>
                    <input type="hidden" id="total-score" name="total-score" value=<?php echo $importScores[7]; ?>>
                </form>
                
            </div>
        </div>
    </body>
</html>