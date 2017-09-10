/**
 * 
 */
function updateContractpartnerSelect(id, name, moneyflowComment,
		postingAccountId) {
	var contractpartnerSelects = document
			.getElementsByName('all_data[mcp_contractpartnerid]');
	var len = contractpartnerSelects.length;

	for (i = 0; i < len; i++) {
		var option = document.createElement('option');
		option.value = id;
		option.innerHTML = name;
		contractpartnerSelects[i].appendChild(option);
		contractpartnerSelects[i].value = id;
	}

	// add_moneyflow specials:
	if (jsonContractpartner != null) {
		jsonContractpartner.push({
			contractpartnerid : id,
			moneyflow_comment : moneyflowComment,
			mpa_postingaccountid : postingAccountId
		});

		if( typeof setContractpartnerDefaults === "function") {
			setContractpartnerDefaults();
		}
	}
}