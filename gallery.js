var ele = document.getElementById("tab_cam");
var pic_array = new Array();

function add_pic_array(value){
	pic_array.push(value);
}

function init_gallery(folder){
	for (var i = 0; i < pic_array.length; i++) {
		var string = pic_array[i];
		document.getElementById("gallery").innerHTML += "<div class='responsive'><div class='gallery'>" +
			"<a href='javascript:;' onclick='modal("+ i +")'>" +
			"<img id='img_" + i + "' src='./gartnetzwerg/Pictures/" + folder + "/" + pic_array[i] + "' alt='' width='300' height='200'>" +
			"</a><div class='desc'>" + /*string.substring(0, (string.length-4)); +*/ "</div></div></div>";
	}
}

var m = document.getElementById('gallery_modal');
var modalImg = document.getElementById("modal_img");

function modal(ii){
	var img = document.getElementById("img_"+ii);
	m.style.display = "flex";
	modalImg.src = img.src;
}

var span = document.getElementsByClassName("close")[0];

var trigger = false;
span.onclick = function() { 
	m.style.display = "none";
}

modalImg.onmouseleave = function() { trigger=false; }
modalImg.onclick = function() { trigger=true; }

m.onclick = function() { if(trigger==false){ 
	m.style.display = "none";
}}

function zeitraffer_modal(data){
	m.style.display = "flex";
	modalImg.src = "./gartnetzwerg/Gifs/"+data+".gif";
}