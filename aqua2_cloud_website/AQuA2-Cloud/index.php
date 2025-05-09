<?php
define('TITLE', "AQuA2");
include '../assets/layouts/header.php';
check_logged_out();
?>


<div class="container">
    <div class="row">
        <div class="col-sm-4">

        </div>
        <div class="col-sm-4">
            <form class="form-auth" action="includes/login.inc.php" method="post">

                <?php insert_csrf_token(); ?>

                <div class="text-center">
                    <img class="mb-1" src="../assets/images/logoA.png" alt="" width="321" height="60" style="display; inline-block;">
                </div>

                <h6 class="h3 mb-3 font-weight-normal text-muted text-center"> Login</h6>

                <div class="text-center mb-3">
                    <small class="text-success font-weight-bold">
                        <?php
                            if (isset($_SESSION['STATUS']['loginstatus']))
                                echo $_SESSION['STATUS']['loginstatus'];

                        ?>
                    </small>
                </div>

                <div class="form-group">
                    <label for="username" class="sr-only">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                    <sub class="text-danger">
                        <?php
                            if (isset($_SESSION['ERRORS']['nouser']))
                                echo $_SESSION['ERRORS']['nouser'];
                        ?>
                    </sub>
                </div>

                <div class="form-group">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <sub class="text-danger">
                        <?php
                            if (isset($_SESSION['ERRORS']['wrongpassword']))
                                echo $_SESSION['ERRORS']['wrongpassword'];
                        ?>
                    </sub>
                </div>

                <button class="btn btn-lg btn-primary btn-block" type="submit" value="loginsubmit" name="loginsubmit">Login</button>

                <p class="mt-3 text-muted text-center"><a href="../reset-password/">Forgot Password</a></p>
                
            </form>
        </div>
        <div class="col-sm-4">

        </div>
    </div>
</div>


<?php

include '../assets/layouts/footer.php'

?>