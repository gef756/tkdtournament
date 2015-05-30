// JavaScript Document
var xmlHttp = createXmlHttpRequestObject();
var showErrors = true;
var cache = new Array();

function createXmlHttpRequestObject(){
	var xmlHttp;
	try{
		xmlHttp = new XMLHttpRequest();
	}
	catch (e){
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		for (var i=0;i<XmlHttpVersions.length && !xmlHttp; i++){
			try{
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}
			catch (e){}
		}
	}
	if (!xmlHttp) alert("Error creating the XMLHttpRequest object.");
	else return xmlHttp;
}

function displayError($message){
	if (showErrors){
		showErrors = false;
		alert("Error encountered: \n" + $message);
	}
}

function startup(){
	Sortable.create("eventsList", {tag:"li"});
	Sortable.create("ring1List", {tag:"li"});
		Sortable.create("ring2List", {tag:"li"});
			Sortable.create("ring3List", {tag:"li"});
				Sortable.create("ring4List", {tag:"li"});
					Sortable.create("ring5List", {tag:"li"});
	Sortable.create("ring6List", {tag:"li"});
		Sortable.create("ring7List", {tag:"li"});
	Droppables.add("trash", {
				   onDrop: function(element){
					   var deleteEvent = confirm("Are you sure you want to delete this event?")
					   if (deleteEvent){
						   Element.hide(element);
						   process(element.id, "delEvent",'-1');
					   }
				   }
				   });
	Droppables.add("complete", {
				   onDrop: function(element){
					   var complEvent = confirm("Mark Complete?");
					   if (complEvent){
						   Element.hide(element);
						   process(element.id, "complEvent",'-1');
					   }
				   }
				   });
				Droppables.add("ring1Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",1);		   
				   }
				   });
								Droppables.add("ring2Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",2);
				   }
				   });
												Droppables.add("ring3Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",3);
				   }
				   });
																Droppables.add("ring4Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",4);
				   }
				   });
																				Droppables.add("ring5Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",5);
				   }
				   });
				Droppables.add("ring6Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",6);
				   }
				   });
				Droppables.add("ring0Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",0);
				   }
				   });
								Droppables.add("ring7Drop", {
				   onDrop: function(element){
						   Element.hide(element);
						   process(element.id, "moveToRing",7);
				   }
				   });
}

function serialize(listID){
	var length = document.getElementById(listID).childNodes.length;
	var serialized = "";
	for (i = 0; i < length; i++){

		var li = document.getElementById(listID).childNodes[i];
var id = li.getAttribute("id");
		serialized += encodeURIComponent(id) + "_";
	}
	return serialized.substring(0, serialized.length - 1);
}

function process(content, action, ringNo){
	if (xmlHttp){
		params="";
		content = encodeURIComponent(content);
		if (action == "updateList")
			params = "?content=" + serialize(content) + "&action=updateList&ringNo="+ringNo;
		else if (action == "addNewEvent"){
			var newTask = trim(encodeURIComponent(document.getElementById(content).value));
			if (newTask){ params = "?content=ConfirmAdd&action=addNewEvent&ringNo="+ringNo;
			params +="&addChung=" + document.getElementById('addChung').value;
			params +="&addHong=" + document.getElementById('addHong').value;
			params +="&addCBID=" + document.getElementById('addCBID').value;
			params +="&addHBID=" + document.getElementById('addHBID').value;
			params +="&addFeedTo=" + document.getElementById('addFeedTo').value;
			params +="&addComments=" + document.getElementById('addComments').value;			
			}
		}
		else if (action =="delEvent") params = "?content=" + content + "&action=delEvent&ringNo=" + ringNo;
		else if (action=="complEvent") params = "?content=" + content + "&action=complEvent&ringNo=" + ringNo;
		else if (action == "moveToRing") params = "?content=" + content + "&action=moveToRing&ringNo=" + ringNo;
		if (params) cache.push(params);
			
		try{
			if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && cache.length > 0){
				var cacheEntry = cache.shift();
				xmlHttp.open("GET", "dd/drag-and-drop.php" + cacheEntry, true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(null);
			}
			else{
				setTimeout("process();", 40);
			}
		}
		catch (e){
			displayError(e.toString());
		}
	}
}

function handleRequestStateChange(){
	if (xmlHttp.readyState == 4){
		if (xmlHttp.status == 200){
			try{
				postUpdateProcess();
			}
			catch (e){
				displayError(e.toString());
			}
		}
		else{
			displayError(xmlHttp.statusText);
		}
	}
}

function postUpdateProcess(){
	var response = xmlHttp.responseText;
	if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >=0) alert(response);
	responseXml = xmlHttp.responseXML;
	xmlDoc = responseXml.documentElement;
	for (i = 0; i <xmlDoc.getElementsByTagName("ring").length; i++){
		var result = xmlDoc.getElementsByTagName("ring")[i].getElementsByTagName("result")[0].textContent;
		var fieldID = xmlDoc.getElementsByTagName("ring")[i].getElementsByTagName("fieldid")[0].firstChild.data;
		document.getElementById(fieldID).innerHTML = result;
	}
	Sortable.create("eventsList");
		Sortable.create("ring1List");
				Sortable.create("ring2List");
						Sortable.create("ring3List");
								Sortable.create("ring4List");
										Sortable.create("ring5List");
												Sortable.create("ring6List");
												Sortable.create("ring7List");
			document.getElementById('addChung').value="";
			document.getElementById('addHong').value="";
			 document.getElementById('addCBID').value="";
			document.getElementById('addHBID').value="";
			document.getElementById('addFeedTo').value="";
			document.getElementById('addComments').value="";	

}



function refreshRing(refRing){
	if (refRing == -1){
		for (i = 0; i<=6; i++){
			refreshRing(i);
		}
	}
	else{
		if (refRing == 0) ringEl = "eventsList";
		else ringEl = "ring"+refRing+"List";
		document.getElementById(ringEl).innerHTML = response;
	}
}

function handleKey(e){
	e = (!e) ? window.event : e;
	code = (e.charCode) ? e.charCode : ((e.keyCode) ? e.keyCode : ((e.which) ? e.which : 0 ));
	if (e.type == "keyDown"){
		if (code == 13){
			process("addEventTable","addNewEvent",'0');
		}
	}
}

function trim(s){
	return s.replace(/(^\s+)|(\s+$)/g, "")
}
