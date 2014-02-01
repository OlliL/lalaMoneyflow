<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_187}</title>
{literal}	
<script type="text/javascript">
var browserType;

if (document.layers) {browserType = "nn4"}
if (document.all) {browserType = "ie"}
if (window.navigator.userAgent.toLowerCase().match("gecko")) {
 browserType= "gecko"
}

function toggle(div_name) {
  if (browserType == "gecko" )
     document.poppedLayer = 
         eval('document.getElementById(div_name)');
  else if (browserType == "ie")
     document.poppedLayer = 
        eval('document.getElementById(div_name)');
  else
     document.poppedLayer = 
         eval('document.layers[div_name]');
  if( document.poppedLayer.style.display == "inline" ) 
     document.poppedLayer.style.display = "none";
  else
     document.poppedLayer.style.display = "inline";
}


</script>
{/literal}
{$HEADER}

		<td align="center" valign="top">
		<h1>{$TEXT_187}</h1>
		<br>
		<br>
		<ul style="margin-left:12px">
		{if $NOT_IN_DB|@count gt 0 }
			<li style="text-align:left;margin:10px"><a href="javascript:toggle('only_in_file_ids')" style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_197} <font color="red">{$NOT_IN_DB|@count}</font></a>
			<div id="only_in_file_ids" style="display: none">
			<table border="0" align="center" cellpadding="2">
				<tr>
					<th width="90"  align="center">{$TEXT_192}</th>
					<th width="90"  align="center">{$TEXT_16}</th>
					<th width="90"  align="center">{$TEXT_17}</th>
					<th width="100" align="center">{$TEXT_18}</th>
					<th width="150" align="center">{$TEXT_2} </th>
					<th width="225" align="center">{$TEXT_21}</th>
					<th width="140" align="center">{$TEXT_19}</th>
					<th width="70"                >&nbsp</th>
					<th width="60"                >&nbsp</th>
				</tr>
				{section name=ID loop=$NOT_IN_DB}
					{assign var='file'   value=$NOT_IN_DB[ID].file}
					<tr>
						<th align="right">{$TEXT_193}</th>
						<td class="contrastbgcolor" align="center">{$file.bookingdate}</td>
						<td class="contrastbgcolor" align="center">{$file.invoicedate}</td>
						<td align="right" class="contrastbgcolor"><font {if $file.amount < 0}color="red"{else}color="black"{/if}>{$file.amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$file.contractpartnername|escape:htmlall}</td>
						<td class="contrastbgcolor">{$file.comment|escape:htmlall}</td>
						<td class="contrastbgcolor">{$CAPITALSOURCECOMMENT|escape:htmlall}</td>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
				{/section}
			</table>
			</div></li>
		{else}
			<li style="text-align:left;margin:10px"><font style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_197} </font><font color="font-weight:bold;text-align:left;font-size:11px;limegreen">0</font></li>
		{/if}
		{if $NOT_IN_FILE|@count gt 0 }
			<li style="text-align:left;margin:10px"><a href="javascript:toggle('only_in_db_ids')" style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_198} <font color="red">{$NOT_IN_FILE|@count}</font></a>
			<div id="only_in_db_ids" style="display: none">
			<table border="0" align="center" cellpadding="2">
				<tr>
					<th width="90"  align="center">{$TEXT_192}</th>
					<th width="90"  align="center">{$TEXT_16}</th>
					<th width="90"  align="center">{$TEXT_17}</th>
					<th width="100" align="center">{$TEXT_18}</th>
					<th width="150" align="center">{$TEXT_2} </th>
					<th width="225" align="center">{$TEXT_21}</th>
					<th width="140" align="center">{$TEXT_19}</th>
					<th width="70"                >&nbsp</th>
					<th width="60"                >&nbsp</th>
				</tr>
				{section name=ID loop=$NOT_IN_FILE}
					{assign var='moneyflow'   value=$NOT_IN_FILE[ID].moneyflow}
					<tr>
						<th align="right">{$TEXT_194}</th>
						<td class="contrastbgcolor" align="center">{$moneyflow.bookingdate}</td>
						<td class="contrastbgcolor" align="center">{$moneyflow.invoicedate}</td>
						<td align="right" class="contrastbgcolor"><font {if $moneyflow.amount < 0}color="red"{else}color="black"{/if}>{$moneyflow.amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$moneyflow.contractpartnername|escape:htmlall}</td>
						<td class="contrastbgcolor">{$moneyflow.comment|escape:htmlall}</td>
						<td class="contrastbgcolor">{$moneyflow.capitalsourcecomment|escape:htmlall}</td>
						{if $moneyflow.owner == true }
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$moneyflow.moneyflowid}','_blank','width=1024,height=120')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$moneyflow.moneyflowid}','_blank','width=1024,height=120')">{$TEXT_37}</a></td>
						{/if}
					</tr>
				{/section}
			</table>
			</div></li>
		{else}
			<li style="text-align:left;margin:10px"><font style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_198} </font><font color="font-weight:bold;text-align:left;font-size:11px;limegreen">0</font></li>
		{/if}
		{if $WRONG_SOURCE|@count gt 0 }
			<li style="text-align:left;margin:10px"><a href="javascript:toggle('diff_source_ids')" style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_196} <font color="red">{$WRONG_SOURCE|@count}</font></a>
			<div id="diff_source_ids" style="display: none">
			<table border="0" align="center" cellpadding="2">
				<tr>
					<th width="90"  align="center">{$TEXT_192}</th>
					<th width="90"  align="center">{$TEXT_16}</th>
					<th width="90"  align="center">{$TEXT_17}</th>
					<th width="100" align="center">{$TEXT_18}</th>
					<th width="150" align="center">{$TEXT_2} </th>
					<th width="225" align="center">{$TEXT_21}</th>
					<th width="140" align="center">{$TEXT_19}</th>
					<th width="70"                >&nbsp</th>
					<th width="60"                >&nbsp</th>
				</tr>
				{section name=ID loop=$WRONG_SOURCE}
					{assign var='file'        value=$WRONG_SOURCE[ID].file}
					{assign var='moneyflow'   value=$WRONG_SOURCE[ID].moneyflow}
					<tr>
						<th align="right">{$TEXT_193}</th>
						<td class="contrastbgcolor" align="center">{$file.bookingdate}</td>
						<td class="contrastbgcolor" align="center">{$file.invoicedate}</td>
						<td align="right" class="contrastbgcolor"><font {if $file.amount < 0}color="red"{else}color="black"{/if}>{$file.amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$file.contractpartnername|escape:htmlall}</td>
						<td class="contrastbgcolor">{$file.comment|escape:htmlall}</td>
						<td class="contrastbgcolor">{$CAPITALSOURCECOMMENT|escape:htmlall}</td>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
	
					<tr>
						<th align="right">{$TEXT_194}</th>
						<td class="contrastbgcolor" align="center">{$moneyflow.bookingdate}</td>
						<td class="contrastbgcolor" align="center">{$moneyflow.invoicedate}</td>
						<td align="right" class="contrastbgcolor"><font {if $moneyflow.amount < 0}color="red"{else}color="black"{/if}>{$moneyflow.amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$moneyflow.contractpartnername|escape:htmlall}</td>
						<td class="contrastbgcolor">{$moneyflow.comment|escape:htmlall}</td>
						<td class="contrastbgcolor">{$moneyflow.capitalsourcecomment|escape:htmlall}</td>
						{if $moneyflow.owner == true }
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$moneyflow.moneyflowid}','_blank','width=1024,height=120')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$moneyflow.moneyflowid}','_blank','width=1024,height=120')">{$TEXT_37}</a></td>
						{/if}
					</tr>
					<tr><td colspan="9">&nbsp;</td></tr>
				{/section}
			</table>
			</div></li>
		{else}
			<li style="text-align:left;margin:10px"><font style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_196} </font><font color="font-weight:bold;text-align:left;font-size:11px;limegreen">0</font></li>
		{/if}
		{if $MATCHING|@count gt 0 }
			<li style="text-align:left;margin:10px"><a href="javascript:toggle('matching_ids')" style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_195} <font color="limegreen">{$MATCHING|@count}</font></a>
			<div id="matching_ids" style="display: none">
			<table border="0" align="center" cellpadding="2">
				<tr>
					<th width="90"  align="center">{$TEXT_192}</th>
					<th width="90"  align="center">{$TEXT_16}</th>
					<th width="90"  align="center">{$TEXT_17}</th>
					<th width="100" align="center">{$TEXT_18}</th>
					<th width="150" align="center">{$TEXT_2} </th>
					<th width="225" align="center">{$TEXT_21}</th>
					<th width="140" align="center">{$TEXT_19}</th>
					<th width="70"                >&nbsp</th>
					<th width="60"                >&nbsp</th>
				</tr>
				{section name=ID loop=$MATCHING}
					{assign var='file'        value=$MATCHING[ID].file}
					{assign var='moneyflow'   value=$MATCHING[ID].moneyflow}
					<tr>
						<th align="right">{$TEXT_193}</th>
						<td class="contrastbgcolor" align="center">{$file.bookingdate}</td>
						<td class="contrastbgcolor" align="center">{$file.invoicedate}</td>
						<td align="right" class="contrastbgcolor"><font {if $file.amount < 0}color="red"{else}color="black"{/if}>{$file.amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$file.contractpartnername|escape:htmlall}</td>	
						<td class="contrastbgcolor">{$file.comment|escape:htmlall}</td>
						<td class="contrastbgcolor">{$CAPITALSOURCECOMMENT|escape:htmlall}</td>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
	
					<tr>
						<th align="right">{$TEXT_194}</th>
						<td class="contrastbgcolor" align="center">{$moneyflow.bookingdate}</td>
						<td class="contrastbgcolor" align="center">{$moneyflow.invoicedate}</td>
						<td align="right" class="contrastbgcolor"><font {if $moneyflow.amount < 0}color="red"{else}color="black"{/if}>{$moneyflow.amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$moneyflow.contractpartnername|escape:htmlall}</td>
						<td class="contrastbgcolor">{$moneyflow.comment|escape:htmlall}</td>
						<td class="contrastbgcolor">{$moneyflow.capitalsourcecomment|escape:htmlall}</td>
						{if $moneyflow.owner == true }
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$moneyflow.moneyflowid}','_blank','width=1024,height=120')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$moneyflow.moneyflowid}','_blank','width=1024,height=120')">{$TEXT_37}</a></td>
						{/if}
					</tr>
					<tr><td colspan="9">&nbsp;</td></tr>
				{/section}
			</table>
			</div></li>
		{else}
			<li style="text-align:left;margin:10px"><font style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_195} </font><font color="font-weight:bold;text-align:left;font-size:11px;limegreen">0</font></li>
		{/if}
		</ul>
		</td>
{$FOOTER}
