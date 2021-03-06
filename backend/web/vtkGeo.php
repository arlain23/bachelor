<?php

header("Access-Control-Allow-Origin: http://backend.dev/");
header("Access-Control-Allow-Origin: http://frontend.dev/");

header('Access-Control-Allow-Origin: *');


?>


<html>
	<body style="background-color: #e4e1db">
		<script type="text/javascript" src ="js/xtk.js"></script>
		<script type="text/javascript">
		window.showMesh = function(fileName) {
			// create and initialize a 3D renderer
			  var r = new X.renderer3D();
			  r.init();	  
			  // create a new X.mesh
			  var theMesh = new X.mesh();	
			  
			  // .. and associate the .vtk file to it
			  theMesh.file = fileName;	
			  
			  r.add(theMesh);		  
			  // re-position the camera to face the skull
			  r.camera.position = [0, 400, 0];	  
			  r.render();	
			  
			  
			}
		window.getDataURI = function(){
			return document.getElementsByTagName("canvas")[0].toDataURL();
		}
		</script>
	</body>
</html>



