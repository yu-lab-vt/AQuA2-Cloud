function table = updateFilterTableWithFEJSON(FilterTable,JSON)
    jd = jsondecode(JSON);
    correctrowCount = size(FilterTable, 1);
    tmp = [jd.cb].';
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
end