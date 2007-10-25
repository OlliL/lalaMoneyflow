<html>
	<head><title>lalaMoneyflow: {$TEXT_187}</title>
{$HEADER}

		<td align="center">
		<h1>{$TEXT_187}</h1>
		<br />
		<h2>{$TEXT_196}</h2>
		<table border=0 width=830 align="center" cellpadding=2>
			<tr>
				<th width="9%"> {$TEXT_192}</th>
				<th width="9%"> {$TEXT_16}</th>
				<th width="9%"> {$TEXT_17}</th>
				<th width="10%">{$TEXT_18}</th>
				<th width="16%">{$TEXT_2} </th>
				<th >           {$TEXT_21}</th>
				<th width="22%">{$TEXT_19}</th>
			</tr>
			{section name=DATA loop=$ALL_NOT_CMP_DATA}
				<tr>
					<th align="right">{$TEXT_193}</tdh>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_NOT_CMP_DATA[DATA].bookingdate}</p></td>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_NOT_CMP_DATA[DATA].invoicedate}</p></td>
					<td align="right" class="contrastbgcolor"><font {if $ALL_NOT_CMP_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_NOT_CMP_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
					<td class="contrastbgcolor">{$ALL_NOT_CMP_DATA[DATA].contractpartnername}</td>
					<td class="contrastbgcolor">{$ALL_NOT_CMP_DATA[DATA].comment}</td>
					<td class="contrastbgcolor">{$ALL_NOT_CMP_DATA[DATA].capitalsourcecomment}</td>
				</tr>
			{/section}
		</table>
		<br />
		<h2>{$TEXT_196}</h2>
		<table border=0 width=830 align="center" cellpadding=2>
			<tr>
				<th width="9%"> {$TEXT_192}</th>
				<th width="9%"> {$TEXT_16}</th>
				<th width="9%"> {$TEXT_17}</th>
				<th width="10%">{$TEXT_18}</th>
				<th width="16%">{$TEXT_2} </th>
				<th >           {$TEXT_21}</th>
				<th width="22%">{$TEXT_19}</th>
			</tr>
			{section name=DATA loop=$ALL_NOT_MON_DATA}
				<tr>
					<th align="right">{$TEXT_194}</tdh>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_NOT_MON_DATA[DATA].bookingdate}</p></td>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_NOT_MON_DATA[DATA].invoicedate}</p></td>
					<td align="right" class="contrastbgcolor"><font {if $ALL_NOT_MON_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_NOT_MON_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
					<td class="contrastbgcolor">{$ALL_NOT_MON_DATA[DATA].contractpartnername}</td>
					<td class="contrastbgcolor">{$ALL_NOT_MON_DATA[DATA].comment}</td>
					<td class="contrastbgcolor">{$ALL_NOT_MON_DATA[DATA].capitalsourcecomment}</td>
				</tr>
			{/section}
		</table>
		<br />
		<br />
		<br />
		<h2>{$TEXT_195}</h2>
		<table border=0 width=830 align="center" cellpadding=2>
			<tr>
				<th width="9%"> {$TEXT_192}</th>
				<th width="9%"> {$TEXT_16}</th>
				<th width="9%"> {$TEXT_17}</th>
				<th width="10%">{$TEXT_18}</th>
				<th width="16%">{$TEXT_2} </th>
				<th >           {$TEXT_21}</th>
				<th width="22%">{$TEXT_19}</th>
			</tr>
			{section name=DATA loop=$ALL_CMP_DATA}
				<tr>
					<th align="right">{$TEXT_193}</tdh>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_CMP_DATA[DATA].cmp.bookingdate}</p></td>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_CMP_DATA[DATA].cmp.invoicedate}</p></td>
					<td align="right" class="contrastbgcolor"><font {if $ALL_CMP_DATA[DATA].cmp.amount < 0}color="red"{else}color="black"{/if}>{$ALL_CMP_DATA[DATA].cmp.amount|number_format} {$CURRENCY}</font></td>
					<td class="contrastbgcolor">{$ALL_CMP_DATA[DATA].cmp.contractpartnername}</td>
					<td class="contrastbgcolor">{$ALL_CMP_DATA[DATA].cmp.comment}</td>
					<td class="contrastbgcolor">{$ALL_CMP_DATA[DATA].cmp.capitalsourcecomment}</td>
				</tr>

				<tr>
					<th align="right">{$TEXT_194}</th>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_CMP_DATA[DATA].mon.bookingdate}</p></td>
					<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_CMP_DATA[DATA].mon.invoicedate}</p></td>
					<td align="right" class="contrastbgcolor"><font {if $ALL_CMP_DATA[DATA].mon.amount < 0}color="red"{else}color="black"{/if}>{$ALL_CMP_DATA[DATA].mon.amount|number_format} {$CURRENCY}</font></td>
					<td class="contrastbgcolor">{$ALL_CMP_DATA[DATA].mon.contractpartnername}</td>
					<td class="contrastbgcolor">{$ALL_CMP_DATA[DATA].mon.comment}</td>
					<td class="contrastbgcolor">{$ALL_CMP_DATA[DATA].mon.capitalsourcecomment}</td>
				</tr>
				<tr><td colspan=6>&nbsp;</td></tr>
			{/section}
		</table>
		</td>
{$FOOTER}
