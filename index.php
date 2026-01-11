<?php
require 'includes/db.php'; 
session_start();

// Logout functionality
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit(); 
}

// Redirection to specific pages
if (isset($_GET['classic']) && $_GET['classic'] === 'true') {
    header('Location: classic.php');
    exit(); 
}
if (isset($_GET['expansion']) && $_GET['expansion'] === 'true') {
    header('Location: frankenstein.php');
    exit(); 
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = "Guest";

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $username = $row['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo.png">
    <title>MEDICAL MAYHEM</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        .hidden {
            display: none;
        }
        #notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 8px;
            z-index: 1000;
            font-family: Arial, Helvetica, sans-serif;
            width: 300px;
            text-align: center;
        }
        #notification .close-btn {
            position: absolute;
            top: -20px;
            right: -10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: black;
        }
        #notification .close-btn:hover {
            color: red;
            background: none;
        }
        #notification button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            background-color: #b0434b;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        #notification button:hover {
            background-color: #582125;
        }
    </style>
</head>
<body>

<header class="navigation">
    <div class="nav">
        <a id="classic-link" href="classic.php">CLASSIC</a>
        <a href="frankenstein.php">EXPANSION</a>
    </div>
    <div class="nav-icon">
        <?php if (!$isLoggedIn): ?>
            <img src="assets/user-icon.png" id="user-icon" alt="User Icon">
        <?php else: ?>
            <a href="account.php">
                <img src="assets/user-icon.png" alt="User Icon">
            </a>
        <?php endif; ?>
    </div>
</header>

<!-- Notification -->
<div id="notification" class="hidden">
    <button class="close-btn" onclick="closeNotification()">×</button>
    <p>You need to log in first to access this feature.</p>
    <button onclick="location.href='login.php';">Log In</button>
</div>

<section class="section1">
    <div class="content">
        <img class="logo" src="assets/logo.png">
        <a class="buynowbtn" id="buyNow">BUY NOW</a>
        <a class="expansionbtn" href="frankenstein.php">
            <img src="assets/frank.png" alt="Frank Icon" class="expansion-icon">EXPANSION APP
        </a>
    </div>
</section>

<!-- Additional Content and Footer -->
<section class="section2">
    <div class="sec2content">
        <img class="scroll" src="assets/scroll.png">
    </div>
</section>

<section class="section3">
    <div class="sec3content">
        <img class="scrolltitle" src="assets/scrolltitle1.png">
        <div class="text-container">
            <p>Download the <b>Medical Mayhem Rule Book</b> to learn more about
                the game, including complete setup, rules, and mechanics!</p>
        </div>
        <a href="assets/RULE BOOK.pdf">Download Rule Book</a>
        
         <img class="scrolltitle" src="assets/scrolltitle2.png">
            <div class="text-container">
                <p>Try the demo version of Medical Mayhem, our unique tabletop
                    game, on <b>playingcards.io!</b> Dive into the action and experience
                    the thrill firsthand.</p>
            </div>
            <a href="https://playingcards.io/f88ceq">playingcards.io</a>
    </div>
</section>
 <!-- Carousel Section -->

    <section class="carouselsection slider1">
        <div class="slider">
            <div class="list">
                <div class="item">
                    <img src="assets/brain.png">
                </div>

                <div class="item">
                    <img src="assets/heart.png">
                </div>

                <div class="item">
                    <img src="assets/liver.png">
                </div>

                <div class="item">
                    <img src="assets/kidney.png">
                </div>

                <div class="item">
                    <img src="assets/lungs.png">
                </div>
            </div>

            <div class="buttons">
                <button id="prev"><</button>
                <button id="next">></button>
            </div>

            <ul class="dots">
                <li class="active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </section>

    <section class="carouselsection2 slider2">
        <div class="slider">
            <div class="list">
                <div class="item">
                    <img src="assets/doctor.png">
                </div>

                <div class="item">
                    <img src="assets/nurse.png">
                </div>

                <div class="item">
                    <img src="assets/pharmacist.png">
                </div>

                <div class="item">
                    <img src="assets/surgeon.png">
                </div>

                <div class="item">
                    <img src="assets/doctor.png">
                </div>
            </div>

            <div class="buttons">
                <button id="prev"><</button>
                <button id="next">></button>
            </div>

            <ul class="dots">
                <li class="active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </section>

    <section class="carouselsection3 slider3">
        <div class="slider">
            <div class="list">
                <div class="item">
                    <img src="assets/card1.png">
                </div>

                <div class="item">
                    <img src="assets/card2.png">
                </div>

                <div class="item">
                    <img src="assets/card3.png">
                </div>

                <div class="item">
                    <img src="assets/card4.png">
                </div>

                <div class="item">
                    <img src="assets/card5.png">
                </div>
                <div class="item">
                    <img src="assets/card6.png">
                </div>
            </div>

            <div class="buttons">
                <button id="prev"><</button>
                <button id="next">></button>
            </div>

            <ul class="dots">
                <li class="active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </section>
<footer>
    <div class="footer-content">
        <img src="assets/footertxt.png">
        <form class="footer-email" action="https://api.web3forms.com/submit" method="POST">
            <input type="hidden" name="access_key" value="255f792c-1893-4ea0-bd40-1c7156fdd77d">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="message" placeholder="Message" required></textarea>
            <button type="submit">SEND</button>
        </form>
    </div>
    
    <div class="footer-content2">
            <div class="social-icons">
                <a href="https://www.instagram.com/mimaversestudios/profilecard/?igsh=MXBqOGI1MnBtYmM4cg==">
                    <img src="assets/instagram.png">
                </a>
                <a href="https://x.com/medicalmayhem_?t=r2vGD_JsfRZ7NTv0c19haA&s=09">
                    <img src="assets/twitter.png">
                </a>
                <a href="https://www.facebook.com/profile.php?id=61568280391874&mibextid=ZbWKwL">
                    <img src="assets/facebook.png">
                </a>
                <a href="https://www.tiktok.com/@medical.mayhem2?_t=8rABZwwcmS9&_r=1">
                    <img src="assets/tik-tok.png">
             </a>
            </div>

            <div class="copyright-content">
                <div class="copyright-text">
                    <p>The MEDICAL MAYHEM name and logo, the distinctive design of the game board, the hospital rooms, the Expansion App name and
                        character, as well as each of the distinctive elements of the board, cards, and the playing pieces are trademarks of Mimaverse
                        Studios for its property trading game and game equipment used with permission. © 2024 Mimaverse Studios.
                        All Rights Reserved. Licensed by Mimaverse Studios.
                    </p>
                </div>

                <img class="mimaverse-logo" src="assets/logo2.png">
            </div>
        </div>
</footer>

<script>
// Handle Buy Now button
document.getElementById('buyNow')?.addEventListener('click', function (event) {
    if (!<?= json_encode($isLoggedIn) ?>) {
        event.preventDefault();
        document.getElementById('notification').classList.remove('hidden');
    }
    else {
        window.location.href = "classic.php";
    }
});

document.getElementById('classic-link')?.addEventListener('click', function (event) {
    if (!<?= json_encode($isLoggedIn) ?>) {
        event.preventDefault();
        document.getElementById('notification').classList.remove('hidden');
    }
    else {
        window.location.href = "classic.php";
    }
});


// Handle user icon click
document.getElementById('user-icon')?.addEventListener('click', function () {
    if (!<?= json_encode($isLoggedIn) ?>) {
        document.getElementById('notification').classList.remove('hidden');
    }
});

// Close the notification
function closeNotification() {
    document.getElementById('notification').classList.add('hidden');
}
</script>

<script src="main.js"></script>

</body>
</html>
