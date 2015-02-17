function toogleVisibilty (of, image, togglesrc1, togglesrc2 ) {

	var obj = document.getElementById(of);
	if (obj.style.display  == "") {
		obj.style.display = "none";
	}else {
		obj.style.display = "";
	};

	imageObj = document.getElementById(image);

	var currentSrc = imageObj.src;
	var newSrc;

	if (currentSrc.search(togglesrc1) != -1) {
		newSrc = currentSrc.replace(togglesrc1, togglesrc2);
	}else {
		newSrc = currentSrc.replace(togglesrc2, togglesrc1);
	};
	imageObj.src = newSrc;

}