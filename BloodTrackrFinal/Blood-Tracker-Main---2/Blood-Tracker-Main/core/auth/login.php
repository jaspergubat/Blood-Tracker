<?php
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql_stmt = "SELECT id, username, email, account_type FROM users WHERE email = '$email' AND password = '$password'";

        $result = mysqli_query($conn, $sql_stmt);
        error_log("SQL Query: $sql_stmt");

        if (mysqli_num_rows($result) > 0) {
            $array = mysqli_fetch_array($result);
            $_SESSION['user_name'] = $array['username'];
            $_SESSION['user_id'] = $array['id'];
            $_SESSION['user_email'] = $array['email'];
            $_SESSION['account_type'] = $array['account_type'];

            error_log("Account Type: {$_SESSION['account_type']}");
            if ($array['account_type'] == 'REGULAR') {
                header('Location: search-and-inquiry.php?success=user-logged'); // User Log In
            } elseif ($array['account_type'] == 'ADMIN') {
                $_SESSION['admin_logged_in'] = true; //Admin Log In
                header('Location: admin.php');
            }
        } elseif (empty($email) || empty($password)) {
            $error = 'Please fill all the necessary fields.';
        } else {
            $error = 'Your email or password is incorrect';
        }
    }