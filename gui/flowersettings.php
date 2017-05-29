<!doctype html>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Introducing Lollipop, a sweet new take on Android.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Android</title>

    <!-- Page styles -->
	<script
			  src="https://code.jquery.com/jquery-3.2.1.js"
			  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
			  crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="./swiper/dist/js/swiper.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./swiper/dist/css/swiper.css">
    <link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="justified.css">
	<link rel="stylesheet" href="./font-awesome/css/font-awesome.css">
	<script src="./JS.js"></script>
	
	<!--Diagramm-->
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.3.0/snap.svg-min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	
	<!--Diagramm2-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>
	<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/DrawSVGPlugin.js?r=12"></script>
	<!-- <meta name="viewport" content="width=device-width"> -->
	
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
  </head>
  <body>
  <div class="container">
<!-- <div class="wrapper"> -->
  
  
      <div class="masthead">
        <nav>
          <ul class="nav nav-justified">
            <li><a href="status.php"><div class="arrowLeft"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></div></a></li>
            <li><a href="#2">Name</a></li>
            <li><a href="#3">Art</a></li>
			<li><a href="#4">ff</a></li>
            
          </ul>
        </nav>
      </div>
	 <br>
	 <h3>Einstellungen</h3>
  <!-- </ul> -->
		<div class="webcamButtons">
			<div class="masthead">
			<div class="subsection">
			<nav>
			  
				<div class="settings1">
					<ul class="nav nav-justified">
						<li><a class="activeSection" href="#2">Pflanzennamen ändern</a></li>
					</ul>
				</div>
				<!--
				<div id="content">

	<p>Dein Inhalt kommt hier rein...</p>

	</div>

	<a class="open-close" href="#">Open / Close</a>-->
	
	
				<!--<div class="gelbebox" style="display:none">-->
				<div class="settingsbox1" >
					 <div class="containerPopUpBox">
						<ul>
							<li>Aktueller Name: Günther
							
								
							</li>
							<br>
							<li>Name ändern zu
							<!--label for="vorname">Vorname</label-->
								<input type="text" name="vorname" id="vorname">
							<button onclick="">Bestätigen</button>

						
						
						</ul>
					 </div>
				</div>
				
			<br>
			<div class="settings2">
				<ul class="nav nav-justified">
					<li><a href="#1">Farbgebung ändern</a></li>
				
				</ul>
			</div>
			<div class="settingsbox2" >
					 <div class="containerPopUpBox">
						<ul>
							<li>Aktuelle Farbe: grün</li>
							<li>
								<label for="changeItem">Farbe ändern?</label>
								<input type="checkbox" name="agb" id="changeItemCheckbox"> ja
								
							</li>
							<li>
								<label for="vorname">Neue Farbe auswählen:</label>
								<input type="text" name="vorname" id="vorname">
							</li>
						</ul>
					 </div>
				</div>
			
			  <br>
			  <ul class="nav nav-justified">
				<li><a href="#1">Pflanze favorisieren</a></li>
							
			  </ul>
			<br>
			  <ul class="nav nav-justified">
				<li><a href="#1">Auto-Bewässerung an/aus</a></li>
				
			  </ul>
			  <br>
			  <ul class="nav nav-justified">
				<li><a href="#1">Standort anpassen</a></li>
				
			  </ul>
			  <br>
			  <ul class="nav nav-justified">
				<li><a href="#1">Notifications einstellen</a></li>
				
			  </ul>
			  <br>
			  <ul class="nav nav-justified">
				<li><a href="#1">Zeitabstände</a></li>
				
			  </ul>
			  <ul class="nav nav-justified">
				<li><a href="#1">Sensoren</a></li>
				<li><a href="#1">Fotos</a></li>
				
			  </ul>
			  <br>
			  <ul class="nav nav-justified">
				<li><a href="#1">Mit Sensoreinheit verknüpfen</a></li>
				
			  </ul>
			  <br>
			  <div class="deleteColour">
				  <ul class="nav nav-justified">
					<li><a href="#1">Pflanze löschen</a></li>
					
				  </ul>
				</div>
			</nav>
		  </div>
		  </div>
		</div>
  </div>
  
  
  
   
  
  
  
  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  
  
    <script>
    var galleryTop = new Swiper('.gallery-top', {
        spaceBetween: 10,
    });
    var galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        centeredSlides: true,
        slidesPerView: 'auto',
        touchRatio: 0.2,
        slideToClickedSlide: true
    });
    galleryTop.params.control = galleryThumbs;
    galleryThumbs.params.control = galleryTop;
    
    </script>
	
	<script> 
	
	$(function () {
  "use strict";
	
	var resizeTracker;

	// Counteracts all transforms applied above an element.
	// Apply a translation to the element to have it remain at a local position
	var unscale = function (el) {
		var svg = el.ownerSVGElement.ownerSVGElement;
		var xf = el.scaleIndependentXForm;
		if (!xf) {
			// Keep a single transform matrix in the stack for fighting transformations
			xf = el.scaleIndependentXForm = svg.createSVGTransform();
			// Be sure to apply this transform after existing transforms (translate)
			el.transform.baseVal.appendItem(xf);
		}
		var m = svg.getTransformToElement(el.parentNode);
		m.e = m.f = 0; // Ignore (preserve) any translations done up to this point
		xf.setMatrix(m);
	};

	[].forEach.call($("text, .tick"), unscale);

	$(window).resize(function () {
		if (resizeTracker) clearTimeout(resizeTracker);
		resizeTracker = setTimeout(function () { [].forEach.call($("text, .tick"), unscale); }, 100);
	});
})
	
	</script>


	<script type="text/javascript">

	$(document).ready(function(){
	$(".settingsbox1").hide();
	
	    $(".settings1").click(function () {

	      $(".settingsbox1").slideToggle("slow");

	    });

	 

	});

	</script>
	<script type="text/javascript">

	$(document).ready(function(){
	$(".settingsbox2").hide();
	
	    $(".settings2").click(function () {

	      $(".settingsbox2").slideToggle("slow");

	    });

	 

	});

	</script>
	<script>
	function myFunction() {
    var x = document.getElementById("myCheck").checked;
    document.getElementById("demo").innerHTML = x;
	}
</script>
  </body>
 </html>