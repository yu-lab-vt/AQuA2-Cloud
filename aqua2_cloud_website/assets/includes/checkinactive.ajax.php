<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/includes/guest_functions.php';

if (isset($_SESSION['auth']))
{
    if(time() > $_SESSION['expire'])
	{

		if (isset($_SESSION['username']) && is_guest_account($_SESSION['username'])) {
			cleanup_guest_data($_SESSION['username']);
		}

        session_unset();
        session_destroy();
        echo 'logout_redirect';
    }
}