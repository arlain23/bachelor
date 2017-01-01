function getPath() {
	var inputName = $('#file1');
	var imgPath;

	
	imgPath = inputName.val();
	alert(imgPath);
	$('#file_src').val(imgPath);
}
