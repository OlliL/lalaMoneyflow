<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_84#}</title>
{$HEADER}
		<td align="center">
		<br>
		<h1>{#TEXT_84#}</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST" name="loginform">
			<input type="hidden" name="action" value="login_user">
			<input type="hidden" name="realaction" value="login">
			<input type="hidden" name="request_uri" value="{$REQUEST_URI}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
			{/section}
		<br>
			<table border=0>
				<tr>
					<th>{#TEXT_85#}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="name" value="{$NAME}" size=10></td>
				</tr>
{literal}
<script language="JavaScript">
  document.loginform.name.focus();
</script>
{/literal}
				<tr>
					<th>{#TEXT_86#}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="password" value="" size=10></td>
				</tr>
			</table>
			<br>
			<input type="submit" value="{#TEXT_88#}">
		</form>
		<br>
		<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">lalaMoneyflow {$VERSION} - &copy by Oliver Lehmann</p>
		
		</td>
{$FOOTER}
