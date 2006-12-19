<html>
	<head><title>lalaMoneyflow: login</title>
{$HEADER}
		<td align="center">
		<br />
		<h1>please login...</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="login_user">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
		<br />
			<table border=0>
				<tr>
					<th>username</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="name" value="{$NAME}" size=10 /></td>
				</tr>
				<tr>
					<th>password</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="password" value="" size=10 /></td>
				</tr>
				<tr>
					<th>stay logged in</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="stay_logged_in" {if $STAY_LOGGED_IN == 1}checked{/if}></td>
				</tr>
			</table>
			<br />
			<input type="submit" name="realaction" value="login">
		</form>
		</td>
{$FOOTER}
