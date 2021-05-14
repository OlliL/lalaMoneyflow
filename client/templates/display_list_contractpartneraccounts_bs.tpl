{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{$CONTRACTPARTNER_NAME}: {#TEXT_263#}</h4>
        </div>
        <div class="row text-center">
          <button class="btn btn-success" onClick="void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartneraccount&amp;contractpartnerid={$CONTRACTPARTNERID}&amp;sr=1','_blank','width=400,height=120'); return false;">{#TEXT_29#}</button>
        </div> 
        <div class="row text-center">
{if $COUNT_ALL_DATA > 0}
          <br>
          <table class="table table-striped table-bordered table-hover">
            <tr>
              <th class="text-center">{#TEXT_32#}</th>
              <th class="text-center">{#TEXT_33#}</th>
            </tr>
{section name=DATA loop=$ALL_DATA}
            <tr>
              <td class="contrastbgcolor">{$ALL_DATA[DATA].accountnumber}</td>
              <td class="contrastbgcolor">{$ALL_DATA[DATA].bankcode}</td>
              <td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_contractpartneraccount&amp;contractpartnerid={$CONTRACTPARTNERID}&amp;contractpartneraccountid={$ALL_DATA[DATA].contractpartneraccountid}&amp;sr=1','_blank','width=400,height=120')">{#TEXT_37#}</a></td>
              <td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartneraccount&amp;contractpartnerid={$CONTRACTPARTNERID}&amp;contractpartneraccountid={$ALL_DATA[DATA].contractpartneraccountid}&amp;sr=1','_blank','width=400,height=120')">{#TEXT_36#}</a></td>
            </tr>
{/section}
          </table>
{/if}
        </div>
      </div>
{$FOOTER}
