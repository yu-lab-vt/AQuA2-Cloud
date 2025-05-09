<?php

function check_logged_in() {

    if (isset($_SESSION['auth'])){

        return true;
    }
    else {

        header("Location: ../AQuA2-Cloud/");
        exit();
    }
}

function check_logged_in_butnot_verified(){

    if (isset($_SESSION['auth'])){

        if ($_SESSION['auth'] == 'loggedin') {
    
            return true;
        }
        elseif ($_SESSION['auth'] == 'verified') {

            header("Location: ../home/");
            exit(); 
        }
    }
    else {

        header("Location: ../AQuA2-Cloud/");
        exit();
    }
}

function check_logged_out() {

    if (!isset($_SESSION['auth'])){

        return true;
    }
    else {

        header("Location: ../AQuA2-Cloud/");
        exit();
    }
}

function check_verified() {

    if (isset($_SESSION['auth'])) {

        if ($_SESSION['auth'] == 'verified') {

            return true;
        }
        elseif ($_SESSION['auth'] == 'loggedin') {

            header("Location: ../verify/");
            exit(); 
        }
    }
    else {

        header("Location: ../AQuA2-Cloud/");
        exit();
    }
}

function force_login($email) {

    require '../assets/setup/db.inc.php';
    
    $sql = "SELECT * FROM users WHERE email=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        
        return false;
    } 
    else {
        
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (!$row = mysqli_fetch_assoc($result)) {
            
            return false;
        }
        else {

            if($row['verified_at'] != NULL){

                $_SESSION['auth'] = 'verified';
            } else{

                $_SESSION['auth'] = 'loggedin';
            }

            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['gender'] = $row['gender'];
            $_SESSION['headline'] = $row['headline'];
            $_SESSION['bio'] = $row['bio'];
            $_SESSION['profile_image'] = $row['profile_image'];
            $_SESSION['banner_image'] = $row['banner_image'];
            $_SESSION['user_level'] = $row['user_level'];
            $_SESSION['verified_at'] = $row['verified_at'];
            $_SESSION['created_at'] = $row['created_at'];
            $_SESSION['updated_at'] = $row['updated_at'];
            $_SESSION['deleted_at'] = $row['deleted_at'];
            $_SESSION['last_login_at'] = $row['last_login_at'];
            
            return true;
        }
    }
}