<?php

session_start();

require '../../assets/includes/auth_functions.php';
require '../../assets/includes/datacheck.php';
require '../../assets/includes/security_functions.php';

check_logged_out();

if (!isset($_POST['loginsubmit'])){

    header("Location: ../");
    exit();
}
else {

    /*
    * -------------------------------------------------------------------------------
    *   Securing against Header Injection
    * -------------------------------------------------------------------------------
    */

    foreach($_POST as $key => $value){

        $_POST[$key] = _cleaninjections(trim($value));
    }


    /*
    * -------------------------------------------------------------------------------
    *   Verifying CSRF token
    * -------------------------------------------------------------------------------
    */

    if (!verify_csrf_token()){

        $_SESSION['STATUS']['loginstatus'] = 'Request could not be validated...';
        header("Location: ../");
        exit();
    }

    
    require '../../assets/setup/db.inc.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {

        $_SESSION['STATUS']['loginstatus'] = 'Fields cannot be empty...';
        header("Location: ../");
        exit();
    } 
    else {

        /*
        * -------------------------------------------------------------------------------
        *   Updating last_login_at
        * -------------------------------------------------------------------------------
        */

        $sql = "UPDATE users SET last_login_at=NOW() WHERE username=?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {

            $_SESSION['ERRORS']['sqlerror'] = 'SQL ERROR';
            header("Location: ../");
            exit();
        }
        else {

            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
        }

        /*
        * -------------------------------------------------------------------------------
        *   Creating SESSION Variables
        * -------------------------------------------------------------------------------
        */

        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {

            $_SESSION['ERRORS']['scripterror'] = 'SQL ERROR';
            header("Location: ../");
            exit();
        } 
        else {

            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {

                $pwdCheck = password_verify($password, $row['password']);

                if ($pwdCheck == false) {

                    $_SESSION['ERRORS']['wrongpassword'] = 'Invalid login...';
                    header("Location: ../");
                    exit();
                } 
                else if ($pwdCheck == true) {

                    session_start();

                    
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
                    $_SESSION['verified_at'] = $row['verified_at'];
                    $_SESSION['created_at'] = $row['created_at'];
                    $_SESSION['updated_at'] = $row['updated_at'];
                    $_SESSION['deleted_at'] = $row['deleted_at'];
                    $_SESSION['last_login_at'] = $row['last_login_at'];
					$_SESSION['user_data_directory'] = $row['user_data_directory'];

                    header("Location: ../../home/");
                    exit();
                } 
            } 
            else {

                $_SESSION['ERRORS']['nouser'] = 'Invalid login...';
                header("Location: ../");
                exit();
            }
        }
    }
}