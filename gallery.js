var ele = document.getElementById("tab_cam");
var img = 20;
var pic_array = new Array();

document.getElementById("tab_cam").onscroll = function() {scroll_load()};

function scroll_load(){
	if (ele.scrollTop >= (ele.scrollHeight-500)) {
    	load_in();
	}
}

function load_in(){
	if(img != 512){
		if(img+20 >= 512){
			img = 512;
		} else {
			img += 20;
		}

		for (var i = img-20; i < img; i++) {
			document.getElementById("gallery").innerHTML += "<div class='responsive'><div class='gallery'>"+
			"<a target='_self' href='./img/aloeveratopf.jpg'>"+
			"<img src='./img/aloeveratopf.jpg' alt='' width='300' height='200'>"+
			"</a><div class='desc'>"+i+"</div></div></div>";
		}
	}
}

function add_pic_array(value){
	pic_array.push(value);
}

function init_gallery(folder){
	for (var i = img-20; i < img; i++) {
		if(i==0){
			document.getElementById("gallery").innerHTML += "<div class='responsive'><div class='gallery'>"+
			"<a target='_self' href='./img/aloeveratopf.jpg'>"+
			"<img src='./img/aloeveratopf.jpg' alt='' width='300' height='200'>"+
			"</a><div class='desc'>Letztes Bild</div></div></div>";
		} else {
			document.getElementById("gallery").innerHTML += "<div class='responsive'><div class='gallery'>"+
			"<a target='_self' href='./img/aloeveratopf.jpg'>"+
			"<img src='/home/pi/Pictures/"+folder+"/"+pic_array[i]+"' alt='' width='300' height='200'>"+
			"</a><div class='desc'>"+i+"</div></div></div>";
		}
	}
}