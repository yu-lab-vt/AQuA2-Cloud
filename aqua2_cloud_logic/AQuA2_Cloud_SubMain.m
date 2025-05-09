%Mark Bright - 2024 - Virginia Tech - bmark21@vt.edu
function AQua2_Cloud_SubMain()
    %Cloud version of MATLAB AQuA2
    try
        startup;
        
        %The only variables we need to globalize are variables passed as
        %arguments from callback functions, as we cannot pass these arguments
        %to the needed functions when we call from the front-end interface.
        %Rather, we globalize f which contains the figure in which majority of
        %the data is stored and retrieve this global variable wherever required.
        %This allows us to hook into whatever function we need
        global f
        %This makes AQuA2 conversion to a cloud compatible interface much easier...

        %We add glocal subsampling settings for the applicaton images,
        %since processing and streaming image data contributes majorly to
        %UI refresh times
        global im1DownSample;
        global im2aDownSample;
        global im2bDownSample;
        global graphCurveDownSample;
        global currentFrame;
        global maximumFrame;


        im1DownSample = 1;
        im2aDownSample = 1;
        im2bDownSample = 1;
        graphCurveDownSample = 1;
        currentFrame = 0;
        maximumFrame = 0;
    
        global GLOBAL_userID
    
        if ~exist('dbg','var')
            dbg = 0;
        end
    
        f = uifigure('Name','AQUA2 Cloud','MenuBar','none','Toolbar','none','NumberTitle','off','Visible','off');
        
        disp('Initializing backend logic');
        Supervisor.instanceBusySQL(true, 'Initializing backend logic');
        ui.com.addCon(dbg);
        if exist('res','var') && ~isempty(res)
            ui.proj.prep([],[],f,2,res);
        end
        f.Visible = 'on';
    
        pause on
        pause(5);

        disp('Instance ready');
        Supervisor.instanceBusySQL(false, 'Idle');
        %If all is well, then we can set the running flag for this instance'
        %registry entry to true
        yestmp = 'yes';
        Network.mysqlCleanOpen();
        sql = strcat('UPDATE aqua_instances SET running = "',yestmp,'", busyStatus = "Idle" WHERE username = "',convertStringsToChars(GLOBAL_userID),'";');
        [query_result] = Network.mysql(0, sql);
        Network.mysql('closeall');
        global count;
        count = 0;
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        quit force;
    end
end
