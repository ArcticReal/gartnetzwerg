function init_new_plant_page(){
	document.getElementById("alert").className = "alert-none";
	document.getElementById("alert").innerHTML = "";
	toggle();
}

function init_settings_page(){
	document.getElementById("alert").className = "alert-none";
	document.getElementById("alert").innerHTML = "";
	toggle();
}

var su = false;
var state = 0; //status site tab

function state_tabs(i){
	state = i;

	document.getElementById("tab_status").className = "";
	document.getElementById("tab_diagramme").className = "";
	document.getElementById("tab_webcam").className = "";
	document.getElementById("tab_info").className = "";

	switch(state){
		case 0: document.getElementById("tab_status").className = "current_tab"; break;
		case 1: 
			document.getElementById("tab_diagramme").className = "current_tab"; 
			init_diagramms();
			break;
		case 2: document.getElementById("tab_webcam").className = "current_tab"; break;
		case 3: document.getElementById("tab_info").className = "current_tab"; break;
		default:;
	}
}

function new_plant_submit(){
	if(document.forms["new_plant"]["plantname"].value == ""){
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "Der Nickname deiner Pflanze darf nicht leer sein.";
	} else if(document.forms["new_plant"]["standort"].value == ""){
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "Der Standort deiner Pflanze darf nicht leer sein.";
	} else {
		document.getElementById("new").submit();
	}
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
var c = document.getElementById("canvas");
var cc = c.getContext("2d");

function init_diagramms(){
	changeZoom(0);
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

function canvas(zoom){
	// Create gradient
	var grd = cc.createLinearGradient(0,0,0,250);
	grd.addColorStop(0,"#fff");
	grd.addColorStop(1,"#d2ebf9");

	// Fill with gradient
	cc.fillStyle = grd;
	cc.fillRect(0,0,448,280);

	cc.beginPath();

	for (var i = 0; i < weight.length-1; i++) {
		cc.moveTo(day_diff(i)*10*zoom,weight_zoom(i));
		cc.lineTo(day_diff(i+1)*10*zoom,weight_zoom(i+1));
	}

	cc.strokeStyle = "#6698FF";
	cc.stroke();
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

var weight = [
	{weight: 78.3, date: new Date("2017-01-01")},
	{weight: 77.3, date: new Date("2017-01-02")},
	{weight: 77.7, date: new Date("2017-01-03")},
	{weight: 78.2, date: new Date("2017-01-04")},
	{weight: 78.6, date: new Date("2017-01-05")},
	{weight: 78.3, date: new Date("2017-01-06")},
	{weight: 78.2, date: new Date("2017-01-07")},
	{weight: 76.8, date: new Date("2017-01-08")},
	{weight: 77.6, date: new Date("2017-01-09")},
	{weight: 77.3, date: new Date("2017-01-10")},
	{weight: 76.8, date: new Date("2017-01-11")},
	{weight: 77.25, date: new Date("2017-01-12")}, //estimated
	{weight: 77.7, date: new Date("2017-01-13")},
	{weight: 77.7, date: new Date("2017-01-14")},
	{weight: 77.8, date: new Date("2017-01-15")},
	{weight: 76.8, date: new Date("2017-01-16")},
	{weight: 77.7, date: new Date("2017-01-17")},
	{weight: 77.8, date: new Date("2017-01-18")},
	{weight: 78.0, date: new Date("2017-01-19")},
	{weight: 77.2, date: new Date("2017-01-20")},
	{weight: 77.2, date: new Date("2017-01-21")},
	{weight: 77.3, date: new Date("2017-01-22")},
	{weight: 77.4, date: new Date("2017-01-23")},
	{weight: 77.2, date: new Date("2017-01-24")},
	{weight: 77.8, date: new Date("2017-01-25")},
	{weight: 77.6, date: new Date("2017-01-26")},
	{weight: 77.7, date: new Date("2017-01-27")},
	{weight: 78.0, date: new Date("2017-01-28")},
	{weight: 77.7, date: new Date("2017-01-29")},
	{weight: 77.7, date: new Date("2017-01-30")},
	{weight: 77.7, date: new Date("2017-01-31")},

	{weight: 77.9, date: new Date("2017-02-01")},
	{weight: 77.93, date: new Date("2017-02-02")},
	{weight: 77.95, date: new Date("2017-02-03")},
	{weight: 77.8, date: new Date("2017-02-04")},
	{weight: 78.0, date: new Date("2017-02-05")},
	{weight: 77.7, date: new Date("2017-02-06")},
	{weight: 77.5, date: new Date("2017-02-07")},
	{weight: 77.2, date: new Date("2017-02-08")},
	{weight: 77.7, date: new Date("2017-02-09")},
	{weight: 78.2, date: new Date("2017-02-10")},
	{weight: 78.5, date: new Date("2017-02-11")},
	{weight: 78.2, date: new Date("2017-02-12")}, //estimated
	{weight: 78.2, date: new Date("2017-02-13")},
	{weight: 77.7, date: new Date("2017-02-14")},
	{weight: 78.0, date: new Date("2017-02-15")},
	{weight: 77.7, date: new Date("2017-02-16")},
	{weight: 77.7, date: new Date("2017-02-17")},
	{weight: 77.7, date: new Date("2017-02-18")},
	{weight: 77.7, date: new Date("2017-02-19")},
	{weight: 77.6, date: new Date("2017-02-20")},
	{weight: 76.7, date: new Date("2017-02-21")},
	{weight: 77.8, date: new Date("2017-02-22")},
	{weight: 77.6, date: new Date("2017-02-23")},
	{weight: 78.0, date: new Date("2017-02-24")},
	{weight: 77.9, date: new Date("2017-02-25")},
	{weight: 77.3, date: new Date("2017-02-26")},
	{weight: 77.2, date: new Date("2017-02-27")},
	{weight: 77.7, date: new Date("2017-02-28")},

	{weight: 77.7, date: new Date("2017-03-01")},
	{weight: 77.2, date: new Date("2017-03-02")}, //estimated
	{weight: 76.7, date: new Date("2017-03-03")},
	{weight: 76.8, date: new Date("2017-03-04")},
	{weight: 77.4, date: new Date("2017-03-05")},
	{weight: 77.1, date: new Date("2017-03-06")},
	{weight: 76.9, date: new Date("2017-03-07")},
	{weight: 77.4, date: new Date("2017-03-08")},
	{weight: 76.9, date: new Date("2017-03-09")},
	{weight: 77.0, date: new Date("2017-03-10")}, //estimated
	{weight: 77.1, date: new Date("2017-03-11")}, //estimated
	{weight: 77.2, date: new Date("2017-03-12")}, //estimated
	{weight: 77.3, date: new Date("2017-03-13")}, //estimated
	{weight: 77.4, date: new Date("2017-03-14")}, //estimated
	{weight: 77.5, date: new Date("2017-03-15")}, //estimated
	{weight: 77.6, date: new Date("2017-03-16")}, //estimated
	{weight: 77.7, date: new Date("2017-03-17")}, //estimated
	{weight: 77.8, date: new Date("2017-03-18")},
	{weight: 78.2, date: new Date("2017-03-19")},
	{weight: 78.125, date: new Date("2017-03-20")}, //estimated
	{weight: 78.05, date: new Date("2017-03-21")}, //estimated
	{weight: 77.975, date: new Date("2017-03-22")}, //estimated
	{weight: 77.9, date: new Date("2017-03-23")},
	{weight: 78.5, date: new Date("2017-03-24")},
	{weight: 77.6, date: new Date("2017-03-25")},
	{weight: 77.5, date: new Date("2017-03-26")}, //estimated
	{weight: 78.6, date: new Date("2017-03-27")}, //estimated
	{weight: 78.54, date: new Date("2017-03-28")}, //estimated
	{weight: 78.48, date: new Date("2017-03-29")}, //estimated
	{weight: 78.42, date: new Date("2017-03-30")}, //estimated
	{weight: 78.36, date: new Date("2017-03-31")}, //estimated

	{weight: 78.30, date: new Date("2017-04-01")}, //estimated
	{weight: 78.2, date: new Date("2017-04-02")},
	{weight: 77.3, date: new Date("2017-04-03")},
	{weight: 76.8, date: new Date("2017-04-04")},
	{weight: 77.2, date: new Date("2017-04-05")},
	{weight: 77.4, date: new Date("2017-04-06")},
	{weight: 77.7, date: new Date("2017-04-07")},
	{weight: 78.2, date: new Date("2017-04-08")},
	{weight: 78.4, date: new Date("2017-04-09")},
	{weight: 77.8, date: new Date("2017-04-10")},
	{weight: 76.8, date: new Date("2017-04-11")},
	{weight: 77.4, date: new Date("2017-04-12")},
	{weight: 77.4, date: new Date("2017-04-13")},
	{weight: 77.08, date: new Date("2017-04-14")}, //estimated
	{weight: 76.75, date: new Date("2017-04-15")}, //estimated
	{weight: 76.43, date: new Date("2017-04-16")}, //estimated
	{weight: 76.1, date: new Date("2017-04-17")},
	{weight: 75.96, date: new Date("2017-04-18")}, //estimated
	{weight: 75.83, date: new Date("2017-04-19")}, //estimated
	{weight: 75.7, date: new Date("2017-04-20")},
	{weight: 75.9, date: new Date("2017-04-21")},
	{weight: 76.1, date: new Date("2017-04-22")},
	{weight: 76.3, date: new Date("2017-04-23")},
	{weight: 76.5, date: new Date("2017-04-24")},
	{weight: 76.7, date: new Date("2017-04-25")},
	{weight: 75, date: new Date("2017-04-26")},
	{weight: 75, date: new Date("2017-04-27")},
	{weight: 75, date: new Date("2017-04-28")},
	{weight: 75, date: new Date("2017-04-29")},
	{weight: 75, date: new Date("2017-04-30")},

	{weight: 75, date: new Date("2017-05-01")}, //estimated
	{weight: 75, date: new Date("2017-05-02")},
	{weight: 77.6, date: new Date("2017-05-03")},
	{weight: 75, date: new Date("2017-05-04")},
	{weight: 75, date: new Date("2017-05-05")},
	{weight: 75, date: new Date("2017-05-06")},
	{weight: 75, date: new Date("2017-05-07")},
	{weight: 75, date: new Date("2017-05-08")},
	{weight: 75, date: new Date("2017-05-09")},
	{weight: 75, date: new Date("2017-05-10")},
	{weight: 75, date: new Date("2017-05-11")},
	{weight: 78.4, date: new Date("2017-05-12")},
	{weight: 75, date: new Date("2017-05-13")},
	{weight: 75, date: new Date("2017-05-14")}, //estimated
	{weight: 75, date: new Date("2017-05-15")}, //estimated
	{weight: 75, date: new Date("2017-05-16")}, //estimated
	{weight: 75, date: new Date("2017-05-17")},
	{weight: 75, date: new Date("2017-05-18")}, //estimated
	{weight: 75, date: new Date("2017-05-19")}, //estimated
	{weight: 75, date: new Date("2017-05-20")},
	{weight: 75, date: new Date("2017-05-21")},
	{weight: 75, date: new Date("2017-05-22")},
	{weight: 75, date: new Date("2017-05-23")},
	{weight: 77.8, date: new Date("2017-05-24")}
	/*{weight: 76.7, date: new Date("2017-05-25")},
	{weight: 60, date: new Date("2017-05-26")},
	{weight: 60, date: new Date("2017-05-27")},
	{weight: 60, date: new Date("2017-05-28")},
	{weight: 60, date: new Date("2017-05-29")},
	{weight: 60, date: new Date("2017-05-30")},
	{weight: 60, date: new Date("2017-05-31")}*/
	];