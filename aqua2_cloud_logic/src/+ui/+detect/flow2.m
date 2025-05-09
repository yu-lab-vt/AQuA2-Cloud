function flow2()

global f
global GLOBAL_BusyTask;

%% the function of RunAllSteps
fh = guidata(f);
Supervisor.instanceBusySQL(true, 'Running all steps');
ui.detect.preProcessRun([],[],f);

Supervisor.instanceBusySQL(true, 'Act run');

if(~isempty(getappdata(f,'datCorrect1')))
    setappdata(f,'datOrg1',getappdata(f,'datCorrect1'));
    rmappdata(f,'datCorrect1');
    setappdata(f,'datOrg2',getappdata(f,'datCorrect2'));
    rmappdata(f,'datCorrect2');
end
fh.registrateCorrect.Enable = 'off';
fh.bleachCorrect.Enable = 'off';

ui.detect.actRun([],[],f);

Supervisor.instanceBusySQL(true, 'Phase');

ui.detect.phaseRun([],[],f);

Supervisor.instanceBusySQL(true, 'Events');

ui.detect.evtRun([],[],f);

Supervisor.instanceBusySQL(true, 'Global');

ui.detect.gloRun([],[],f);

Supervisor.instanceBusySQL(true, 'Features');

ui.detect.feaRun([],[],f);

Supervisor.instanceBusySQL(true, 'Finishing');

% controls
fh.deOutBack.Visible = 'on';
fh.deOutNext.Enable = 'off';
fh.deOutRun.Text = 'Extract';
fh.deOutNext.Text = '--';

fh.deOutTab.SelectedTab = fh.deOutTab.Children(end);
opts = getappdata(f,'opts');
opts.enableTab = numel(fh.deOutTab.Children)+1;

for i = 1:numel(fh.deOutTab.Children)
    fh.deOutTab.Children(i).ForegroundColor = [0,0,0];
end

setappdata(f,'opts',opts);

end


