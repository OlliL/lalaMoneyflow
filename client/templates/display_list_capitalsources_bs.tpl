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
          <h4>{#TEXT_1#}</h4>
        </div>
        <div class="container">

          <div class="text-center">

            <table style="margin: 0 auto;">
              <tr>
                <td>
                  <button class="btn btn-success" onClick="void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&amp;sr=1','_blank','width=600,height=650'); return false;">{#TEXT_29#}</button>

                  &nbsp;
                </td>
                <td>
                  <ul class="pagination">
{if $LETTER eq "all" || ($LETTER eq null && $COUNT_ALL_DATA > 0)}
                    <li class="active"><a href="#">{#TEXT_28#}</a></li>
{else}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_capitalsources&amp;letter=all">{#TEXT_28#}</a></li>
{/if}
{section name=LETTER loop=$ALL_INDEX_LETTERS}
{if $LETTER eq $ALL_INDEX_LETTERS[LETTER]}
                    <li class="active"><a href="#">{$LETTER}</a></li>
{else}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_capitalsources&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a></li>
{/if}
{/section}
                  </ul>
                </td>
              </tr>
            </table>
            <form action="{$ENV_INDEX_PHP}" method="GET" name="validForm">
              <input type="hidden"   name="action"                   value="list_capitalsources">
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
                <th class="text-center">{#TEXT_21#}</th>
                <th class="text-center">{#TEXT_30#}</th>
                <th class="text-center">{#TEXT_31#}</th>
                <th class="text-center">{#TEXT_32#}</th>
                <th class="text-center">{#TEXT_33#}</th>
                <th class="text-center">{#TEXT_34#}</th>
                <th class="text-center">{#TEXT_35#}</th>
                <th class="text-center">{#TEXT_210#}</th>
                <th class="text-center">{#TEXT_282#}</th>
                <th class="text-center" colspan="2"></th>
              </tr>
            </thead>
            <tbody>
{section name=DATA loop=$ALL_DATA}
              <tr>
                <td style="white-space: nowrap;">{$ALL_DATA[DATA].comment}</td>
                <td>{$ALL_DATA[DATA].typecomment}</td>
                <td>{$ALL_DATA[DATA].statecomment}</td>
                <td>{$ALL_DATA[DATA].accountnumber}</td>
                <td>{$ALL_DATA[DATA].bankcode}</td>
                <td>{$ALL_DATA[DATA].validfrom}</td>
                <td>{$ALL_DATA[DATA].validtil}</td>
                <td class="text-center"><b>{if $ALL_DATA[DATA].att_group_use == 1}<font color="green">{#TEXT_25#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
                <td class="text-center"><b>{if $ALL_DATA[DATA].import_allowed == 1}<font color="green">{#TEXT_28#}{elseif $ALL_DATA[DATA].import_allowed == 2}<font color="green">{#TEXT_298#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
{if $ALL_DATA[DATA].owner == true }
                <td class="text-center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&amp;capitalsourceid={$ALL_DATA[DATA].capitalsourceid}&amp;sr=1','_blank','width=600,height=650')">{#TEXT_36#}</a></td>
                <td class="text-center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_capitalsource&amp;capitalsourceid={$ALL_DATA[DATA].capitalsourceid}&amp;sr=1','_blank','width=500,height=350')">{#TEXT_37#}</a></td>
{/if}
              </tr>
{/section}
            </tbody>
          </table>
{/if}
        </div>
      </div>
{$FOOTER}