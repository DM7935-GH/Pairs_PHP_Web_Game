// This 2D array holds the data for the levels
// For each level array: [0 - number of cards, 1 - cards to match, 2 - time given, 3 - moves allowed]
const levelData = [[6, 2, 30, 30], [8, 2, 25, 30], [9, 3, 45, 30], [12, 4, 25, 35],
[12, 3, 35, 50], [12, 2, 40, 40], [16, 4, 30, 75]];

// These lists hold the filenames of the emoji component images
const skinList = ['red.png', 'yellow.png', 'green.png'];
const eyesList = ['normal.png', 'closed.png', 'laughing.png', 'long.png', 'rolling.png', 'winking.png'];
const mouthList = ['open.png', 'sad.png', 'smiling.png', 'straight.png', 'surprise.png', 'teeth.png'];


// These are variables that will be used before, during and after the game.
var gameState = "not-started";
var currentlevel = 1;
var pointsArray = [0, 0, 0, 0, 0, 0, 0];

// These are variables that will be used during each level
var timer = 30;
var timerController;
var faceDownController;
var movesLeft = 0;
var cardRefs = []; // DOM references to the cards currently in the game
var cardEmojis = []; // Stores the numbers representing which emoji each card has
var matchedCards = []; // Tracks which cards have been matched
var faceUpCards = []; // Tracks which cards are face up without having been fully matched


function startGame () {
    // This function is invoked when the 'Start The Game' button is clicked.

    if (gameState === "not-started" || gameState === "game-ended") {
        gameState = "starting";

        // Hide and show the correct elements
        document.getElementById("startButton").style.display = "none";
        document.getElementById("gameText").style.display = "block"; 
        document.getElementById("levelCompleteText").style.display = "none";
        document.getElementById("pointsText").style.display = "none";
        document.getElementById("nextLevelButton").style.display = "none";
        document.getElementById("gameEndedTextA").style.display = "none";
        document.getElementById("gameEndedTextB").style.display = "none";
        document.getElementById("endScoreText").style.display = "none";
        document.getElementById("submitScoreButton").style.display = "none";
        document.getElementById("playAgainButton").style.display = "none";

        currentlevel = 1;
        pointsArray = [0, 0, 0, 0, 0, 0, 0];
        startLevel();
    }
}


function endGame (condition) {
    // This is the function called when the user either completes or loses the game
    clearInterval(timerController);
    clearInterval(faceDownController);
    gameState = "game-ended";

    document.getElementById("card-container").style.display = "none";

    // Calculate the total points from each level as the end score
    var endScore = 0;
    for (i=0; i < pointsArray.length; i++) {
        endScore += pointsArray[i];
    }
    document.getElementById("endScoreText").textContent = "Final Score: " + endScore;

    if (document.getElementById("total-score").value < endScore) {
        //If a new highscore has been set for the total score, then colour the game container gold
        document.getElementById("total-score").value = endScore;

        document.getElementById("gameText").style.color = "black";
        document.getElementById("game-box").style.backgroundColor = "#FFD700";
        document.getElementById("game-box").style.border ="4px solid rgb(0, 0, 0)";
        document.getElementById("card-container").style.backgroundColor ="rgb(210, 185, 0)";
    } else {
        document.getElementById("game-box").style.backgroundColor = "grey";
        document.getElementById("gameText").style.color = "white";
        document.getElementById("game-box").style.border ="4px solid rgb(170, 205, 255)";
        document.getElementById("card-container").style.backgroundColor ="rgb(100, 100, 100)";
    }

    switch (condition) {
        case "moves":
            // The user lost the game due to running out of moves
            console.log("The game is over due to running out of moves.");

            document.getElementById("gameEndedTextA").textContent = "Game Over!";
            document.getElementById("gameEndedTextB").textContent = "You ran out of moves!";
            document.getElementById("gameEndedTextA").style.color = "rgb(255, 50, 50)";
            document.getElementById("gameEndedTextB").style.color = "rgb(255, 50, 50)";
            document.getElementById("endScoreText").style.color = "rgb(255, 50, 50)";
            document.getElementById("submitScoreButton").classList.remove("btn-success");
            document.getElementById("playAgainButton").classList.remove("btn-success");
            document.getElementById("submitScoreButton").classList.add("btn-danger");
            document.getElementById("playAgainButton").classList.add("btn-danger");

            break;
        
        case "timer":
            // The user lost the game due to running out of time
            console.log("The game is over due to running out of time.");
            document.getElementById("gameEndedTextA").textContent = "Game Over!";
            document.getElementById("gameEndedTextB").textContent = "You ran out of time!";
            document.getElementById("gameEndedTextA").style.color = "rgb(255, 50, 50)";
            document.getElementById("gameEndedTextB").style.color = "rgb(255, 50, 50)";
            document.getElementById("endScoreText").style.color = "rgb(255, 50, 50)";
            document.getElementById("submitScoreButton").classList.remove("btn-success");
            document.getElementById("playAgainButton").classList.remove("btn-success");
            document.getElementById("submitScoreButton").classList.add("btn-danger");
            document.getElementById("playAgainButton").classList.add("btn-danger");

            break;

        case "win":
            // The user completed the last level of the game
            console.log("The game has been won!");
            document.getElementById("gameEndedTextA").textContent = "Congratulations!";
            document.getElementById("gameEndedTextB").textContent = "You have beaten every level of the game!";
            document.getElementById("gameEndedTextA").style.color = "rgb(0, 200, 50)";
            document.getElementById("gameEndedTextB").style.color = "rgb(0, 200, 50)";
            document.getElementById("endScoreText").style.color = "rgb(0, 200, 50)";
            document.getElementById("submitScoreButton").classList.remove("btn-danger");
            document.getElementById("playAgainButton").classList.remove("btn-danger");
            document.getElementById("submitScoreButton").classList.add("btn-success");
            document.getElementById("playAgainButton").classList.add("btn-success");
    }

    document.getElementById("gameEndedTextA").style.display = "block";
    document.getElementById("gameEndedTextB").style.display = "block";
    document.getElementById("endScoreText").style.display = "block";
    document.getElementById("submitScoreButton").style.display = "block";
    document.getElementById("playAgainButton").style.display = "block";
}


function startLevel() {
    // Resets the timer, points, number of moves and card references
    var ldcl = levelData[currentlevel - 1];
    timer = ldcl[2];
    movesLeft = ldcl[3];
    matchedCards = []; faceUpCards = []; cardRefs = []; cardEmojis = [];

    document.getElementById("levelText").textContent = "Level " + currentlevel;
    document.getElementById("timerText").textContent = "Timer: " + timer + "s";
    document.getElementById("movesText").textContent = "Moves Left: " + movesLeft;

    document.getElementById("game-box").style.backgroundColor = "grey";
    document.getElementById("gameText").style.color = "white";
    document.getElementById("game-box").style.border ="4px solid rgb(170, 205, 255)";
    document.getElementById("card-container").style.backgroundColor ="rgb(100, 100, 100)";
    
    
    printGameState();

    // The cards are created by cloning a hidden template card.
    var templateCard = document.getElementById("template-card");
    cardFaceDown(templateCard);

    // Removes any other (non-template) cards in the container.
    var cardsToRemove = templateCard.parentNode.children;
    for (l = cardsToRemove.length - 1; l > 0; l--) {
        cardsToRemove[l].remove();
    }

    // Randomly decides which emojis the cards will have.
    var emojiCode = 0;
    while (cardEmojis.length < ldcl[0]) {
        emojiCode = Math.floor(Math.random() * 108);
        // Generates a random number between 0-107, corresponding to a unique combination of emoji components (skin, eyes and mouth)

        if (cardEmojis.indexOf(emojiCode) === -1) {
            for (n = 0; n < ldcl[1]; n++) { cardEmojis.push(emojiCode); }            
        }
    }

    // Randomly shuffles the emojis that the cards will have
    for (i = cardEmojis.length - 1; i >= 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = cardEmojis[i];
        cardEmojis[i] = cardEmojis[j];
        cardEmojis[j] = temp;
    }
    
    // Clones the cards needed for this level.    
    for (x = 0; x < ldcl[0]; x++) {
        cardRefs[x] = templateCard.cloneNode(true);

        cardRefs[x].setAttribute('onclick', 'cardClicked(' + x + ')');
        cardRefs[x].removeAttribute('id');
        cardRefs[x].style.display = "block";

        // Use the emoji code to assign the correct component images to the card.
        emojiCode = cardEmojis[x];
        cardRefs[x].children[1].src = 'Images/Emojis/skin/' + skinList[Math.floor(emojiCode / 36)];
        emojiCode -= (Math.floor(emojiCode / 36) * 36);
        cardRefs[x].children[2].src = 'Images/Emojis/eyes/' + eyesList[Math.floor(emojiCode / 6)];
        emojiCode -= (Math.floor(emojiCode / 6) * 6);
        cardRefs[x].children[3].src = 'Images/Emojis/mouth/' + mouthList[emojiCode];

        document.getElementById("card-container").appendChild(cardRefs[x]);
    }

    document.getElementById("card-container").style.display = "grid";

    // This is the asynchronous anonymous function that controls the timer.
    timerController = setInterval(function () { 
        timer -= 1;
        document.getElementById("timerText").textContent = "Timer: " + timer + "s";
        if (timer < 1 && (gameState == "ongoing" || gameState == "checking")) { endGame("timer"); }
    }, 1000);

    gameState = "ongoing";
}


function nextLevel() {
    // This is the function called when the 'Next Level' button is clicked.
    if (gameState == "level-won") {
        currentlevel += 1;
        document.getElementById("card-container").style.display = "grid";
        document.getElementById("levelCompleteText").style.display = "none";
        document.getElementById("pointsText").style.display = "none";
        document.getElementById("nextLevelButton").style.display = "none";

        gameState = "starting";
        startLevel();
    }
}


function levelWon() {
    // This is the function called when all the cards in the level have been matched.
    clearInterval(timerController);
    clearInterval(faceDownController);
    gameState = "level-won";
    console.log("Level: " + currentlevel + " has been completed.");

    if (currentlevel == levelData.length) {
        // If this is the final level, then the user has won the game
        endGame("win");
    } else {
        document.getElementById("card-container").style.display = "none";
        document.getElementById("levelCompleteText").style.display = "block";
        document.getElementById("pointsText").style.display = "block";
        document.getElementById("nextLevelButton").style.display = "block";
    }    
}


function cardClicked (position) {
    // This is the function that is called when any of the cards are clicked
    // The 'position' parameter is used to determine which card was clicked

    if (gameState === "ongoing") {
        gameState = "checking";
        if (faceUpCards.indexOf(position) === -1 && matchedCards.indexOf(position) === -1) {
            // If this card is currently facing down (face up/matched cards cannot be flipped by clicking them)

            movesLeft -= 1;
            document.getElementById("movesText").textContent = "Moves Left: " + movesLeft;

            if (faceUpCards.length === 0) {
                //If there are currently no face up unmatched cards
                cardFaceUp(cardRefs[position]);
                faceUpCards.push(position);

                if (movesLeft < 1) {
                    endGame("moves");
                }

            } else if (cardEmojis[faceUpCards[0]] === cardEmojis[position]) {
                // If this card has the same emoji as other face up unmatched cards
                cardFaceUp(cardRefs[position]);
                faceUpCards.push(position);

                if (faceUpCards.length == levelData[currentlevel - 1][1]) {
                    // If all cards with this emoji are now face up, then they should be considered matched
                    faceUpCards.forEach( function (value) {
                        matchedCards.push(value);
                        cardRefs[value].style.backgroundColor = "rgb(150,255,150)";
                    });
                    faceUpCards = [];

                    // Awards the user points for matching cards
                    givePoints(timer);

                    if (matchedCards.length == levelData[currentlevel - 1][0]) {
                        // If all the cards have been matched, then this level has been won

                        // Give extra points for the number of moves left, to reward not making mistakes
                        givePoints(movesLeft * 2);
                        levelWon();
                    } else if (movesLeft < 1) {
                        endGame("moves");
                    }
                }
            } else {
                // If this card does not have the same emoji as other face up cards
                // All unmatched face up cards should now be flipped to be face down
                cardFaceUp(cardRefs[position]);
                faceUpCards.push(position);
                
                faceUpCards.forEach( function (value) {
                    cardRefs[value].style.backgroundColor = "rgb(255,150,150)";

                    faceDownController = setTimeout(function () {
                        if (cardRefs[value].style.backgroundColor == "rgb(255, 150, 150)") {
                            cardFaceDown(cardRefs[value]);
                            cardRefs[value].style.backgroundColor = "rgb(200,200,200)";
                        }
                    }, 500);
                });
                faceUpCards = [];

                if (movesLeft < 1) {
                    endGame("moves");
                }
            }
        }

        printGameState();
        if (gameState === "checking") { gameState = "ongoing"; }
    }
}


function cardFaceUp(card) {
    // Flips the card to be face up, with its emoji showing.
    card.children[1].style.opacity = 1;
    card.children[2].style.opacity = 1;
    card.children[3].style.opacity = 1;
    card.style.backgroundColor = "white";
}


function cardFaceDown(card) {
    // Flips the card to be face down, with its emoji not showing.
    card.children[1].style.opacity = 0;
    card.children[2].style.opacity = 0;
    card.children[3].style.opacity = 0;
    card.style.backgroundColor = "rgb(200,200,200)";
}


function givePoints(points) {
    pointsArray[currentlevel - 1] += points;
    document.getElementById("pointsText").textContent = "Points: " + pointsArray[currentlevel - 1];

    if (document.getElementById("level-" + currentlevel + "-score").value < pointsArray[currentlevel - 1]) {
        // If a new highscore has been set for this level, then colour the game container gold
        document.getElementById("level-" + currentlevel + "-score").value = pointsArray[currentlevel - 1];
        
        document.getElementById("gameText").style.color = "black";
        document.getElementById("game-box").style.backgroundColor = "#FFD700";
        document.getElementById("game-box").style.border ="4px solid rgb(0, 0, 0)";
        document.getElementById("card-container").style.backgroundColor ="rgb(210, 185, 0)";
    }
}


function printGameState() {
    // Prints the current state of the game to the console.
    console.log("Level: " + currentlevel + " - Timer: " + timer + " - Points: " +
        pointsArray[currentlevel - 1] + " - Moves Left: " + movesLeft);
}