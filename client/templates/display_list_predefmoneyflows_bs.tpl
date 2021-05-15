{$HEADER}
      <div class="container">
        <div class="text-center">
          <h4>{#TEXT_3#}</h4>
        </div>
        <div class="container">

          <div class="text-center">

            <table style="margin: 0 auto;">
              <tr>
                <td>
                  <button class="btn btn-success" onClick="void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;sr=1','_blank','width=920,height=120'); return false;">{#TEXT_29#}</button>
                  &nbsp;
                </td>
                <td>
                  <ul class="pagination">
{if $LETTER eq "all" || ($LETTER eq null && $COUNT_ALL_DATA > 0)}
                    <li class="active"><a href="#">{#TEXT_28#}</a></li>
{else}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&amp;letter=all">{#TEXT_28#}</a></li>
{/if}
{section name=LETTER loop=$ALL_INDEX_LETTERS}
{if $LETTER eq $ALL_INDEX_LETTERS[LETTER]}
                    <li class="active"><a href="#">{$LETTER}</a></li>
{else}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a></li>
{/if}
{/section}
                  </ul>
                </td>
              </tr>
            </table>
            <form action="{$ENV_INDEX_PHP}" method="GET" name="validForm">
              <input type="hidden"   name="action"                   value="list_predefmoneyflows">
              <input type="hidden"   name="letter"                   value="{$LETTER}"          >		
            </form>
          </div>
{if $COUNT_ALL_DATA > 0}
          <br><br>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">{#TEXT_18#}</th>
                <th class="text-center">{#TEXT_2#}</th>
                <th class="text-center">{#TEXT_21#}</th>
                <th class="text-center">{#TEXT_232#}</th>
                <th class="text-center">{#TEXT_19#}</th>
                <th class="text-center">{#TEXT_206#}</th>
                <th class="text-center">{#TEXT_207#}</th>
                <th class="text-center" colspan="2"></th>
              </tr>
            </thead>
            <tbody>
{section name=DATA loop=$ALL_DATA}
              <tr>
                <td class="text-right of_number_to_be_evaluated">{$ALL_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
                <td>{$ALL_DATA[DATA].contractpartnername}</td>
                <td>{$ALL_DATA[DATA].comment}</td>
                <td>{$ALL_DATA[DATA].postingaccountname}</td>
                <td>{$ALL_DATA[DATA].capitalsourcecomment}</td>
                <td class="text-center">{if $ALL_DATA[DATA].once_a_month == 1}{#TEXT_25#}{else}{#TEXT_26#}{/if}</td>
                <td class="text-center">{$ALL_DATA[DATA].last_used}</td>
                <td class="text-center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;predefmoneyflowid={$ALL_DATA[DATA].predefmoneyflowid}&amp;sr=1','_blank','width=920,height=120')">{#TEXT_36#}</a></td>
                <td class="text-center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_predefmoneyflow&amp;predefmoneyflowid={$ALL_DATA[DATA].predefmoneyflowid}&amp;sr=1','_blank','width=920,height=120')">{#TEXT_37#}</a></td>
              </tr>
{/section}
            </tbody>
          </table>
{/if}
        </div>
      </div>

      <script>
        $("td.of_number_to_be_evaluated:contains('-')").addClass('red');
      </script>

{$FOOTER}