# Pairs PHP Web Game
This project recreates the card game 'Pairs' as a PHP-based web application. It was originally deployed to a Microsoft Azure virtual machine using Apache HTTP Server.

### Overview
Pairs is a simple yet versatile card game, where the objective is to match identical sets of cards by flipping them over. This version introduces additional complexity through time limits, move limits, and multiple levels.

Main features:
- Account registration & avatar selection.
- 7 generated game levels of increasing difficulty.
- Score system & persistent leaderboard.
- Intuitive & mobile-friendly user interface.

### Examples
<img width="1022" height="642" alt="Image" src="https://github.com/user-attachments/assets/91b8d9f2-1e82-4c20-ab34-b38f34869afb" />  

### Implementation
- PHP: Server-side scripting.
- HTML + CSS + Bootstrap: Website structural framework & visual design.
- JavaScript: Game logic + Client-side scripting.
- Cookies + JSON: Persistent account registration & data storage.

### Repository Structure
- `index.php` - The PHP code for the website's home page.
- `registration.php` - The PHP code for the account registration page.
- `pairs.php` - The PHP code for the Pairs game page.
- `leaderboard.php` - The PHP code for the leaderboard page.
- `Images` - Contains the images used within the website.
- `Protected/gameLogic.js` - The JavaScript code for the Pairs game logic.
- `Protected/navbar.php` - The PHP code for the website's navigation bar.
- `Protected/storage.json` - The JSON data storage file for the leaderboard (and accounts by proxy).
- `Protected/wg_styles.css` - The CSS code for the website-wide styles.
