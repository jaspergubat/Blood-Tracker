<style>
    .floating-btn {
            position: fixed;
            top: 9%;
            right: 0%;
            background: darkred;
            color: white;
            border: none;
            overflow: hidden;
            width: 200px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 24px;
            transition: 1s;
        }
        .floating-btn:hover {
            background: #b10000;
        }

        .video-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .video-modal video {
            max-width: 80%;
            max-height: 80%;
            overflow: hidden;
        }
        .video-modal .close-btn {
            overflow: hidden;
            background: darkred;
            color: white;
            position: absolute;
            top: 10%;
            left: 86%;
            z-index: 99999;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 60px;
            height: 50px;
            font-size: 4em;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .video-modal .close-btn:hover {
            background: #b10000;
        }
</style>
<!-- Login -->
<div class="hero">
    <div class="hero-content">
        <form action="#" method="post">
            <div class="details">
                <div class="desc">
                    <span class="title">BloodTrackr</span>
                    <p class="description">Register and keep track of blood drives</p>
                    <?php
                        if(isset($error)) {
                            echo '<span class="error-msg"> Error: ' . $error . '</span>';
                        } elseif (isset($_SESSION['success_message'])) {
                            echo '<span class="success-msg"> Success: ' . $_SESSION['success_message'] . '</span>';
                            unset($_SESSION['success_message']);
                        } elseif (isset($_SESSION['error_message'])) {                            
                            echo '<span class="error-msg"> Success: ' . $_SESSION['error_message'] . '</span>';
                            unset($_SESSION['error_message']);
                        }
                    ?>
                </div>
                <div class="fields">
                    <div class="field input">
                        <label for="email">Email Address*</label>
                        <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
                    </div>
                    <div class="field input">   
                        <label for="password">Password*</label>
                        <input id="password" type="password" class="form-control" name="password" required autocomplete="password">
                    </div>
                    <div class="redirect">
                        <span class="text">Don't have an account?</span>
                        <a href="register.php">Register</a>
                    </div>
                </div>
                <div class="actions">
                    <button class="primary" name="submit" type="submit">Sign in</button>
                </div>
            </div>
        </form>
    </div>
</div>

<button class="floating-btn" onclick="openVideo()">
        About Us
    </button>

    <div class="video-modal" id="videoModal">
        <button class="close-btn" onclick="closeVideo()">X</button>
        <video id="aboutUsVideo" controls>
            <source src="assets/video/BloodTrackr.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <script>
        function openVideo() {
            document.getElementById('videoModal').style.display = 'flex';
            document.getElementById('aboutUsVideo').play();
        }

        function closeVideo() {
            document.getElementById('videoModal').style.display = 'none';
            document.getElementById('aboutUsVideo').pause();
            document.getElementById('aboutUsVideo').currentTime = 0;
        }
    </script>