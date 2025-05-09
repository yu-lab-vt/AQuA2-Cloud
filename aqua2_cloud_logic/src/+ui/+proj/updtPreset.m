function updtPreset()
    global f
    global referenceFilePath

    fh = guidata(f);
    try
        Supervisor.instanceBusySQL(true, 'Reading presets .csv file...');
        cfg = readtable(referenceFilePath,'PreserveVariableNames',true);
        cNames = cfg.Properties.VariableNames(4:end-1);
        fh.preset.Items = cNames;
        preset = find(strcmp(fh.preset.Items,fh.preset.Value));
        opts = util.parseParam(preset);
        
        if isfield(opts,'frameRate') && ~isempty(opts.frameRate)
            fh.tmpRes.Value = num2str(opts.frameRate);
        end
        if isfield(opts,'spatialRes') && ~isempty(opts.spatialRes)
            fh.spaRes.Value = num2str(opts.spatialRes);
        end
        if isfield(opts,'regMaskGap') && ~isempty(opts.regMaskGap)
            fh.bdSpa.Value = num2str(opts.regMaskGap);
        end
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end