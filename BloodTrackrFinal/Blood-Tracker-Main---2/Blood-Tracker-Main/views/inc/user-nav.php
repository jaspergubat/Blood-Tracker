<!-- Navigation Header -->
<div class="header">
    <div class="bg-lime"></div>
    <ul class="navbar">
        <a href="user-index.php" class="site-logo">
            <div class="logo-wrapper">
                <img src="assets/images/logo.png" alt="BloodTrackr">
            </div>
            <div class="logo-group">
                <div class="logo-title">BloodTrackr</div>
                <div class="logo-desc">blood services</div>
            </div>
        </a>
        <ul class="nav">
            <a id="home-link" href="user-index.php"><i class="fas fa-home"></i> <span>Home</span></a>
            <a id="search-link" href="search-and-inquiry.php"><i class="fas fa-search"></i> <span>Search and Inquiry</span></a>
            <a id="review-link" href="review-and-feedback.php"><i class="fas fa-comments"></i> <span>Review and Feedback</span></a>
        </ul>
        <div class="left-group">
            <div class="user" id="user-btn">
                <div class="user-group">
                    <div class="user-icon">
                        <?php
                            if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
                                echo strtoupper(substr($_SESSION['user_name'], 0, 1));
                            }
                        ?>
                    </div>
                    <i class="fa-solid fa-caret-down"></i>
                </div>
            </div>
            <div class="icon-button" id="menu-btn">
                <i class="fa-solid fa-bars menu"></i>
            </div>
        </div>
    </ul>
</div>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
    <div class="menu-content">
        <a href="user-index.php">Home</a>
        <a href="search-and-inquiry.php">Search and Inquiry</a>
        <a href="review-and-feedback.php">Review and Feedback</a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var mobileMenu = document.getElementById("mobile-menu");
    var orientationMessage = document.querySelector(".orientation-message");

    function showOrientationMessage() {
        if (window.innerWidth <= 640 && window.innerHeight > window.innerWidth) {
            orientationMessage.style.display = "block";
        } else {
            orientationMessage.style.display = "none";
        }
    }

    document.getElementById("menu-btn").addEventListener("click", function() {
        if (mobileMenu.style.right === "" || mobileMenu.style.right === "-300px") {
            mobileMenu.style.right = "0";
            orientationMessage.style.display = "none";
        } else {
            mobileMenu.style.right = "-300px";
            showOrientationMessage();
        }
    });

    showOrientationMessage();
    window.addEventListener("resize", showOrientationMessage);
});
</script>

<script>
        const currentUrl = window.location.href;

        const urlToLinkIdMap = {
            'http://localhost:8000/user-index.php': 'home-link',
            'http://localhost:8000/search-and-inquiry.php': 'search-link',
            'http://localhost:8000/review-and-feedback.php': 'review-link'
        };

        const activeLinkId = urlToLinkIdMap[currentUrl];

        if (activeLinkId) {
            document.getElementById(activeLinkId).classList.add('active');
        }
    </script>