function actRun(~,~,f)
% ----------- Modified by Xuelong Mi, 02/09/2023 -----------
% active region detection and update overlay map

disp('Detecting');
Supervisor.instanceBusySQL(true, 'Detecting');

fh = guidata(f);
bd = getappdata(f,'bd');
opts = getappdata(f,'opts');

% only inside user drawn cells
sz = opts.sz;
evtSpatialMask = true(sz(1:3));
if bd.isKey('cell')
    bd0 = bd('cell');
    if sz(3)==1
        if numel(bd0) > 0
            evtSpatialMask = false(sz(1:3));
            for ii=1:numel(bd0)
                p0 = bd0{ii}{2};
                evtSpatialMask(p0) = true;
            end
        end
    else
        evtSpatialMask = bd0;
    end
end

% load setting
opts.thrARScl = str2double(fh.thrArScl.Value);
opts.minSize = str2double(fh.minSize.Value);
opts.maxSize = str2double(fh.maxSize.Value);
opts.minDur = str2double(fh.minDur.Value);
opts.circularityThr = str2double(fh.circularityThr.Value);
opts.spaMergeDist = 0; %str2double(fh.spaMergeDist.Value);

% channel 1
disp('Active region detection')
Supervisor.instanceBusySQL(true, 'Active region detection');

dF1 = getappdata(f,'dF1');
[arLst1] = act.acDetect(dF1,opts,evtSpatialMask,1);  % foreground and seed detection
setappdata(f,'arLst1',arLst1);
clear dF1;

% channel 2
if(~opts.singleChannel)
    dF2 = getappdata(f,'dF2');
    [arLst2] = act.acDetect(dF2,opts,evtSpatialMask,2);  % foreground and seed detection
    clear dF2;
else
    arLst2 = [];
end
setappdata(f,'arLst2',arLst2);

setappdata(f,'opts',opts);

disp('Working');
Supervisor.instanceBusySQL(true, 'Working');
fh.movLType.Value = 'Raw + overlay';
if ~opts.singleChannel
    fh.movRType.Value = 'Raw + overlay';
end
ui.mov.movViewSel([],[],f);
ui.detect.postRun([],[],f,arLst1,arLst2,[],[],'Step 2: active voxels');

fh.GaussFilter.Enable = 'on';

fh.nEvtName.Text = 'nAct';
if(~opts.singleChannel)
    fh.nEvt.Text = [num2str(numel(arLst1)),' | ',num2str(numel(arLst2))];
else
    fh.nEvt.Text = [num2str(numel(arLst1))];
end
disp('ActRun done');
Supervisor.instanceBusySQL(true, 'ActRun done');
end