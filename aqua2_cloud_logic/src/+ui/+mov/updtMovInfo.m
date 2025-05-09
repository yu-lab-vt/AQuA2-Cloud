function updtMovInfo(f,n,T)

global currentFrame
global maximumFrame

fh = guidata(f);
% dat = getappdata(f,'dat');
opts = getappdata(f,'opts');

n1 = n; 
n2 = T;
currentFrame = n;
maximumFrame = T;
% n2 = size(dat,3);

t1 = n1*opts.frameRate; t2 = n2*opts.frameRate;
n_str = [num2str(n1),'/',num2str(n2),' Frame'];
% t_str = [num2str(t1),'/',num2str(t2),' Second'];
fh.curTime.Text = [n_str];%,'  ',t_str];

end