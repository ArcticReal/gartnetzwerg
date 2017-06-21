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

function isInt(value) {
  return !isNaN(value) && 
         parseInt(Number(value)) == value && 
         !isNaN(parseInt(value, 10));
}

function new_plant_submit(free_su){
	var errors = new Array();

	var name = document.forms["new_plant"]["plantname"].value;
	var n_name = name.search(/^[^\\'"]{2,}$/); //(/^[A-Za-z0-9 ]{3,20}$/);
	if(name == ""){
		errors.push("Der Pflanzenname deiner Pflanze darf nicht leer sein.");
	} else if(n_name == -1){
		errors.push("Der Pflanzenname muss mindestens 2 Zeichen lang sein und darf keine ',\" und \\ enthalten.");
	}

	if(document.forms["new_plant"]["scientific_name"].value == -1){
		errors.push("Die Art deiner Pflanze darf nicht leer sein.");
	} 

	var ort = document.forms["new_plant"]["standort"].value;
	var n_ort = ort.search(/^[^\\'"]{2,}$/); //(/^[A-Za-z0-9 ]{3,20}$/);
	if(ort == ""){
		errors.push("Der Standort deiner Pflanze darf nicht leer sein.");
	} else if(n_ort == -1){
		errors.push("Der Standort muss mindestens 2 Zeichen lang sein und darf keine ',\" und \\ enthalten.");
	}

	if(free_su > 0){
		if((document.forms["new_plant"]["sensorunit_name"].value == "" ||
			document.forms["new_plant"]["sensorunit_mac"].value == "") && 
			document.forms["new_plant"]["sensorunit"].value < 0){
			//000
			errors.push("Keine Sensoreinheit ausgewählt.");
		} else if((document.forms["new_plant"]["sensorunit_name"].value != "" ||
			document.forms["new_plant"]["sensorunit_mac"].value != "") && 
			document.forms["new_plant"]["sensorunit"].value >= 0){
			//111
			errors.push("Keine eindeutige Sensoreinheit verknüpft.");
		} else if((document.forms["new_plant"]["sensorunit_name"].value != "" ||
			document.forms["new_plant"]["sensorunit_mac"].value != "") && 
			document.forms["new_plant"]["sensorunit"].value < 0){
			//011 - okay!

			var name = document.forms["new_plant"]["sensorunit_name"].value;
			var n_name = name.search(/^[^\\'"]{2,}$/);
			if(n_name == -1){
				errors.push("Der Sensorname muss mindestens 2 Zeichen lang sein und darf keine ',\" und \\ enthalten.");
			}

			var mac = document.forms["new_plant"]["sensorunit_mac"].value;
			var n_mac = mac.search(/^([\da-f]{2}\:){5}[\da-f]{2}$/ig);
			if(n_mac == -1){
				errors.push("Ungültige MAC-Adresse. (Format: XX:XX:XX:XX:XX:XX)");
			}
		} else if((document.forms["new_plant"]["sensorunit_name"].value == "" ||
			document.forms["new_plant"]["sensorunit_mac"].value == "") && 
			document.forms["new_plant"]["sensorunit"].value >= 0){
			//100 - okay!
		} else if(((document.forms["new_plant"]["sensorunit_name"].value == "" ||
			document.forms["new_plant"]["sensorunit_mac"].value != "") && 
			document.forms["new_plant"]["sensorunit"].value >= 0)||
			((document.forms["new_plant"]["sensorunit_name"].value != "" ||
			document.forms["new_plant"]["sensorunit_mac"].value == "") && 
			document.forms["new_plant"]["sensorunit"].value >= 0)){
			//101
			//110
			errors.push("Keine eindeutige Sensoreinheit verknüpft.");
		} else if(((document.forms["new_plant"]["sensorunit_name"].value == "" ||
			document.forms["new_plant"]["sensorunit_mac"].value != "") && 
			document.forms["new_plant"]["sensorunit"].value < 0)||
			((document.forms["new_plant"]["sensorunit_name"].value != "" ||
			document.forms["new_plant"]["sensorunit_mac"].value == "") && 
			document.forms["new_plant"]["sensorunit"].value < 0)){
			//001
			//010
			errors.push("Keine Sensoreinheit verknüpft.");
		} else {
			errors.push("Undefinierter Fehler.");
		}
	} else {
		if(document.forms["new_plant"]["sensorunit_name"].value == "" &&
			document.forms["new_plant"]["sensorunit_mac"].value == ""){
			//00
			errors.push("Keine Sensoreinheit eingefügt.");
		} else if(document.forms["new_plant"]["sensorunit_name"].value != "" &&
			document.forms["new_plant"]["sensorunit_mac"].value != ""){
			//11 - okay!

			var name = document.forms["new_plant"]["sensorunit_name"].value;
			var n_name = name.search(/^[^\\'"]{2,}$/);
			if(n_name == -1){
				errors.push("Der Sensorname muss mindestens 2 Zeichen lang sein und darf keine ',\" und \\ enthalten.");
			}

			var mac = document.forms["new_plant"]["sensorunit_mac"].value;
			var n_mac = mac.search(/^([\da-f]{2}\:){5}[\da-f]{2}$/ig);
			if(n_mac == -1){
				errors.push("Ungültige MAC-Adresse. (Format: XX:XX:XX:XX:XX:XX)");
			}
		} else if(document.forms["new_plant"]["sensorunit_name"].value == "" &&
			document.forms["new_plant"]["sensorunit_mac"].value != ""){
			//01
			errors.push("Sensorname darf nicht leer sein.");
		} else if(document.forms["new_plant"]["sensorunit_name"].value != "" &&
			document.forms["new_plant"]["sensorunit_mac"].value == ""){
			//10
			errors.push("MAC-Adresse darf nicht leer sein.");
		} else {
			errors.push("Undefinierter Fehler.");
		}
	}

	if (errors.length > 0) {
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "<div><i class='fa fa-times-circle fa-3x'></i></div> <strong>Etwas stimmt nicht ganz...</strong> <br/>";
		document.getElementById("alert").innerHTML += "<ul>";
		for (var i = 0; i < errors.length; i++) {
			document.getElementById("alert").innerHTML += "<li>" + errors[i] + "</li>";
		}
		document.getElementById("alert").innerHTML += "</ul>";
	} else {
		document.getElementById("new").submit();
	}
}

var delete_counter = 0;
function delete_plant_submit(){
	if(delete_counter==0){
		document.getElementById("delete_button").value = "Bist du dir sicher?";
		delete_counter++;
	} else if(delete_counter==1){
		document.getElementById("delete_button").value = "Bist du dir wirklich sicher?";
		delete_counter++;
	} else if(delete_counter==2)
		document.getElementById("delete_plant").submit();
}

function delete_sensor_unit_submit(){
	if(document.forms["delete_su"]["sensorunit"].value == -1){
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "Keine Sensoreinheit zum Löschen ausgewählt.";
	} else {
		document.getElementById("delete_su").submit();
	}
}

function settings_submit(){
	var errors = new Array();

	var email = document.forms["settings"]["email"].value;
	var n = email.search(/^(([^<>()\[\]\\.,;:\s@"']+(\.[^<>()\[\]\\.,;:\s@"']+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
	
	var owm_location = document.forms["settings"]["wohnort"].value;
	var n2 = owm_location.search(/^[^\\'"]{2,}$/);

	var owm_key = document.forms["settings"]["owm_key"].value;
	var n3 = owm_key.search(/^[^\\'"]{2,}$/);

	if(email!="" && n == -1){
		errors.push("Ungültige Email.");
	} else if(owm_location!="" && n2 == -1){
		errors.push("Der Standort darf kein ',\" oder \ enthalten.");
	} else if(owm_key!="" && n3 == -1){
		errors.push("Der OpenWeatherMap-Key darf kein ',\" oder \ enthalten.");
	} else {
		document.getElementById("settings").submit();
	}
}

function flowersettings_submit(){
	var errors = new Array();

	var name = document.forms["flowersettings"]["plantname"].value;
	var n_name = name.search(/^.{2,}$/); //(/^[A-Za-z0-9 ]{3,20}$/);
	if(name!="" && n_name == -1){
		errors.push("Der Pflanzenname muss mindestens 2 Zeichen lang sein.");
	}

	if (errors.length > 0) {
		document.getElementById("alert").className = "";
		document.getElementById("alert").innerHTML = "<i class='fa fa-times-circle fa-3x'></i> <strong>Etwas stimmt nicht ganz...</strong><br/><ul>";
		for (var i = 0; i < errors.length; i++) {
			document.getElementById("alert").innerHTML += "<li>" + errors[i] + "</li>";
		}
		document.getElementById("alert").innerHTML += "</ul>";
	} else {
		document.getElementById("flowersettings").submit();
	}
}

function status_submit(i){
	switch(i){
		case 0: document.getElementById("b1").submit(); break;
		case 1: document.getElementById("b2").submit(); break;
		case 2: document.getElementById("b3").submit(); break;
		case 3: document.getElementById("b4").submit(); break;
		default:;
	}
}

function vacation_submit(i){
	if(i==1){
		if(document.forms["vacation"]["start_date"].value == ""){
			document.getElementById("alert").className = "";
			document.getElementById("alert").innerHTML = "Das Start-Datum darf nicht leer sein.";
		} else if(document.forms["vacation"]["end_date"].value == ""){
			document.getElementById("alert").className = "";
			document.getElementById("alert").innerHTML = "Das End-Datum darf nicht leer sein.";
		} else {
			document.getElementById("vacation").submit();
		}
	} else if(i==0){
		document.getElementById("vacation").submit();
	} else {

	}
}

//# DIAGRAMM-FUNKTIONEN ########################################################################################

var day_factors = [
	{v: 7, t: "Werte der letzten 7 Tage"},
	{v:14, t:"Werte der letzten 2 Wochen"},
	{v:21, t:"Werte der letzten 3 Wochen"},
	{v:31, t:"Werte des letzten Monat"},
	{v:62, t:"Werte der letzten 2 Monate"},
	{v:92, t:"Werte des letzten Quartal"},
	{v:184, t:"Werte der letzten 6 Monate"},
	{v:365, t:"Werte des letzten Jahres"}];
var current_factor = 0;

var c = new Array();
var diagramm = new Array();

var w = window.innerWidth
	|| document.documentElement.clientWidth
	|| document.body.clientWidth;
w -= 48;

function init_diagrams(){
	for (var i = 1; i <= 6; i++){
		c.push(document.getElementById("canvas"+i));
		c[i-1].style.width = "calc(100% - 2px)";
		c[i-1].width = w;
	}

	diagramm = [
		//	canvas-object		   sensor-data		  plant-min-max   array-positions     min-max-values
		{c: c[0].getContext("2d"), data: new Array(), dates: new Array(), min: 0, max: 0, 
		d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
		{c: c[1].getContext("2d"), data: new Array(), dates: new Array(), min: 0, max: 0, 
		d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
		{c: c[2].getContext("2d"), data: new Array(), dates: new Array(), min: 0, max: 0, 
		d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
		{c: c[3].getContext("2d"), data: new Array(), dates: new Array(), min: 0, max: 0, 
		d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
		{c: c[4].getContext("2d"), data: new Array(), dates: new Array(), min: 0, max: 0, 
		d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1},
		{c: c[5].getContext("2d"), data: new Array(), dates: new Array(), min: 0, max: 0, 
		d_min:-1, d_max:-1, min_v: -1, max_v: -1, degreesize: 0, zero: -1}];

}

function init_canvas(cc, height, width, days){
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
		//document.getElementById("diadebug").innerHTML += diagramm[cc].data[i];
		if(diagramm[cc].data[i]!=0){
			if(days == 365){
				diagramm[cc].c.fillRect((diagramm[cc].data.length-1-i)*((width-21)/days)+20+((diagramm[cc].data.length-1-i)*1),
				5+(diagramm[cc].max_v*diagramm[cc].degreesize)-(diagramm[cc].data[i]*diagramm[cc].degreesize),
				(width-20)/days,
				diagramm[cc].data[i]*diagramm[cc].degreesize);
			} else if(days > 75){
				diagramm[cc].c.fillRect((diagramm[cc].data.length-1-i)*((width-23)/days)+20+((diagramm[cc].data.length-1-i)*3),
				5+(diagramm[cc].max_v*diagramm[cc].degreesize)-(diagramm[cc].data[i]*diagramm[cc].degreesize),
				(width-20)/days,
				diagramm[cc].data[i]*diagramm[cc].degreesize);
			} else {
				diagramm[cc].c.fillRect((diagramm[cc].data.length-1-i)*((width-25)/days)+20+((diagramm[cc].data.length-1-i)*5),
				5+(diagramm[cc].max_v*diagramm[cc].degreesize)-(diagramm[cc].data[i]*diagramm[cc].degreesize),
				(width-20)/days,
				diagramm[cc].data[i]*diagramm[cc].degreesize);
			}
		}
	}
	//document.getElementById("diadebug").innerHTML += "<br/>";

	for (var i = 0; i < diagramm[cc].data.length; i++) {
		if(days == 7){
			//datum text
			diagramm[cc].c.font = '10pt Helvetica';
			diagramm[cc].c.fillStyle = 'black';
			var date_text = diagramm[cc].dates[i];
			var w_d = diagramm[cc].c.measureText(date_text).width;
			if(w_d >= ((width-20)/days)){
				var str = diagramm[cc].dates[i];
				date_text = str.substring(5,11);
			}
			diagramm[cc].c.fillText(date_text, (diagramm[cc].data.length-1-i)*((width-20)/days)+20+(w_d/Math.pow(2,(3-current_factor))), height - 5);
		} else {
			if(i % (days/7) == 0){
				//datum text
				diagramm[cc].c.font = '10pt Helvetica';
				diagramm[cc].c.fillStyle = 'black';
				var str = diagramm[cc].dates[i];
				var date_text = str.substring(5,11);
				var w_d = diagramm[cc].c.measureText(date_text).width;
				diagramm[cc].c.fillText(date_text, (diagramm[cc].data.length-1-i)*((width-20)/days)+20+(w_d/8), height - 5);
			}
		}
	}

	// more Degrees
	diagramm[cc].c.font = '10pt Helvetica';
	diagramm[cc].c.fillStyle = '#444';
	var text_m = 0;
	var w_m = 0;

	var amount = 0;

	if((diagramm[cc].max_v - diagramm[cc].min_v) <= 10){
		amount = 1;
	}

	if(((diagramm[cc].max_v - diagramm[cc].min_v)/2) <= 10){
		amount = 2;
	}

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

function init_diagramm(canvas,days){
	//document.getElementById("diadebug").innerHTML = w;

	update_drawing_borders(canvas);
	update_degree_size(canvas,300);
	init_canvas(canvas,300,w, days);
}

function add_data(array,dates,data){
	diagramm[array].dates.push(dates);
	diagramm[array].data.push(data);
}

function set_min_max(array,min_data,max_data){
	diagramm[array].min = min_data;
	diagramm[array].max = max_data;
}

function update_degree_size(array,height){
	diagramm[array].degreesize =(height-20)/(diagramm[array].max_v - diagramm[array].min_v);

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
													"max: "+diagramm[array]y.max;*/
}

function change_days(days){
	if(days == 0){
		current_factor = 0;
		for (var i = 0; i < diagramm.length-1; i++)
			init_canvas(i,300,w,day_factors[current_factor].v);
		document.getElementById("dayfactor").innerHTML = day_factors[current_factor].t;
	} else if(days == 1 && current_factor+1 <= day_factors.length-1){
		current_factor += 1;
		for (var i = 0; i < diagramm.length-1; i++)
			init_canvas(i,300,w,day_factors[current_factor].v);
		document.getElementById("dayfactor").innerHTML = day_factors[current_factor].t;
	} else if(days == -1 && current_factor-1 >= 0){
		current_factor -= 1;
		for (var i = 0; i < diagramm.length-1; i++)
			init_canvas(i,300,w,day_factors[current_factor].v);
		document.getElementById("dayfactor").innerHTML = day_factors[current_factor].t;
	} else if(days > 1){
		for (var i = 0; i < diagramm.length-1; i++)
			init_canvas(i,300,w, days);
		document.getElementById("dayfactor").innerHTML = "Werte der letzten "+days+" Tage";
	}
}

function smiley(i){
	if(i == 0){
		document.getElementById("empty_flower_list").innerHTML = "<span><i class='fa fa-3x fa-meh-o'></i><p id='trigger'>Hier ist es ganz schön leer.<br/><small><i>Erste Pflanze einfügen</i></small></p></span>";
	} else {
		document.getElementById("empty_flower_list").innerHTML = "<span><i class='fa fa-3x fa-smile-o'></i><p id='trigger'>Hier ist es ganz schön leer.<br/><small><i>Erste Pflanze einfügen</i></small></p></span>";
	}
}