<?php 

$right_navbar = "";

// This determines what will appear on the right side of the navbar
if ($_SESSION['registered'] == false) {
    $right_navbar = "
    <li class='nav-item'>
        <a href='registration.php' class='nav-link btn' name='register' id='register-button'>Register</a>
    </li>
    ";
} else {
    $right_navbar = "
    <li class='nav-item'>
        <a href='leaderboard.php' class='nav-link btn' name='leaderboard' id='leaderboard-button'>Leaderboard</a>
    </li>
    
    <li class='nav-item mx-auto navbar-avatar'>
        <!--The user's avatar is created by overlaying three images on top of each other-->
        <img src='Images/Avatars/skin/{$_SESSION['avatar_skin']}'> <!--The skin-->
        <img src='Images/Avatars/eyes/{$_SESSION['avatar_eyes']}'> <!-- The eyes-->
        <img src='Images/Avatars/mouth/{$_SESSION['avatar_mouth']}'> <!--The mouth -->
    </li> 
    ";
}

echo 
"
<nav class='navbar navbar-expand-lg'> <!--All of the code within the nav tags is for the navbar-->
    <div class='container-fluid'> 
        <!--Container-fluid is a bootstrap class that causes the navbar to expand to fit the width of the viewport-->
        
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
            <!--Bootstrap will collapse the horizontal navbar content into a vertical list if the viewport is small enough-->
            <!--The 'navbar-toggler' button will expand or contract this list when clicked-->
        </button>

        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <!--This is the container for the navbar's individual elements-->
            
            <ul class='navbar-nav me-auto'> <!--Navbar items that are aligned to the left-->
                <li class='nav-item'>
                    <a href='index.php' class='nav-link btn' aria-current='page' name='home' id='home-button'>Home</a>
                </li>

                <li class='nav-item'>
                    <a href='pairs.php' class='nav-link btn' name='memory' id='play-pairs-button'>Play Pairs</a>
                </li>    
            </ul>

            <ul class='navbar-nav ms-auto'> <!--Navbar items that are aligned to the right-->
                {$right_navbar}
            </ul>
        </div>
    </div>
</nav>
";
?>