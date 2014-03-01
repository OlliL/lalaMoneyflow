<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $USERID > 0}{#TEXT_99#}{else}{#TEXT_100#}{/if}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		{if $USERID gt 0}
			<table><tr><td>
		{/if}
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"     value="edit_user">
			<input type="hidden" name="realaction" value="save">
			<input type="hidden" name="userid"     value="{$USERID}">
			<input type="hidden" name="REFERER"    value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>{#TEXT_85#}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[name]"          size=10 value="{$ALL_DATA.name|escape:htmlall}"></td>
				</tr>
				<tr>
					<th>{#TEXT_86#}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="all_data[password]" size=10 value=""></td>
				</tr>
				<tr>
					<th>{#TEXT_92#}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="all_data[password2]" size=10 value=""></td>
				</tr>
				<tr>
					<th>{#TEXT_96#}</th>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[perm_login]" size=1>
							<option value=0 {if $ALL_DATA.perm_login == 0}selected{/if} > {#TEXT_26#}
							<option value=1 {if $ALL_DATA.perm_login == 1}selected{/if} > {#TEXT_25#}
						</select>
					</td>
				</tr>
				<tr>
					<th>{#TEXT_97#}</th>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[perm_admin]" size=1>
							<option value=0 {if $ALL_DATA.perm_admin == 0}selected{/if} > {#TEXT_26#}
							<option value=1 {if $ALL_DATA.perm_admin == 1}selected{/if} > {#TEXT_25#}
						</select>
					</td>
				</tr>
				<tr>
					<th>{#TEXT_98#}</th>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[att_new]" size=1>
							<option value=0 {if $ALL_DATA.att_new == 0}selected{/if} > {#TEXT_26#}
							<option value=1 {if $ALL_DATA.att_new == 1}selected{/if} > {#TEXT_25#}
						</select>
					</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				</tr>
				<tr>
					<th>{#TEXT_210#}</th>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="access_relation[ref_id]" size=1>
							<option value=""> </option> 
							{section name=GROUP loop=$GROUPS}
								<option {if $GROUPS[GROUP].groupid == $ACCESS_RELATION.ref_id}selected{/if} value="{$GROUPS[GROUP].groupid}">{$GROUPS[GROUP].name|escape:htmlall}</option>
							{/section}
						</select>
					</td>
				</tr>
				{if $USERID gt 0}
				<tr>
					<th>{#TEXT_34#}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="access_relation[validfrom]"          size=10 value="{$ACCESS_RELATION.validfrom}"{if $ACCESS_RELATION.validfrom_error == 1} style="color:red"{/if}></td>
				</tr>
				{/if}
			</table>
			<br>
			<input type="submit" value="{#TEXT_22#}">
			<input type="button" value="{#TEXT_23#}" onclick="javascript:void self.close();">
		</form>
		{if $USERID gt 0}
		</td><td style="border-right:solid #000000"></td>
		<td valign="top" align="center" style="padding-left:10px">

		<h2>{#TEXT_242#}</h2>
		<table>
			<tr>
				<th width="100">{#TEXT_210#}</th>
				<th width="80">{#TEXT_34#}</th>
				<th width="80">{#TEXT_35#}</th>
			<tr/>
			{section name=RELATION loop=$ACCESS_RELATIONS}
			<tr>
				<td class="contrastbgcolor">{$ACCESS_RELATIONS[RELATION].name|escape:htmlall}</td>
				<td class="contrastbgcolor" style="text-align:center;">{$ACCESS_RELATIONS[RELATION].validfrom}</td>
				<td class="contrastbgcolor" style="text-align:center;">{$ACCESS_RELATIONS[RELATION].validtil}</td>
			</tr>
			{/section}
		</table>
		</td></tr></table>
		{/if}
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
