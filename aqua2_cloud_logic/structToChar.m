function outputChar = structToChar(inputStruct)
    if ~isstruct(inputStruct)
        error('Input must be a struct.');
    end
    if isempty(inputStruct)
        outputChar = '';
        return;
    end
    lines = strings(1, numel(inputStruct));
    for k = 1:numel(inputStruct)
        if isfield(inputStruct, 'name') && isfield(inputStruct, 'line')
            lines(k) = sprintf('In %s (line %d)', inputStruct(k).name, inputStruct(k).line);
        else
            lines(k) = '[Invalid struct format]';
        end
    end
    outputString = strjoin(lines, ' > ');
    outputChar = char(outputString);
end
