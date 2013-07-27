<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_187}</title>
{literal}	
<script>
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
		<ul style="margin-left:12px">
		{if $ONLY_IN_FILE_IDS|@count gt 0 }
			<br>
			<li><a href="javascript:toggle('only_in_file_ids')"><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_197} <font color="red">{$ONLY_IN_FILE_IDS|@count}</font></p></a></li>
			<div id="only_in_file_ids" style="display: none">
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%"> {$TEXT_192}</th>
					<th width="9%"> {$TEXT_16}</th>
					<th width="9%"> {$TEXT_17}</th>
					<th width="10%">{$TEXT_18}</th>
					<th width="16%">{$TEXT_2} </th>
					<th >           {$TEXT_21}</th>
					<th width="22%">{$TEXT_19}</th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{section name=ID loop=$ONLY_IN_FILE_IDS}
					{assign var='file_id'   value=$ONLY_IN_FILE_IDS[ID].file}
					<tr>
						<th align="right">{$TEXT_193}</tdh>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$FILE_ARRAY[$file_id].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$FILE_ARRAY[$file_id].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $FILE_ARRAY[$file_id].amount < 0}color="red"{else}color="black"{/if}>{$FILE_ARRAY[$file_id].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].contractpartnername}</td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].comment}</td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].capitalsourcecomment}</td>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
				{/section}
			</table>
			</div>
		{else}
			<li><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_197} <font color="limegreen">0</font></p></li>
		{/if}
		{if $ONLY_IN_DB_IDS|@count gt 0 }
			<li><a href="javascript:toggle('only_in_db_ids')"><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_198} <font color="red">{$ONLY_IN_DB_IDS|@count}</font></p></a></li>
			<div id="only_in_db_ids" style="display: none">
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%"> {$TEXT_192}</th>
					<th width="9%"> {$TEXT_16}</th>
					<th width="9%"> {$TEXT_17}</th>
					<th width="10%">{$TEXT_18}</th>
					<th width="16%">{$TEXT_2} </th>
					<th >           {$TEXT_21}</th>
					<th width="22%">{$TEXT_19}</th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{section name=ID loop=$ONLY_IN_DB_IDS}
					{assign var='db_id'   value=$ONLY_IN_DB_IDS[ID].db}
					<tr>
						<th align="right">{$TEXT_194}</tdh>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$DB_ARRAY[$db_id].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$DB_ARRAY[$db_id].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $DB_ARRAY[$db_id].amount < 0}color="red"{else}color="black"{/if}>{$DB_ARRAY[$db_id].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].contractpartnername}</td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].comment}</td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].capitalsourcecomment}</td>
						{if $DB_ARRAY[$db_id].owner == true }
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$DB_ARRAY[$db_id].moneyflowid}','_blank','width=1024,height=80')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$DB_ARRAY[$db_id].moneyflowid}','_blank','width=1024,height=80')">{$TEXT_37}</a></td>
						{/if}
					</tr>
				{/section}
			</table>
			</div>
		{else}
			<li><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_198} <font color="limegreen">0</font></p></li>
		{/if}
		{if $DIFF_SOURCE_IDS|@count gt 0 }
			<li><a href="javascript:toggle('diff_source_ids')"><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_196} <font color="red">{$DIFF_SOURCE_IDS|@count}</font></p></a></li>
			<div id="diff_source_ids" style="display: none">
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%"> {$TEXT_192}</th>
					<th width="9%"> {$TEXT_16}</th>
					<th width="9%"> {$TEXT_17}</th>
					<th width="10%">{$TEXT_18}</th>
					<th width="16%">{$TEXT_2} </th>
					<th >           {$TEXT_21}</th>
					<th width="22%">{$TEXT_19}</th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{section name=ID loop=$DIFF_SOURCE_IDS}
					{assign var='file_id' value=$DIFF_SOURCE_IDS[ID].file}
					{assign var='db_id'   value=$DIFF_SOURCE_IDS[ID].db}
					<tr>
						<th align="right">{$TEXT_193}</tdh>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$FILE_ARRAY[$file_id].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$FILE_ARRAY[$file_id].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $FILE_ARRAY[$file_id].amount < 0}color="red"{else}color="black"{/if}>{$FILE_ARRAY[$file_id].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].contractpartnername}</td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].comment}</td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].capitalsourcecomment}</td>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
	
					<tr>
						<th align="right">{$TEXT_194}</th>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$DB_ARRAY[$db_id].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$DB_ARRAY[$db_id].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $DB_ARRAY[$db_id].amount < 0}color="red"{else}color="black"{/if}>{$DB_ARRAY[$db_id].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].contractpartnername}</td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].comment}</td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].capitalsourcecomment}</td>
						{if $DB_ARRAY[$db_id].owner == true }
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$DB_ARRAY[$db_id].moneyflowid}','_blank','width=1024,height=80')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$DB_ARRAY[$db_id].moneyflowid}','_blank','width=1024,height=80')">{$TEXT_37}</a></td>
						{/if}
					</tr>
					<tr><td colspan=6>&nbsp;</td></tr>
				{/section}
			</table>
			</div>
		{else}
			<li><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_196} <font color="limegreen">0</font></p></li>
		{/if}
		{if $MATCHING_IDS|@count gt 0 }
			<li><a href="javascript:toggle('matching_ids')"><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_195} <font color="limegreen">{$MATCHING_IDS|@count}</font></p></a></li>
			<div id="matching_ids" style="display: none">
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%"> {$TEXT_192}</th>
					<th width="9%"> {$TEXT_16}</th>
					<th width="9%"> {$TEXT_17}</th>
					<th width="10%">{$TEXT_18}</th>
					<th width="16%">{$TEXT_2} </th>
					<th >           {$TEXT_21}</th>
					<th width="22%">{$TEXT_19}</th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{section name=ID loop=$MATCHING_IDS}
					{assign var='file_id' value=$MATCHING_IDS[ID].file}
					{assign var='db_id'   value=$MATCHING_IDS[ID].db}
					<tr>
						<th align="right">{$TEXT_193}</tdh>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$FILE_ARRAY[$file_id].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$FILE_ARRAY[$file_id].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $FILE_ARRAY[$file_id].amount < 0}color="red"{else}color="black"{/if}>{$FILE_ARRAY[$file_id].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].contractpartnername}</td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].comment}</td>
						<td class="contrastbgcolor">{$FILE_ARRAY[$file_id].capitalsourcecomment}</td>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
	
					<tr>
						<th align="right">{$TEXT_194}</th>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$DB_ARRAY[$db_id].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$DB_ARRAY[$db_id].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $DB_ARRAY[$db_id].amount < 0}color="red"{else}color="black"{/if}>{$DB_ARRAY[$db_id].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].contractpartnername}</td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].comment}</td>
						<td class="contrastbgcolor">{$DB_ARRAY[$db_id].capitalsourcecomment}</td>
						{if $DB_ARRAY[$db_id].owner == true }
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$DB_ARRAY[$db_id].moneyflowid}','_blank','width=1024,height=80')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$DB_ARRAY[$db_id].moneyflowid}','_blank','width=1024,height=80')">{$TEXT_37}</a></td>
						{/if}
					</tr>
					<tr><td colspan=6>&nbsp;</td></tr>
				{/section}
			</table>
			</div>
		{else}
			<li><p style="font-weight:bold;text-align:left;font-size:11px;">{$TEXT_195} <font color="limegreen">0</font></p></li>
		{/if}
		</ul>
		</td>
{$FOOTER}
