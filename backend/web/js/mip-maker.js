var theControllerURL;
var uniqueID;
var uniqueIDString = "";

/*
 * function inicialiseControllerPath 
 * set controller url needed for ajax request 
 */
function inicialiseControllerPath(controllerURL){
	theControllerURL = controllerURL;
};
/*
 * function createMIP evoked by DOM 
 * param: String representng fileURLs with delimiter ';' 
 */
$(function(){
	$('.submit-button').hide();
	  if ( $('#multiplefileform-files').length ) { 
		  document.getElementById('multiplefileform-files').addEventListener('change', handleFileSelect, false);
	  }
	  if ( $('#fileform-file').length ) {
		  document.getElementById('fileform-file').addEventListener('change', handleFileSelect, false);
	  }
});

/*
 * function handleFileSelec 
 * calls readFle for each file 
 */
function handleFileSelect(evt) {
    var files = evt.target.files; 
	for (i = 0; i < files.length; i++) { 
		readFile(files[i]);
		console.log("handleFileSelect: data read")
	}
}

function createMIP(fileURLs, controllerURL){
	theControllerURL = controllerURL;
	var fileURLArray = fileURLs.split(";");
	
	console.log(theControllerURL)
	console.log(fileURLArray)
	for (i = 0; i < fileURLArray.length; i++) { 
		readDataFromURL(fileURLArray[i]);
		console.log("createMIP: data read")
	}
}

/*
 * function readDataFromURL used to access blob from string url
 */
function readDataFromURL(fileURL){
	var oReq = new XMLHttpRequest();
	oReq.open("GET", fileURL, true);
	oReq.responseType = "arraybuffer";
	oReq.onload = function(oEvent) {
		  var blob = new Blob([oReq.response]);
		  console.log("readDataFromURL: data read")
		  readFile(blob);
		};
	oReq.send();
}

/*
 * function readFile used to inicialize FileReader
 */
function readFile(file) {
    var blob = makeSlice(file, 0, file.size);
    var reader = new FileReader();
    reader.onloadend = function (evt) {
        if (evt.target.readyState === FileReader.DONE) {
        	console.log("readFile: data read")
            readNIFTI(file.name, evt.target.result);
        }
    };
    reader.readAsArrayBuffer(blob);
}

/*
 * function readNIFTI used to read data from NIfTI file
 */
function readNIFTI(name, buf) {
	console.log("im in read Nifti")
    if (nifti.isCompressed(buf)) {
    	buf = nifti.decompress(buf);
    }

    if (nifti.isNIFTI(buf)) {
    	$('#logger').text("Please wait until MIPs are created");
        niftiHeader = nifti.readHeader(buf);
        niftiImage = nifti.readImage(niftiHeader, buf);
        
        //read image array dimensions from NIfTI header
        var dim1 = niftiHeader.dims[1];
        var dim2 = niftiHeader.dims[2];
        var dim3 = niftiHeader.dims[3];
        
        //Total size of bytes needed for an array
        var imageSize = dim1*dim2*dim3;
        
        //read byte offset representing the beginning of image data
        var imageOffset = niftiHeader.vox_offset;
                     
        //get data from ArrayBuffer which represent an image
        var niftiImageSlice = makeSlice(buf,imageOffset,imageSize);
        
        //create an Array from ArrayBuffer
        var arrayFromBuffer = new Int8Array(niftiImageSlice);
        
        
        console.log("isNIFTI: data read")
        
        //MIP creation
        
        var angleIncrementation = 10;
        var index = 0;
        
        uniqueID = makeid();
		uniqueIDString += uniqueID;
		uniqueIDString += ',';
		/* get hold of DOM canvas */
		var canvas = document.getElementById('mipCanvas');
		var ctx = canvas.getContext('2d');
		
        for (rotationAngle = 0 ; rotationAngle < 360 ; rotationAngle += angleIncrementation){
        	index += 1;
        	console.log("rotating: " + rotationAngle)
        	var rotatedArray = rotateArray(arrayFromBuffer, dim1, dim2, dim3, rotationAngle);
        	console.log("rotated array created")
            makeMipFromArray(rotatedArray, dim1, dim2, dim3, index,uniqueID,canvas,ctx);
        	console.log("mip from array created")
        }
        /*
        makeOneMIP(arrayFromBuffer, dim1, dim2, dim3);
        console.log("mip created");
        var myaeee = rotateArray(arrayFromBuffer, dim1, dim2, dim3, 160);
         console.log("i dont know done");
        makeMipFromArray(myaeee, dim1, dim2, dim3);
        console.log("mip created2");*/
    }   
    else{
    	console.log("data is not nifti");
    	$('.submit-button').show();
    }
}

/*
 * function makeSlice used  to slice ArrayBuffer to get a given number of bytes
 */
function makeSlice(file, start, length) {

    if (File.prototype.slice) {
        return file.slice(start, start + length);
    }
    if (File.prototype.mozSlice) {
        return file.mozSlice(start, length);
    }
    if (File.prototype.webkitSlice) {
        return file.webkitSlice(start, length);
    }
    return null;
}

/*
 * Function makeMipFromArray used to create MIP from 3D array of data
 */
function makeMipFromArray(array,dim1,dim2,dim3, imageIndex, uniqueID, canvas, ctx){
	// 2D array to hold a MIP
	var mip = zeros([dim1,dim2]);
	// 1D array to hold all maximal values chosen
	// max from ArrayBuffer throws error about exceeding call stack
	// needed not to exceed the call stack
	var maximalValuesArray = new Int16Array(dim1*dim2);
	
	for(x = 0; x < dim1; x++){ 
		for (y = 0; y < dim2; y++){ 
			var values = [];   //create tmp array to store z values
			for (z = 0; z < dim3 ; z++) { 
			    values[z] = array[x][y][z];
			}
			var  maxx = Math.max.apply(null, values); // find maximal intensity from z values
			mip[x][y] = maxx; 						//save it to the MIP array
			maximalValuesArray[y*dim1 + x] = maxx;	//and to array holding max values
		}
	}
	
	//find total maximum of all maximum values - needed for rescaling
	var  totalMax = Math.max.apply(null, maximalValuesArray);
	for (x = 0; x < dim1; x++){ 	
		for(y = 0; y < dim2; y++){ 
			mip[x][y] = Math.round((mip[x][y] / totalMax) * 256); //256 - number of colours available for 8bits encoding
		}
	}
	
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	var myImageData = ctx.createImageData(dim1, dim2);

     for(var x = 0; x < dim1; x++){
    	 for(var y = 0; y < dim2; y++){
    		  var i = (y*dim1 + x) * 4; 	// 4 channels for canvas
	          myImageData.data[i] = 0;		// 1-3 channels for RGB
	          myImageData.data[i + 1] = 0;
	          myImageData.data[i + 2] = 0;
	          myImageData.data[i + 3] = mip[x][y] ;	//4th channel for grayscale
	     }
	}
    ctx.putImageData(myImageData, 0, 0);
    console.log("canvas is done");
    
    $('.submit-button').show();
    $('#logger').text("Now you can post the files");
    var dataURL = canvas.toDataURL('image/png');
    
   
    $.ajax({
    	  type: "POST",
    	  url: theControllerURL,
    	  async: false,
    	  data: { 
    	     imgBase64: dataURL,
    	     imageIndex: imageIndex,
    	     uniqueID: uniqueID,
    	  }
    	}).done(function(o) {
    	  console.log('saved'); 
    	  if ( $('#multiplefileform-gifuniqueid').length ) {  		  
    		  $('#multiplefileform-gifuniqueid').val(uniqueIDString); 
    	  }
    	  if ( $('#fileform-gifuniqueid').length ) {  		  
    		  $('#fileform-gifuniqueid').val(uniqueIDString) 
    	  }
    	 
    	  
    	});  
}

/*
 *  function createArray used to create an array with a given length
 */
function createArray(length) {
    var arr = new Array(length || 0),
        i = length;

    if (arguments.length > 1) {
        var args = Array.prototype.slice.call(arguments, 1);
        while(i--) arr[length-1 - i] = createArray.apply(this, args);
    }

    return arr;
}

/*
 * function arrayIndex used to get an element from arrayBuffer from x,y,z indexing
 */
function arrayIndex(x, y, z, xSize, ySize) {
    return z + ySize * (y + x * xSize);
}



/*
 * Rotation matrix ***
 * Around the Z-axis
|cos θ   −sin θ   0| |x|   |x cos θ − y sin θ|   |x'|
|sin θ    cos θ   0| |y| = |x sin θ + y cos θ| = |y'|
|  0       0      1| |z|   |        z        |   |z'|

*around the Y-axis 

| cos θ    0   sin θ| |x|   | x cos θ + z sin θ|   |x'|
|   0      1       0| |y| = |         y        | = |y'|
|−sin θ    0   cos θ| |z|   |−x sin θ + z cos θ|   |z'|

*around the X-axis 

|1     0           0| |x|   |        x        |   |x'|
|0   cos θ    −sin θ| |y| = |y cos θ − z sin θ| = |y'|
|0   sin θ     cos θ| |z|   |y sin θ + z cos θ|   |z'|

*/


/*
 * function rotateArray is used to rotate the array with a given angle
 */
function rotateArray(data, dim1, dim2, dim3, angle){
	//convert angle to radians
	var angleRad = 3.1416 / 180 * angle 
	// array for the future rotated array
    var rotatedImageArray = zeros([dim1, dim2,dim3]);
	//variables needed for calculations
    var cosAngle = Math.cos(angleRad);
    var sinAngle = Math.sin(angleRad);
    var newY;
    var newX;
    var newZ;
    var cx = dim1/2;	// centre of the new coordinate system in X
    var cy = dim2/2;	// centre of the new coordinate system in Y
    var cz = dim2/2;	// centre of the new coordinate system in Z
    
	for(x = 0; x < dim1; x++){ 
		for (y = 0; y < dim2; y++){ 
			for (z = 0; z < dim3 ; z++) {
			    pixelValue = data[arrayIndex(x,y,z,dim1,dim2)];
			    
			    // rotate around z axes
			    /*
			    newX = Math.round ((x-cx) * cosAngle - (y-cy) * sinAngle + cx);
			    newY = Math.round ((x-cx) * sinAngle + (y-cy) * cosAngle + cy);
			    
			    if (newY >= 0 && newY < dim2 && newX >= 0 && newX < dim1){
			    	rotatedImageArray[newX][newY][z] = pixelValue;	
			    }
			    */
			    
			    // rotate around X axes 
			    
			    /*
			    newY = Math.round ((y-cy) * cosAngle - (z-cz) * sinAngle + cy);
			    newZ = Math.round ((y-cy) * sinAngle + (z-cz) * cosAngle + cz);
			    
			    if (newY >= 0 && newY < dim2 && newZ >= 0 && newZ < dim3){
			    	rotatedImageArray[x][newY][newZ] = pixelValue;	
			    }
			    */
			    
			    // rotate around Y axes
			    x = x - cx;
			    z = z - cz;
			    newX = Math.round (x * cosAngle + z * sinAngle) + cx;
			    newZ = Math.round (-x * sinAngle + z * cosAngle) + cz;
			    x = x + cx;
			    z = z + cz;
		    	    
			    if (newZ >= 0 && newZ < dim3 && newX >= 0 && newX < dim1){
			    	var tmpVal = rotatedImageArray[newX][y][newZ];
			    	if (tmpVal == 0){
			    		rotatedImageArray[newX][y][newZ] = pixelValue;
			    	} 
			    		
			    }	    		    
			}
		}
	}
	return rotatedImageArray;
}

/*
 * function zeros used to inicialise an array with given dimensions with zeros
 */
function zeros(dimensions) {
    var array = [];

    for (var i = 0; i < dimensions[0]; ++i) {
        array.push(dimensions.length == 1 ? 0 : zeros(dimensions.slice(1)));
    }

    return array;
}

/*
 * function makeid used to create a random string of characters with length of 5
 * needed to create a folder with unique name
 */

function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}


// not used but maybe needed later


function makeOneMIP(data,dim1,dim2,dim3){
	var mip = createArray(dim1,dim2);
	var maximalValuesArray = new Int16Array(dim1*dim2);
	for (y = 0; y < dim2; y++){ 	
		for(x = 0; x < dim1; x++){ 
			var values = [];   //create tmp array to store z values
			for (z = 0; z < dim3 ; z++) { 
			    values[z] = data[arrayIndex(x,y,z,dim1,dim2)];
			}
			var  maxx = Math.max.apply(null, values); // find maximal intensity from z values and rescale it to 256
			mip[x][y] = maxx; //save it to the MIP array
			maximalValuesArray[y*dim1 + x] = maxx;
		}
	}
	var  totalMax = Math.max.apply(null, maximalValuesArray);
	for (x = 0; x < dim1; x++){ 	//first dimension
		for(y = 0; y < dim2; y++){ //second dimension
			mip[x][y] = Math.round((mip[x][y] / totalMax) * 256); //256 - number of colours available for 8bits encoding
			//console.log(mip[x][y])
		}
	}
	
	
	var canvas = document.getElementById('myCanvas1');
	var ctx = canvas.getContext('2d');
	var myImageData = ctx.createImageData(dim1, dim2);
	
    for(var y = 0; y < dim2; y++){
     for(var x = 0; x < dim1; x++){
          var i = (y*dim1 + x) * 4; 	// 4 channels for canvas
          myImageData.data[i] = 0;
          myImageData.data[i + 1] = 0;
          myImageData.data[i + 2] = 0;
          myImageData.data[i + 3] = mip[x][y] ;
     }
}
ctx.putImageData(myImageData, 0, 0);

}
