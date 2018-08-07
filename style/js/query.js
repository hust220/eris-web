
function delentry(id) {
	// submit deletion request to database
	xhReq =createXMLHttpRequest();
	var url = "delentry.php?jobid="+id;
	xhReq.open("get", url, true);
	xhReq.onreadystatechange = function () {
		if (xhReq.readyState != 4) {return;}
		var serverResponse = xhReq.responseText;
		if (serverResponse) {
		// hide the row in the table
			var obj = document.getElementById("entrytag"+id);
			var tbody = document.getElementById("tbodytag");
			obj.style.display = 'none';
			tbody.removeChild(obj);
		}
	}
	xhReq.send(null);
	
}
