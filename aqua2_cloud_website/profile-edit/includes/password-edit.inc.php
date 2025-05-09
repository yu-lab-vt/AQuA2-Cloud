<?php

if (isset($_POST['update-profile'])) {

    if( !empty($oldPassword) && !empty($newpassword) && !empty($passwordrepeat)){

        $sql = "SELECT password FROM users WHERE id=?;";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {

            $_SESSION['ERRORS']['sqlerror'] = 'SQL ERROR';
            header("Location: ../");
            exit();
        }
        else {

            mysqli_stmt_bind_param($stmt, "s", $_SESSION['id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){

                $pwdCheck = password_verify($oldPassword, $row['password']);

                if ($pwdCheck == false){

                    $_SESSION['ERRORS']['passworderror'] = 'Incorrect current password...';
                    header("Location: ../");
                    exit();
                }
                if ($oldPassword == $newpassword){

                    $_SESSION['ERRORS']['passworderror'] = 'New password cannot be same as old password...';
                    header("Location: ../");
                    exit();
                }
                if ($newpassword !== $passwordrepeat){

                    $_SESSION['ERRORS']['passworderror'] = 'Confirmed password does not match new password...';
                    header("Location: ../");
                    exit();
                }

                $passwordUpdated = true;
            }
        }
    }
    else{

        $_SESSION['ERRORS']['passworderror'] = 'Password fields cannot be empty...';
        header("Location: ../");
        exit();
    }  
} 
else {

    header("Location: ../");
    exit();
}

