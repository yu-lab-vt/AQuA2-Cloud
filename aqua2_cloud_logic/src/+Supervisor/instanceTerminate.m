function [output] = instanceTerminate(obj,conn,message)
    %Supervisor variables
    global WSServer
    global GLOBAL_userID
    global count
    global GLOBAL_manStop

    %Globalized AQuA Variables
    global f


    %Dont halt for errors here. We force quit the instance even if parts
    %fail
    %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    %Close AQuA by simply utilizing "closeMe.m" code.
    try
        figFav = getappdata(f,'figFav');
        if ~isempty(figFav) && isvalid(figFav)
            delete(figFav);
        end
        delete(f)
    catch
    end
    %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    %If this method was called from the function routing table, then
    %echo back the payload as confirmation that terminate request was processed.
    %We do this as late as possible so that if anything goes wrong, we
    %minimize the risk of remote user getting bugged out.
    if ~exist('obj')
        try
            conn.send(message);
        catch
        end
    end
    
    %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    %Supervisor terminate the web socket server java object
    try, WSServer.closeAll, catch, fprintf("Closing web-socket server..."), end
    try
        WSServer.stop
        delete(WSServer)
        clear WSServer
    catch
    end
    %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    try
        Network.mysqlCleanOpen();
        sql = strcat('DELETE FROM aqua_instances WHERE username = "',convertStringsToChars(GLOBAL_userID),'";')
        [query_result] = Network.mysql(0, sql)
    catch
    end

    %Set count so that the timeout while loop within the supervisor
    %function stops iterating. Set manual stop flag so we don't repeatedly
    %try to kill the WS object
    GLOBAL_manStop = true;
    count = 18000;

    output = 1;
end