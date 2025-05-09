/*
   jPolygon - a ligthweigth javascript library to draw polygons over HTML5 canvas images.
   Project URL: http://www.matteomattei.com/projects/jpolygon
   Author: Matteo Mattei <matteo.mattei@gmail.com>
   Version: 1.0
   License: MIT License
   
   Modified to jPolgyonCBIL by Mark Bright
   Author: Mark Bright <bmark21@vt.edu>
*/

function cd_line_intersects(p0, p1, p2, p3) {
    var s1_x, s1_y, s2_x, s2_y;
    s1_x = p1['x'] - p0['x'];
    s1_y = p1['y'] - p0['y'];
    s2_x = p3['x'] - p2['x'];
    s2_y = p3['y'] - p2['y'];

    var s, t;
    s = (-s1_y * (p0['x'] - p2['x']) + s1_x * (p0['y'] - p2['y'])) / (-s2_x * s1_y + s1_x * s2_y);
    t = ( s2_x * (p0['y'] - p2['y']) - s2_y * (p0['x'] - p2['x'])) / (-s2_x * s1_y + s1_x * s2_y);

    if (s >= 0 && s <= 1 && t >= 0 && t <= 1)
    {
        return true;
    }
    return false; // No collision
}

function point(x, y, cd_ctx){
    cd_ctx.fillStyle="red";
    cd_ctx.strokeStyle = "red";
    cd_ctx.fillRect(x-2,y-2,4,4);
    cd_ctx.moveTo(x,y);
}

function cd_draw(end,cd_ctx,cd_perimeter)
{
	//end argument is presumed to be a flag to close polygon
    cd_ctx.lineWidth = 1;
    cd_ctx.strokeStyle = "white";
    cd_ctx.lineCap = "square";
    cd_ctx.beginPath();

    for(var i=0; i<cd_perimeter.length; i++){
        if(i==0){
            cd_ctx.moveTo(cd_perimeter[i]['x'],cd_perimeter[i]['y']);
            end || point(cd_perimeter[i]['x'],cd_perimeter[i]['y'], cd_ctx);
        } else {
            cd_ctx.lineTo(cd_perimeter[i]['x'],cd_perimeter[i]['y']);
            end || point(cd_perimeter[i]['x'],cd_perimeter[i]['y'], cd_ctx);
        }
    }
    if(end)
	{
        cd_ctx.lineTo(cd_perimeter[0]['x'],cd_perimeter[0]['y']);
        cd_ctx.closePath();
        cd_ctx.fillStyle = 'rgba(255, 0, 0, 0.5)';
        cd_ctx.fill();
        cd_ctx.strokeStyle = 'blue';
        cd_complete = true;
    }
    cd_ctx.stroke();
	return [cd_ctx,cd_perimeter,cd_complete];
}

function cd_check_intersect(x,y, cd_perimeter){
    if(cd_perimeter.length < 4){
        return false;
    }
    var p0 = new Array();
    var p1 = new Array();
    var p2 = new Array();
    var p3 = new Array();

    p2['x'] = cd_perimeter[cd_perimeter.length-1]['x'];
    p2['y'] = cd_perimeter[cd_perimeter.length-1]['y'];
    p3['x'] = x;
    p3['y'] = y;

    for(var i=0; i<cd_perimeter.length-1; i++){
        p0['x'] = cd_perimeter[i]['x'];
        p0['y'] = cd_perimeter[i]['y'];
        p1['x'] = cd_perimeter[i+1]['x'];
        p1['y'] = cd_perimeter[i+1]['y'];
        if(p1['x'] == p2['x'] && p1['y'] == p2['y']){ continue; }
        if(p0['x'] == p3['x'] && p0['y'] == p3['y']){ continue; }
        if(cd_line_intersects(p0,p1,p2,p3)==true){
            return true;
        }
    }
    return false;
}

function cd_point_it(event, cd_canvas, cd_complete, cd_perimeter, cd_perimeter_scaled, actualImageWidth, actualImageHeight, DVPMode) 
{
    if(cd_complete)
	{
        toast('Polygon already created');
		return [false, [], [], true];
    }
    var rect, x, y;

    if(event.ctrlKey || event.which === 3 || event.button === 2)
	{
        if(cd_perimeter.length<3){
            alert('You need at least three points for a polygon');
            return [false, [], [], true];
        }
        x = cd_perimeter[0]['x'];
        y = cd_perimeter[0]['y'];
        if(cd_check_intersect(x,y, cd_perimeter))
		{
            toast('The line you are drawing intersects another line');
            return [false, [], [], true];
        }
        cd_draw(true, cd_ctx, cd_perimeter, cd_complete);
        alert('Polygon closed');
		event.preventDefault();

        return [true, cd_perimeter, cd_perimeter_scaled, false];
    } else 
	{
        rect = cd_canvas.getBoundingClientRect();
        x = event.clientX - rect.left;
        y = event.clientY - rect.top;
		
		var rect = cd_canvas.getBoundingClientRect();
		if (DVPMode == 1)
		{
			corrected_x = x - ((rect.width - actualImageWidth)/2);
			corrected_y = y - ((rect.height - actualImageHeight)/2);
		}
        if (DVPMode == 2)
        {
            corrected_x = x;
            corrected_y = y - ((rect.height - actualImageHeight)/2);
        }
		
        if (cd_perimeter.length>0 && x == cd_perimeter[cd_perimeter.length-1]['x'] && y == cd_perimeter[cd_perimeter.length-1]['y']){
            return [false, [], [], false];
        }
        if(cd_check_intersect(x,y, cd_perimeter))
		{
            toast('The line you are drawing intersects another line');
            return [false, [], [], true];
        }
        cd_perimeter.push({'x':x,'y':y});
		cd_perimeter_scaled.push({'x':(corrected_x/actualImageWidth),'y':(corrected_y/actualImageHeight)});
        cd_draw(false, cd_ctx, cd_perimeter, cd_complete);
        return [false, cd_perimeter, cd_perimeter_scaled, false];
    }
}