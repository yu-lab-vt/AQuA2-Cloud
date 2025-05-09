function addOne(~,~,f,evtNow)
    % add one or multiple events to favorite list
    
    fh = guidata(f);
    btSt = getappdata(f,'btSt');
	fts1 = getappdata(f,'fts1');
    
    if evtNow > 0 && evtNow <= numel(fts1.basic.center)		
        lst = union(evtNow,btSt.evtMngrMsk1);        
        btSt.evtMngrMsk1 = lst;
        setappdata(f,'btSt',btSt);
        ui.evt.evtMngrRefresh([],[],f);
        fts = getappdata(f,'fts1');
        
        n0 = fts.curve.tBegin(evtNow(1));
        n1 = fts.curve.tEnd(evtNow(1));
        n = round((n0+n1)/2);        
        ui.movStep(f,n,[],1);
    end
    
end
