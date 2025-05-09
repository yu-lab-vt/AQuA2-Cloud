function flow(op)
    %op is the operator and can be 'back', 'run', 'next', and 'chg'
    global f
    global GLOBAL_BusyTask
    try
        fh = guidata(f);
        nTabTot = numel(fh.deOutTab.Children);
        ixTab = find(fh.deOutTab.SelectedTab==fh.deOutTab.Children);
        opts = getappdata(f,'opts');
        if isfield(opts,'enableTab')
            enableTab = opts.enableTab;
        else
            enableTab = numel(fh.deOutTab.Children)+1;
        end

        % controls
        if strcmp(op,'chg')
            if ixTab>enableTab
                fh.deOutTab.SelectedTab=evtDat.OldValue;
                ixTab = find(fh.deOutTab.SelectedTab==fh.deOutTab.Children);
            end
            if isfield(opts,'isLoadData') && opts.isLoadData
                ixTab = nTabTot;
                fh.deOutTab.SelectedTab=fh.deOutTab.Children(ixTab);
            end
        end

        % go to previous step
        if strcmp(op,'back') && ixTab>1
            ixTab = ixTab - 1;
            fh.deOutTab.SelectedTab = fh.deOutTab.Children(ixTab);
        end

        % run current step
        if strcmp(op,'run')
            switch ixTab
                case 1										 
                    ui.detect.preProcessRun([],[],f);
                    fh.movLType.Value = 'Threshold preview';
                    if ~opts.singleChannel
                        fh.movRType.Value = 'Threshold preview';
                    end
                    ui.mov.movViewSel([],[],f);
                case 2								 
                    ui.detect.actRun([],[],f);
                case 3 
                    ui.detect.phaseRun([],[],f);
                case 4										 
                    ui.detect.evtRun([],[],f);
                case 5										 
                    ui.detect.gloRun([],[],f);
                case 6									 
                    ui.detect.feaRun([],[],f);
            end
            opts = getappdata(f,'opts');
            enableTab = max(enableTab,ixTab+1);
            if enableTab<=nTabTot
                fh.deOutTab.Children(enableTab).ForegroundColor = [0,0,0];
            end
            opts.enableTab = enableTab;
            fh.deOutNext.Enable = 'on';
        end

        % go to next step
        if strcmp(op,'next')
            if ixTab<nTabTot
                if(ixTab==1 && strcmp(fh.registrateCorrect.Enable,'on'))
                    if(~isempty(getappdata(f,'datOOrg1')))
                        rmappdata(f,'datOOrg1');
                        rmappdata(f,'datOOrg2');
                    end
                    fh.registrateCorrect.Enable = 'off';
                    fh.bleachCorrect.Enable = 'off';
                    fh.medSmo.Enable = 'off';
                end
                ixTab = ixTab + 1;
                fh.deOutTab.SelectedTab = fh.deOutTab.Children(ixTab);
            else
                %fCFU = uifigure('Name','AQUA2-CFU','MenuBar','none','Toolbar','none',...
                %'NumberTitle','off','Visible','off');
                %ui.com.cfuCon(fCFU,f);
                %fCFU.Visible = 'on';
            end
        end

        if ixTab>=enableTab
            fh.deOutNext.Enable = 'off';
        else
            fh.deOutNext.Enable = 'on';
        end
        if ixTab>1
            fh.deOutBack.Visible = 'on';
        else
            fh.deOutBack.Visible = 'off';
        end
        if ixTab==nTabTot
            fh.deOutNext.Enable = 'off';
            fh.deOutRun.Text = 'Extract';
            fh.deOutNext.Text = '--';
        else
            fh.deOutRun.Text = 'Run';
            fh.deOutNext.Text = 'Next';
        end
        setappdata(f,'opts',opts);
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end


