<?php

require 'includes/db.php'; 
session_start();


if (isset($_GET['classic']) && $_GET['classic'] === 'true') {
  
    header('classic.php');
    exit(); 
}
if (isset($_GET['expansion']) && $_GET['expansion'] === 'true') {
   
    header('Location: expansion.php');
    exit(); 
}

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit(); 
}

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
    <link rel="stylesheet" href="frankenstein.css">
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
            border: none;
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
    <!-- Header -->

   <header class="navigation">
    <div class="nav">
        <a href="index.php">CLASSIC</a>
        <a href="frankenstein.php">EXPANSION</a>
    </div>
    <div class="nav-icon">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <img src="assets/user-icon.png" id="user-icon" alt="User Icon">
        <?php else: ?>
            <a href="account.php">
                <img src="assets/user-icon.png" alt="User Icon">
            </a>
        <?php endif; ?>
    </div>
    <script>
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
   </script>

</header>

<!--Notification-->

<div id="notification" class="hidden">
    <button class="close-btn" onclick="closeNotification()">×</button>
    <p>You need to log in first to access your account.</p>
    <button onclick="location.href='login.php';">Log In</button>
</div>



    <!-- 1st Section -->

    <section class="expsection1">
        <img class="frank-logo1" src="assets/logo3.png">
        <img class="frank-logo2" src="assets/frankensteinlogo.png">
        <img class="frank-logo3" src="assets/frank.png">
    </section>

    <!-- 2nd  Section -->

    <section class="expsection2">
        <img class="asset1" src="assets/1.png">

        <div class="expsec2-text">
            <p>Face a thrilling twist on the classic Medical Mayhem! Battle the mad scientist’s 
                creations—The Monster Five and Frankenstein himself—as you navigate eerie labs and face monstrous foes.

                <br><br>Do you have what it takes to survive?</p>
        </div>

        <a class="expappbtn" href="https://drive.google.com/file/d/10InDJHmiwMKnYSr77SRlOV2flfi9Ncvq/view">MEDICAL MAYHEM EXPANSION APP</a>
        <div class="bottom-asset">
            <img class="asset2" src="assets/2.png">
            <img class="asset4" src="assets/4.png">
            <img class="asset5" src="assets/5.png">
            <img class="asset3" src="assets/3.png">
        </div>
    </section>

    <!-- 3rd Section -->
    
    <section class="expsection3">
        <img class="asset6" src="assets/6.png">

        <div class="expsec3-content">
            <img class="asset8" src="assets/8.png">
            
            <div class="text-container">
                <p>Download the Expansion App Rule Book 
                    <br>for complete game setup, rules, and 
                    <br>mechanics!</p>

                <a class="text-container-btn" href="assets/RULEBOOK EXPANSION.pdf">Download Rule Book</a>
            </div>

            <img class="asset7" src="assets/7.png">
        </div>
        
    </section>

    <!-- 4th Section -->

     <section class="expsection4">
        <img class="header-logo" src="assets/frankensteinlogo.png">
        <div class="expsec4-content">
            <div class="text-content-container">
                <p>Order now to add our newest Expansion Items to 
                    <br>your classic Medical Mayhem tabletop game 
                    <br>and dive deeper into the thrill!</p>

                <p>Expansion Package Includes:
                    <ul>
                        <li>New Map Description Banner</li>
                        <li>Double-sided Frankenstein Placard</li>
                        <li>Frankenstein Standeer</li>
                        <li>10 Frankenstein Lair Stickers (for Map)</li>
                        <li>Set of Sacrificial Organs</li>
                    </ul>
                </p>

                <button class="dlnow-btn" id="buyNow">BUY NOW</button>
            </div>
            <img src="assets/frankorgans.png">
        </div>
    </section>

    <!-- Notification -->
    <div id="notification" class="hidden">
    <button class="close-btn" onclick="closeNotification()">×</button>
    <p>You need to log in first to place an order.</p>
    <button onclick="location.href='login.php';">Log In</button>
</div>

    <!-- Footer -->

    <footer class = "exp">
        <div class="footer-form">
            <img src="assets/footertxt.png" >
            <form class="footer-email"  action="https://api.web3forms.com/submit" method="POST">
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
        if (!isLoggedIn) {
            // User is not logged in, show notification
            event.preventDefault(); // Prevent default action
            document.getElementById('notification').classList.remove('hidden');
        } else {
            // User is logged in, proceed with the purchase
            window.location.href = 'expansion.php';
            // Add your redirect or purchase logic here
        }
    });

    // Handle user icon click
    document.getElementById('user-icon')?.addEventListener('click', function () {
        if (!isLoggedIn) {
            document.getElementById('notification').classList.remove('hidden');
        }
    });

    // Close the notification
    function closeNotification() {
        document.getElementById('notification').classList.add('hidden');
    }
</script>

</body>
</html>