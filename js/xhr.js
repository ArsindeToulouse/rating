(function(){
	var radios = document.getElementsByName("rating");
	var divRadios;
	var responseOutput;
	var divEdit;

	function createXHR(){
		try{ return new XMLHttpRequest();} catch(e){}
		try{ return new ActiveXObject("Msxml2.XMLHTTP.6.0");} catch(e){}
		try{ return new ActiveXObject("Msxml2.XMLHTTP.3.0");} catch(e){}
		try{ return new ActiveXObject("Msxml2.XMLHTTP");} catch(e){}
		try{ return new ActiveXObject("Microsoft.XMLHTTP");} catch(e){}
		return null;
	}
	function sendRequest(url, id = null){
		var str = document.getElementById("commentItem").value;
		var reg = /![a-zA-Zа-яА-Я0-9 -_]*/gim;
		if (reg.test(str) || str === "") {
			responseOutput.innerHTML = "Хотелось бы услышать Ваше мнение :)";
			for (var i in radios) {
				radios[i].checked = false;
			}
		}else{
			var xhr = createXHR();
			var formData = new FormData(document.forms.rates);
			localStorage["ls"] = Math.trunc(Date.now() / 1000);
			formData.append("ls", localStorage["ls"]);
			formData.append("hash", localStorage["hash"]);
			if(id) formData.append("id", id);
			if(xhr){
				xhr.open("POST", url, true);
				xhr.onreadystatechange = function(){handleResponse(xhr);};
				xhr.send(formData);
			}
		}
	}
	function handleResponse(xhr){
		if (xhr.readyState == 4 && xhr.status == 200) {
			var data = JSON.parse(xhr.responseText);
			console.log(data);
			if(data.id) localStorage["id"] = data.id;
			responseOutput.innerHTML = data.msg;
			localStorage['comment'] = textComment.value;
			for (var i in radios) {
				if(radios[i].checked){
					localStorage["rate"] = radios[i].value;
				} 
			}
		}
	}
	function handleCheckResponse(xhr){
		// если пользователь уже голосовал
		// показываем, что он написал в первый раз
		// но редактировать позволяем только после нажатия 
		//  кнопки EDIT
		if (xhr.readyState == 4 && xhr.status == 200) {
			var data = JSON.parse(xhr.responseText);
			if(data.result) {
				responseOutput.innerHTML = data.msg;
				textComment.disabled = true;
				textComment.value = data.comment;
				for (var i in radios) {
					radios[i].disabled = true;
					if (radios[i].value === data.rate) {
						radios[i].checked = true;
					}
				}
				localStorage['ls'] = data.ls;
				divEdit.style.display = 'block';
			}else{ 
				responseOutput.innerHTML = "";
				localStorage.clear();
				nowDate = String(Date.now());
				console.log(nowDate);
				localStorage['hash'] = calcSHA1(nowDate);
			}
		}
	}
	function ready(){
		var url = "setComment.php";
		for (var i in radios) {
			if(radios[i].checked){
				(localStorage["id"]) ? sendRequest(url, localStorage["id"]) : sendRequest(url);
			} 
		}
	}
	function edit(){
		textComment.disabled = false;
		for (var i in radios) {
			radios[i].disabled = false;
		}
		divEdit.style.display = 'none';
	}
	function initialise(){
		responseOutput = document.getElementById("responseOutput");
		textComment = document.getElementById("commentItem");
		divRadios = document.getElementById("radios");
		divRadios.addEventListener("click", ready);
		divEdit = document.getElementById("editComment");
		divEdit.addEventListener("click", edit);
	}
	function checkRating(){
		var url = "getInfo.php";
		// инициализируем элементы формы с которыми работаем.
		initialise();
		// запрашиваем сервер - а голосовал ли этот пользователь раньше?
		// сохранен ли hash даты первого захода на страницу голосования
		if (localStorage['hash']) {
			var response;
			var xhr = createXHR();
			if(xhr){
				xhr.open("GET", url + "?hash=" + localStorage['hash'], true);
				xhr.onreadystatechange = function(){
					handleCheckResponse(xhr);
				};
				xhr.send();
			}
		}else{
		// если пользователь заходит первый раз создаем для него hash-значение
			nowDate = String(new Date());
			localStorage['hash'] = calcSHA1(nowDate);
		}
	}
			
	document.addEventListener("DOMContentLoaded", checkRating);
})();