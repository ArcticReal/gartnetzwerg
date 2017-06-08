function init_settings_page(){
	document.getElementById("alert").className = "alert-none";
	document.getElementById("alert").innerHTML = "";
	toggle();
}

var su = false;
var state = 0; //status site tab

function state_tabs(i){
	if(i>=0 && i<=3)
		state = i;
	else{
		var currentUrl = document.URL;
		var urlParts   = currentUrl.split('#');
		var string = (urlParts.length > 1) ? urlParts[1] : 0;
		switch(string){
			case "state": state = 0; break;
			case "diagramms": state = 1; break;
			case "cam": state = 2; break;
			case "info": state = 3; break;
			default: state = 0;
		}
	}
	
	document.getElementById("tab_status").className = "";
	document.getElementById("tab_diagramme").className = "";
	document.getElementById("tab_cam").className = "";
	document.getElementById("tab_info").className = "";

	document.getElementById("status").className = "item";
	document.getElementById("diagramme").className = "item";
	document.getElementById("cam").className = "item";
	document.getElementById("info").className = "item";

	switch(state){
		case 0:
			document.getElementById("tab_status").className = "current_tab";
			document.getElementById("status").className += " current_tab";
			break;
		case 1: 
			document.getElementById("tab_diagramme").className = "current_tab";
			document.getElementById("diagramme").className += " current_tab";
			init_diagramms();
			break;
		case 2:
			document.getElementById("tab_cam").className = "current_tab";
			document.getElementById("cam").className += " current_tab";
			break;
		case 3:
			document.getElementById("tab_info").className = "current_tab";
			document.getElementById("info").className += " current_tab";
			break;
		default:;
	}
}

function new_plant_submit(){
	if(document.forms["new_plant"]["plantname"].value == ""){
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "Der Pflanzenname deiner Pflanze darf nicht leer sein.";
	} else if(document.forms["new_plant"]["standort"].value == ""){
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "Der Standort deiner Pflanze darf nicht leer sein.";
	} else {
		document.getElementById("new").submit();
	}
}

function settings_submit(){
	document.getElementById("settings").submit();
}

function toggle(){
	if(su == false){
		document.getElementById("availSU").className = "invisible";
		document.getElementById("newSU").className = "";
		su = true;
	} else {
		document.getElementById("availSU").className = "";
		document.getElementById("newSU").className = "invisible";
		su = false;
	}
}


//# DIAGRAMM-FUNKTIONEN ########################################################################################

var zfactor = 1;

var c=[
	document.getElementById("canvas1"),
	document.getElementById("canvas2"),
	document.getElementById("canvas3"),
	document.getElementById("canvas4")
];

var diagramm = [
	{c: c[0].getContext("2d"), data: new Array(), min: 0, max: 0, drawing_min:0, drawing_max:0},
	{c: c[1].getContext("2d"), data: new Array(), min: 0, max: 0, drawing_min:0, drawing_max:0},
	{c: c[2].getContext("2d"), data: new Array(), min: 0, max: 0, drawing_min:0, drawing_max:0},
	{c: c[3].getContext("2d"), data: new Array(), min: 0, max: 0, drawing_min:0, drawing_max:0}];

/*htmlcanvas is the "this" object in the HTML part. 
* cc is the id of one of the arrayobject above.
*/
function init_canvas(cc, width, height, days){
	// Create gradient
	var grd = diagramm[cc].c.createLinearGradient(0,0,0,250);
	grd.addColorStop(0,"#FFFFFF");
	grd.addColorStop(1,"#DDDDDD");

	// Fill with gradient
	diagramm[cc].c.fillStyle = grd;
	diagramm[cc].c.fillRect(0,0,width,height);

	diagramm[cc].c.beginPath();

	//balken color
	var color = diagramm[cc].c.createLinearGradient(0,0,0,250);
	color.addColorStop(0,"#00FF00");
	color.addColorStop(1,"#00FF00");
	diagramm[cc].c.fillStyle = color;

	for (var i = 0; i < diagramm[cc].data.length; i++) {
		//c[cc].moveTo(i*10,100-canvas_data[cc][i]);
		//c[cc].lineTo((i+1)*10,100-canvas_data[cc][i+1]);
		diagramm[cc].c.fillRect(2+(i*(width/days)),150-diagramm[cc].data[i],10,diagramm[cc].data[i]);
	}

	// 0 Degree
	diagramm[cc].c.moveTo(0,150);
	diagramm[cc].c.lineTo(500,150);
	diagramm[cc].c.strokeStyle = "#0000FF";
	diagramm[cc].c.stroke();

	// min Degree
	diagramm[cc].c.moveTo(0,200-diagramm[cc].drawing_min-diagramm[cc].min);
	diagramm[cc].c.lineTo(500,200-diagramm[cc].drawing_min-diagramm[cc].min);
	diagramm[cc].c.strokeStyle = "#000000";
	diagramm[cc].c.stroke();

	diagramm[cc].c.moveTo(0,200-diagramm[cc].drawing_max-diagramm[cc].max);
	diagramm[cc].c.lineTo(500,200-diagramm[cc].drawing_max-diagramm[cc].max);
	diagramm[cc].c.strokeStyle = "#FF0000";
	diagramm[cc].c.stroke();
}

function init_diagramms(days){
	//changeZoom(0);
	update_drawing_borders(0);
	//update_drawing_borders(1);
	//update_drawing_borders(2);
	//update_drawing_borders(3);
	init_canvas(0,500,200, days);
	//init_canvas(1,500,200, days);
	//init_canvas(2,500,200, days);
	//init_canvas(3,500,200, days);
	document.getElementById("diadebug").innerHTML = days;
}

function add_data(array,data){
	diagramm[array].data.push(data);
}

function set_min_max(array,min_data,max_data){
	diagramm[array].min = min_data;
	diagramm[array].max = max_data;
}

function update_drawing_borders(array){
	diagramm[array].drawing_max = diagramm[array].max;
	diagramm[array].drawing_min = 0;

	if(diagramm[array].drawing_min > diagramm[array].min){
		diagramm[array].drawing_min = diagramm[array].min;
	}

	for (var i = 0; i < diagramm[array].data.length; i++) {
		if(diagramm[array].data[i] > diagramm[array].drawing_max){
			diagramm[array].drawing_max = diagramm[array].data[i];
		}
	}

	for (var i = 0; i < diagramm[array].data.length; i++) {
		if(diagramm[array].data[i] < diagramm[array].drawing_min){
			diagramm[array].drawing_min = diagramm[array].data[i];
		}
	}
}

function day_diff(date){
	var t = Date.parse(weight[0].date) - Date.parse(weight[date].date);
	var x = Math.floor(t/(1000*60*60*24));

	return Math.abs(x);
}

function min_weight(){
	var x = 100;
	for (var i = 0; i < weight.length; i++) {
		if(weight[i].weight <= x)
			x = weight[i].weight;
	}
	return x;
}

function max_weight(){
	var x = 0;
	for (var i = 0; i < weight.length; i++) {
		if(weight[i].weight >= x)
			x = weight[i].weight;
	}
	return x;
}

function max_weightID(){
	var x = 0;
	var ii = i;
	for (var i = 0; i < weight.length; i++) {
		if(weight[i].weight >= x){
			x = weight[i].weight;
			ii = i;
		}
	}
	return ii;
}

function min_weightID(){
	var x = 100;
	var ii = i;
	for (var i = 0; i < weight.length; i++) {
		if(weight[i].weight <= x){
			x = weight[i].weight;
			ii = i;
		}
	}
	return ii;
}

function zoom_factor(){
	var x = 0;
	for (var i = 0; i < 200; i++) {
		if((280 - max_weight()*i + min_weight()*i - 20)<=0){
			x = i-1;
			break;
		}
	}
	//document.getElementById("demo").innerHTML = "Zoomfactor "+x+".";
	return x;
}

function weight_zoom(d){
	x = weight[d].weight;
	return 280 - x*zoom_factor() + min_weight()*zoom_factor() - 10;
}

function markMaxWeight(zoom){
	//cc2.moveTo(0,
	//	280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);
	//cc2.lineTo(430,
	//	280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);

	cc.font = '10pt Calibri';
	cc.fillStyle = 'red';
	var text = max_weight()+" kg";
	var w = cc.measureText(text).width;
	cc.fillText(text, day_diff(max_weightID())*10*zoom-(w/2), 
		280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);
}

function markMinWeight(zoom){
	//cc2.moveTo(0,
	//	280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);
	//cc2.lineTo(430,
	//	280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);

	cc.font = '10pt Calibri';
	cc.fillStyle = 'green';
	var text = min_weight()+" kg";
	var w = cc.measureText(text).width;
	cc.fillText(text, day_diff(min_weightID())*10*zoom-(w/2), 
		280 - min_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);
}

function markCurrentWeight(zoom){
	//cc2.moveTo(0,
	//	280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);
	//cc2.lineTo(430,
	//	280 - max_weight()*zoom_factor() + min_weight()*zoom_factor() - 10);

	cc.font = '10pt Calibri';
	cc.fillStyle = 'blue';
	var text = weight[weight.length-1].weight +" kg";
	var w = cc.measureText(text).width;
	cc.fillText(text, day_diff(weight.length-1)*10*zoom, 
		280 - weight[weight.length-1].weight*zoom_factor() + min_weight()*zoom_factor() - 10);
}

function changeZoom(x){
	cc.clearRect(0, 0, c.width, c.height);

	if(x > 0){
		zfactor *= 1.5;
	} else if(x < 0){
		zfactor /= 1.5;
	} else if(x == 0){
		zfactor = 0.4;
	}

	canvas(zfactor);
	markCurrentWeight(zfactor);
	if(weight[weight.length-1].weight != max_weight())
		markMaxWeight(zfactor);
	if(weight[weight.length-1].weight != min_weight())
		markMinWeight(zfactor);
}