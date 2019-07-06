{$HEADER}
{literal}
<script>
<!--
function Go(x)
{
 if(x == "nothing")
 {
   document.monthlysettlements_year.reset();
   document.monthlysettlements_year.elements[0].blur();
   return;
 }
 else
  {
   location.href = x;
   document.monthlysettlements.reset();
   document.monthlysettlements.elements[0].blur();
  }
}
//-->
</script>
{/literal}


{math equation="y * x + z" y=$NUM_ADDABLE_SETTLEMENTS x=35 z=135 assign=ADD_WIN_HEIGHT}
      <div>
        <div class="text-center">
          <h4>{#TEXT_4#}</h4>
        </div>


        <div class="text-center">
          <form action="#" method="get" class="form-inline">
            <table style="margin: 0 auto;">
              <tr>
                <td>
                  <button class="btn btn-success" onClick="void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&amp;sr=1','_blank','width=500,height={$ADD_WIN_HEIGHT}'); return false;">{#TEXT_29#}</button>
                  &nbsp;
                </td>
{if {$ALL_YEARS|@count} gt 0}
                <td class="text-right">
                  <select class="form-control"  name="monthlysettlements_year" size=1 onchange="Go(this.form.monthlysettlements_year.options[this.form.monthlysettlements_year.options.selectedIndex].value)">
  {section name=YEAR loop=$ALL_YEARS}
                    <option {if $ALL_YEARS[YEAR] == $SELECTED_YEAR}selected{/if} value="{$ENV_INDEX_PHP}?action=list_monthlysettlements&amp;monthlysettlements_year={$ALL_YEARS[YEAR]}"> {$ALL_YEARS[YEAR]}
  {/section}
                  </select>
                  &nbsp;
                </td>
                <td>
                  <ul class="pagination">
  {section name=MONTH loop=$ALL_MONTHS}
    {if $ALL_MONTHS[MONTH].nummeric ne $SELECTED_MONTH}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements&amp;monthlysettlements_month={$ALL_MONTHS[MONTH].nummeric}&amp;monthlysettlements_year={$SELECTED_YEAR}">{$ALL_MONTHS[MONTH].name}</a></li> 
    {else}
                    <li class="active"><a href="#">{$ALL_MONTHS[MONTH].name}</a></li>
    {/if}
  {/section}
                  </ul>
                </td>
{/if}
		</tr>
	      </table>
	  </form>



        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>

{if $COUNT_ALL_DATA > 0}
{math equation="y * x + z" y=$NUM_EDITABLE_SETTLEMENTS x=60 z=200 assign=EDIT_WIN_HEIGHT}

        <div class="row">
          <div class="col-lg-4 col-lg-push-4 col-sm-6 col-sm-push-3">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                <h4>{#TEXT_53#} {$MONTH.name} {$YEAR}</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
                      <col style="width:60%">
                      <col style="width:40%">

                      <thead>
                        <tr>
                          <th class="text-center">{#TEXT_19#}</th>
                          <th class="text-center">{#TEXT_18#}</th>
                        </tr>
                      </thead>

                      <tbody>

	{section name=DATA loop=$ALL_DATA}
		{if $ALL_DATA[DATA].capitalsourcetype != 5}
			<tr>
			  <td class="text-left">{$ALL_DATA[DATA].capitalsourcecomment}</td>
			  <td class="text-right of_number_to_be_evaluated">{$ALL_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
			</tr>
		{else}
			{assign var=CREDIT_EXISTS value=1}
		{/if}
	{/section}
			<tr>
                          <td class="text-right">&sum;</td>
                          <td class="text-right of_number_to_be_evaluated"><u>{$SUMAMOUNT|number_format} {#CURRENCY#}</u></td>
			</tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                
	{if $CREDIT_EXISTS == 1 }
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
                      <col style="width:60%">
                      <col style="width:40%">

                      <tbody>

	{section name=DATA loop=$ALL_DATA}
		{if $ALL_DATA[DATA].capitalsourcetype == 5}
			<tr>
			  <td class="text-left">{$ALL_DATA[DATA].capitalsourcecomment}</td>
			  <td class="text-right of_number_to_be_evaluated">{$ALL_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
			</tr>
		{/if}
	{/section}
			<tr>
                          <td class="text-right">&sum;</td>
                          <td class="text-right of_number_to_be_evaluated"><u>{$CREDIT_SUMAMOUNT|number_format} {#CURRENCY#}</u></td>
			</tr>
                      </tbody>
                    </table>
                  </div>
                </div>
 	{/if}
              </div>
            </div>
          </div>
        </div>

      <script>
        $("td.of_number_to_be_evaluated:contains('-')").addClass('red');
      </script>

      <div class="form-group">
        <div class="col-sm-12 text-center">
          <button type="submit" class="btn btn-primary" onClick="void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&amp;monthlysettlements_month={$MONTH.nummeric}&amp;monthlysettlements_year={$YEAR}&amp;sr=1','_blank','width=500,height={$EDIT_WIN_HEIGHT}')"   >{#TEXT_36#}</button>
          <button type="button" class="btn btn-danger"  onclick="void window.open('{$ENV_INDEX_PHP}?action=delete_monthlysettlement&amp;monthlysettlements_month={$MONTH.nummeric}&amp;monthlysettlements_year={$YEAR}&amp;sr=1','_blank','width=600,height={$EDIT_WIN_HEIGHT}')" >{#TEXT_37#}</button>
        </div>  
      </div>  
{/if}

    </div>
  </div>
{$FOOTER}
