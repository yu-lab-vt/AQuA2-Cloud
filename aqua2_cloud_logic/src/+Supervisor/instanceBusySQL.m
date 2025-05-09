function [output] = instanceBusySQL(busy, busyMessage)
    try
        %Sets busy flag and busy message
        %Boolean, char array
        %Inputs are required to be character arrays. They cannot be strings
        %(i.e. 'var1' and not "var1")
        Network.mysqlCleanOpen();
        global GLOBAL_userID;
        
        if (busy)
            busyAsString = 'yes';
        else
            busyAsString = 'no';
        end
        
        if ~exist('busyMessage','var')
            busyMessage = 'Undefined';
        end
        
        sql = strcat('UPDATE aqua_instances SET busy = "',busyAsString,'", busyStatus = "',busyMessage,'" WHERE username = "',convertStringsToChars(GLOBAL_userID),'";');
        [query_result] = Network.mysql(0, sql);
        output = query_result;
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end