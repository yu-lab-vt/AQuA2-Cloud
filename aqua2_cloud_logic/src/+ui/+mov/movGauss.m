function movGauss()
global f
fh = guidata(f);
btSt = getappdata(f,'btSt');
col = getappdata(f,'col');
n = round(fh.sldMov.Value);
if ~isfield(btSt,'GaussFilter')
    btSt.GaussFilter = 0;
end
if btSt.GaussFilter==0
    btSt.GaussFilter = 1;
    fh.GaussFilter.BackgroundColor = [.8,.8,.8];
else
    btSt.GaussFilter = 0;
    fh.GaussFilter.BackgroundColor = col;
end
setappdata(f,'btSt',btSt);
if n>0
    ui.over.adjMov([],[])
    ui.movStep(f,n,[],1);
end
end