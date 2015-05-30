// JavaScript Document
// JavaScript Document
test12 = "test12"
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

function processUpdate(ringNo){

	if (xmlHttp){
					
		params="";
			params = "?action=updateEvent";
			params +="&rowID=" + document.getElementById('rowID').innerHTML;
			params +="&ring=" + ringNo;
			params +="&chung=" + document.getElementById('chung').innerHTML;
				params +="&hong=" + document.getElementById('hong').innerHTML;
			params +="&cLScore=" + document.getElementById('cLScore').value;		
			params +="&hLScore=" + document.getElementById('hLScore').value;	
			params +="&cMScore=" + document.getElementById('cMScore').value;	
			params +="&hMScore=" + document.getElementById('hMScore').value;
			params +="&cHScore=" + document.getElementById('cHScore').value;	
			params +="&hHScore=" + document.getElementById('hHScore').value;
			params +="&lStatus=" + document.getElementById('lStatus').checked;
			params +="&mStatus=" + document.getElementById('mStatus').checked;
			params +="&hStatus=" + document.getElementById('hStatus').checked;
		if (params) cache.push(params);
		else
			params = "&action=RefreshList";
			cache.push(params);

		try{
			if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && cache.length > 0){
				var cacheEntry = cache.shift();
				xmlHttp.open("GET", "dd/updateringsta.php" + cacheEntry, true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(null);
			}
			else{
				setTimeout("processUpdate();", 100);
			}
		}
		catch (e){
			displayError(e.toString());
		}
	}
}

function postUpdateProcess(){
		var response = xmlHttp.responseText;
	if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >=0) alert(response);
	responseXml = xmlHttp.responseXML;
	xmlDoc = responseXml.documentElement;
	for (i = 0; i <xmlDoc.getElementsByTagName("dataInp").length; i++){
		var type = xmlDoc.getElementsByTagName("dataInp")[i].getElementsByTagName("type")[0].textContent;
		var result = xmlDoc.getElementsByTagName("dataInp")[i].getElementsByTagName("result")[0].textContent;
		var fieldID = xmlDoc.getElementsByTagName("dataInp")[i].getElementsByTagName("fieldid")[0].firstChild.data;
		
		if (result != null && fieldID != null){
				var d = document.getElementById(fieldID);
		if (d.value != result) d.setAttribute('style','background: #08f;');
		if (type == 'value'){
			if (d.value != result) d.setAttribute('style','background: #08f;');
			document.getElementById(fieldID).value = result;
		}
		else if (type == 'checked'){
			document.getElementById(fieldID).value = result;
		}
		else if (type == 'innerHTML'){
			if (d.innerHTML != result) d.setAttribute('style','background: #08f;');
			document.getElementById(fieldID).innerHTML = result;
		}


		
		}
	}
} 