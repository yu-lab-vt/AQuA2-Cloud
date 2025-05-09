function isSig = eventCurveSignificance(curve,t0,t1,t00,t11,sigThr)
    isSigLeft = false;
    isSigRight = false;
    T = numel(curve);

    % relative local? Here just assume noise related to intensity
    sigma0 = sqrt(median((curve(2:end)-curve(1:end-1)).^2)/0.9099);
    curve = curve/sigma0;
    [maxThr,tPeak] = max(curve(t00:t11));
    tPeak = t00 + tPeak - 1;
    minThr = min(curve(max(1,t0-5):min(T,t1+5)));
    thrs = maxThr:-(maxThr-minThr)/20:minThr;

    for k = 1:numel(thrs)
        if isSigRight && isSigLeft
            isSig = true;
            return;
        end
        curThr = thrs(k);
        ts = find(curve(t0:tPeak)<curThr,1,'last') + t0;
        if isempty(ts)
            ts = t0;
        end
        te = find(curve(tPeak:t1)<curThr,1) + tPeak - 2;
        if isempty(te)
            te = t1;
        end
        fg = curve(ts:te);
        dur = te - ts + 1;

        t_Left_start = ts - 1;
        while t_Left_start>0 && curve(t_Left_start)>=curThr
            t_Left_start = t_Left_start - 1;
        end
        t_Left_start = find(curve(1:t_Left_start)>=curThr,1,'last') + 1;
        if isempty(t_Left_start)
            t_Left_start = 1;
        end
        t_Left_start = max(t_Left_start,ts-dur);
        bgL = curve(t_Left_start:ts-1)';

        t_Right_end = te + 1;
        while t_Right_end<=T && curve(t_Right_end)>=curThr
            t_Right_end = t_Right_end + 1;
        end
        t_Right_end = find(curve(t_Right_end:T)>=curThr,1) - 2 + t_Right_end;
        if isempty(t_Right_end)
            t_Right_end = T;
        end
        t_Right_end = min(t_Right_end,te+dur);
        bgR = curve(te+1:t_Right_end)';
        
        if ~isempty(bgL)
            tScoreL = (mean(fg)-mean(bgL))/sqrt(1/numel(fg)+1/numel(bgL));
            if tScoreL>=sigThr
                [mu,sigma] = se.ordStatSmallSampleWith0s(fg,bgL,bgR);
                L = (mean(fg) - mean(bgL));
                z_Left = (L-mu)/sigma;
                if z_Left>=sigThr
                    isSigLeft = true;
                end
            end
        end

        if ~isempty(bgR)
            tScoreR = (mean(fg)-mean(bgR))/sqrt(1/numel(fg)+1/numel(bgR));
            if tScoreR>=sigThr
                [mu,sigma] = se.ordStatSmallSampleWith0s(fg,bgR,bgL);
                L = (mean(fg) - mean(bgR));
                z_Right = (L-mu)/sigma;
                if z_Right>=sigThr
                    isSigRight = true;
                end
            end
        end
    end
    isSig = isSigLeft & isSigRight;
end