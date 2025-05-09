%Mark Bright (bmark21@vt.edu)
function [outputArg] = BEFunctionRoutingTable(obj,conn,message)
    try
    global f
    global projectState;
    global busy;
    global count;
    global referenceFilePath;
    global loadingProject;
    loadingProject = 0; %Default is zero. Set to 1 when executing loading project command.

    count = 0; %Reset timeout watchdog upon recieving a command from the users
    fh = guidata(f);
    %Entry function for directing recieved payloads from client. This
    %function will call the appropriate subroutines; dependant on the
    %nature of the payload. This is the BE function routing table.
    deconstructedData = Network.fromStreamPayload(obj,conn,message);
    switch deconstructedData{1}
        case "projStat"
            %Reference projectState global variable defined in AQuA_WBS_Main
            disp("BEFunctionRoutingTable projStat");
            if projectState == 0
                ui.proj.newProj(obj,conn,message); %Loads the new project interface. ui.proj.newProj has been modified to send a payload
            else
                responseCellArray = {["projStat"] [10000000] [projectState]};
                conn.send(Network.toStreamPayload("projStat",responseCellArray));
            end
        %The following case is a FE UI Refresh request. Feedback all
        %current UI parameters.
        case "FEUIRfsh"
            disp("BEFunctionRoutingTable FEUIRfsh");
            Supervisor.FEUISync(obj,conn,message); %Conn is properly close and dehandled within this function
        case "BEUISync"
            disp("BEFunctionRoutingTable BEUISync");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            Supervisor.BEUISync(obj,conn,message);
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdNewPj"
            disp("BEFunctionRoutingTable cmdNewPj");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            Supervisor.instanceBusySQL(true, 'Loading new project');
            try
                ui.proj.prep();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdDPRnB"
            disp("BEFunctionRoutingTable cmdDPRnB");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.detect.flow('run');
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "RnAlStps"
            disp("BEFunctionRoutingTable RnAlStps");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.detect.flow2();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdDPNxB"
            disp("BEFunctionRoutingTable cmdDPNxB");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.detect.flow('next');
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdDPBkB"
            disp("BEFunctionRoutingTable cmdDPBkB");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.detect.flow('back');
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "AddAllFl"
            disp("BEFunctionRoutingTable AddAllFl");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.mov.updtCursorFunMov([],[],f,'addrm','addAll');
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "ExportPr"
            disp("BEFunctionRoutingTable ExportPr");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.proj.getOutputFolder(deconstructedData{8},deconstructedData{5});
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "loadPrst"
            disp("BEFunctionRoutingTable loadPrst");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            Safe1 = fh.preset.Items;
            try
                referenceFilePath = deconstructedData{5};
                ui.proj.updtPreset();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                fh.preset.Items = Safe1;
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "loadExpr"
            disp("BEFunctionRoutingTable loadExpr");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            Supervisor.instanceBusySQL(true, 'Loading existing project file');
            try
                referenceFilePath = deconstructedData{5};
                loadingProject = 1;
                ui.proj.loadExp();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                loadingProject = 0;
            end
            loadingProject = 0;
            Supervisor.instanceBusySQL(false, 'Done');
        case "loadOpts"
            disp("BEFunctionRoutingTable loadOpts");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                referenceFilePath = deconstructedData{5};
                ui.detect.loadOpt();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "saveOpts"
            disp("BEFunctionRoutingTable saveOpts");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                referenceFilePath = deconstructedData{5};
                ui.detect.saveOpt();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "celldraw"
            disp("BEFunctionRoutingTable celldraw");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                vertexDataJSON = deconstructedData{5};
                ui.mov.drawReg([],[],f,'add','cell',vertexDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "ldmkdraw"
            disp("BEFunctionRoutingTable ldmkdraw");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                vertexDataJSON = deconstructedData{5};
                ui.mov.drawReg([],[],f,'add','landmk',vertexDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "selecEvt"
            disp("BEFunctionRoutingTable selecEvt");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                selectedEvt = str2double(deconstructedData{5});
                ui.evt.evtMngrSelectOne([],[],f,selectedEvt);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "maddEvt1"
            disp("BEFunctionRoutingTable maddEvt1");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                addEvt = str2double(deconstructedData{5});
                ui.evt.addOne([],[],f,addEvt);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "maddEvt2"
            disp("BEFunctionRoutingTable maddEvt2");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                addEvt = str2double(deconstructedData{5});
                ui.evt.addOne2([],[],f,addEvt);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cellremv"
            disp("BEFunctionRoutingTable cellremv");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                cursorPointDataJSON = deconstructedData{5};
                ui.mov.movClick([],[],f,'rm','cell',cursorPointDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "ldmkremv"
            disp("BEFunctionRoutingTable ldmkremv");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                cursorPointDataJSON = deconstructedData{5};
                ui.mov.movClick([],[],f,'rm','landmk',cursorPointDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cellname"
            disp("BEFunctionRoutingTable cellname");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                cursorPointDataJSON = deconstructedData{5};
                ui.mov.movClick([],[],f,'name','cell',cursorPointDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "ldmkname"
            disp("BEFunctionRoutingTable ldmkname");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                cursorPointDataJSON = deconstructedData{5};
                ui.mov.movClick([],[],f,'name','landmk',cursorPointDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cellsave"
            disp("BEFunctionRoutingTable cellsave");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                referenceFilePath = deconstructedData{5};
                ui.mov.regionSL([],[],f,'save','cell',deconstructedData{8},deconstructedData{5});
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "ldmksave"
            disp("BEFunctionRoutingTable ldmksave");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                referenceFilePath = deconstructedData{5};
                ui.mov.regionSL([],[],f,'save','landmk',deconstructedData{8},deconstructedData{5});
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cellload"
            disp("BEFunctionRoutingTable cellload");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                referenceFilePath = deconstructedData{5};
                ui.mov.regionSL([],[],f,'load','cell',deconstructedData{5},"");
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "ldmkload"
            disp("BEFunctionRoutingTable ldmkload");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                referenceFilePath = deconstructedData{5};
                ui.mov.regionSL([],[],f,'load','landmk',deconstructedData{5},"");
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "tablEdit"
            disp("BEFunctionRoutingTable tablEdit");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                [fh.filterTable.Data, fh.evtTable.Data] = Supervisor.updateTablesWithFEJSON(fh.filterTable.Data,fh.evtTable.Data,deconstructedData{5});
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdLUpOv"
            disp("BEFunctionRoutingTable cmdLUpOv");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.over.chgOv(2);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdLUpFe"
            disp("BEFunctionRoutingTable cmdLUpFe");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.detect.updtFeature([],[],f);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "checkroi"
            disp("BEFunctionRoutingTable checkroi");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                vertexDataJSON = deconstructedData{5};
                ui.mov.drawReg([],[],f,'check','roi',vertexDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "viewfavo"
            disp("BEFunctionRoutingTable viewfavo");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                cursorPointDataJSON = deconstructedData{5};
                ui.mov.movClick([],[],f,'addrm','viewFav',cursorPointDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "deleRest"
            disp("BEFunctionRoutingTable deleRest");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                cursorPointDataJSON = deconstructedData{5};
                ui.mov.movClick([],[],f,'addrm','delRes',cursorPointDataJSON);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "showCurv"
            disp("BEFunctionRoutingTable showCurv");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.evt.evtMngrShowCurve([],[],f);
            catch exception
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "selecAll"
            disp("BEFunctionRoutingTable selecAll");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.evt.evtMngrSelAll([],[],f);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "seldelet"
            disp("BEFunctionRoutingTable seldelet");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.evt.evtMngrDeleteSel([],[],f);
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "savewave"
            disp("BEFunctionRoutingTable savewave");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.evt.saveWaves([],[],f,deconstructedData{5});
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        case "PrstSync"
            disp("BEFunctionRoutingTable PrstSync");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            Supervisor.PrstSync(obj,conn,message);
            Supervisor.instanceBusySQL(false, 'Done');
        case "cmdGFlBt"
            disp("BEFunctionRoutingTable cmdGFlBt");
            Supervisor.AcknowledgeCommand(obj,conn,message);
            try
                ui.mov.movGauss();
            catch error
                disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
                Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
            end
            Supervisor.instanceBusySQL(false, 'Done');
        otherwise
            conn.close(); %Close connection object
            conn.delete(); %Delete invalid handle
        end
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end

