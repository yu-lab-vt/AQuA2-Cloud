function getOutputFolder(directory,suffix)

    global f;
    
    Supervisor.instanceBusySQL(true, 'Exporting data...');
    opts = getappdata(f,'opts');
    
    % SP, 18.07.16
    definput = {'_AQuA2'};
    selname = suffix;
    
    selname = char(selname);
    if isempty(selname)
        selname = '_AQuA2';
    end
    file0 = [opts.fileName1,selname];
    clear definput selname
    
    %file0 = [opts.fileName,'_AQuA']; SP, 18.07.16
    selpath = directory;
    path0 = [selpath];
    if ~isnumeric(selpath)
        ui.proj.saveExp([],[],f,file0,path0);
    end
    
end