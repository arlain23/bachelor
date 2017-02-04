function setHeaderData(fileURL){
	var oReq = new XMLHttpRequest();
	oReq.open("GET", fileURL, true);
	oReq.responseType = "arraybuffer";
	oReq.onload = function(oEvent) {
		  var blob = new Blob([oReq.response]);
		  // ...
		  readFile(blob);
		};
	oReq.send();
}

function readFile(file) {
   var blob = makeSlice(file, 0, file.size);
   var reader = new FileReader();
   reader.onloadend = function (evt) {
       if (evt.target.readyState === FileReader.DONE) {
           readNIFTI(file.name, evt.target.result);
       }
   };
   reader.readAsArrayBuffer(blob);
}
function readNIFTI(name, buf) {
   if (nifti.isCompressed(buf)) {
   	buf = nifti.decompress(buf);
   }

   if (nifti.isNIFTI(buf)) {
       niftiHeader = nifti.readHeader(buf);
       var logger = document.getElementById('niftiHeader');
       logger.innerText = niftiHeader.toFormattedString();
       
   }
}
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

/* Vtk files */

function getIframeWindow(iframe_object) {
	  var doc;

	  if (iframe_object.contentWindow) {
	    return iframe_object.contentWindow;
	  }

	  if (iframe_object.window) {
	    return iframe_object.window;
	  } 

	  if (!doc && iframe_object.contentDocument) {
	    doc = iframe_object.contentDocument;
	  } 

	  if (!doc && iframe_object.document) {
	    doc = iframe_object.document;
	  }

	  if (doc && doc.defaultView) {
	   return doc.defaultView;
	  }

	  if (doc && doc.parentWindow) {
	    return doc.parentWindow;
	  }

	  return undefined;
	}

