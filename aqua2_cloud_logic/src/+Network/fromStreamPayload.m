%Mark Bright (bmark21@vt.edu)
function [deconstructedPayload] = fromStreamPayload(obj,conn,message)
    try
        %Payload deconstructor for AQuA2 Cloud Logic Module WebSocket Server
        %Convert from int8 vector to unicode
        message = convertCharsToStrings(native2unicode(message.'));
        %Payload deconstructor for AQuA2 Cloud Logic Module WebSocket Server
        functionName = extractBetween(message,1,8);
        noOfArguments = (str2num(extractBetween(message,9,11)) - 100);
    
        payload_process_offset = 12;
        %We offset by 11 in the case of the MATLAB deconstructor
        payloadData = {};
        payloadData{1} = functionName;
        payloadData{2} = noOfArguments;
        data_width = 0;
        if noOfArguments > 0
            for x = 1:noOfArguments
                payloadData{numel(payloadData) + 1} = extractBetween(message,payload_process_offset,payload_process_offset + 7);
                payload_process_offset = payload_process_offset + 8;
                data_width = str2num(extractBetween(message,payload_process_offset,payload_process_offset + 7)) - 10000000;
                payloadData{numel(payloadData) + 1} = data_width;
                payload_process_offset = payload_process_offset + 8;
                payloadData{numel(payloadData) + 1} = extractBetween(message,payload_process_offset,payload_process_offset + (data_width - 1));
                payload_process_offset = payload_process_offset + data_width;
            end
        end
        deconstructedPayload = payloadData;
    catch error
        Network.instanceSQL(false,0,'Error',convertStringsToChars(join([structToChar(error.stack),error.message],"")));
        disp(error.message);
    end
end


