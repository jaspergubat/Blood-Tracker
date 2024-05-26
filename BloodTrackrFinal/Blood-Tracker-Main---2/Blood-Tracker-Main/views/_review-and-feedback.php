<div class="reviews-container">
    <div class="search">
        <div class="search-header">
            <i class="fa-solid fa-magnifying-glass"></i>
            <header>Search</header>
        </div>
        <form action="" method="GET" class="filters-group">
            <div class="filters">
                <div class="filter">
                    <label for="name">Select Blood Bank</label>
                    <select class="form-control" id="name" name="name">
                        <option value="">Select a blood bank</option>
                        <?php
                            $bloodBanks = getAllBloodBanks($conn);

                            foreach ($bloodBanks as $bloodBank) {
                                $selected = isset($_GET['name']) && $_GET['name'] == $bloodBank['name'] ? 'selected' : '';
                                echo "<option value=\"{$bloodBank['name']}\" $selected>{$bloodBank['name']}</option>";

                                if ($selected) {
                                    $_SESSION['selectedBankID'] = $bloodBank['id'];
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="actions" style="background-color: white">
                <button type="submit" class="secondary">Confirm Selection</button>
            </div>
        </form>
    </div>
    
    <div class="content-container">
        <div class="search-header">
            <header>Reviews and Feedback</header>
        </div>
        <div class="content-group">
            <?php

            function displayBloodBankDetailsAndReviews($conn) {
                if (isset($_GET['name']) && !empty($_GET['name'])) {
                    $selectedBankName = $_GET['name'];
                    $stmt = $conn->prepare("SELECT * FROM blood_bank WHERE name = ?");
                    $stmt->bind_param("s", $selectedBankName);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (isset($_SESSION['selectedBankID'])) {
                        $selectedBankID = $_SESSION['selectedBankID'];
                        $stmtReviews = $conn->prepare("SELECT * FROM reviews WHERE bank_id = ?");
                        $stmtReviews->bind_param("i", $selectedBankID);
                        $stmtReviews->execute();
                        $reviewsResult = $stmtReviews->get_result();
                    } else {
                        $reviewsResult = false;
                    }

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $bloodTypesString = str_replace(['[', ']', '"'], '', $row['blood_types']);
                            $bloodTypesArray = explode(",", $bloodTypesString);
                            ?>
                            <div class="bank-details">
                                <header><?php echo $row['name']; ?></header>
                                <div class="details-section">
                                    <div class="detail">
                                        <div class="group">
                                            <i class="fa-solid fa-location-arrow icon"></i>
                                            <div class="text-group">
                                                <span class="location"><?php echo $row['location']; ?></span>
                                                <span class="region"><?php echo $row['region']; ?></span>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <i class="fa-solid fa-calendar-days icon"></i>
                                            <div class="text-group">
                                                <span class="date-time"><?php echo $row['date'] . ' | ' . $row['time']; ?></span>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <i class="fa-solid fa-phone icon"></i>
                                            <div class="text-group">
                                                <span class="contact"><?php echo $row['telephone_num']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blood-types">
                                        <span class="title">Blood Types Available:</span>
                                        <div class="blood-group">
                                            <?php
                                            foreach ($bloodTypesArray as $bloodType) {
                                                echo '<div class="blood">' . trim($bloodType) . '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "Error fetching blood bank details: " . $conn->error;
                    }

                    ?>
                    <div class="reviews">
                        <div class="reviews-header">
                            <header>Reviews</header>
                            <button id="rating-btn" class="primary">Write a Review</button>
                        </div>
                        <div class="reviews-section">
                            <?php
                            if ($reviewsResult && $reviewsResult->num_rows > 0) {
                                while ($reviewRow = $reviewsResult->fetch_assoc()) {
                                    ?>
                                    <div class="review">
                                        <div class="user">
                                            <div class="user-icon"><?php echo strtoupper(substr($reviewRow['user_name'], 0, 1)); ?></div>
                                            <div class="user-group">
                                                <span class="date"><?php echo $reviewRow['created_at']; ?></span>
                                                <span class="username"><?php echo $reviewRow['user_name']; ?></span>
                                            </div>
                                        </div>
                                        <div class="review-message">
                                            <div class="stars-container">
                                                <?php
                                                $rating = $reviewRow['rating'];
                                                for ($i = 0; $i < $rating; $i++) {
                                                    echo '<i class="fa-solid fa-star star"></i>';
                                                }
                                                ?>
                                                <span class="rating"><?php echo $rating; ?>.0</span>
                                            </div>
                                            <p class="description"><?php echo $reviewRow['message']; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<p class="no-reviews">No reviews made for this blood bank.</p>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } else {
                    echo '<p>Please select a blood bank to see details and reviews.</p>';
                }
            }

            if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['name'])) {
                displayBloodBankDetailsAndReviews($conn);
            }

            ?>
        </div>
    </div>
</div>