function [table,evtTable] = updateTablesWithFEJSON(FilterTable,EventTable,JSONData)

	decoded = jsondecode(JSONData);
	jd = decoded.tmp1
	jd2 = decoded.tmp2
    correctrowCount = size(FilterTable, 1)
	correctrowCount2 = size(EventTable, 1)
	
    tmp = [jd.cb].';
	if (correctrowCount2 ~= 0)
		tmp2 = [jd2.cb].';
	end
    if (correctrowCount ~= size(tmp, 1))
        error("Decoded JSON table row count mismatches FilterTable row count")
    else
        for r = 1:correctrowCount
            if (tmp(r) == 0)
                FilterTable{r,1} = false;
            else
                FilterTable{r,1} = true;
            end
        end
    
        for r = 1:correctrowCount
            FilterTable(r,3) = {str2double(jd(r).min)};
        end
        
        for r = 1:correctrowCount
            FilterTable(r,4) = {str2double(jd(r).max)};
        end
    end
	table = FilterTable;
	
	if (correctrowCount2 ~= 0)
		if (correctrowCount2 ~= size(tmp2, 1))
			error("Decoded JSON table row count mismatches eventTable row count")
		else
			for r = 1:correctrowCount2
				if (tmp2(r) == 0)
					EventTable{r,1} = false;
				else
					EventTable{r,1} = true;
				end
			end
		end
	end
	evtTable = EventTable;
end