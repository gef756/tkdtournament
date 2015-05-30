// JavaScript Document
var xmlHttp = createXmlHttpRequestObject();
var xsltFileUrl = "grid/grid_winners.xsl";
var feedGridUrl = "grid/grid_winners.php";
var gridDivId = "gridDiv";
var statusDivId = "statusDiv";
var tempRow;
var editableId = null;
var stylesheetDoc;
var oldWinInfo = {};

function init(){
	if (window.XMLHttpRequest && window.XSLTProcessor && window.DOMParser){
		loadStylesheet();
		loadGridPage(1);
		return;
	}
	if (window.ActiveXObject && createMsxml2DOMDocumentObject()){
		loadStylesheet();
		loadGridPage(1);
		return;
	}
	alert("Your browser doesn't support the necessary functionality.");
}

function createMsxml2DOMDocumentObject(){
	var msxml2DOM;
	var msxml2DOMDocumentVersions = new Array("Msxml2.DOMDocument.6.0","Msxml2.DOMDocument.5.0","Msxml2.DOMDocument.4.0");
	for (var i=0; i<msxml2DOMDocumentVersions.length && !msxml2DOM; i++){
		try{
			msxml2DOM = new ActiveXObject(msxml2DOMDocumentVersions[i]);
		}
		catch (e){}
	}
	if (!msxml2DOM)
		alert("Please upgrade your MSXML Version from \n http://msdn.microsoft.com/XML/XMLDownloads/default.aspx");
	else
		return msxml2DOM;
}

function createXmlHttpRequestObject(){
	var xmlHttp;
	try{
		xmlHttp = new XMLHttpRequest();
	}
	catch(e){
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		for (var i = 0; i<XmlHttpVersions.length && !xmlHttp; i++){
			try{
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}
			catch (e){}
		}
	}
	if (!xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else
		return xmlHttp;
}

function loadStylesheet(){
	xmlHttp.open("GET", xsltFileUrl, false);
	xmlHttp.send(null);
	if (this.DOMParser){
		var dp = new DOMParser();
		stylesheetDoc = dp.parseFromString(xmlHttp.responseText, "text/xml");
	}
	else if (window.ActiveXObject){
		stylesheetDoc = createMsxml2DOMDocumentObject();
		stylesheetDoc.async = false;
		stylesheetDoc.load(xmlHttp.responseXML);
	}
}

function loadGridPage(pageNo){
	editableId = false; 
	if (xmlHttp && (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)){
		var query = feedGridUrl + "?action=FEED_GRID_PAGE&page=" + pageNo;
		xmlHttp.open("GET",query, true);
		xmlHttp.onreadystatechange = handleGridPageLoad;
		xmlHttp.send(null);
	}
}

function handleGridPageLoad(){
	if (xmlHttp.readyState == 4){
		if (xmlHttp.status == 200){
			response = xmlHttp.responseText;
			if (response.indexOf("ERRNO") >=0 || response.indexOf("error") >= 0 || response.length == 0){
				alert(response.length == 0 ? "Server error." : response);
				return;
			}
			xmlResponse = xmlHttp.responseXML;
			if (window.XMLHttpRequest && window.XSLTProcessor && window.DOMParser){
				var xsltProcessor = new XSLTProcessor();
				xsltProcessor.importStylesheet(stylesheetDoc);
				page = xsltProcessor.transformToFragment(xmlResponse, document);
				var gridDiv = document.getElementById(gridDivId);
				gridDiv.innerHTML = "";
				gridDiv.appendChild(page);
			}
			else if (window.ActiveXObject){
				var theDocument = createMsxml2DOMDocumentObject();
				theDocument.async = false;
				theDocument.load(xmlResponse);
				var gridDiv = document.getElementById(gridDivId);
				gridDiv.innerHTML = theDoucment.transformNode(stylesheetDoc);
			}
		}
		else{
			alert("Error reading server response.")
		}
	}
}

function editId(id, editMode){
	var productRow = document.getElementById(id).cells;
	if (editMode  == 'edit'){
		if (editableId) editId(editableID, 'cancel');
		save(id);
		//productRow[1].innerHTML = '<input class="editName" type="text" readonly="readonly" name="eventName" value="' + productRow[1].innerHTML + '">';
		//productRow[2].innerHTML = '<input class="editGender" type="text" name="gender" value="' + productRow[2].innerHTML + '">';
		//productRow[3].innerHTML = '<input class="editRanking" type="text" name="ranking" value="' + productRow[3].innerHTML + '">';
		for (var i=0; i < productRow.length; i++){
			oldWinInfo[i] = productRow[i].innerHTML;
		}
		productRow[4].innerHTML = '<input class="editWinner" type="text" name="winner" value="' +productRow[4].innerHTML + '">';
		productRow[5].innerHTML = '<input class="editWinnerName" type="text" name="winnerName" value="' + productRow[5].innerHTML + '">';
		//productRow[6].innerHTML = '<input class="editPoints" type="text" name="points"' + productRow[6].innerHTML + '">';
		productRow[7].innerHTML = '<a href="#" onclick="updateRow(document.forms.grid_form_id,' + id + ')">Update</a><br/><a href="#" onclick="editId(' + id + ',\'cancel\')">Cancel</a>';
		editableId = id;
	}
	else if (editMode == 'cancel'){
		//productRow[1].innerHTML = document.forms.grid_form_id.eventName.value;
		//productRow[2].innerHTML = document.forms.grid_form_id.gender.value;
		//productRow[3].innerHTML = document.forms.grid_form_id.ranking.value;
		productRow[4].innerHTML = oldWinInfo[4];
		productRow[5].innerHTML = oldWinInfo[5];
		//productRow[6].innerHTML = document.forms.grid_form_id.points.value;
		productRow[7].innerHTML = '<a href="#" onclick="editId(' + id + ', \'edit\')">Edit</a>';
		editableId = null;
	}
		else if (editMode == 'update'){
		//productRow[1].innerHTML = document.forms.grid_form_id.eventName.value;
		//productRow[2].innerHTML = document.forms.grid_form_id.gender.value;
		//productRow[3].innerHTML = document.forms.grid_form_id.ranking.value;
		productRow[4].innerHTML = document.forms.grid_form_id.winner.value;
		productRow[5].innerHTML = document.forms.grid_form_id.winnerName.value;
		//productRow[6].innerHTML = document.forms.grid_form_id.points.value;
		productRow[7].innerHTML = '<a href="#" onclick="editId(' + id + ', \'edit\')">Edit</a>';
		editableId = null;
	}
	else{alert('Error.');}
}

function save(id){
	var tr = document.getElementById(id).cells;
	tempRow = new Array(tr.length);
	for (var i=0; i<tr.length; i++)
		tempRow[i] = tr[i].innerHTML;
}

function undo(id){
	var tr = document.getElementById(id).cells;
	for (var i = 0; i<tempRow.length; i++)
		tr[i].innerHTML = tempRow[i];
	editableId = null;
}

function updateRow(grid, productId){

	if (xmlHttp && (xmlHttp.readyState == 4|| xmlHttp.readyState === 0)){
		var query = feedGridUrl + "?action=UPDATE_ROW&id=" + productId + "&" + createUpdateUrl(grid);
		xmlHttp.open("GET", query, true);
		xmlHttp.onreadystatechange = handleUpdatingRow;
		xmlHttp.send(null);
	}
}

function handleUpdatingRow(){
	if(xmlHttp.readyState == 4){
		if (xmlHttp.status == 200){
			response = xmlHttp.responseText;
			if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >=0 || response.length == 0)
				alert(response.length == 0 ? "Server error." : response);
			else
				editId(editableId, 'update');
		}
		else{
			undo(editableId);
			alert("Error on server side.");
		}
	}
}

function createUpdateUrl(grid){
	var str="";
	for (var i=0; i<grid.elements.length; i++){
		switch (grid.elements[i].type){
			case "text":
			case "textarea":
				str += grid.elements[i].name + "=" + escape(grid.elements[i].value)+"&";
				break;
			case "checkbox":
				if (!grid.elements[i].disabled)
					str += grid.elements[i].name + "=" + (grid.elements[i].checked ? 1:0) + "&";
				break;
		}
	}
	return str;
	alert(str);
}
