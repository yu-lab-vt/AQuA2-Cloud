function feaRun(~,~,f)
    
    fh = guidata(f);
    opts = getappdata(f,'opts');
    opts.ignoreTau = fh.ignoreTau.Value;
    opts.propMetric = fh.propMetric.Value;
    opts.networkFeatures = fh.networkFeatures.Value;
    setappdata(f,'opts',opts);
    
    disp('Running feature extraction...');
    Supervisor.instanceBusySQL(true, 'Running feature extraction...');

    % features
    ui.detect.updtFeature([],[],f);
    
    % enable feature overlay
    ui.over.chgOv(1)
    
    fh.updtFeature1.Enable = 'on';
    fh.pFilter.Visible = 'on';
    fh.pEvtMngr.Visible = 'on';
    fh.pExport.Visible = 'on';
    fh.pSys.Visible = 'on';
    fh.deOutNext.Enable = 'on';
    fh.nEvtName.Text = 'nEvt';
    fh.overlayDat.Value = 'Events';
    fh.overlayFeature.Enable = 'on';
    fh.overlayColor.Enable = 'on';
    ui.over.chgOv(2);
    %
    setappdata(f,'finishAll',true);
    
    setappdata(f,'needReCheckCFU',true);
end