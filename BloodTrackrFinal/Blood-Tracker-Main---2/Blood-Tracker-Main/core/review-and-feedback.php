<?php
    if (isset($_SESSION['user_id'])) {
        $selectedBloodBankId = $_SESSION['selectedBankID'];

        $user_id = $_SESSION["user_id"];
        $user_name = $_SESSION["user_name"];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = $_POST['rating'];
            $message = $_POST['message'];

            $existingReviewQuery = "SELECT id FROM reviews WHERE user_id = '$user_id' AND bank_id = '$selectedBloodBankId' AND rating = '$rating' AND message = '$message'";
            $existingReviewResult = $conn->query($existingReviewQuery);
        
            if ($existingReviewResult && $existingReviewResult->num_rows > 0) {
                echo "You have already submitted this review for this blood bank.";
            } else {
                $insertQuery = "INSERT INTO reviews (user_id, user_name, bank_id, rating, message) 
                                VALUES ('$user_id', '$user_name', '$selectedBloodBankId', '$rating', '$message')";
        
                if ($conn->query($insertQuery) === TRUE) {
                    echo "Review added successfully!";
                } else {
                    echo "Error adding review: " . $conn->error;
                }
            }
            header("Refresh:0");
        }
    } else {
        $_SESSION['error_message'] = 'Please log in first before continuing';
        header('location:login.php?error=user_access_deny');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $bloodBankName = isset($_GET['name']) ? $_GET['name'] : '';
    
        $query = "SELECT id, name, location, telephone_num, region, time, blood_types, date FROM blood_bank WHERE
            (name LIKE '%$bloodBankName%') LIMIT 1";
    
        $result = $conn->query($query);
    }
    
    function getAllBloodBanks($conn)
    {
        $query = "SELECT DISTINCT id, name FROM blood_bank ORDER BY name ASC";
        $result = $conn->query($query);

        $bloodBanks = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bloodBanks[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }

        return $bloodBanks;
    }