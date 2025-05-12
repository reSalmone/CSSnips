<div class="navbar">
    <div class="left-navbar">
        <a href="index.php" class="navbar-title">CSSnips</a>
        <button class="nbutton" onclick="location.href='explorer.php'" type="button">
            <span>Explore</span>
            <img src="assets/images/search.png" class="nicon">
        </button>
        <button class="nbutton" onclick="location.href = 'challenges.php'" type="button">
            <span>Challenges</span>
            <img src="assets/images/target.png" class="nicon">
        </button>
        <button class="nbutton" onclick="location.href = 'creator.php'" type="button">
            <span>Create</span>
            <img src="assets/images/add.png" class="nicon">
        </button>
    </div>
    <div class="right-navbar">
        <?php
        /*se trova in questa sessione un username settato significa che l'utente ha già una sessione attiva e vede logout,
        se no gli fa vedere il bottone login che runna openLogin() che è una funzione che sta nel file login.js che mostra
        il blocco con id #page da display: none; a display: block;*/
        if (isset($_SESSION['username'])) { ?>
            <div class="dropdown">
                <button class="nbutton">
                    <span>Account</span>
                    <img src="assets/images/omino.png" class="nicon">
                </button>
                <div class="dropdown-content">
                    <a href="account.php">Account</a>
                    <a href="activity.php">Activity</a>
                    <a href="watchlist.php">Watchlist</a>
                    <a href="account_settings.php">Account settings</a>
                    <a href="logout.php?redirect=<?php echo $redirect; ?>">Logout</a>
                </div>
            </div>
        <?php } else {
            echo '<button class="nbutton" type="button" onclick="openLogin(event);">
                                <span>Login</span>
                            </button>';
            echo '<button class="nbutton" type="button" onclick="openSignup(event);">
                                <span>Signup</span>
                            </button>';
        }
        ?>
    </div>
</div>