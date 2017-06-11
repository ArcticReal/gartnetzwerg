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

var c=[
	document.getElementById("canvas1"),
	document.getElementById("canvas2"),
	document.getElementById("canvas3"),
	document.getElementById("canvas4"),
	document.getElementById("canvas5"),
	document.getElementById("canvas6"),
	document.getElementById("canvas7")
];

var diagramm = [
	//	canvas-object		   sensor-data		  plant-min-max   array-positions     min-max-values
	{c: c[0].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
	{c: c[1].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
	{c: c[2].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
	{c: c[3].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
	{c: c[4].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
	{c: c[5].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
	{c: c[6].getContext("2d"), data: new Array(), min: 0, max: 0, 
	d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1}];

function init_canvas(cc, width, height, days){
	// Create gradient
	var grd = diagramm[cc].c.createLinearGradient(0,0,0,250);
	grd.addColorStop(0,"#FFFFFF");
	grd.addColorStop(1,"#FFFFFF");

	// Fill with gradient
	diagramm[cc].c.fillStyle = grd;
	diagramm[cc].c.fillRect(0,0,width,height);

	//balken color
	var color = diagramm[cc].c.createLinearGradient(0,0,0,250);
	color.addColorStop(0,"#00FF00");
	color.addColorStop(1,"#00FF00");
	diagramm[cc].c.fillStyle = color;

	for (var i = 0; i < diagramm[cc].data.length; i++) {
		if(diagramm[cc].data[i]!=0){
			diagramm[cc].c.fillRect(20+(i*(width-20)/days),
								5+(diagramm[cc].max_v*diagramm[cc].degreesize)-(diagramm[cc].data[i]*diagramm[cc].degreesize),
								(width-20)/days,
								diagramm[cc].data[i]*diagramm[cc].degreesize);
		}
	}

	// more Degrees
	diagramm[cc].c.font = '10pt Helvetica';
	diagramm[cc].c.fillStyle = '#444';
	var text_m = 0;
	var w_m = 0;

	var amount = 0;
	for (var i = 5; i < (diagramm[cc].max_v - diagramm[cc].min_v); i+=5){
		if(((diagramm[cc].max_v - diagramm[cc].min_v)/i) <= 10){
			amount = i; break;
		}
	}

	for (var i = diagramm[cc].min_v; i < diagramm[cc].max_v; i++){
		if(i % amount == 0 && i!=0 && i!=diagramm[cc].min && i!=diagramm[cc].max){
			text_m = i;
			w_m = diagramm[cc].c.measureText(text_m).width;
			diagramm[cc].c.fillText(text_m, 5, 10+((diagramm[cc].max_v-i)*diagramm[cc].degreesize));

			diagramm[cc].c.beginPath();
			diagramm[cc].c.moveTo(w_m+10,	5+((diagramm[cc].max_v-i)*diagramm[cc].degreesize));
			diagramm[cc].c.lineTo(width,	5+((diagramm[cc].max_v-i)*diagramm[cc].degreesize));
			diagramm[cc].c.strokeStyle = "#444";
			diagramm[cc].c.stroke();
			diagramm[cc].c.closePath();
		}
	}

	// zero Degree
	diagramm[cc].c.font = '10pt Helvetica';
	diagramm[cc].c.fillStyle = 'black';
	var text_z = 0;
	var w_z = diagramm[cc].c.measureText(text_z).width;
	diagramm[cc].c.fillText(text_z, 5, 10+((diagramm[cc].max_v-0)*diagramm[cc].degreesize));

	diagramm[cc].c.beginPath();
	diagramm[cc].c.moveTo(w_z+10,	5+((diagramm[cc].max_v-0)*diagramm[cc].degreesize));
	diagramm[cc].c.lineTo(width,	5+((diagramm[cc].max_v-0)*diagramm[cc].degreesize));
	diagramm[cc].c.strokeStyle = "black";
	diagramm[cc].c.stroke();
	diagramm[cc].c.closePath();

	// min Degree
	if(diagramm[cc].min != 0){
		diagramm[cc].c.font = '10pt Helvetica';
		diagramm[cc].c.fillStyle = 'blue';
		var text_min = diagramm[cc].min;
		var w_min = diagramm[cc].c.measureText(text_min).width;
		diagramm[cc].c.fillText(text_min, 5, 10+((diagramm[cc].max_v-diagramm[cc].min)*diagramm[cc].degreesize));

		diagramm[cc].c.beginPath();
		diagramm[cc].c.moveTo(w_min+10,	5+((diagramm[cc].max_v-diagramm[cc].min)*diagramm[cc].degreesize));
		diagramm[cc].c.lineTo(width,	5+((diagramm[cc].max_v-diagramm[cc].min)*diagramm[cc].degreesize));
		diagramm[cc].c.strokeStyle = "blue";
		diagramm[cc].c.stroke();
		diagramm[cc].c.closePath();
	}

	// max Degree
	if(diagramm[cc].max != 0 && diagramm[cc].max != diagramm[cc].min){
		diagramm[cc].c.font = '10pt Helvetica';
		diagramm[cc].c.fillStyle = 'red';
		var text_max = diagramm[cc].max;
		var w_max = diagramm[cc].c.measureText(text_max).width;
		diagramm[cc].c.fillText(text_max, 5, 10+((diagramm[cc].max_v-diagramm[cc].max)*diagramm[cc].degreesize));

		diagramm[cc].c.beginPath();
		diagramm[cc].c.moveTo(w_max+10,	5+((diagramm[cc].max_v-diagramm[cc].max)*diagramm[cc].degreesize));
		diagramm[cc].c.lineTo(width,	5+((diagramm[cc].max_v-diagramm[cc].max)*diagramm[cc].degreesize));
		diagramm[cc].c.strokeStyle = "red";
		diagramm[cc].c.stroke();
		diagramm[cc].c.closePath();
	}

	//document.getElementById("diadebug").innerHTML += "<br/> Degree-Size: "+diagramm[cc].degreesize;
}

function init_diagramms(days){
	for (var i = 0; i < diagramm.length; i++) {
		update_drawing_borders(i);
		update_degree_size(i,200);
		init_canvas(i,500,200, days);
	}
}

function add_data(array,data){
	diagramm[array].data.push(data);
}

function set_min_max(array,min_data,max_data){
	diagramm[array].min = min_data;
	diagramm[array].max = max_data;
}

function update_degree_size(array,height){
	diagramm[array].degreesize =(height-10)/(diagramm[array].max_v - diagramm[array].min_v);

	//get the pixels for zero
	if(diagramm[array].min_v == 0){
		diagramm[array].zero = 5;
	}
	diagramm[array].zero = diagramm[array].max_v / diagramm[array].degreesize;
	//document.getElementById("diadebug").innerHTML += "zero*dsize: "+diagramm[array].zero*diagramm[array].degreesize;
}

function update_drawing_borders(array){
	var max_value = diagramm[array].max;
	var min_value = 0;

	if(diagramm[array].d_min > diagramm[array].min){
		min_value = diagramm[array].min;
		diagramm[array].d_min = diagramm[array].min;
	}

	for (var i = 0; i < diagramm[array].data.length; i++) {
		if(diagramm[array].data[i] > max_value){
			max_value = diagramm[array].data[i];
			diagramm[array].d_max = i;
		}
	}

	for (var i = 0; i < diagramm[array].data.length; i++) {
		if(diagramm[array].data[i] < min_value){
			min_value = diagramm[array].data[i];
			diagramm[array].d_min = i;
		}
	}

	diagramm[array].max_v = max_value;
	diagramm[array].min_v = min_value;

	/*document.getElementById("diadebug").innerHTML = "min_v: "+diagramm[array].min_v+"<br/>"+
													"max_v: "+diagramm[array].max_v+"<br/>"+
													"d_min: "+diagramm[array].d_min+"<br/>"+
													"d_max: "+diagramm[array].d_max+"<br/>"+
													"min: "+diagramm[array].min+"<br/>"+
													"max: "+diagramm[array].max;*/
}