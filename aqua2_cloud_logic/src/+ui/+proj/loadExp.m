function loadExp()

global f
global referenceFilePath

    try
        % cfgFile = 'uicfg.mat';
        p0 = '.';
        try
            load('./cfg/DefaultFolder.mat');
            if exist(PathName,'dir')
                p0 = PathName;
            end
        catch
            p0 = '.';
        end
        setappdata(f,'fexp',[referenceFilePath]);
        ui.proj.prep();
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end