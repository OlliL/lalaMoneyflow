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

var clicky;

$(document).mousedown(function(e) {
	// The latest element clicked
	clicky = $(e.target);
});

// when 'clicky == null' on blur, we know it was not caused by a click
// but maybe by pressing the tab key
$(document).mouseup(function(e) {
	clicky = null;
});

function updateSelect(selects, id, name) {
	var len = selects.length;

	for (i = 0; i < len; i++) {
		var option = document.createElement('option');
		option.value = id;
		option.innerHTML = name;
		selects[i].appendChild(option);
		selects[i].value = id;
	}
}

function updateContractpartnerSelect(id, name, moneyflowComment,
		postingAccountId) {
	var selects = document.getElementsByName('all_data[mcp_contractpartnerid]');
	updateSelect(selects, id, name);

	// add_moneyflow specials:
	if (typeof jsonContractpartner !== 'undefined') {
		jsonContractpartner.push({
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
	var selects = document.getElementsByName('all_data[mpa_postingaccountid]');
	updateSelect(selects, id, name);
}

function updateCapitalsourceSelect(id, comment) {
	var selects = document.getElementsByName('all_data[mcs_capitalsourceid]');
	updateSelect(selects, id, comment);
}