<?php
    if(isset($_POST['submit'])) {
        $username =  $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        $sql_stmt = "SELECT * FROM users WHERE email = '$email' && password = '$password'";
        $result = mysqli_query($conn, $sql_stmt);

        if (mysqli_num_rows($result) > 0) {
        $error = 'User already exists';
        } elseif ($password != $confirmPassword) {
        $error = 'Password does not match.';
        } elseif (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill all the necessary fields.';
        } else {
        $insert = "INSERT INTO users(username, email, password, account_type) VALUES('$username', '$email', '$password', 'REGULAR')";
        mysqli_query($conn, $insert);
        $success = 'User successfully created.';
        $_SESSION['success_message'] = $success;
        header('location:login.php?success=user_created');
        }
    }