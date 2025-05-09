function [output] = instanceExceptionSQL(exception)
    try
        %Sets exception message in instance registry entry.
        %char array
        %Inputs are required to be character arrays. They cannot be strings
        %(i.e. 'var1' and not "var1")
        Network.mysqlCleanOpen();
        global GLOBAL_userID;

        if ~exist('exception','var')
            exception = 'None';
        end

        exceptionMessagePrepared = convertStringsToChars(exception);

        % Truncate if longer than 250 characters
        maxLength = 250;
        if length(exceptionMessagePrepared) > maxLength
            divider = ' | ';
            divLength = length(divider);
            keepLength = floor((maxLength - divLength) / 2);
            startPart = exceptionMessagePrepared(1:keepLength);
            endPart = exceptionMessagePrepared(end-keepLength+1:end);
            exceptionMessagePrepared = [startPart divider endPart];
        end

        sql = strcat('UPDATE aqua_instances SET exception = "',exceptionMessagePrepared,'" WHERE username = "',convertStringsToChars(GLOBAL_userID),'";');
        [query_result] = Network.mysql(0, sql);
        output = query_result;
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end