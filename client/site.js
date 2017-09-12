/**
 * 
 */

/*
 * When the page is loaded, the booking form is set to the defaults which might
 * be previous entered data or empty (if the page is initially loaded)
 */
var FORM_MODE_DEFAULT = -2;
/*
 * This is used when in the select box "New booking" is selected explicitly to
 * always null the form
 */
var FORM_MODE_EMPTY = -1;

var focusedElement;
var lastFocusedInput; // see display_footer_bs.tpl

function saveFocusedElement() {
	focusedElement = lastFocusedInput;
}

function restoreLastFocusedElement() {
	return focusedElement.focus();
}

function updateSelect(selectElements, id, name) {
	var len = selects.length;

	for (i = 0; i < len; i++) {
		var option = document.createElement('option');
		option.value = id;
		option.innerHTML = name;
		selectElements[i].appendChild(option);
		selectElements[i].value = id;
	}
}

function updateContractpartnerSelect(id, name, moneyflowComment,
		postingAccountId) {
	var selectElements = document.getElementsByName('all_data[mcp_contractpartnerid]');
	updateSelect(selectElements, id, name);

	// add_moneyflow specials:
	if (typeof addMoneyflowJsonContractpartner !== 'undefined') {
		addMoneyflowJsonContractpartner.push({
			contractpartnerid : id,
			moneyflow_comment : moneyflowComment,
			mpa_postingaccountid : postingAccountId
		});

		if (typeof setContractpartnerDefaults === "function") {
			setContractpartnerDefaults();
		}
	}
}

function updatePostingAccountSelect(id, name) {
	var selectElements = document.getElementsByName('all_data[mpa_postingaccountid]');
	updateSelect(selectElements, id, name);
}

function updateCapitalsourceSelect(id, comment) {
	var selectElements = document.getElementsByName('all_data[mcs_capitalsourceid]');
	updateSelect(selectElements, id, comment);
}

function clearErrorDiv(divName) {
	var element = document.getElementById(divName);
	while (element != null) {
		element.outerHTML = "";
		delete element;
		element = document.getElementById(divName);
	}
}

function populateErrorDiv(jsonString, parentDiv, divName) {
    var responseText = $.parseJSON(jsonString);
    var length = responseText.length;

    element = document.getElementById(parentDiv);

    for(i=0 ; i < length ; i++ ) {
    	var errorDiv = document.createElement('div');
    	errorDiv.id = divName;
    	errorDiv.className = 'alert alert-danger';
    	errorDiv.innerHTML = responseText[i]; 
    	element.appendChild(errorDiv);
    }
}