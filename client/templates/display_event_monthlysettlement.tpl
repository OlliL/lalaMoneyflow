{$HEADER}
      <div class="container container-middle">
	<div class="row">
	  <div class="col-md-12">
            <div class="panel-title text-center">
              <h4>{#TEXT_205#}</h4>
            </div>
          </div>
        </div>
        
{math equation="y * x + z" y=$NUM_ADDABLE_SETTLEMENTS x=35 z=135 assign=ADD_WIN_HEIGHT}
        <div class="form-group">
          <div class="col-sm-12 text-center">
              <a class="btn btn-default" href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&amp;monthlysettlements_month={$MONTH}&amp;monthlysettlements_year={$YEAR}&amp;sr=1','_blank','width=500,height={$ADD_WIN_HEIGHT}')">{#TEXT_25#}</a>
              <a class="btn btn-primary" href="{$REQUEST_URI}">{#TEXT_26#}</a>
          </div>
        </div>
      </div>
{$FOOTER}