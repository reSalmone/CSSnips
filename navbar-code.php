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
        if (isset($_SESSION['username'])) { ?>
            <button class="nbutton" id="dropdown-nbutton">
                <?php echo "<span>" . htmlspecialchars($_SESSION['username']) . "</span>" ?>
                <img src="assets/images/omino.png" class="nicon">
                <div class="dropdown-content">
                    <a href="account.php">Account</a>
                    <a href="activity.php">Activity</a>
                    <a href="watchlist.php">Watchlist</a>
                    <a href="account_settings.php">Account settings</a>
                    <a href="logout.php?redirect=<?php echo $redirect; ?>">Logout</a>
                </div>
            </button>
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