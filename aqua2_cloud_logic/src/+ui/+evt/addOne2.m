function addOne(~,~,f,evtNow)
    % add one or multiple events to favorite list
    
    fh = guidata(f);
    btSt = getappdata(f,'btSt');
    fts2 = getappdata(f,'fts2');
    opts = getappdata(f,'opts');
	
    if ~opts.singleChannel
        if evtNow > 0 && evtNow <= numel(fts2.basic.center)		
            lst = union(evtNow,btSt.evtMngrMsk2);        
            btSt.evtMngrMsk2 = lst;
            setappdata(f,'btSt',btSt);
            ui.evt.evtMngrRefresh([],[],f);
            fts = getappdata(f,'fts2');
            
            n0 = fts.curve.tBegin(evtNow(1));
            n1 = fts.curve.tEnd(evtNow(1));
            n = round((n0+n1)/2);        
            ui.movStep(f,n,[],1);
        end
    end
end
