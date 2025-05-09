function thrAdjust(value)
global f
fh = guidata(f);
opts = getappdata(f,'opts');


if (value >= fh.sldActThr.Limits(1)) && (value <= fh.sldActThr.Limits(2))
	curV = fh.sldActThr.Value;
	curV = value;
	curV = round(curV*10)/10;
	fh.sldActThr.Value = curV;
	fh.thrArScl.Value = num2str(curV);
end

fh = guidata(f);
fh.movLType.Value = 'Threshold preview';
if ~opts.singleChannel
    fh.movRType.Value = 'Threshold preview';
end
ui.mov.movViewSel([],[],f);
end