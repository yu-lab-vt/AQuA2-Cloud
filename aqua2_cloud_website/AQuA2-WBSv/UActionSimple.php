<?php
	//Mark Bright - bmark21@vt.edu
	session_start();
    switch ($_POST['UActionSimpleFunction'])
    {
        case "InstanceInfo":
            InstanceInfo();
            break;
        case "CreateInstance":
            CreateInstance();
            break;
        case "TerminateInstance":
            TerminateInstance();
            break;
		case "SetInstanceBusyStatusAsSendingCommand":
			SetInstanceBusyStatusAsSendingCommand();
			break;
		case "SetInstanceIdle":
			SetInstanceIdle();
			break;
		case "ClearInstanceException":
			ClearInstanceException();
			break;
		case "SetInstanceExceptionTransportSystem":
			SetInstanceExceptionTransportSystem();
			break;
        default:
            echo json_encode(array(false, "Internal UActionSimple function not found"));
    }
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	///SETUP FUNCTIONS
	//Sets up the SQL connection so that we may make SQL queries
	function mySQLConfigure()
	{
		try 
		{
			//SERVER
			require $_SERVER['DOCUMENT_ROOT'] . '/assets/setup/env.php';
			require $_SERVER['DOCUMENT_ROOT'] . '/assets/setup/db.inc.php';
			//Running the two php scripts above results in an accessible mySQL connection object $conn
			if (!$conn)
			{
				echo json_encode(array(false, "Failure to connect to RDBMS. Uaction mySQLConfigure."));
				die();
			}
			else
			{
				return $conn;
			}
		}
		catch (Exception $e) 
		{
            //return information using variable $e
			echo json_encode(array(false, $e->getMessage()));
			die();
		}
	}
	
	//NONTRANSPORT FUNCTION DECLARATIONS
	function InstanceInfo()
	{
		$conn = mySQLConfigure(); //Results in usable $conn DB access object
		$instance_socket = "";
		$instance_running = false;
		$instance_busy = false;
		$instance_busyProgress = "";
		$instance_busyStatus = "";
		$instance_exception = "";
		$display_string = "";
		
		//Query AQuA instances registry for existing user AQuA instance
		$sql = "SELECT * FROM aqua_instances WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - InstanceInfo"));
			exit();
		} 
		else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);
			mysqli_stmt_close($stmt);
			
			if (mysqli_num_rows($result) == 0)
			{
				mysqli_close($conn);
				echo json_encode(array(true, "InstanceInfo", array("Null", $instance_socket, $instance_running, $instance_busy, $instance_busyProgress, $instance_busyStatus, $instance_exception)));
				exit();
			}
			else
			{
				while ($row = $result->fetch_assoc()) 
				{
					$display_string = "<br />User: ".$row["username"];
					$display_string .= "<br />Launched: ".$row["launched"];
					$display_string .= "<br />Socket: ".$row["socket"];
					$_SESSION['AQuA_Instance_socket'] = $row["socket"];
                    $instance_socket = $row["socket"];
					if ($row["terminate"] == 'yes')
					{
						$display_string .= "<br />Flag - Terminate: "."True";
					}
					else
					{
						$display_string .= "<br />Flag - Terminate: "."False";
					}
					if ($row["running"] == 'yes')
					{
						$display_string .= "<br />Instance running: "."Yes";
						$instance_running = true;
					}
					else
					{
						$display_string .= "<br />Instance running: "."No";
						$instance_running = false;
					}
					if ($row["busy"] == 'yes')
					{
						$display_string .= "<br />Instance busy: "."Yes";
						$instance_busy = true;
					}
					else
					{
						$display_string .= "<br />Instance busy: "."No";
						$instance_busy = false;
					}
					$display_string .= "<br />Busy progress (%): ".$row["busyProgress"];
					$instance_busyProgress = $row["busyProgress"];
					if ($row["busyStatus"] == 'Undefined')
					{
						$display_string .= "<br />Busy message: "."Not busy";
					}
					else
					{
						$display_string .= "<br />Busy message: ".$row["busyStatus"];
					}
					$display_string .= "<br />Exceptions: ".$row["exception"]."</p>";
					$instance_exception = $row["exception"];
					$instance_busyStatus = $row["busyStatus"];
				}
			}
		}
		mysqli_close($conn);
		echo json_encode(array(true, "InstanceInfo" , array($display_string, $instance_socket, $instance_running, $instance_busy, $instance_busyProgress, $instance_busyStatus, $instance_exception)));
	}

	function CreateInstance()
	{
		$conn = mySQLConfigure();
		$sql = "SELECT * FROM aqua_instances WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - CreateInstance"));
			exit();
        } 
        else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			mysqli_stmt_close($stmt);
			
			if (mysqli_num_rows($result) == 0)
			{
			}
			else
			{
				mysqli_close($conn);
				echo json_encode(array(false, "UActionSimple - CreateInstance - User already has an instance"));
				exit();
			}
		}
		
		$socketAllocated = false;
		$AQuA_Instance_socket = "";
		$sql = "SELECT * FROM aqua_instances WHERE socket=?;";
		$stmt = mysqli_stmt_init($conn);
		while (!$socketAllocated)
		{
			try 
			{
				if (!mysqli_stmt_prepare($stmt, $sql))
				{
					mysqli_close($conn);
					echo json_encode(array(false, "UActionSimple - CreateInstance - SQL prepare fail"));
					exit();
				} 
				else 
				{
					$random = rand(3000,4000);
					$AQuA_Instance_socket = strval($random);
					mysqli_stmt_bind_param($stmt, "s", $AQuA_Instance_socket);
					mysqli_stmt_execute($stmt);

					$result = mysqli_stmt_get_result($stmt);
					mysqli_stmt_close($stmt);

					if (mysqli_num_rows($result) == 0)
					{
						$socketAllocated = true;
					}
				}
			} catch (Exception $e)
			{
				mysqli_close($conn);
				echo json_encode(array(false, "$e->getMessage()"));
				exit();
			}
		}
		
		$sql = "insert into aqua_instances(username, launched, socket, busy, busyStatus) values ( ?, NOW() ,?,?,?)";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) 
		{
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - CreateInstance - SQL prepare fail"));
			exit();
		} 
		else 
		{
			$initBusyMsg = "no";
			$initBusyStatusMsg = "Starting";
			mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['username'], $AQuA_Instance_socket, $initBusyMsg, $initBusyStatusMsg);
			mysqli_stmt_execute($stmt);
			mysqli_close($conn);			
			error_log("AQuA2-Cloud instance attempting to be created for user: " . $_SESSION['username'] . " with socket: " . $AQuA_Instance_socket);
			$username = escapeshellarg($_SESSION['username']);
			exec("sudo /opt/a2ud/aqua2_cloud_website_shellScripts/launch_matlab_instance.sh $username > /dev/null 2>&1 &");
			echo json_encode(array(true, "CreateInstance", "CreateInstance"));
			exit();
		}
	}

	function TerminateInstance()
	{
		$conn = mySQLConfigure(); 
		$sql = "DELETE FROM aqua_instances WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql))
		{
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - TerminateInstance - SQL prepare fail"));
			exit();
		} 
		else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			mysqli_stmt_close($stmt);
			echo json_encode(array(true, "TerminateInstance", "Success"));
		}
		mysqli_close($conn);
	}

	function SetInstanceBusyStatusAsSendingCommand()
	{
		$conn = mySQLConfigure(); 
		$sql = "UPDATE aqua_instances SET busyStatus='Sending Command' WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql))
		{
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - SetInstanceBusyStatusAsSendingCommand - SQL prepare fail"));
			exit();
		} 
		else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			echo json_encode(array(true, "SetInstanceBusyStatusAsSendingCommand", "Success"));
		}
		mysqli_close($conn);
	}

	function SetInstanceIdle()
	{
		$conn = mySQLConfigure(); 
		$sql = "UPDATE aqua_instances SET busyStatus='Idle' WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql))
		{
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - SetInstanceIdle - SQL prepare fail"));
			exit();
		} 
		else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			echo json_encode(array(true, "SetInstanceIdle", "Success"));
		}
		mysqli_close($conn);
	}

	function ClearInstanceException()
	{
		$conn = mySQLConfigure(); 
		$sql = "UPDATE aqua_instances SET exception='None' WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql))
		{
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - ClearInstanceException - SQL prepare fail"));
			exit();
		} 
		else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			echo json_encode(array(true, "ClearInstanceException", "Success"));
		}
		mysqli_close($conn);
	}

	function SetInstanceExceptionTransportSystem()
	{
		$conn = mySQLConfigure(); 
		$sql = "UPDATE aqua_instances SET exception='Transport system hiccup - This may be due to an unstable client connection. Try resetting and/or refreshing the page. If this does not fix the problem, you may want to submit a bug report.' WHERE username=?;";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql))
		{
			mysqli_close($conn);
			echo json_encode(array(false, "UActionSimple - SetInstanceExceptionTransportSystem - SQL prepare fail"));
			exit();
		} 
		else 
		{
			mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			echo json_encode(array(true, "SetInstanceExceptionTransportSystem", "Success"));
		}
		mysqli_close($conn);
	}