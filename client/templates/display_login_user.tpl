<html>
	<head><title>lalaMoneyflow: {$TEXT_84}</title>
{$HEADER}
		<td align="center">
		<br />
		<h1>{$TEXT_84}</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="login_user">
			<input type="hidden" name="realaction" value="login">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
		<br />
			<table border=0>
				<tr>
					<th>{$TEXT_85}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="name" value="{$NAME}" size=10 /></td>
				</tr>
				<tr>
					<th>{$TEXT_86}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="password" value="" size=10 /></td>
				</tr>
				<tr>
					<th>{$TEXT_87}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="stay_logged_in" {if $STAY_LOGGED_IN == 1}checked{/if}></td>
				</tr>
			</table>
			<br />
			<input type="submit" value="{$TEXT_88}">
		</form>
		<br />
		<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">lalaMoneyflow {$VERSION} - &copy by Oliver Lehmann</p>
		
		</td>
{$FOOTER}
