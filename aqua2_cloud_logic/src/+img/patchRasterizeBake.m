%Raterize and bake, into image, patch information from AQuA/2 bd formats
%Feed bd vector information into bd, image into image, and define RGB [255,255,255] color in
%color
function result = patchRasterizeBake(image,bd)
    image = flipud(image);
    if bd.isKey('cell')
        bd0 = bd('cell');
        color = [0.5 0.5 0.8];
        for ii=1:numel(bd0)
            xyLst = bd0{ii}{1};
            xy = xyLst{1};
            xy = flipud(xy);
            for jj=1:length(xy)
                image(xy(jj,1),(xy(jj,2)+1),1) = color(1);
                image(xy(jj,1),(xy(jj,2)+1),2) = color(2);
                image(xy(jj,1),(xy(jj,2)+1),3) = color(3);
            end             
        end
    end
    
    if bd.isKey('landmk')
        bd0 = bd('landmk');
        color = [0.4 0.4 0];
        for ii=1:numel(bd0)
            xyLst = bd0{ii}{1};
            xy = xyLst{1};
            xy = flipud(xy);
            for jj=1:length(xy)
                image(xy(jj,1),(xy(jj,2)+1),1) = color(1);
                image(xy(jj,1),(xy(jj,2)+1),2) = color(2);
                image(xy(jj,1),(xy(jj,2)+1),3) = color(3);
            end                
        end
    end
    result = image;
    image = flipud(image);
end