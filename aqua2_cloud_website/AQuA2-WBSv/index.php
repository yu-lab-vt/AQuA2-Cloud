<?php
//Mark Bright - bmark21@vt.edu
session_start();

require '../assets/setup/env.php';
require '../assets/setup/db.inc.php';
require '../assets/includes/auth_functions.php';
require '../assets/includes/security_functions.php';
require './A2CUtilities.php';

generate_csrf_token();
check_verified();

define('FM_SELF_URL', $_SERVER['PHP_SELF']);

// Root path for file manager
// We use $_SESSION['user_data_directory'] to retrieve the user's directory folder
$root_path = $_SESSION['user_data_directory'];
$http_host = $_SERVER['HTTP_HOST'];
$iconv_input_encoding = 'UTF-8';
$datetime_format = 'd.m.y H:i';

// private key and session name to store to the session
if ( !defined( 'FM_SESSION_ID')) {
    define('FM_SESSION_ID', 'filemanager');
}

// Show directory size: true or speedup output: false
$calc_folder = isset($cfg->data['calc_folder']) ? $cfg->data['calc_folder'] : true;
$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
    || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';

$wd = fm_clean_path(dirname($_SERVER['PHP_SELF']));

// clean and check $root_path
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>".'Root path'." \"{$root_path}\" ".'not found!'." </h1>";
    exit;
}

defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);

// always use ?p=
if (!isset($_GET['p']) && empty($_FILES)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// clean path
$p = fm_clean_path($p);
$_SESSION['fm_path'] = $p;
//$_SESSION['user_current_FM_path'] = $root_path;

// instead globals vars
define('FM_PATH', $p);
defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);

unset($p, $iconv_input_encoding);

/*************************** /ACTIONS ***************************/

// Process referenced file/folder
if (isset($_POST['group'], $_POST['linkreferenced'])) 
{
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                $new_path = $path . '/' . $f;
            }
        }
        if ($errors == 0) {
            fm_set_msg('Routine completed');
        } else {
            fm_set_msg('Error while deleting items', 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }

    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// get current path
$path = FM_ROOT_PATH;
if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}

$_SESSION['user_current_FM_path'] = $path;

// get parent folder
$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
$current_path = array_slice(explode("/",$path), -1)[0];
if (is_array($objects)) 
{
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (@is_file($new_path)) {
            $files[] = $file;
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..') {
            $folders[] = $file;
        }
    }
}

if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}

fm_show_header(); // HEADER

fm_show_nav_path(FM_PATH); // current path
$num_files = count($files);
$num_folders = count($folders);
$all_files_size = 0;
?>

<?php 

include 'file_table_section.php';
include 'version_and_downsampleSections.php';
include 'main_interface.php';
fm_show_footer();
echo '<script type="text/javascript">','ClientStartup()','</script>';

/**
 * Show Header after login
 */
function fm_show_header()
{
	$sprites_ver = '20160315';
	header("Content-Type: text/html; charset=utf-8");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Pragma: no-cache");

	?>
	<!DOCTYPE html>
	<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="AQuA2">
    <meta name="author" content="CBIL">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <title> AQuA2-Cloud </title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap-4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendor/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="interface_style_01.css">
	</head>
	<body class="navbar-normal">
	<div id="wrapper" class="container-fluid">
    <?php include './Modals/modals.php'; ?>
	<!--///////////////////////////////////////////////////////////////////////////////////////-->
<?php
}

/**
 * Show page footer
 */
function fm_show_footer()
{
    ?>
	</div>
	<script src="../assets/vendor/jquery-3.5.1/jquery-3.5.1.min.js"></script>
	<script src="../assets/vendor/bootstrap-4.5.2/js/bootstrap.min.js"></script>
	<script src="../assets/vendor/DataTables-1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="../assets/vendor/DataTables-1.12.1/sorting/absolute.js"></script>
	<script src="../assets/vendor/ekko-lightbox-5.3.0/ekko-lightbox.min.js"></script>
	<script src="../assets/vendor/jquery-ui-1.13.2/jquery-ui.min.js"></script>


	<script type="text/javascript" src="jPolygonCBIL.js"></script>
	<script type="text/javascript" src="cursorClickPolygonDrawModeDisableAllElseHelper.js"></script>
	<script src="./Modals/showModals.js"></script>
	<script src="./Functions/FrontEndUIUpdate.js"></script>
	<script src="./Functions/MiscSetA.js"></script>
	<script src="./Functions/UIRActions.js"></script>
	<script src="./Functions/BackEndUISync.js"></script>

	<script>
		//GLOBAL JAVASCRIPT VARIABLE DECLARATION
		var d = Date();
		var ProjectCurrentTab = 0;
		var DetectPipeBackButtonVisible = true;
		var DetectPipeBackButtonDisabled = true;
		var DetectPipeRunButtonVisible = true;
		var DetectPipeRunButtonDisabled = true;
		var DetectPipeNextButtonVisible = true;
		var DetectPipeNextButtonDisabled = true;
		var custom_isNumeric = /^[-+]?(\d+|\d+\.\d*|\d*\.\d+)$/;
		var pageWideTextConsoleLatest = "";
		var movieTotalFrameCount = 0;
		var movieCurrentFrame = 0;
		
		//Refactor
		var instanceRunning = false;
		var instanceBusy = false;
		var instanceBusyStatus = "";
		var instanceException = "";
		
		var instanceLinkedRequest = false;
		var instanceLinked = false;
		var instanceLinkInfo = "";
		var sbsLockedIn = false;
		
		var viewportSingleIm1Data = "";
		var viewportDualIm1Data = "";
		var viewportDualIm2Data = "";
		
		var viewportCurrentTab = 1;
		var canvasDrawMode = false;
		var cd_canvas = undefined;
		var cd_perimeter = new Array();
		var cd_perimeter_scaled = new Array();
		var ctx = undefined;
		var cd_complete = false;
		
		//For rendering viewport single and viewport left image and tracking the drawable area for polygon draw tool
		var centerShift_x = 0;
		var centerShift_y = 0;
		var WidthValid = 0;
		var HeightValid = 0;
		
		//Enable switching of tabs
		var ViewportTabSwitchingAllowed = false;
		var DetectionTabSwitchingAllowed = false;
		
		//Movie info
		var currentFrame = 0;
		var maximumFrame = 0;
		
		//Polygon draw mode type (cell or landmark)
		var cursorCurrentMode = "";
		var DVPImageHeight;
		var DVPImageWidth;
		//==== BEGIN STANDARDIZED FUNCTIONS ====//
		
		var user_defined_name = "";
		
		var curvePlotBEWidth = 0;
		var curvePlotBEHeight = 0;
		
		var tableCheckboxElapsed = 0;
		var instanceExistedFlag = false;
		var tableCheckboxChanged = false;

		var FirstInformationQueryDone = false;
		var instance_socket = "";
		var instance_running = false;
		var instance_running_last_value = instance_running;
		var instance_busy = false;
		var instance_busy_status = "";
		var instance_busy_status_last_value = instance_busy_status;
		var instance_exception = "";

		var waitingOnServer = true;
		var waitingOnServerLastValue = waitingOnServer;
		var settingInstanceIdle = false;
		var settedInstanceIdleFlag = false;
		var refreshingAfterSettingInstanceIdle = false;
		var project_state = 0;

		//Toast message display time tracker
		var toastElapsed = 0;

		//File manager variables
		var referencedFileName = "";
		var referencedFileDirectory = "";
		var referencedFileFull = "";

		//Session console
		var sessionConsoleText = "";
		var instanceBusyTextConsoleLatest = "";
		var instanceExceptionTextConsoleLatest = "";

		//UI related variables
		var DetectPipelineCurrentTab = 0;
		var DetectPipelineMaximumTab = 1;
		var windowResizeElapsed = 0;
		var windowResizedFlag = false;
		var AQuA_SideBySideMode = false;

		//Passthroughs for dual viewport processing after load wait
		var DVP_onLoadLeftThis = null;
		var DVP_onLoadRightThis = null;
		var dualImagesLoaded = 0;

		//Auto error recovery
		var autoErrorRecovery = false;
		var autoErrorRecoveryTimer = 0;
		var autoErrorRecoveryAttempted = false;
		var autoErrorRecoveryFailed = false;


		function ClientStartup()
		{
			$(".overlay").show();
			const ClientLoopInterval = setInterval(ClientLoop, 500);
			document.getElementById("instanceInfoText").style.backgroundColor = "#feae00";
			document.getElementById("instanceInfoText").style.color = "#000000";
			
			fvTable = document.getElementById("favouriteTable-table");
			fvTable.addEventListener('click', function(event) 
			{
				if (event.target.tagName === 'TD' && event.target.cellIndex !== 0) 
				{
					var row = event.target.parentElement;
					var cellIndex = event.target.cellIndex;
					var cellData = row.cells[cellIndex].textContent;

					if (row.rowIndex != 0)
					{
						UActionPayloadSC("selecEvt",["selecEvt",10000000,row.rowIndex],"fvTableRowClickedEventListener");
					}
				}
			});
			viewportSingleIm1DataSrc = 'images/gridInvert.png';
			CanvasSingleViewportController(viewportSingleIm1DataSrc);
			curvePortImData = 'images/gridInvert.png';
			window.addEventListener("resize", function ()
			{
				if (!windowResizedFlag) {windowResizedFlag = true;}
				windowResizeElapsed = 0;
			})
		}
		
		function ClientLoop()
		{
		UActionSimple("InstanceInfo", false); //Query twice per second
		ClientLoopMiscFunctions();
		if (FirstInformationQueryDone == true) //If we queried once, we can allow the client logic to run
		{
			//console.log(instance_busy_status);
			//console.log(instance_exception);
			//console.log(project_state);
			if (instance_socket == ""){
				showNoInstanceModal(); 
				SetInstanceInfoIndicator("No Instance Found", "#feae00", "#000000");
				return;}
			if (instance_exception != "None"){
				showinstanceExceptionModal(); 
				SetInstanceInfoIndicator("Instance Exception", "#feae00", "#000000");
				//If we clear an exception:
				//Set busy_status to "Idle"
				return;
			}
			if (!instance_running){
				showBusyInstanceModal(); 
				SetInstanceInfoIndicator("Instance Starting", "#feae00", "#000000");
				return;}
			if ((instance_busy_status != "Idle") && (instance_busy_status != "Done"))
			{
				//console.log("Instance busy");
				showBusyInstanceModal(); 
				SetInstanceInfoIndicator("Instance Busy", "#feae00", "#000000");
				return;
			}
			if (waitingOnServer){
				//console.log("Waiting on server");
				waitingOnServerLastValue = true;
				if (instance_running && !instance_running_last_value) 
				{
					//console.log("Instance running differential detected");
					instance_running_last_value = instance_running;
					SetInstanceInfoIndicator("Getting Instance Information", "#feae00", "#000000");
					UActionPayload("projStat","","ClientLoopProjectState0Detect");
					return;
				}
				if ((instance_busy_status == "Idle") || (instance_busy_status == "Done"))
				{
					//console.log("instance_busy_status == done");
					if (((!settingInstanceIdle) && (!settedInstanceIdleFlag)) && (instance_busy_status != "Idle"))
					{
						//console.log("settingInstanceIdle == true");
						SetInstanceInfoIndicator("Instance returning to idle state", "#feae00", "#000000");
						settingInstanceIdle = true;
						UActionSimple("SetInstanceIdle", false);
						return;
					}
					if ((settingInstanceIdle) && (!settedInstanceIdleFlag))
					{
						//console.log("Holding");
						SetInstanceInfoIndicator("Instance waiting for idle state return", "#feae00", "#000000");
						return;
					}
					if (((settingInstanceIdle) && (settedInstanceIdleFlag)) && (!refreshingAfterSettingInstanceIdle))
					{
						settingInstanceIdle = false;
						settedInstanceIdleFlag = false;
						refreshingAfterSettingInstanceIdle = true;
						//console.log("Instance busy status reported as done and just finished setting instance busy status to idle");
						SetInstanceInfoIndicator("Getting Instance Information", "#feae00", "#000000");
						UActionPayload("FEUIRfsh","","ClientLoopProjectState0Detect");
						return;
					}
				}
				else
				{
					showBusyInstanceModal();
					SetInstanceInfoIndicator("Waiting on Server", "#feae00", "#000000");
					return;
				}
			}
			if (!waitingOnServer && waitingOnServerLastValue){waitingOnServerLastValue = waitingOnServer; hideAllModals();}
			SetInstanceInfoIndicator("Instance ready", "#00ff00", "#000000");
			return;
		}
			SetInstanceInfoIndicator("Getting Instance Information", "#feae00", "#000000");
		}

		function ClientLoopMiscFunctions()
		{
			if (toastElapsed > 0){toastElapsed = toastElapsed - 500;}
			else
			{
				if (document.getElementById("snackbar").className == "show")
				{
					document.getElementById("snackbar").className = document.getElementById("snackbar").className.replace("show", "");
				}
			}

			if (autoErrorRecovery)
			{
				autoErrorRecoveryTimer = autoErrorRecoveryTimer + 500;
				if (autoErrorRecoveryTimer >= 2000)
				{
					autoErrorRecoveryAttempted = true;
					autoErrorRecovery = false;
					autoErrorRecoveryTimer = 0;
					UActionPayload("FEUIRfsh","","ClientLoopProjectState0Detect");
				}
			}

			//var instance_busy = false;
			//var instance_busy_status = "";
			//var instance_exception = "";
			if (instance_busy_status != "") //Don't update to console if no instance (instance_busy_status == "")
			{
				if (instance_busy_status != instanceBusyTextConsoleLatest)
				{
					instanceBusyTextConsoleLatest = instance_busy_status;
					sessionConsoleText += instanceBusyTextConsoleLatest + "\n";
					document.getElementById("busyModalConsole").innerHTML = sessionConsoleText;
					document.getElementById("exceptionModalConsole").innerHTML = sessionConsoleText;
				}
				if ((instance_exception != "None") && (instance_exception != instanceExceptionTextConsoleLatest))
				{
					instanceExceptionTextConsoleLatest = instance_exception;
					sessionConsoleText += instanceExceptionTextConsoleLatest + "\n";
					document.getElementById("busyModalConsole").innerHTML = sessionConsoleText;
					document.getElementById("exceptionModalConsole").innerHTML = sessionConsoleText;
				}
				if ((instance_exception == "None") && (instance_exception != instanceExceptionTextConsoleLatest))
				{
					instanceExceptionTextConsoleLatest = instance_exception;
					sessionConsoleText += "No exceptions or exception cleared" + "\n";
					document.getElementById("busyModalConsole").innerHTML = sessionConsoleText;
					document.getElementById("exceptionModalConsole").innerHTML = sessionConsoleText;
				}
				document.getElementById("busyModalConsole").scrollTop = document.getElementById("busyModalConsole").scrollHeight;
				document.getElementById("exceptionModalConsole").scrollTop = document.getElementById("exceptionModalConsole").scrollHeight;
			}

			windowResizeElapsed = windowResizeElapsed + 500;
			if (windowResizeElapsed >= 1000)
			{
				if (windowResizedFlag)
				{
					windowResizedFlag = false;
					if (AQuA_SideBySideMode)
					{
						CanvasDualViewportController(DVP_onLoadLeftThis,DVP_onLoadRightThis);
					}
					else
					{
						CanvasSingleViewportController(viewportSingleIm1DataSrc);
					}
					CurveViewportController(curvePortImData, 740, 740);
				}
				windowResizeElapsed = 0;
			}
			if(dualImagesLoaded >= 2)
			{
				dualImagesLoaded = 0;
				CanvasDualViewportController(DVP_onLoadLeftThis, DVP_onLoadRightThis);
			}

			tableCheckboxElapsed = tableCheckboxElapsed + 500;
			if (tableCheckboxElapsed >= 3000)
			{
				if (tableCheckboxChanged)
				{
					tableCheckboxChanged = false;
					UIR_tableModified();
				}
				tableCheckboxElapsed = 0;
			}
		}

		function UActionSimple(UActionSimpleFunction, waitingOnServerSetFlag)
		{
			//console.log("UActionSimple called" + UActionSimpleFunction);
			if (waitingOnServerSetFlag)
			{
				waitingOnServer = true;
			}
			$.ajax(
			{
				type:'POST',
				url:'UActionSimple.php',
				data: 
				{
					UActionSimpleFunction: UActionSimpleFunction
				},
				cache : false,
				dataType: 'json',
				success:function(responseObject)
				{
				   UActionSimpleResponse(responseObject);
				},
				error:function (jqXHR, textStatus) {
					//Display full error information in console.log
					console.log("Error: " + textStatus);
					console.log("jqXHR: " + jqXHR);
				}
			});
		}

		function UActionSimpleResponse(responseObject)
		{
			var success = responseObject[0];
			if (success)
			{
				var functionName = responseObject[1];
				var functionData = responseObject[2];
				switch (functionName)
				{
					case "InstanceInfo":
						display_string = functionData[0];
						instance_socket = functionData[1];
						instance_running = functionData[2];
						instance_busy = functionData[3];
						instance_busy_status = functionData[5];
						instance_exception = functionData[6];
						FirstInformationQueryDone = true;
					break;
					case "TerminateInstance":
						redirectToHome();
					case "SetInstanceIdle":
						settedInstanceIdleFlag = true;
					break;
					default:
					break;
				}
			}
			else
			{
				//console.log(responseObject[1]);
				//UActionErrorSimple(responseObject[1]);
			}
		}

		function UActionPayload(UActionFunction, data, callingFunction)
		{
			//console.log("UActionPayload called");
			//console.log("UActionFunction: " + UActionFunction);
			//console.log("data: " + data);
			//console.log("callingFunction: " + callingFunction);
			waitingOnServer = true;
			$.ajax(
			{
				type:'POST',
				url:'UActionPayload.php',
				data: 
				{
					UActionFunction: UActionFunction,
					data: data,
					callingFunction : callingFunction
				},
				cache : false,
				dataType: 'json',
				success:function(responseObject)
				{
					UActionPayloadResponse(responseObject);
					//console.log("UActionPayload response success");
					//console.log("UActionPayload responseObject: " + responseObject);

					if (autoErrorRecoveryAttempted)
					{
						autoErrorRecoveryAttempted = false;
						console.log("Auto error recovery succeeded in recovering the frontend UI");
						toast("Auto error recovery succeeded in countering connection instability");
					}
				},
				error:function (jqXHR, textStatus) {
					//Display full error information in console.log
					//console.log("UActionPayload error");
					//console.log("Error: " + textStatus);
					//console.log("jqXHR: " + jqXHR);
					if (((!autoErrorRecovery) && (!autoErrorRecoveryFailed)) && (!autoErrorRecoveryAttempted))
					{
						//console.log("Auto error recovery initiated");
						autoErrorRecovery = true;
						return;
					}
					if ((autoErrorRecoveryAttempted) && (!autoErrorRecoveryFailed))
					{
						autoErrorRecoveryFailed = true;
						console.log("Auto error recovery failed to recover the frontend UI");
						UActionSimple("SetInstanceExceptionTransportSystem", false);
					}
				}
			});
		}

		function UActionGetUserPath(UActionFunction, data, callingFunction)
		{
			//console.log("UActionGetUserPath called");
			$.ajax(
				{
				type: 'POST',
				url: 'UActionGetUserPath.php',
				dataType: 'json',
				success: function(response) 
				{
					//console.log("UActionGetUserPath response success");
					//console.log("UActionGetUserPath response: " + response);
					if (response.success) 
					{
						var userPath = response.path;
						switch (callingFunction)
						{
							case "UIR_DetectPipeSaveButton":
								toast("Attempting to save options csv...");
								var toJoin = [userPath,"/",data,".csv"];
								path = toJoin.join("");
								UActionPayloadSC("saveOpts",["location",10000000,path],"getCurrentUserPathSwitch");
								break;
							case "UIR_ExportTabExportButton":
								toast("Attempting to save experiment file (.mat)...");
								UActionPayloadSC("ExportPr",["filexxxx",10000000,data,"pathxxxx",10000000,userPath],"getCurrentUserPathSwitch");
								break;
							case "UIR_DRLCellSave":
								toast("Attempting to save cell boundary data (.mat)...");
								UActionPayloadSC("cellsave",["filexxxx",10000000,data,"pathxxxx",10000000,userPath],"getCurrentUserPathSwitch");
								break;
							case "UIR_DRLLdMarkSave":
								toast("Attempting to save landmark boundary data (.mat)...");
								UActionPayloadSC("ldmksave",["filexxxx",10000000,data,"pathxxxx",10000000,userPath],"getCurrentUserPathSwitch");
								break;
							case "UIR_FavouritesTabSaveWavesButton":
								toast("Attempting to save curves data...");
								UActionPayloadSC("savewave",["pathxxxx",10000000,userPath],"getCurrentUserPathSwitch");
								break;
							default:
								console.log("UActionResponse getCurrentUserPath Switch Default");
							break;
						}
					}
					else 
					{
						console.error("Failed to get user path:", response.error);
					}
				},
				error: function(jqXHR, textStatus) {
					console.error("Error getting user path:", textStatus, jqXHR);
				}
			});
		}

		function UActionPayloadSC(UActionFunction, data, callingFunction) //WE NEED TO PREVENT SENDING COMMAND SET TWICE. IM THINKING SOME ARG?
		{
			//console.log("UActionPayloadSC called");
			//console.log("UActionFunction: " + UActionFunction);
			//console.log("data: " + data);
			//console.log("callingFunction: " + callingFunction);
			$(".overlay").show(); // Optional, for user feedback during the transition
			$.ajax(
			{
				type: 'POST',
				url: 'UActionSimple.php',
				data: {
					UActionSimpleFunction: "SetInstanceBusyStatusAsSendingCommand"
				},
				cache: false,
				dataType: 'json',
				success: function(responseObject)
				{
					if (responseObject[0] === true) // Successful update
					{
						//console.log("SetInstanceBusyStatusAsSendingCommand success");
						//console.log("UActionFunction: " + UActionFunction);
						//console.log("data: " + data);
						//console.log("callingFunction: " + callingFunction);
						UActionPayload(UActionFunction, data, callingFunction); // Proceed with the real payload
					}
					else
					{
						console.error("Failed to set busy status before command:", responseObject[1]);
					}
				},
				error: function(jqXHR, textStatus)
				{
					console.error("UActionPayloadSC error:", textStatus, jqXHR);
				}
			});
		}

		function UActionPayloadResponse(responseObject)
		{
			var success = responseObject[0];
			if (success)
			{
				var functionName = responseObject[2];
				var functionData = responseObject[3];
				switch (functionName)
				{
					case "projStat":
						project_state = Number(functionData[2]);
						switch (project_state)
						{
							case 1:
								//console.log("UActionPayloadResponse projStat case 1");
								UActionPayload("FEUIRfsh","","UActionPayloadResponse");
								break;
							case 2:
								//console.log("UActionPayloadResponse projStat case 2");
								UActionPayload("FEUIRfsh","","UActionPayloadResponse")
								break;
							default:
								break;
						}
						break;
					case "FEUIRfsh":
						//console.log("UActionPayloadResponse FEUIRfsh case");
						project_state = FrontEndUIUpdate(project_state, functionData);
						waitingOnServer = false;
						refreshingAfterSettingInstanceIdle = false;
						$(".overlay").hide();
						break;
					default:
						break;
				}
			}
			else
			{
				UActionSimple("SetInstanceExceptionTransportSystem",false);
			}
		}	
		
		function redirectToHome() {window.location.href="../home";}
		
		function appendToTextArea(textAreaID, string) //This function takes a single-line string and appends it to a textarea with ID given as argument. It autoscrolls.
		{
			var textarea = document.getElementById(textAreaID);
			textarea.value += string +'\n';
			textarea.scrollTop = textarea.scrollHeight;
		}

		//Useful for clearing options from dropdown elements
		function removeOptions(selectElement) 
		{
		   var i, L = selectElement.options.length - 1;
		   for(i = L; i >= 0; i--) {
			  selectElement.remove(i);
		   }
		}
		
		function getExtension(filename) 
		{
			var parts = filename.split('.');
			return parts[parts.length - 1];
		}

		function isCSV(filename) 
		{
			var ext = getExtension(filename);
			switch (ext.toLowerCase()) {
			case 'csv':
			//etc
			return true;
		}
			return false;
		}

		/////////////////// BLOCK ////////////////////
		
		function makeid(length) 
		{
			let result = '';
			const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			const charactersLength = characters.length;
			let counter = 0;
			while (counter < length) {
			  result += characters.charAt(Math.floor(Math.random() * charactersLength));
			  counter += 1;
			}
			return result;
		}
		
		function selectElementRemoveOptions(selectElement) 
		{
		   var i, L = selectElement.options.length - 1;
		   for(i = L; i >= 0; i--) 
		   {
			  selectElement.remove(i);
		   }
		}
		
		function proofReadingTableConstructor(stream)
		{
			
			//console.log("proofReadingTableConstructor stream debug");
			//console.log(stream);
			
			var streamSlice = stream.slice(1, stream.length - 1);
			var jsonArray = streamSlice.split(",");
			//console.log(jsonArray);
			var rowCount = 0;

			for (let i = 0; i < jsonArray.length; i++) {
			  if (jsonArray[i] == "false" || jsonArray[i] == "true")
			  {
				rowCount++;
			  }
			}
			var myTable = document.getElementById("proofReadingTable-table")

			//Clear all the rows from the table
			myTable.innerHTML = "";

			//Build header row
			var headerRow = myTable.insertRow(0);
			var cell1 = headerRow.insertCell(0);
			var cell2 = headerRow.insertCell(1);
			var cell3 = headerRow.insertCell(2);
			var cell4 = headerRow.insertCell(3);
			cell1.innerHTML = "  ";
			cell2.innerHTML = "Feature"; 
			cell3.innerHTML = "Min";
			cell4.innerHTML = "Max"; 

			for (let r = 0; r < rowCount; r++) 
			{
			  var currentRow = myTable.insertRow(r + 1);
			  var cell1 = currentRow.insertCell(0);
			  var cell2 = currentRow.insertCell(1);
			  
			  
			  
			  //For column three, make an editable field
			  adjacentHTML1 = "<div class='form-control'><input data-uniqueid='";
			  adjacentHTML2 = "' value='";
			  adjacentHTML3 = "' type='text' onchange=proofReadingTableTextEdit(this) /</div>";
			  var assembly2 = [adjacentHTML1, r.toString(),"3", adjacentHTML2, jsonArray[r + rowCount*2].toString() ,adjacentHTML3];
			  adjacentHTML1 = "<div class='form-control'><input data-uniqueid='";
			  adjacentHTML2 = "' value='";
			  adjacentHTML3 = "' type='text' onchange=proofReadingTableTextEdit(this) /</div>";
			  var assembly3 = [adjacentHTML1, r.toString(),"4", adjacentHTML2, jsonArray[r + rowCount*3].toString() ,adjacentHTML3];
			  var cell3 = currentRow.insertCell(2);
			  var cell4 = currentRow.insertCell(3);
			  cell1.innerHTML = "";
			  cell2.innerHTML = jsonArray[r + rowCount]; 
			  
			  adjacentHTML1 = "<div class='form-control'><input value='";
			  adjacentHTML2 = "' type='checkbox' onclick=proofReadingTableCBClick(this) /</div>";
			  var assembly = [adjacentHTML1, r.toString(), adjacentHTML2];
			  
			  cell1.insertAdjacentHTML('beforeEnd', assembly.join(''))
			  cell3.insertAdjacentHTML('beforeEnd', assembly2.join(''))
			  cell4.insertAdjacentHTML('beforeEnd', assembly3.join(''))
			  
				if (jsonArray[r] == "true")
				{
					myTable.rows[r + 1].cells[0].querySelector('input[type="checkbox"]').checked = true;
				}
			  
			  
			  
			}
		}
		
		function proofReadingTableCBClick(proofReadingTableCB) {
			tableCheckboxElapsed = 0;
			tableCheckboxChanged = true;
			toast("Unless other checkbox changes are made, resync in 3 seconds...");
		}
		
		function proofReadingTableTextEdit(proofReadingTableTextField)
		{
			if ((proofReadingTableTextField.dataset.uniqueid == "43") || (proofReadingTableTextField.dataset.uniqueid == "44"))
			{
				if (proofReadingTableTextField.value == "null")
				{
					UIR_tableModified();
					return;
				}
			}
			if (isNaN(proofReadingTableTextField.value) || (Number(proofReadingTableTextField.value) < 0))
			{
				toast("Non-negative numerical values only...");
				$(".overlay").show();
				waitingOnServer = true;
				UActionPayload("FEUIRfsh","","proofReadingTableTextEdit");
				return;
			}
			UIR_tableModified();
		}

		function UIR_exitInstance()
		{
			$("#discoverInstance").modal("hide");
			$("#noInstance").modal("hide");
			$("#exitInstance").modal("toggle");
			$("#busyInstance").modal("hide");
			$("#killInstance").modal("hide");
			$("#sysFailed").modal("hide");
		}

		function favouritesTableConstructor(stream)
		{
			var streamSlice = stream.slice(1, stream.length - 1);
			var jsonArray = streamSlice.split(",");
			var rowCount = 0;

			for (let i = 0; i < jsonArray.length; i++) {
			if (jsonArray[i] == "false" || jsonArray[i] == "true")
			{
				rowCount++;
			}
			}
			var myTable = document.getElementById("favouriteTable-table")

			//Clear all the rows from the table
			myTable.innerHTML = "";

			//Build header row
			var headerRow = myTable.insertRow(0);
			var cell1 = headerRow.insertCell(0);
			var cell2 = headerRow.insertCell(1);
			var cell3 = headerRow.insertCell(2);
			var cell4 = headerRow.insertCell(3);
			var cell5 = headerRow.insertCell(4);
			var cell6 = headerRow.insertCell(5);
			var cell7 = headerRow.insertCell(6);
			var cell8 = headerRow.insertCell(7);
			cell1.innerHTML = "  ";
			cell2.innerHTML = "CH"; 
			cell3.innerHTML = "Index";
			cell4.innerHTML = "Frame"; 
			cell5.innerHTML = "Size";
			cell6.innerHTML = "Dur"; 
			cell7.innerHTML = "df/f";
			cell8.innerHTML = "Tau"; 

			for (let r = 0; r < rowCount; r++) 
			{
			var currentRow = myTable.insertRow(r + 1);
			var cell1 = currentRow.insertCell(0);
			var cell2 = currentRow.insertCell(1);
			var cell3 = currentRow.insertCell(2);
			var cell4 = currentRow.insertCell(3);
			var cell5 = currentRow.insertCell(4);
			var cell6 = currentRow.insertCell(5);
			var cell7 = currentRow.insertCell(6);
			var cell8 = currentRow.insertCell(7);
			cell1.innerHTML = "";
			cell2.innerHTML = jsonArray[r + rowCount]; 
			cell3.innerHTML = jsonArray[r + rowCount*2];
			cell4.innerHTML = jsonArray[r + rowCount*3];
			cell5.innerHTML = jsonArray[r + rowCount*4];
			cell6.innerHTML = jsonArray[r + rowCount*5];
			cell7.innerHTML = jsonArray[r + rowCount*6];
			cell8.innerHTML = jsonArray[r + rowCount*7];
			
			adjacentHTML1 = "<div class='form-control'><input value='";
			adjacentHTML2 = "' type='checkbox' onclick=favouritesTableCBClick(this) /</div>";
			var assembly = [adjacentHTML1, r.toString(), adjacentHTML2];
			
			cell1.insertAdjacentHTML('beforeEnd', assembly.join(''))
			
				if (jsonArray[r] == "true")
				{
					myTable.rows[r + 1].cells[0].querySelector('input[type="checkbox"]').checked = true;
				}
			}
		}

		function favouritesTableCBClick(favouritesTableCB) {
			tableCheckboxElapsed = 0;
			tableCheckboxChanged = true;
			toast("Unless other checkbox changes are made, system sync in 3 seconds...");
		}

		function UIR_tableModified()
		{
			var table = document.getElementById("proofReadingTable-table");
			var rows = table.rows.length - 1;
			
			var tmp1 = [];
			
			for (var r = 1; r <= rows; r++) 
			{
				var cr = table.rows[r];
				var cbV = cr.cells[0].querySelector('input[type="checkbox"]').checked;
				var fv = cr.cells[1].innerText.trim();
				var minV = cr.cells[2].querySelector('input').value.trim();
				var maxV = cr.cells[3].querySelector('input').value.trim();
				var rd = {
				cb: cbV,
				feature: fv,
				min: minV,
				max: maxV
				};

				tmp1.push(rd);
			}
			
			var table = document.getElementById("favouriteTable-table");
			var rows = table.rows.length - 1;
			
			var tmp2 = [];
			
			for (var r = 1; r <= rows; r++) 
			{
				var cr = table.rows[r];
				var cbV = cr.cells[0].querySelector('input[type="checkbox"]').checked;
				var rd = {
				cb: cbV
				};

				tmp2.push(rd);
			}
			
			UActionPayloadSC("tablEdit",["tablJSON",10000000,JSON.stringify({tmp1,tmp2})],"UIR_tableModified");
		}
		
		function CanvasSingleViewportController(imgSrc)
		{
			//console.log("CanvasSingleViewportController");
			if (imgSrc)
			{
				//console.log("CanvasSingleViewportController w imgSrc");
				$('<img/>').attr('src', imgSrc).on('load', function()
				{
					//console.log("CLEANDRAW CanvasSingleViewportController");
					cd_canvas = document.getElementById("MoviePanelImagePrimary");
					cd_ctx = cd_canvas.getContext("2d");
					
					var img = new Image();
					img.src = this;
					
					cd_canvas.width = cd_canvas.parentElement.getBoundingClientRect().width;
					cd_canvas.height = cd_canvas.parentElement.getBoundingClientRect().height;
					
					var hRatio = cd_canvas.width / this.width;
					var vRatio = cd_canvas.height / this.height;
					var ratio  = Math.min ( hRatio, vRatio );
					
					DVPImageHeight = this.height*ratio;
					DVPImageWidth = this.width*ratio;
					//console.log(DVPImageHeight);
					//console.log(DVPImageWidth);
					
					centerShift_x = ( cd_canvas.width - this.width*ratio ) / 2;
					centerShift_y = ( cd_canvas.height - this.height*ratio ) / 2; 
					WidthValid = this.width*ratio;
					HeightValid = this.height*ratio;
					
					cd_ctx.clearRect(0,0,cd_canvas.width, cd_canvas.height);
					cd_ctx.drawImage(this, 0,0, this.width, this.height, centerShift_x,centerShift_y,this.width*ratio, this.height*ratio);
					//console.log(this.width, this.height, centerShift_x,centerShift_y,this.width*ratio, this.height*ratio);
				});
			}
		}
		
		function CurveViewportController(imgSrc,Width,Height)
		{
			if (imgSrc)
			{
				$('<img/>').attr('src', imgSrc).on('load', function()
				{
					//console.log("CLEANDRAW CurveViewportController");
					cd_Curvecanvas = document.getElementById("graphDisplay");
					cd_ctx = cd_Curvecanvas.getContext("2d");
					
					var img = new Image();
					img.src = this;
					
					cd_Curvecanvas.width = cd_Curvecanvas.parentElement.getBoundingClientRect().width;
					cd_Curvecanvas.height = cd_Curvecanvas.parentElement.getBoundingClientRect().height;
					
					var scaleFactor = Math.min(cd_Curvecanvas.width / this.width, cd_Curvecanvas.height / this.height);
					
					//console.log("DEBUG + ====================================");
					//console.log(this.width);
					//console.log(this.height);
					//console.log(cd_canvas.width);
					//console.log(cd_canvas.height);
					
					var scaledWidth = this.width * scaleFactor;
					var scaledHeight = this.height * scaleFactor;

					//console.log(scaledWidth);
					//console.log(scaledHeight);

					var x = (cd_Curvecanvas.width - scaledWidth) / 2;
					var y = 0;
					
					cd_ctx.clearRect(0,0,cd_Curvecanvas.width, cd_Curvecanvas.height);
					cd_ctx.drawImage(this, x, y, scaledWidth, scaledHeight);
					//cd_ctx.drawImage(this, 0,0, this.width, this.height, centerShift_x,centerShift_y,this.width*ratio, this.height*ratio);
				});
			}
		}
		
		function CanvasDualViewportControllerLoadQueue(imgSrc01, imgSrc02)
		{
			//console.log("CanvasDualViewportControllerLoadQueue1");
			if (imgSrc01 && imgSrc02)
			{
				//console.log("CanvasDualViewportControllerLoadQueue2");
				$('<img/>').attr('src', imgSrc01).on('load', function()
				{
					//console.log("pass check");
					//console.log(this);
					DVP_onLoadLeftThis = this;
					dualImagesLoaded = dualImagesLoaded + 1;
					//console.log('DVP left image reports loaded');
				});
				
				$('<img/>').attr('src', imgSrc02).on('load', function()
				{
					DVP_onLoadRightThis = this;
					dualImagesLoaded = dualImagesLoaded + 2;
					//console.log('DVP right image reports loaded');
				});
			}
		}
		
		function  CanvasDualViewportController(LeftThis,RightThis)
		{
			cd_canvas = document.getElementById("MoviePanelImagePrimary");
			cd_ctx = cd_canvas.getContext("2d");
			
			cd_canvas.width = cd_canvas.parentElement.getBoundingClientRect().width;
			cd_canvas.height = cd_canvas.parentElement.getBoundingClientRect().height;
			
			var hRatio = (cd_canvas.width / 2) / DVP_onLoadLeftThis.width;
			var vRatio = cd_canvas.height / DVP_onLoadLeftThis.height;
			var ratio  = Math.min ( hRatio, vRatio );
			
			centerShift_x = ( cd_canvas.width - DVP_onLoadLeftThis.width*ratio ) / 4;
			centerShift_y = ( cd_canvas.height - DVP_onLoadLeftThis.height*ratio ) / 2;
			
			DVPImageHeight = DVP_onLoadLeftThis.height*ratio;
			DVPImageWidth = DVP_onLoadLeftThis.width*ratio;
			
			cd_ctx.clearRect(0,0,cd_canvas.width, cd_canvas.height);
			cd_ctx.drawImage(DVP_onLoadLeftThis, 0,0, DVP_onLoadLeftThis.width, DVP_onLoadLeftThis.height, 0,centerShift_y,DVP_onLoadLeftThis.width*ratio, DVP_onLoadLeftThis.height*ratio);
			
			var hRatio = (cd_canvas.width / 2) / DVP_onLoadRightThis.width;
			var vRatio = cd_canvas.height / DVP_onLoadRightThis.height;
			var ratio  = Math.min ( hRatio, vRatio );
			
			centerShift_x = ( cd_canvas.width - DVP_onLoadRightThis.width*ratio ) / 4;
			centerShift_y = ( cd_canvas.height - DVP_onLoadRightThis.height*ratio ) / 2;
			
			DVPImageHeight = DVP_onLoadRightThis.height*ratio;
			DVPImageWidth = DVP_onLoadRightThis.width*ratio;
			
			cd_ctx.drawImage(DVP_onLoadRightThis, 0,0, DVP_onLoadRightThis.width, DVP_onLoadRightThis.height, (centerShift_x)*4,centerShift_y,DVP_onLoadRightThis.width*ratio, DVP_onLoadRightThis.height*ratio);
		}
	</script>
	<div id="snackbar"></div>
	</body>
	</html>
	<?php
}
?>