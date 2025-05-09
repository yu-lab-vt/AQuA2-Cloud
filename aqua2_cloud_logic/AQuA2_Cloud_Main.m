%Mark Bright - 2024 - Virginia Tech - bmark21@vt.edu
function [output] = AQuA2_Cloud_Main(userIDArg)
    try
        folder = fileparts(which("mfilename"));
        addpath(genpath(folder));
        global GLOBAL_userID
        GLOBAL_userID = convertCharsToStrings(userIDArg);
        fprintf('AQuA2 Logical module registered to user %s...\n',convertStringsToChars(GLOBAL_userID));
        global GLOBAL_manStop
        GLOBAL_manStop = false; %Assume timeout unless set otherwise
        folder = fileparts(which(mfilename));
        addpath(genpath(folder));
        %AQUA_WBS_MAIN
    
        Network.mysqlCleanOpen();
    
        %Prepare SQL query to retrieve username and user data directory using user ID (int)
        sql = strcat('SELECT username,user_data_directory FROM users WHERE username="',convertStringsToChars(GLOBAL_userID),'";');
        [username_cellarray,user_data_directory_cellarray] = Network.mysql(0, sql); %Populate the return variables with cell arrays.
        if isempty(username_cellarray)
            disp('FATAL: The logical module could not SQL query the AQuA instances table for an existing AQuA instance record...')
            disp('Logical module shutting down...')
            quit force;
            return
        end
        user_data_directory = char(user_data_directory_cellarray); %Set the user data directory to user DB value
        if isempty(user_data_directory)
            disp('FATAL: The logical module could not interpret the user data directory from the AQuA instance record...')
            disp('Logical module shutting down...')
            quit force;
            return
        end
        %We now check aqua web instance registry to obtain the socket and determine later whether or not we are an unwanted duplicate
        sql = strcat('SELECT username,socket,terminate,running FROM aqua_instances WHERE username="',convertStringsToChars(GLOBAL_userID),'";');
        [username_cellarray,socket_cellarray,terminate_cellarray,running_cellarray] = Network.mysql(0, sql);
        Network.mysql('closeall');
        %If socket_cellarray is empty, there is no valid socket information and we must terminate
        if isempty(socket_cellarray) %Returns true if no record of an existing AQuA instance is found in the AQuA instances table
            disp('FATAL: The logical module could not SQL query the AQuA instances table for a valid socket allocation...')
            disp('Logical module shutting down...')
            quit force;
            return
        else
            allocated_socket = str2num(char(socket_cellarray));
        end
    
        %We know which user instantiated us, we know we are unique, and our allocated port socket.
        %We now setup a websocket server within this MATLAB function.
        %Moving forward, assume reliable SQL queries and use them for advanced error reporting
        global WSServer
        WSServer = Network.EchoServer(allocated_socket);
        disp('AQuA2 Cloud Logic Module - Start web-socket server')
        Supervisor.instanceBusySQL(true, 'Initializing connection');
    
        %Inactivity timeout
        global count;
        count = 0;
        global registrycheck;
        registrycheck = 0;
    
        %Use a project state variable to broadly describe AQuA's current state
        global projectState;
        projectState = 0;
    
        %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        AQuA2_Cloud_SubMain();
        %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    
        %Terminate upon inactivity and not busy after 30 minutes,
        %terminate regardless after two days
        while(GLOBAL_manStop == false)
            count = count + 1;
            Network.mysqlCleanOpen();
            sql = strcat('SELECT username,socket,terminate,running,busy FROM aqua_instances WHERE username="',convertStringsToChars(GLOBAL_userID),'";');
            [username_cellarray,socket_cellarray,terminate_cellarray,running_cellarray,busy_cellarray] = Network.mysql(0, sql);
            Network.mysql('closeall');
            if isempty(socket_cellarray)
                %The user's AQuA instance is no longer present in the table. Force quit
                disp('FATAL: Periodic instance table check could not find a valid record. Logical module shutting down...')
                if (GLOBAL_manStop == false)
                    Supervisor.instanceTerminate();
                end
            end

            if (count < 1800) %Do nothing for first 30 minutes of idling
            else
                if (count < 172800) %After 2 days, terminate regardless
                    if (string(busy_cellarray) == "no")
                        if (GLOBAL_manStop == false)
                            Supervisor.instanceTerminate();
                        end
                    end 
                else
                    if (GLOBAL_manStop == false)
                        Supervisor.instanceTerminate();
                    end
                end
            end
            pause(1);
        end
        if (GLOBAL_manStop == false)
            Supervisor.instanceTerminate();
        end
        disp('AQuA2 Cloud Logic Module stopped...')
        quit force;
        output = 1;
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        quit force;
        output = 1;
    end
end