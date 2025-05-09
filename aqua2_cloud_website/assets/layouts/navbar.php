    <?php if (!isset($_SESSION['auth'])) { ?>

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-2">

        <?php } else { ?>

            <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm p-2">


            <?php } ?>


            <div class="container">
                <a class="navbar-brand" href="../home">

                    <?php echo APP_NAME; ?>

                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

						<!--
                        <li class="nav-item">
                            <a class="nav-link" href="../welcome">Welcome</a>
                        </li>
						-->

                        <?php if (!isset($_SESSION['auth'])) { ?>

                            <li class="nav-item">
                                <a class="nav-link" href="../contact">Contact</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="../AQuA2-Cloud">Login</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="../register">Signup</a>
                            </li>

                        <?php } else { ?>

                            <li class="nav-item">
                                <a class="nav-link" href="../home">Home</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="../contact">Contact</a>
                            </li>

                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle" type="button" id="imgdropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="navbar-img" src="../assets/uploads/users/<?php echo $_SESSION['profile_image'] ?>">
                                    <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="imgdropdown">
                                    <a class="dropdown-item text-muted" href="../profile-edit"><i class="fa fa-pencil-alt pr-2"></i> Edit Profile</a>
				    <a class="dropdown-item text-muted" href="../guide"><i class="fa fa-book pr-2"></i> User Guide</a>
									<?php if ($_SESSION['auth'] == 'verified') { ?>
									<div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-muted" href="../AQuA2-WBSv"><i class="fa fa-film pr-2"></i> AQuA2-Cloud</a>
                                    <a class="dropdown-item text-muted" href="../user-data-library"><i class="fa fa-folder-open pr-2"></i> User Data Library</a>
									<div class="dropdown-divider"></div>
									<?php } ?>
                                    <a class="dropdown-item text-muted" href="../logout"><i class="fa fa-running pr-2"></i> Logout</a>
                                </div>
                            </div>

                        <?php } ?>





                    </ul>
                </div>
            </div>
            </nav>