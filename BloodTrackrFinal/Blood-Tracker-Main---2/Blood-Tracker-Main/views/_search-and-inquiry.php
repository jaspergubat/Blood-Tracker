<script>
    var key = '<?php echo $sessionValue; ?>';
    console.log(key);
</script>
<div class="orientation-message">
    Please change the orientation of your screen to landscape for maximum functionality.
</div>

<!-- Search and Inquiry -->
<div class="search-container" id="KULPIKULPI">
    <!-- Filters -->
    <section class="filters-container">
        <div class="filters-header">
            <i class="fa-solid fa-filter"></i>
            <header>Filters</header>
        </div>
        <form class="filters-group" method="GET">
            <div class="filters">
                <div class="filter">
                    <label for="blood_type">Blood Type</label>
                    <select id="blood_type" class="form-control" name="blood_type">
                        <option value="">Select Blood Type</option>
                        <option value="A" <?php if(isset($_GET['blood_type']) && $_GET['blood_type'] == 'A') echo 'selected'; ?>>A</option>
                        <option value="B" <?php if(isset($_GET['blood_type']) && $_GET['blood_type'] == 'B') echo 'selected'; ?>>B</option>
                        <option value="O" <?php if(isset($_GET['blood_type']) && $_GET['blood_type'] == 'O') echo 'selected'; ?>>O</option>
                        <option value="AB" <?php if(isset($_GET['blood_type']) && $_GET['blood_type'] == 'AB') echo 'selected'; ?>>AB</option>
                    </select>
                </div>
                <div class="filter">
                    <label for="region">Region</label>
                    <select id="region" class="form-control" name="region">
                        <option value="">Select Region</option>
                        <option value="Northern And Central Luzon" <?php if(isset($_GET['region']) && $_GET['region'] == 'Northern And Central Luzon') echo 'selected'; ?>>Northern And Central Luzon</option>
                        <option value="National Capital Region" <?php if(isset($_GET['region']) && $_GET['region'] == 'National Capital Region') echo 'selected'; ?>>National Capital Region</option>
                        <option value="Visayas" <?php if(isset($_GET['region']) && $_GET['region'] == 'Visayas') echo 'selected'; ?>>Visayas</option>
                        <option value="Mindanao" <?php if(isset($_GET['region']) && $_GET['region'] == 'Mindanao') echo 'selected'; ?>>Mindanao</option>
                    </select>
                </div>
                <div class="filter">
                    <label for="time">Time</label>
                    <select id="time" class="form-control" name="time">
                    <option value="">Select Time</option>
                    <option value="8:00 AM to 3:00 PM" <?php if(isset($_GET['time']) && $_GET['time'] == '8:00 AM to 3:00 PM') echo 'selected'; ?>>8:00 AM to 3:00 PM</option>
                    <option value="7:30 AM to 5:00 PM" <?php if(isset($_GET['time']) && $_GET['time'] == '7:30 AM to 5:00 PM') echo 'selected'; ?>>7:30 AM to 5:00 PM</option>
                    <option value="8:30 AM to 4:30 PM" <?php if(isset($_GET['time']) && $_GET['time'] == '8:30 AM to 4:30 PM') echo 'selected'; ?>>8:30 AM to 4:30 PM</option>
                    <option value="9:00 AM to 4:00 PM" <?php if(isset($_GET['time']) && $_GET['time'] == '9:00 AM to 4:00 PM') echo 'selected'; ?>>9:00 AM to 4:00 PM</option>
                    <option value="8:00 AM to 5:00 PM" <?php if(isset($_GET['time']) && $_GET['time'] == '8:00 AM to 5:00 PM') echo 'selected'; ?>>8:00 AM to 5:00 PM</option>
                    <option value="9:30 AM to 4:30 PM" <?php if(isset($_GET['time']) && $_GET['time'] == '9:30 AM to 4:30 PM') echo 'selected'; ?>>9:30 AM to 4:30 PM</option>
                </select>
                </div>
            </div>
            <div class="actions" style="background-color: white;">
                <button type="submit" class="secondary">Apply Filters</button>
            </div>
        </form>
    </section>
    <!-- Search Content -->
    <section class="search">
        <div class="search-header">
            <header>Search and Inquiry</header>
        </div>
            <div class="search-wrapper">
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $bloodTypesString = str_replace(['[', ']', '"'], '', $row['blood_types']);
                        $bloodTypesArray = explode(",", $bloodTypesString);
                ?>
                        <div class="search-content" onclick="redirectToBankMap('<?php echo $row['name']; ?>')">
                            <span class="bank-name"><?php echo $row['name']; ?></span>
                            <div class="address">
                                <span class="location"><?php echo $row['location']; ?></span>
                                <span class="region"><?php echo $row['region']; ?></span>
                            </div>
                            <div class="extra-details">
                                <span class="date-and-time"><?php echo $row['date']; ?> | <?php echo $row['time']; ?></span>
                                <span class="contact">Contact: <?php echo $row['telephone_num']; ?></span>
                            </div>
                            <div class="blood-types">
                                <span class="blood-header">Blood Types Available:</span>
                                <div class="blood-group">
                                    <?php
                                    foreach ($bloodTypesArray as $bloodType) {
                                        echo '<div class="blood">' . trim($bloodType) . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                ?>
            </div>
    </section>
</div>