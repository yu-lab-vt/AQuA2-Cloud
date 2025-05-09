%Mark Bright (bmark21@vt.edu)
function [constructedPayload] = toStreamPayload(functionName,data)
    try
        %Input to the AQuA WBS MATLAB Module constructor will be a string
        %as the first argument (designator) and a cell array as 2nd argument
        %The bulk data will be included within this cell array

        %The format of the data cell array is
        %{["8charStr"] [10000000] ["anylengthofstringordata"]}
        %      /\          /\                /\
        %must be 8 chars | always  |    can be any string length or number

        %Output is a flat string
        %First we iterate through the input data cell array and check that its
        %size is divisible by three. Otherwise the cell array count is invalid.
        %For argument data sizes in bytes, feed 10000000 placeholder and we automatically
        %change it to the arg data size
        if ~mod(numel(data)/3,1)
            %fprintf("Input cell array is correct size")
            %data
            numberOfArguments = numel(data)/3;
        else
            %fprintf('Input cell array is not divisble by three. Wrong Size.')
            return
        end
        if numberOfArguments > 0
            for x = 1:numberOfArguments
                if isstring(data{(x*3)})
                    data{(x*3) - 1} = string(numel(unicode2native(data{(x*3)})) + 10000000);
                else
                    data{(x*3)} = string(data{(x*3)});
                    data{(x*3) - 1} = string(numel(unicode2native(data{(x*3)})) + 10000000);
                end
            end
        end
        numberOfArgumentsBase = 100;
        numberOfArgumentsString = string(numberOfArgumentsBase + numberOfArguments);
        flatstring = "";
        flatstring = append(flatstring,functionName);
        flatstring = append(flatstring,numberOfArgumentsString);
        for x = 1:numel(data)
            flatstring = append(flatstring,data{x});
        end
        %flatstring
        constructedPayload = unicode2native(flatstring);
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end