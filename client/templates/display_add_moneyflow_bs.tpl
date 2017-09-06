{$HEADER}
<div class="container">
<form class="form-horizontal" action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST" name="addmoney">
<div class="well">
  <input type="hidden" name="action" value="add_moneyflow">
  <input type="hidden" name="realaction" value="save">
  
  <div class="form-group">
    <div class="col-sm-12 text-center">
      <button type="submit" class="btn btn-primary">{#TEXT_22#}</button>
    </div>  
  </div>  
</div>

<div class='span12'><hr></div>

<div class="span2 well">

{section name=ERROR loop=$ERRORS}
  <div class="alert alert-danger">
    {$ERRORS[ERROR]}
  </div>
{/section}

  <input type="hidden" name="all_data[-1][predefmoneyflowid]" value="-1">
  <input type="hidden" name="all_data[-1][checked]" value="1">


  <div class="form-group">
    <label class="control-label col-sm-4" for="comment">{#TEXT_209#}</label>
    <div class="input-group col-sm-8">
      <input type="checkbox" id="private" name="all_data[-1][private]">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-4" for="bookingdate">{#TEXT_16#}</label>
    <div class='input-group col-sm-8 date' id="bookingdate">
      <input type="text" class="form-control" name="all_data[-1][bookingdate]">
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>
        <script type="text/javascript">
            $(function () {
                $('#bookingdate').datetimepicker({
                  format: 'YYYY-MM-DD',
                  defaultDate: '{$TODAY}'
                });
            });
        </script>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-4" for="invoicedate">{#TEXT_17#}</label>
    <div class='input-group col-sm-8 date' id="invoicedate">
      <input type="text" class="form-control" name="all_data[-1][invoicedate]">
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>
        <script type="text/javascript">
            $(function () {
                $('#invoicedate').datetimepicker({
                  format: 'YYYY-MM-DD'
                });
            });
        </script>
  </div>
 
  <div class="form-group">
    <label class="control-label col-sm-4" for="amount">{#TEXT_18#}</label>
    <div class="input-group col-sm-8">
      <input type="number" step="0.01" class="form-control" id="amount" name="all_data[-1][amount]">
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-euro"></span>
      </span>
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-4" for="mcp_contractpartnerid">{#TEXT_2#}</label>
    <div class="input-group col-sm-8">
      <select class="form-control" name="all_data[-1][mcp_contractpartnerid]" id="mcp_contractpartnerid">
        <option value=""> </option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
        <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
      </select>
    </div>
  </div>
    
  <div class="form-group">
    <label class="control-label col-sm-4" for="comment">{#TEXT_21#}</label>
    <div class="input-group col-sm-8">
      <input type="text" class="form-control" id="comment" name="all_data[-1][comment]">
    </div>
  </div>
 
  <div class="form-group">
    <label class="control-label col-sm-4" for="mpa_postingaccountid">{#TEXT_232#}</label>
    <div class="input-group col-sm-8">
      <select class="form-control" name="all_data[-1][mpa_postingaccountid]" id="mpa_postingaccountid">
        <option value=""> </option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
        <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-4" for="mcs_capitalsourceid">{#TEXT_19#}</label>
    <div class="input-group col-sm-8">
      <select class="form-control" name="all_data[-1][mcs_capitalsourceid]" id="mcs_capitalsourceid">
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
        <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
      </select>
    </div>
</form>
  </div>
  </div>
</div>{$FOOTER}
