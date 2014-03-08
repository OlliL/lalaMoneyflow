<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_254#}</title>
{literal}
<script language="javascript" type="text/javascript">
	window.onload = function () {
	    timemode_func();
	    accountmode_func();
	    sortlist('accounts_yes');
	}
	
	function timemode_func() {
	    document.getElementById("year_caption").style.visibility = "hidden";
	    document.getElementById("year_data").style.visibility = "hidden";
	    document.getElementById("month_caption").style.visibility = "hidden";
	    document.getElementById("month_data").style.visibility = "hidden";
	    document.getElementById("timeframe_caption").style.visibility = "hidden";
	    document.getElementById("timeframe_data").style.visibility = "hidden";
	    if (document.getElementById("timemode").selectedIndex == "0") {
	        document.getElementById("year_caption").style.visibility = "visible";
	        document.getElementById("year_data").style.visibility = "visible";
	    } else if (document.getElementById("timemode").selectedIndex == "1") {
	        document.getElementById("month_caption").style.visibility = "visible";
	        document.getElementById("month_data").style.visibility = "visible";
	    } else if (document.getElementById("timemode").selectedIndex == "2") {
	        document.getElementById("timeframe_caption").style.visibility = "visible";
	        document.getElementById("timeframe_data").style.visibility = "visible";
	    }
	}
	
	function accountmode_func() {
	    document.getElementById("single_account").style.visibility = "hidden";
	    document.getElementById("multiple_accounts").style.visibility = "hidden";
	    if (document.getElementById("accountmode").selectedIndex == "0") {
	        document.getElementById("single_account").style.visibility = "visible";
	        document.getElementById("account").className = "single_account";
	    } else if (document.getElementById("accountmode").selectedIndex == "1") {
	        document.getElementById("multiple_accounts").style.visibility = "visible";
	        document.getElementById("account").className = "multiple_accounts";
	    }
	}
	
	function sortlist(list) {
	    var elem = document.getElementById(list);
	    var tmpAry = [];

	    for (var i=0;i<elem.options.length;i++)
	        tmpAry.push(elem.options[i]);

	    tmpAry.sort(function(a,b){ return (a.text.toUpperCase() < b.text.toUpperCase())?-1:1; });

	    while (elem.options.length > 0)
                elem.options[0] = null;

	    for (var i=0;i<tmpAry.length;i++) {
	        elem.options[i] = tmpAry[i];
	    }

	    return;
	}

	function move(from, to) {
	    /* add selected item to the 'to' selectbox */
	    for (i = 0; i < document.getElementById(from).length; i++) {
	        if (document.getElementById(from).options[i].selected == true) {
	            document.getElementById(to).options[document.getElementById(to).length] = new Option(
	                document.getElementById(from).options[i].text, document.getElementById(from).options[i].value );
	        }
	    }
	
	    /* remove selected item from the 'from' selectbox */
	    for (i = (document.getElementById(from).length - 1); i >= 0; i--) {
	        if (document.getElementById(from).options[i].selected == true) {
	            document.getElementById(from).options[i] = null;
	        }
	    }
	    sortlist(to);
	}
	
	function markAllAccounts() {
	    for (i = 0; i < document.getElementById("accounts_yes").length; i++) {
	        document.getElementById("accounts_yes").options[i].selected = true;
	    }
	}
	
	
	function submitform(f) {
            markAllAccounts();
            var url = "";     
            win = window.open(url, 'new_window', "width=1024,height=900,status=no,resizable=yes,scrollbars=yes,menubar=no,toolbar=no,location=0");
           document.forms[f].submit();
        }


</script>
<style type="text/css">
#year_caption,#month_caption,#timeframe_caption,#year_data,#month_data,#timeframe_data,#single_account,#multiple_accounts
	{
	position: absolute;
	left: 0em;
	right: 0em;
	top: 0em
}

#time_caption,#time_data
	{
	position: relative;
	height:1.9em;
}
	
div.single_account {
	height:1.9em;
	left: 0em;
	right: 0em;
	top: 0em
}

div.multiple_accounts {
	width: 45.5em;
	height: 16.6em;
	left: 0em;
	right: 0em;
	top: 0em
}
</style>
{/literal}
{$HEADER}
            <td align="center" valign="top">
               <h1>{#TEXT_254#}</h1>
               <form action="{$ENV_INDEX_PHP}" method="POST" target="new_window" name="reporting" onsubmit="submitform('reporting');"> 
                  <input type="hidden" name="action" value="plot_report">
                  <table border="0">
                     <tr>
                        <th rowspan="2">Auswertungsart</th>
                        <td class="contrastbgcolor">
                           <select class="contrastbgcolor" size="1" name="timemode" id="timemode"
                              onchange="timemode_func();">
                              <option value="1">1 {#TEXT_57#}</option>
                              <option value="2">1 {#TEXT_56#}</option>
                              <option value="3">{#TEXT_257#}</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td class="contrastbgcolor">
                           <select class="contrastbgcolor" size="1" name="accountmode" id="accountmode"
                              onchange="accountmode_func();">
                              <option value="1">1 {#TEXT_232#}</option>
                              <option value="2">{#TEXT_258#}</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <th>
                           <div id="time_caption">
                              <div id="year_caption" class="content">{#TEXT_57#}</div>
                              <div id="month_caption" class="content">{#TEXT_56#}</div>
                              <div id="timeframe_caption" class="content">{#TEXT_256#}</div>
                           </div>
                        </th>
                        <td class="contrastbgcolor">
                           <div id="time_data">
                              <div id="year_data">
                                 <select class="contrastbgcolor" size="1" name="year">
 					{section name=YEAR loop=$YEARS}
						<option value="{$YEARS[YEAR]}"> {$YEARS[YEAR]}
					{/section}
                                 </select>
                              </div>
                              <div id="month_data">
                                 <select class="contrastbgcolor" size="1" name="month_month">
 					{section name=MONTH loop=$MONTHS}
						<option value="{$MONTHS[MONTH].value}"> {$MONTHS[MONTH].text}
					{/section}
                                 </select>
                                 <select class="contrastbgcolor" size="1" name="year_month">
 					{section name=YEAR loop=$YEARS}
						<option value="{$YEARS[YEAR]}"> {$YEARS[YEAR]}
					{/section}
                                 </select>
                              </div>
                              <div id="timeframe_data">
                                 <select class="contrastbgcolor" size="1" name="yearfrom">
 					{section name=YEAR loop=$YEARS}
						<option value="{$YEARS[YEAR]}"> {$YEARS[YEAR]}
					{/section}
                                 </select>
                                 - 
                                 <select class="contrastbgcolor" size="1" name="yeartil">
 					{section name=YEAR loop=$YEARS}
						<option value="{$YEARS[YEAR]}"> {$YEARS[YEAR]}
					{/section}
                                 </select>
                              </div>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <th>{#TEXT_232#}</th>
                        <td class="contrastbgcolor">
                           <div id="account" style="position: relative;">
                              &nbsp;
                              <div id="single_account">
                                 <select class="contrastbgcolor" size="1" name="single_account">
					{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
						<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name|escape:htmlall}</option>
					{/section}
                                 </select>
                              </div>
                              <div id="multiple_accounts">
                                 <table border=0 cellpadding=0 cellspacing=0>
                                 <tr><th>{#TEXT_259#}</th><th></th><th>{#TEXT_260#}</th></tr>
                                    <tr>
                                       <td align="center">
                                          <select class="contrastbgcolor" size="10" id="accounts_yes"
                                             name="multiple_accounts[]" multiple
                                             style="width:20em;min-height:15em">
						{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
							<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name|escape:htmlall}</option>
						{/section}
                                          </select>
                                       </td>
                                       <td align="center"><input type="button"
                                          onClick="move('accounts_yes','accounts_no')" value=" >> "><br>
                                          <input type="button"
                                             onClick="move('accounts_no','accounts_yes')" value=" << ">
                                       </td>
                                       <td align="center"><select class="contrastbgcolor" size="10" id="accounts_no"
                                          multiple style="width:20em;min-height:15em"> </select></td>
                                    </tr>
                                 </table>
                              </div>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="2" align="center"><input type="submit"
                           value="{#TEXT_71#}"></td>
                     </tr>
                  </table>
               </form>
            </td>
{$FOOTER}
