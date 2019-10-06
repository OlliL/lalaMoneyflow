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

	function move(from, to, all) {
	    /* add selected item to the 'to' selectbox */
	    for (i = 0; i < document.getElementById(from).length; i++) {
	        if (all || document.getElementById(from).options[i].selected == true) {
	            document.getElementById(to).options[document.getElementById(to).length] = new Option(
	                document.getElementById(from).options[i].text, document.getElementById(from).options[i].value );
	        }
	    }
	
	    /* remove selected item from the 'from' selectbox */
	    for (i = (document.getElementById(from).length - 1); i >= 0; i--) {
	        if (all || document.getElementById(from).options[i].selected == true) {
	            document.getElementById(from).options[i] = null;
	        }
	    }
	    sortlist(to);
	}
	
	function markAllAccounts(select) {
	    for (i = 0; i < document.getElementById("accounts_yes").length; i++) {
	        document.getElementById("accounts_yes").options[i].selected = select;
	    }
	    for (i = 0; i < document.getElementById("accounts_no").length; i++) {
	        document.getElementById("accounts_no").options[i].selected = select;
	    }
	}
	
	
	function submitform(f) {
            markAllAccounts(true);
            var url = "";     
            win = window.open(url, 'new_window', "width=1024,height=600,status=no,resizable=yes,scrollbars=yes,menubar=no,toolbar=no,location=0");
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
               <form action="{$ENV_INDEX_PHP}" method="POST" target="new_window" name="reporting" onsubmit="submitform('reporting');markAllAccounts(false);return false; "> 
                  <input type="hidden" name="action" value="plot_report">
                  <table border="0">
                     <tr>
                        <th rowspan="2">Auswertungsart</th>
                        <td class="contrastbgcolor">
                           <select class="contrastbgcolor" size="1" name="timemode" id="timemode"
                              onchange="timemode_func();">
                              <option value="1">1 {#TEXT_57#}</option>
                              <option value="2" selected>1 {#TEXT_56#}</option>
                              <option value="3">{#TEXT_257#}</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td class="contrastbgcolor">
                           <select class="contrastbgcolor" size="1" name="accountmode" id="accountmode"
                              onchange="accountmode_func();">
                              <option value="1">1 {#TEXT_232#}</option>
                              <option value="2" selected>{#TEXT_258#}</option>
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
						<option value="{$YEARS[YEAR]}" {if $YEARS[YEAR] == $CURRENT_YEAR}selected{/if}> {$YEARS[YEAR]}
					{/section}
                                 </select>
                              </div>
                              <div id="month_data">
                                 <select class="contrastbgcolor" size="1" name="month_month">
 					{section name=MONTH loop=$MONTHS}
						<option value="{$MONTHS[MONTH].value}" {if $MONTHS[MONTH].value == $CURRENT_MONTH}selected{/if}> {$MONTHS[MONTH].text}
					{/section}
                                 </select>
                                 <select class="contrastbgcolor" size="1" name="year_month">
 					{section name=YEAR loop=$YEARS}
						<option value="{$YEARS[YEAR]}" {if $YEARS[YEAR] == $CURRENT_YEAR}selected{/if}> {$YEARS[YEAR]}
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
						<option value="{$YEARS[YEAR]}" {if $YEARS[YEAR] == $CURRENT_YEAR}selected{/if}> {$YEARS[YEAR]}
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
                                 <select class="contrastbgcolor" size="1" name="account">
					{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
						<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
					{/section}
                                 </select>
                              </div>
                              <div id="multiple_accounts">
                                 <table border=0 cellpadding=0 cellspacing=0>
                                 <tr><th>{#TEXT_259#}</th><th></th><th>{#TEXT_260#}</th></tr>
                                    <tr>
                                       <td align="center">
                                          <select class="contrastbgcolor" size="10" id="accounts_yes"
                                             name="accounts_yes[]" multiple
                                             style="width:20em;min-height:15em">
						{section name=ACCOUNT loop=$ACCOUNTS_YES}
							<option value="{$ACCOUNTS_YES[ACCOUNT].postingaccountid}"> {$ACCOUNTS_YES[ACCOUNT].name}</option>
						{/section}
                                          </select>
                                       </td>
                                       <td align="center">
                                          <input type="button" onClick="move('accounts_yes','accounts_no', true)" value=" >> "><br>
                                          <input type="button" onClick="move('accounts_yes','accounts_no', false)" value=" > "><br><br>
                                          <input type="button" onClick="move('accounts_no','accounts_yes', false)" value=" < "><br>
                                          <input type="button" onClick="move('accounts_no','accounts_yes', true)" value=" << "><br>
                                       </td>
                                       <td align="center">
                                          <select class="contrastbgcolor" size="10" id="accounts_no"
                                             name="accounts_no[]" multiple 
                                             style="width:20em;min-height:15em">
						{section name=ACCOUNT loop=$ACCOUNTS_NO}
							<option value="{$ACCOUNTS_NO[ACCOUNT].postingaccountid}"> {$ACCOUNTS_NO[ACCOUNT].name}</option>
						{/section}
                                          </select></td>
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
