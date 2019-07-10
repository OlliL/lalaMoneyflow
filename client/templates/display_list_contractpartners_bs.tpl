{$HEADER}

{literal}
<script language="JavaScript">
	function mySubmit() {
		if(document.validForm.currently_valid_checkbox.checked) {
			document.validForm.currently_valid.value = "1";
		} else {
			document.validForm.currently_valid.value = "0";
		}
		document.validForm.submit();
	}
</script>
{/literal}


      <div class="container">
        <div class="text-center">
          <h4>{#TEXT_2#}</h4>
        </div>
        <div class="container">

          <div class="text-center">

            <table style="margin: 0 auto;">
              <tr>
                <td>
                  <button class="btn btn-success" onClick="void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;sr=1','_blank','width=600,height=460'); return false;">{#TEXT_29#}</button>

                  &nbsp;
                </td>
                <td>
                  <ul class="pagination">
{if $LETTER eq "all" || ($LETTER eq null && $COUNT_ALL_DATA > 0)}
                    <li class="active"><a href="#">{#TEXT_28#}</a></li>
{else}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_contractpartners&amp;letter=all">{#TEXT_28#}</a></li>
{/if}
{section name=LETTER loop=$ALL_INDEX_LETTERS}
{if $LETTER eq $ALL_INDEX_LETTERS[LETTER]}
                    <li class="active"><a href="#">{$LETTER}</a></li>
{else}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_contractpartners&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a></li>
{/if}
{/section}
                  </ul>
                </td>
              </tr>
            </table>
            <form action="{$ENV_INDEX_PHP}" method="GET" name="validForm">
              <input type="hidden"   name="action"                   value="list_contractpartners">
              <input type="hidden"   name="letter"                   value="{$LETTER}"          >		
              <input type="hidden"   name="currently_valid"          value="{$CURRENTLY_VALID}" >		
              <input type="checkbox" name="currently_valid_checkbox" onchange="mySubmit()" {if $CURRENTLY_VALID eq true}checked{/if}> {#TEXT_287#}
            </form>
          </div>
{if $COUNT_ALL_DATA > 0}
          <br><br>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">{#TEXT_41#}</th>
                <th class="text-center">{#TEXT_34#}</th>
                <th class="text-center">{#TEXT_35#}</th>
                <th class="text-center">{#TEXT_272#}</th>
                <th class="text-center">{#TEXT_232#}</th>
                <th class="text-center" colspan="3"></th>
              </tr>
            </thead>
            <tbody>
{section name=DATA loop=$ALL_DATA}
              <tr>
                <td>{$ALL_DATA[DATA].name}</td>
                <td>{$ALL_DATA[DATA].validfrom}</td>
                <td>{$ALL_DATA[DATA].validtil}</td>
                <td>{$ALL_DATA[DATA].moneyflow_comment}</td>
                <td>{$ALL_DATA[DATA].mpa_postingaccountname}</td>
                <td><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_contractpartner&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=1000,height=150')">{#TEXT_37#}</a></td>
                <td><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=600,height=460')">{#TEXT_36#}</a></td>
                <td><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=list_contractpartneraccounts&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=500,height=350')">{#TEXT_263#}</a></td>
              </tr>
{/section}
            </tbody>
          </table>
{/if}
        </div>
      </div>
{$FOOTER}
