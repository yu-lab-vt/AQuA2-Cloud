function PrstSync(obj,conn,message)
    %If project state = 0, this function will never be called
    %Retrieve fh parameters dependent on the current project state
    global f;
    global projectState

    fh = guidata(f)
	try
		deconstructedData = Network.fromStreamPayload(obj,conn,message);
		fh.preset.Value = fh.preset.Items{str2num(deconstructedData{5}) + 1};
		ui.proj.updtPreset();
	catch error
        Supervisor.instanceExceptionSQL(strcat(structToChar(error.stack), ": ", error.message));
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
	end
end