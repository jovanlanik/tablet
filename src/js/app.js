//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

function checkChange(check, sourceOn, sourceOff, id)
{
	if (check.checked == true) source = sourceOn;
	else source = sourceOff;
	getElement(null, source);
	setTimeout(function(){
		loadElement(id);
	}, 250);
}

function loadCheck(id)
{
	check = document.getElementById(id);
	check.checked = false;
	check.onclick();
}

function openPopup(id, name, source)
{
	var popupBack = document.createElement("div");
	popupBack.setAttribute("id", id);
	popupBack.classList.add("popupBack");
	window.onclick = function(event) {
		if (event.target == popupBack) popupBack.remove();
	}
	document.body.appendChild(popupBack);
	var popupWin = document.createElement("div");
	popupWin.classList.add("popupWin");
	popupBack.appendChild(popupWin);
	var popupTitle = document.createElement("div");
	popupTitle.classList.add("popupTitle");
	popupTitle.innerHTML = "<h4>" + name + "</h4><button class=\"button\" onclick=\"closePopup('" + id + "')\"><i class=\"material-icons\">close</i>";
	popupWin.appendChild(popupTitle);
	var popupCont = document.createElement("div");
	popupCont.classList.add("popupCont");
	popupCont.setAttribute("id", id+"Cont");
	popupCont.setAttribute("php", source);
	popupWin.appendChild(popupCont);
	loadElement(popupCont);
}

function openForm(id, name, source)
{
	openPopup(id, name, source);
	setTimeout(function() {
		var slider = document.getElementsByClassName("slider");
		for(var i = 0; i < slider.length; i++)
		{
			var output = document.getElementById(slider[i].getAttribute("sliderout"));
			output.innerHTML = slider[i].value;
			slider[i].oninput = function()
			{
				var out = document.getElementById(this.getAttribute("sliderout"));
				out.innerHTML = this.value;
			}
		}
	}, 500);
}

function closePopup(id)
{
	if(id == null) return;
	element = document.getElementById(id);
	if(element == null) return;
	if(element.classList.contains("popupBack")) element.remove();
}

function getElement(element, source, id, postCont)
{
	req = new XMLHttpRequest();
	req.onreadystatechange = function()
	{
		if(this.readyState == 4 && this.status == 200)
		{
			if(element != null) element.innerHTML = this.response;
			if(id !== undefined && id != null)
			{
				if(Array.isArray(id))
				{
					id.forEach(id_e => {
						loadElement(id_e);
					});
				}
				else loadElement(id);
			}
		}
	}
	if(postCont != null)
	{
		req.open("POST", source, true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send(postCont);
	}
	else
	{
		req.open("GET", source, true);
		req.send();
	}
}

function getForm(formID, id, pop)
{
	var form;
	if(formID instanceof Element) form = formID;
	else form = document.getElementById(formID);
	if(form != null) form.submit();
	setTimeout(function() {
	if(id !== undefined && id != null)
	{
		if(Array.isArray(id))
		{
			id.forEach(id_e => {
				loadElement(id_e);
			});
		}
		else{
			loadElement(id);
		}
	}
	closePopup(pop);
	}, 500);
}

function loadElement(id)
{
	var element;
	if(id == null) return;
	else if(id instanceof Element) element = id;
	else element = document.getElementById(id);
	if(element != null && element.hasAttribute("php")) getElement(element, element.getAttribute("php"));
	return;
}

function reloadElement(id, script)
{
	getElement(null, script);
	loadElement(id);
}

function pageLoad()
{
	loadCheck("showAllCheck");
	loadElement("date");
	loadElement("table");
	loadElement("user");
}

function fuckshit(i, n)
{
	getElement(null, './src/php/?remove=' + i + '&num=' + n + '');
	setTimeout(function() {
		loadElement('table');
		closePopup('lessonPopup');
	}, 250);
}
