function drawReg(~,~,f,op,lbl,vertexDataJSONEncoded)
    % updtFeature update network features after user draw regions
    
    fh = guidata(f);
    bd = getappdata(f,'bd');
    btSt = getappdata(f,'btSt');
    opts = getappdata(f,'opts');
    
    if bd.isKey(lbl)
        bd0 = bd(lbl);
    else
        bd0 = [];
    end
    
    ax = fh.mov;
    if ~fh.sbs.Value
        ax = fh.mov;
    else
        ax = fh.movL;
    end
    
	vertexes = jsondecode(vertexDataJSONEncoded);
	vertexes = struct2table(vertexes);
	vertexes = table2array(vertexes);
	
	vertexesBuild = vertexes;

	axImageWidth = ax.XLim(2);
	axImageHeight = ax.YLim(2);

	for r = 1:size(vertexes,1)
		vertexesBuild(r,1) = axImageWidth*vertexes(r,1);
		vertexes(r,2) = 1 - vertexes(r,2);
		vertexesBuild(r,2) = axImageHeight*vertexes(r,2);
	end
	
	vertexes = vertexesBuild;
  
    if strcmp(op,'add')
        tmp = [];
		hh = drawpolygon('Position',vertexes,'Parent',ax);
        %hh = drawpolygon(ax);
        if ~isempty(hh)
            nPts = size(hh.Position,1);
            if nPts>2
                msk = flipud(hh.createMask);
                tmp{1} = bwboundaries(msk);
                tmp{2} = find(msk>0);
                tmp{3} = 'manual';
                tmp{4} = 'None';
                bd0{end+1} = tmp;
                delete(hh)
            end
        end
    end
	
    if strcmp(op,'check')
        tmp = [];
		hh = drawpolygon('Position',vertexes,'Parent',ax)
        if ~isempty(hh)
            nPts = size(hh.Position,1);
            if nPts>2
                msk = flipud(hh.createMask);
                pix = find(msk);
                datOrg1 = getappdata(f,'datOrg1');
                datVec1 = reshape(datOrg1,[],size(datOrg1,4));
                curve1 = mean(datVec1(pix,:),1); clear datVec1;
                curve1 = curve1*(opts.maxValueDat1 - opts.minValueDat1)+opts.minValueDat1;
                %figure;
				%hold on;
                %plot(curve1);
                if ~opts.singleChannel
                    datOrg2 = getappdata(f,'datOrg2');
                    datVec2 = reshape(datOrg2,[],size(datOrg2,4));
                    curve2 = mean(datVec2(pix,:),1); clear datVec2;
                    curve2 = curve2*(opts.maxValueDat2 - opts.minValueDat2)+opts.minValueDat2;
                    %plot(curve2);
                end
                legend('channel 1','channel 2');
                title('ROI curve');
                delete(hh)
				
                sz = opts.sz;
                T = sz(4);
                ax = fh.curve;
                hh = findobj(ax,'Type','line');
                delete(hh);
                hh = findobj(ax,'Type','text');
                delete(hh);
                ax.XLim = [0,T+1];
                ax.YLim = [min(curve1),max(curve1)];
                line(ax,1:T,curve1,'Color','b','LineWidth',1);
            end
        end
    end
    
    if strcmp(op,'arrow')
        opts = getappdata(f,'opts');
        fh.drawNorth.BackgroundColor = [.8,.8,.8];
        pause(1e-4);
        hh = drawline(ax);
        if ~isempty(hh)
            bd0 = hh.Position;
            opts.northx = bd0(2,1)-bd0(1,1);
            opts.northy = bd0(2,2)-bd0(1,2);
            setappdata(f,'opts',opts);
            delete(hh)
            fh.drawNorth.BackgroundColor = [.96,.96,.96];
            pause(1e-4);
        end
    end
    
    bd(lbl) = bd0;
    setappdata(f,'bd',bd);
    f.Pointer = 'arrow';
    ui.movStep(f,[],[],1);
    
end







