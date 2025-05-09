<?php
//Mark Bright - bmark21@vt.edu
	require $_SERVER['DOCUMENT_ROOT'] . '/assets/vendor/autoload.php';
	session_start();
    	$_SESSION['expire'] = time()+1*300;
    	$socket = $_SESSION['AQuA_Instance_socket'];
    
	//We instantiate a websocket client to connect to the websocket server
	$client = new WebSocket\Client("ws://127.0.0.1:" . $socket);
        
    //Build and send the payload
    //Put the recieve capability of the L4 link into php try
    try 
    {
		$payload = AQuA_WBS_Payload_BuildAndSend();
		$client->binary($payload);
        	$message = $client->receive();
		$client->close();
		//Retrieve, interpret, and react to the recieved payload
		list($responseFunction, $responseData) = AQuA_WBS_Payload_RetrieveAndReact($message);
		//Respond to async request with functionName for JS function routing table and array of args
		echo json_encode(array(true, "", $responseFunction, $responseData));
    } 
    catch (\WebSocket\ConnectionException $e) 
    {
        //If we failed to stream the bytes or something went wrong. Stop here.
        echo json_encode(array(false, $e->getMessage()));
    }
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //TRANSPORT FUNCTIONS
	//Takes 3*n sized array where n is the number of arguments with the [3*n - 1]th element being generic 10000000 placeholders.
	//This function computes the data length of the data segment of each argument and updates [3*n - 1]th element accordingly
	//It returns the number of arguments of the argument array and the modified argument array itself
	function AQuA_WBS_Payload_Args_Constructor($Input_Arg_Array_tmp)
	{
		$no_of_args = 000;
		
		if (is_countable($Input_Arg_Array_tmp))
		{
			//print_r($Input_Arg_Array_tmp);
			if (is_int(count($Input_Arg_Array_tmp) / 3))
			{
				$no_of_args = (count($Input_Arg_Array_tmp) / 3);
			}
			else
			{
				die("Argument array size is not a multiple of 3!");
			}
			if ($no_of_args > 0)
			{
				for ($x = 0; $x < $no_of_args; $x++)
				{
					//print_r((($x + 1)*3) - 2);
					$Input_Arg_Array_tmp[((($x + 1)*3) - 2)] = (mb_strlen($Input_Arg_Array_tmp[((($x + 1)*3) - 1)]) + 10000000);
				}
			}
			$payload_args = join($Input_Arg_Array_tmp);
			return [$no_of_args, $payload_args];
		}
		else
		{
			return [0, []];
		}
	}

	//
	function AQuA_WBS_Payload_Constructor($Payload_FunctionName_tmp, $Payload_No_of_Args, $payload_arg_section_tmp) 
	{
        if (is_array($payload_arg_section_tmp)) {
            $payload_arg_section_tmp = implode('', $payload_arg_section_tmp);
        }
        $payload_tmp = implode('', [
            $Payload_FunctionName_tmp,
            strval($Payload_No_of_Args + 100),
            $payload_arg_section_tmp
        ]);
	  return $payload_tmp;
	}
	
	function AQuA_WBS_Payload_BuildAndSend()
	{
		$Payload_FunctionName = $_POST['UActionFunction']; //Payload function name
		$no_of_args = 000; //Placeholder. This integer is automatically computed by the payload constructor

		//Count number of arguments in the input argument array and return the count. This checks for multiple of 3 in the process
		//Compute data width of each argument data segment and return an updated argument array
        $data = $_POST['data'] ?? '';
		list($no_of_args, $payload_arg_section) = AQuA_WBS_Payload_Args_Constructor($data);

		//Then pass to payload constructor for serialization
		$Constructed_Payload = AQuA_WBS_Payload_Constructor($Payload_FunctionName, $no_of_args, $payload_arg_section);

		//$mydata is the serialized payload. We can now stream it as a series of bytes across the OSI L4 Transport layer
		$mydata = $Constructed_Payload;
		
		return $mydata;
	}
	
	function AQuA_WBS_Payload_RetrieveAndReact($message)
	{
		//Deconstruct a payload according to the AQuA WBS payload specification
		////////////////////////////////////////////////////////////////
		//Such a payload has the following structure
		//--------------------------------------------------------------
		//| [function - 8 bytes] | [no. of arguments - 3 bytes] | [REPLICATABLE DATA SECTIONS]
		//where the replicatable data sections have the following format
		//--------------------------------------------------------------
		//|[arg name - 8 bytes] | [no. of bytes - 8 bytes] | [DATA]
		//
		//The following is the deconstructor
		$Received_Payload = str_split($message,1);
		//print_r($Received_Payload);

		//We first read the first 8 bytes of the payload and the following two bytes to understand the destination and size of the payload

		$Recieved_Payload_FunctionName_Array = array_slice($Received_Payload, 0, 8);
		//echo "\n";
		//print_r($Recieved_Payload_FunctionName_Array);
		$Recieved_Payload_FunctionName = implode($Recieved_Payload_FunctionName_Array);
		//print_r(gettype($Recieved_Payload_FunctionName));
		//print_r($Recieved_Payload_FunctionName);

		$Recieved_Payload_no_of_args_Array = array_slice($Received_Payload, 8, 3);
		//print_r($Recieved_Payload_no_of_args_Array);
		$Recieved_Payload_no_of_args_as_string = implode($Recieved_Payload_no_of_args_Array);
		$Recieved_Payload_no_of_args = ((int) $Recieved_Payload_no_of_args_as_string) - 100;
		//print_r(gettype($Recieved_Payload_no_of_args));
		//print_r($Recieved_Payload_no_of_args);

		$Recieved_Payload_Data = [];

		$payload_process_offset = 11; //Used to keep track of the current byte offset
		//The first 10 bytes of the payload response are the function name and the no. of args. Offset for this
		$data_width = 0; //Temporary variable used to track the data size for designators.

		for ($x = 0; $x < $Recieved_Payload_no_of_args; $x++) //Iterate through each arg
		{
			array_push($Recieved_Payload_Data, (implode(array_slice($Received_Payload, $payload_process_offset, 8))));
			//print_r(gettype((implode(array_slice($Received_Payload, $payload_process_offset, 8)))));
			$payload_process_offset = $payload_process_offset + 8;
			$data_width = (((int) (implode(array_slice($Received_Payload, $payload_process_offset, 8)))) - 10000000);
			array_push($Recieved_Payload_Data, $data_width);
			$payload_process_offset = $payload_process_offset + 8;
			array_push($Recieved_Payload_Data, implode((array_slice($Received_Payload, $payload_process_offset, $data_width))));
			$payload_process_offset = $payload_process_offset + $data_width;
			//array_push($Recieved_Payload_Data, (((int) (implode(array_slice($Received_Payload, $payload_process_offset, 8)))) - 10000000));
			//print_r($Recieved_Payload_Data);
		}
		
		//We implode the [3*n]th elements of the recieved playload array as they are subarrays
		return [$Recieved_Payload_FunctionName, $Recieved_Payload_Data];
	}
?>