function newProj(obj,conn,message)
    try
        global f
        global projectState
    
        fh = guidata(f);
        fh.Card1.Visible = 'off';
        fh.Card2.Visible = 'on';
    
        projectState = 1; %Project state of 1 means we are loaded into new project menu. Send payload return indicating so.
        responseCellArray = {["projStat"] [10000000] [projectState]};
        conn.send(Network.toStreamPayload("projStat",responseCellArray));
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end