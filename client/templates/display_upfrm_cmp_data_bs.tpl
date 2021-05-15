{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_187#}</h4>
        </div>

        <div class="row">
          <div class="col-xs-8">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="searchmoneyflow" id="cmpfrmform" enctype="multipart/form-data">
          <input type="hidden" name="action" value="analyze_cmp_data">

          <div class="span2 well">

            <div id="searchMoneyflowErrorsGoHere">
            </div>

     <div class="row">
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="cmpfrmstartdateDiv">
                  <input type="text" class="form-control" name="all_data[startdate]" id="cmpfrmstartdate" value="{$ALL_DATA.startdate}" required data-error="{#TEXT_329#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="cmpfrmstartdate">{#TEXT_69#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#cmpfrmstartdateDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true
                      });
                  });
              </script>
            </div>

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="cmpfrmenddateDiv">
                  <input type="text" class="form-control" name="all_data[enddate]" id="cmpfrmenddate" value="{$ALL_DATA.enddate}" required data-error="{#TEXT_330#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="cmpfrmenddate">{#TEXT_70#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#cmpfrmenddateDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true, 
                      });
                  });
              </script>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcs_capitalsourceid]" id="cmpfrmcs_capitalsourceid">
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
                    <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"  {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA.mcs_capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
                  </select>
                </div>
                <label for="cmpfrmcs_capitalsourceid">{#TEXT_19#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-1 col-xs-12">
              <input id="cmpfrmuse_imported_data"  data-toggle="toggle" value="1" data-on="{#TEXT_282#}" data-off="{#TEXT_193#}" type="checkbox" {if $ALL_DATA.use_imported_data == 1}checked{/if} name="all_data[use_imported_data]">
            </div>
    </div>
     <div class="row">
            <div class="form-group col-md-12 col-xs-12" id="cmpfrmfile_details_div">
              <div class="col-md-6">
                <input id="file-input" name="file" type="file" class="file"  data-show-preview="false" data-show-upload="false" data-show-caption="true">
              </div>
              <div class="form-group col-md-6">
                <span class="has-float-label">
                  <div class="input-group col-xs-12">
                    <select class="form-control" name="all_data[format]" id="cmpfrformat">
{section name=FORMAT loop=$FORMAT_VALUES}
                      <option value="{$FORMAT_VALUES[FORMAT].formatid}" {if $FORMAT_VALUES[FORMAT].formatid == $ALL_DATA.format}selected{/if}>{$FORMAT_VALUES[FORMAT].name}</option>
{/section}
                    </select>
                  </div>
                  <label for="cmpfrmformat">{#TEXT_189#}</label>
                </span>
                <div class="help-block with-errors"></div>
              </div>
            </div>
     </div>
    <div class="row">
            <div class="form-group col-sm-12 text-center">
              <span>
                <button type="submit" class="btn btn-primary">{#TEXT_190#}</button>
              </span>
            </div>  
    </div>
        </form>
      </div>

<script>
  var errors='{$ERRORS}';

  function toggleUseImportedData(item) {
    isChecked = $(item).prop('checked')
    if(!isChecked) {
       $('#cmpfrmfile_details_div').show();
    } else {
       $('#cmpfrmfile_details_div').hide();
    }
  }
  
  $(function() {
    $('#cmpfrmuse_imported_data').change(function() {
      toggleUseImportedData(this)
    })
  })
  
  toggleUseImportedData($('#cmpfrmuse_imported_data'));
  if(errors) {
    populateErrorDiv(errors,'searchMoneyflowErrorsGoHere','searchMoneyflowErrors');
  }
  
  $('#cmpfrmform').validator();
  
</script>
{$FOOTER}
