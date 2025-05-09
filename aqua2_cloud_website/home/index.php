<?php

define('TITLE', "Home");
include '../assets/layouts/header.php';
check_verified();

// Verify and create user directory if it doesn't exist
if (isset($_SESSION['user_data_directory'])) {
    $userDir = $_SESSION['user_data_directory'];
    
    // Verify the directory exists, create if it doesn't
    if (!is_dir($userDir)) {
        error_log("Creating missing user directory for verified user: " . $_SESSION['username']);

        $old_umask = umask(0007);
        if (!mkdir($userDir, 02770, false)) {
            error_log("Failed to create user directory: " . $userDir);
        } else {
            error_log("Successfully created user directory: " . $userDir);

            $sourceFile = '/tmp/sampleData/sampleData.tif';
            $destFile = $userDir . '/sampleData.tif';

            if (copy($sourceFile, $destFile)) {
                chmod($destFile, 0660);               // rw-rw----
                chown($destFile, 'www-data');         // or $_SESSION['username'] if applicable
                chgrp($destFile, 'a2cloud');          // set group to a2cloud
                error_log("Copied and set permissions for sample data: " . $destFile);
            } else {
                error_log("Failed to copy sample data to: " . $destFile);
            }
            
            // Create sample empty folder
            $emptyFolder = $userDir . '/sampleEmptyFolder';
            if (mkdir($emptyFolder, 02770)) {
                chown($emptyFolder, 'www-data');    // Or session user if applicable
                chgrp($emptyFolder, 'a2cloud');
                error_log("Successfully created empty folder: " . $emptyFolder);
            } else {
                error_log("Failed to create empty folder: " . $emptyFolder);
            }
        }
        umask($old_umask);
    }
}

?>

<main role="main" class="container">

    <div class="row">
        <div class="col-sm-3">

            <?php include('../assets/layouts/profile-card.php'); ?>

        </div>
        <div class="col-sm-9">

            <div class="d-flex align-items-center p-3 mt-5 mb-3 text-white-50 bg-purple rounded box-shadow">
                <img class="mr-3" src="" alt="" width="0" height="48">
                <div class="lh-100">
                    <h6 class="mb-0 text-white lh-100">Greetings <?php echo $_SESSION['username']; ?></h6>
                    <small>Last logged in: <?php echo date("m-d-Y", strtotime($_SESSION['last_login_at'])); ?></small>
				</div>
            </div>

            <div class="my-3 p-3 bg-white rounded box-shadow">
                <h6 class="mb-0">
                </h6>
                
		<sub class="text pb-2 mb-0">Note: If you are using a guest account, any data uploaded is temporary and is deleted upon logging out.<br></sub>
		<sub class="text-muted border-bottom border-gray pb-2 mb-0"><br></sub>
		<sub class="text-muted border-bottom border-gray pb-2 mb-0">User FTP Information</sub>
                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <font color="black">Use FTP Protocol. Use port REPLACEMEWITHFTPPORT
.<br>
						<font color="black">Encryption: TLS/SSL Explicit<br>
						<font color="black">Address: REPLACEMEWITHSERVERIPDOMAIN<br>
						<font color="black">Username: [Your username]<br>
						<font color="black">Password: [Your login password]<br>
                    </p>
                </div>
                <sub class="text-muted border-bottom border-gray pb-2 mb-0">Modules</sub>

                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">[AQuA2-Cloud | Activity Quantification and Analysis - Cloud Version]</strong>
			This application allows for intensity-based activity detection within a broad range of fluorescent imaging movies<br>
			Supported browsers: Edge & Chrome<br>
						<font color="green">
						Version: 1.0.0
 
                    </p>
                </div>
                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">[---]</strong>
                        ---<br>
                        ---
                    </p>
                </div>
                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">[---]</strong>
                        ---<br>
                        ---
                    </p>
                </div>
            </div>

        </div>
    </div>
</main>




    <?php

    include '../assets/layouts/footer.php'

    ?>