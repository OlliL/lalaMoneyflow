/**
 * 
 */
function updateContractpartnerSelect(id, name, moneyflowComment,
		postingAccountId) {
	var selects = document.getElementsByName('all_data[mcp_contractpartnerid]');
	var len = selects.length;

	for (i = 0; i < len; i++) {
		var option = document.createElement('option');
		option.value = id;
		option.innerHTML = name;
		selects[i].appendChild(option);
		selects[i].value = id;
	}

	// add_moneyflow specials:
	if (jsonContractpartner != null) {
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
	var len = selects.length;
	console.log(id);
	console.log(name);
	for (i = 0; i < len; i++) {
		var option = document.createElement('option');
		option.value = id;
		option.innerHTML = name;
		selects[i].appendChild(option);
		selects[i].value = id;
	}
}