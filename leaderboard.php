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

$levelShown = "total";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $levelShown = $_POST["levelSelect"];
}

// Associative data arrays used instead of conditional statements for efficiency
$mapper1 = array("total"=>"total_score", "1"=>"l1_score", "2"=>"l2_score", "3"=>"l3_score",
"4"=>"l4_score", "5"=>"l5_score", "6"=>"l6_score", "7"=>"l7_score",);
$mapper2 = array("total"=> "Total Scores", "1"=> "Level 1 Scores", "2"=> "Level 2 Scores", "3"=> "Level 3 Scores",
"4"=> "Level 4 Scores", "5"=> "Level 5 Scores", "6"=> "Level 6 Scores", "7"=> "Level 7 Scores");

// Retrieves the JSON file storing all of the user scores and converts it into an array
$userScoresJSON = file_get_contents('Protected/storage.json');
$userScoresAll = json_decode($userScoresJSON, true);

$levelScores = array(); // Holds the username-score pairs to be displayed

if ($userScoresAll != null) {
    // If the JSON file is not empty, meaning the scores of at least one user has been saved

    foreach ($userScoresAll as $name => $scores) {
        // Fills $levelScores with each user's score on a particular level
        $levelScores[$name] = $scores[$mapper1[$levelShown]];
    }
}

arsort($levelScores); // Sorts the username-score pairs in descending order of the scores
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Leaderboard Page</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!--Invokes the Content Delivery Network allowing the use of Bootstrap's CSS and JavaScript features-->

        <link rel=stylesheet type='text/css' href='./Protected/wg_styles.css'>
        
        <style type="text/css">
            #main > p {
                color: aliceblue;
                font-family: Verdana, Tahoma, sans-serif !important;
                text-align: center !important;
            }

            form {
                display: flex;
                justify-content: center !important;
                padding: 0px 20px;
            }

            label {
                color: aliceblue !important;
                font-family: Verdana, Tahoma, sans-serif !important;
                font-size: 1rem;
                text-align: center;
            }

            select {
                background-color: rgba(0,0,0, 0.7);
                color: white;
                border: 2px solid white;
                border-radius: 5px;
            }

            option {
                font-family: Verdana, Tahoma, sans-serif !important;
                font-size: 0.8rem;
            }

            input {
                font-family: Verdana, Tahoma, sans-serif !important;
                border: 2px solid white !important;
                font-size: 0.7rem !important;
                padding: 4px 8px !important;
            }

            #table-container {
                background-color: rgba(0, 0, 0, 0.8);
                border-radius: 10px;
                border: 3px solid #ffffff;
                padding: 20px;
                width: 85%;
                max-width: 800px;
                margin: 0 auto;
                text-align: center;
                font-family: 'Trebuchet MS', sans-serif;
                border-spacing: 2px;
            }

            table {
                width: 100%;
                margin: 5px 0px;
            }

            table th, table td {
                text-align: center;
                color: rgb(255, 255, 255);
                padding: 8px;
            }
            table th {
                font-size: 1.1rem;
                background-color: rgba(0,0,255,0.5);
            }
            table td {          
                font-size: 0.9rem;
                border-top: 2px solid rgb(173, 173, 173);
            }

        </style>
    </head>

    <body>
        <?php include 'Protected/navbar.php';?> <!-- This line of PHP code adds in the navbar from navbar.php -->

        <div id="main" class="d-flex flex-column">
            <p class='h3 text-center mt-2 mb-4 fw-bold'>Leaderboard Page<p>

            <form class="" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <!--This form is used to select which scores should be shown-->

                <label for="levelSelect" class="me-2 my-auto">Choose which scores to display:</label>

                <select name="levelSelect" id="levelSelect" class="mx-2 my-auto">
                    <option value="total">Total Scores</option>
                    <option value="1">Level 1</option>
                    <option value="2">Level 2</option>
                    <option value="3">Level 3</option>
                    <option value="4">Level 4</option>
                    <option value="5">Level 5</option>
                    <option value="6">Level 6</option>
                    <option value="7">Level 7</option>
                </select>

                <input type="submit" value="Show" class="ms-3 my-auto btn btn-sm btn-primary rounded-pill fw-bold">
            </form>

            <div id="table-container" class="mt-4 mb-3">
                <h4 class="mb-4 fw-bold text-white"><?php echo $mapper2[$levelShown]; ?></h4>
                
                <table> <!--The table containing the leaderboard-->
                    <thead>
                        <tr class="py-2">
                            <th scope="col">Rank</th>
                            <th scope="col">Username</th>
                            <th scope="col">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // This PHP code fills the leaderboard table with the scores
                        $rank = 1;

                        foreach ($levelScores as $username => $score) {
                            echo "<tr>
                                <td>{$rank}</td>
                                <td>{$username}</td>
                                <td>{$score}</td>
                            </tr>";

                            $rank++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </body>
</html>