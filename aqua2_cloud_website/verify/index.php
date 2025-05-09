<?php
define('TITLE', "Verification Hold");
include '../assets/layouts/header.php';
check_logged_in_butnot_verified(); 
?>

<main role="main" class="container">

    <div class="row">
        <div class="shadow-lg box-shadow col-sm-7 px-5 m-5 bg-light rounded align-self-center verify-message">
                <?php insert_csrf_token(); ?>
            
                <h5 class="text-center mb-5 text-primary">Account Verification Required</h5>

                <p>
                    To safeguard the storage and compute resources of the hosting server against bots and bad actors, manual approval of new accounts is currently enabled. Please email REPLACEMECONTACTNAME1 (Email: REPLACEMECONTACTEMAIL1) to have your account approved.
					
					In the meantime, you may access the welcome, contact, and user guide pages. We are excited and look forward to helping you with your data analysis!
                </p>
                <br>
                <div class="text-center mt-5">
                    <h6 class="text-success">
                        <?php
                            if (isset($_SESSION['STATUS']['verify']))
                                echo $_SESSION['STATUS']['verify'];

                        ?>
                    </h6>
                </div>
        </div>
    </div>
</main>

<?php
include '../assets/layouts/footer.php'
?>