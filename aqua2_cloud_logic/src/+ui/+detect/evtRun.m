function evtRun(~,~,f)
% Modified by Xuelong Mi 02/28/2023
fh = guidata(f);
opts = getappdata(f,'opts');
if(fh.needSpa.Value)
    disp('Detecting')
    Supervisor.instanceBusySQL(true, 'Detecting');
    try
        opts.sourceSzRatio = str2double(fh.sourceSzRatio.Value);
        opts.sourceSensitivity = str2double(fh.sourceSensitivity.Value);
		opts.whetherExtend = fh.whetherExtend.Value;											
        setappdata(f,'opts',opts);
    catch
        msgbox('Error setting parameters')
    end

    seLst1 = getappdata(f,'seLst1');
    subEvtLst1 = getappdata(f,'subEvtLst1');
    dF1 = getappdata(f,'dF1');
    majorInfo1 = getappdata(f,'majorInfo1');
    seLabel1 = getappdata(f,'seLabel1');
    disp('Spatial splitting');
    Supervisor.instanceBusySQL(true, 'Spatial splitting');
    [riseLst1,datR1,evtLst1,~] = evt.se2evtTop(dF1,seLst1,subEvtLst1,seLabel1,majorInfo1,opts);
    setappdata(f,'riseLst1',riseLst1);
    setappdata(f,'evt1',evtLst1);
    clear dF1;

    if(~opts.singleChannel)
        dF2 = getappdata(f,'dF2');
        seLst2 = getappdata(f,'seLst2');
        subEvtLst2 = getappdata(f,'subEvtLst2');
        majorInfo2 = getappdata(f,'majorInfo2');
        seLabel2 = getappdata(f,'seLabel2');
        disp('Handling channel 2');
        Supervisor.instanceBusySQL(true, 'Handling channel 2');
        [riseLst2,datR2,evtLst2,~] = evt.se2evtTop(dF2,seLst2,subEvtLst2,seLabel2,majorInfo2,opts);
        clear dF2;
    else
        riseLst2 = []; evtLst2 = []; datR2 = [];
    end
    setappdata(f,'riseLst2',riseLst2);
    setappdata(f,'evt2',evtLst2);
else
    datR1 = [];
    datR2 = [];
    evtLst1 = getappdata(f,'seLst1');
    evtLst2 = getappdata(f,'seLst2');
    setappdata(f,'evt1',evtLst1); 
    setappdata(f,'evt2',evtLst2);
    setappdata(f,'riseLst1',[]); 
    setappdata(f,'riseLst2',[]);
end

disp('Post run events');
Supervisor.instanceBusySQL(true, 'Post run events');

ui.detect.postRun([],[],f,evtLst1,evtLst2,datR1,datR2,'Events');
% fh.updtFeature1.Enable = 'off';
fh.nEvtName.Text = 'nEvt';
if(~opts.singleChannel)
    fh.nEvt.Text = [num2str(numel(evtLst1)),' | ',num2str(numel(evtLst2))];
else
    fh.nEvt.Text = [num2str(numel(evtLst1))];
end

disp('Finishing');
Supervisor.instanceBusySQL(true, 'Finishing');

end





