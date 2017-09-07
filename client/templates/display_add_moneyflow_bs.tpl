{$HEADER}
<div class="container">
  
  <div class="well well-sm">
      <select class="form-control" id="selectmoneyflow" onchange="preFillForm(this.value)">
        <option value="-1">Neue Buchung</option>
      </select>
  </div>
  
  <form action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST" name="addmoney">
    <input type="hidden" name="action"                          value="add_moneyflow">
    <input type="hidden" name="realaction"                      value="save">
    <input type="hidden" name="all_data[0][predefmoneyflowid]" value="-1"               id="predefmoneyflowid" >
    <input type="hidden" name="all_data[0][checked]"           value="1">

    <div class="span2 well">

{section name=ERROR loop=$ERRORS}
    <div class="alert alert-danger" id="errors">
      {$ERRORS[ERROR]}
    </div>
{/section}
    
      <div class="form-group">
        <span class="has-float-label">
          <div class='input-group date col-xs-12' id="bookingdate">
            <input type="text" class="form-control" name="all_data[0][bookingdate]">
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
          <label for="bookingdate">{#TEXT_16#}</label>
        </span>
        <script type="text/javascript">
            $(function () {
                $('#bookingdate').datetimepicker({
                  format: 'YYYY-MM-DD',
                  defaultDate: '{$TODAY}',
                  focusOnShow: false
                });
            });
        </script>
      </div>

      <div class="form-group">
        <span class="has-float-label">
          <div class='input-group date col-xs-12' id="invoicedate">
            <input type="text" class="form-control" name="all_data[0][invoicedate]">
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
          <label for="invoicedate">{#TEXT_17#}</label>
        </span>
        <script type="text/javascript">
            $(function () {
                $('#invoicedate').datetimepicker({
                  format: 'YYYY-MM-DD',
                  focusOnShow: false
                });
            });
        </script>
      </div>

      <div class="form-group has-float-label">
        <div class="input-group col-xs-12">
          <input type="number" step="0.01" class="form-control" id="amount" name="all_data[0][amount]">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-euro"></span>
          </span>
        </div>
        <label for="amount">{#TEXT_18#}</label>
      </div>

      <div class="form-group has-float-label">
        <div class="input-group col-xs-12">
          <select class="form-control" name="all_data[0][mcp_contractpartnerid]" id="mcp_contractpartnerid">
            <option value=""> </option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
            <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
          </select>
        </div>
        <label for="mcp_contractpartnerid">{#TEXT_2#}</label>
      </div>


      <div class="form-group has-float-label">
        <div class="input-group col-xs-12">
          <input type="text" class="form-control" id="comment" name="all_data[0][comment]">
        </div>
        <label for="comment">{#TEXT_21#}</label>
      </div>
 
      <div class="form-group has-float-label">
        <div class="input-group col-xs-12">
          <select class="form-control" name="all_data[0][mpa_postingaccountid]" id="mpa_postingaccountid">
            <option value=""> </option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
            <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
          </select>
        </div>
        <label for="mpa_postingaccountid">{#TEXT_232#}</label>
      </div>

      <div class="form-group has-float-label">
        <div class="input-group col-xs-12">
          <select class="form-control" name="all_data[0][mcs_capitalsourceid]" id="mcs_capitalsourceid">
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
            <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
          </select>
        </div>
        <label for="mcs_capitalsourceid">{#TEXT_19#}</label>
      </div>

      <div class="form-group input-group col-xs-12">
          <div class=' col-xs-6'>
            <input data-toggle="toggle" value="1" data-on="{#TEXT_209#}" data-off="{#TEXT_301#}" data-onstyle="danger" data-offstyle="success" type="checkbox" name="all_data[0][private]">
            <input data-toggle="toggle" value="1" data-on="{#TEXT_302#}" data-off="{#TEXT_303#}" type="checkbox" name="all_data[0][save_as_predefmoneyflow]">
          </div>
          <div class='col-xs-6  text-right'>
            <button type="button" class="btn btn-warning" onclick="preFillForm(-1)">{#TEXT_304#}</button>
          </div>
      </div>
 
    </div>
      
    <div class="form-group">
      <div class="col-sm-12 text-center">
        <button type="submit" class="btn btn-primary">{#TEXT_22#}</button>
      </div>  
    </div>  
      
  </form>
</div>

<script language="JavaScript">
  
  var jsonPreDefMoneyflows = {$JSON_PREDEFMONEYFLOWS};
  var jsonFormDefaults = {$JSON_FORM_DEFAULTS};
  var currency = "{#CURRENCY#}";
  
  /* When the page is loaded, the booking form is set to the defaults which might be previous entered data or empty (if the page is initially loaded) */
  var BOOKING_DEFAULT = -2;
  /* This is used when in the select box "New booking" is selected explicitly to always null the form */
  var BOOKING_EMPTY = -1;

  function fillSelectMoneyflow(currency, jsonPreDefMoneyflows) {
    var jsonPredefmoneyflowsSize = jsonPreDefMoneyflows.length;
  
    var select = document.getElementById('selectmoneyflow');
  
    for (var i = 0; i < jsonPredefmoneyflowsSize; i++){
      var preDefMoneyflow = jsonPreDefMoneyflows[i];
    
      var opt = document.createElement('option');
      opt.value = i;
      opt.innerHTML =  preDefMoneyflow["contractpartnername"] +
                       " | " + 
                       parseFloat(preDefMoneyflow["amount"]).toFixed(2) + 
                       currency + 
                       " | " + 
                       preDefMoneyflow["comment"];

      select.appendChild(opt);
    }
  }
  
  function deleteErrors() {
    var element = document.getElementById("errors");
    if ( element != null ) {
      element.outerHTML = "";
      delete element;
    }
  }
  
  function preFillForm(jsonPreDefMoneyflowIndex) {
    if ( jsonPreDefMoneyflowIndex === BOOKING_DEFAULT || jsonPreDefMoneyflowIndex === BOOKING_EMPTY ) {
      document.addmoney.predefmoneyflowid.value = -1;
      document.addmoney.amount.value = "";
      document.addmoney.mcp_contractpartnerid.value = "";
      document.addmoney.comment.value = "";
      document.addmoney.mpa_postingaccountid.value = "";
      document.addmoney.mcs_capitalsourceid.selectedIndex = 0;

      if( jsonPreDefMoneyflowIndex === BOOKING_EMPTY) {
        deleteErrors();

        var select = document.getElementById('selectmoneyflow');
        select.selectedIndex = 0;
      } else {
        if ( "predefmoneyflowid" in jsonFormDefaults ) {
          document.addmoney.predefmoneyflowid.value = jsonFormDefaults["predefmoneyflowid"];
        }
      
        if ( "amount" in jsonFormDefaults ) {
          document.addmoney.amount.value = jsonFormDefaults["amount"];
        }

        if ( "mcp_contractpartnerid" in jsonFormDefaults ) {
          document.addmoney.mcp_contractpartnerid.value = jsonFormDefaults["mcp_contractpartnerid"];
        }

        if ( "comment" in jsonFormDefaults ) {
          document.addmoney.comment.value = jsonFormDefaults["comment"];
        }

        if ( "mpa_postingaccountid" in jsonFormDefaults ) {
          document.addmoney.mpa_postingaccountid.value = jsonFormDefaults["mpa_postingaccountid"];
        }

        if ( "mcs_capitalsourceid" in jsonFormDefaults ) {
          document.addmoney.mcs_capitalsourceid.value = jsonFormDefaults["mcs_capitalsourceid"];
        }
      }
    } else if  ( (+jsonPreDefMoneyflowIndex) >= 0 && (+jsonPreDefMoneyflowIndex) < jsonPreDefMoneyflows.length ) {  
      var predefmoneyflow = jsonPreDefMoneyflows[jsonPreDefMoneyflowIndex];

      document.addmoney.predefmoneyflowid.value = predefmoneyflow["predefmoneyflowid"];
      document.addmoney.amount.value = parseFloat(predefmoneyflow["amount"]).toFixed(2);
      document.addmoney.mcp_contractpartnerid.value = predefmoneyflow["mcp_contractpartnerid"];
      document.addmoney.comment.value = predefmoneyflow["comment"];
      document.addmoney.mpa_postingaccountid.value = predefmoneyflow["mpa_postingaccountid"];
      document.addmoney.mcs_capitalsourceid.value = predefmoneyflow["mcs_capitalsourceid"];
      
      deleteErrors();
    }
  }


  fillSelectMoneyflow(currency, jsonPreDefMoneyflows);
  preFillForm(BOOKING_DEFAULT);

</script>

{$FOOTER}
