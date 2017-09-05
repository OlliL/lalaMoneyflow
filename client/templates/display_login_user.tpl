<!DOCTYPE HTML>
<html>
	<head><title>lalaMoneyflow: {#TEXT_84#}</title>
{$HEADER}
		<br><br>
<div class="mj-parent">
  <div class="col-md-4 col-md-offset-4 mj-child">


<form action="{$ENV_INDEX_PHP}" method="POST" name="loginform">
<input type="hidden" name="action" value="login_user">
<input type="hidden" name="realaction" value="login">
<input type="hidden" name="request_uri" value="{$REQUEST_URI}">

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title text-center"><h3>Willkommen!</h3></div>
      </div>

      <div class="panel-body">

        <div class="text-center">
          <h4>Geben Sie Ihre Nutzerdaten ein und klicken Sie auf „Anmelden“.</h4>
        </div>
        <br>

			{section name=ERROR loop=$ERRORS}
			<div class="alert alert-danger">
  				{$ERRORS[ERROR]}
			</div>
			{/section}


        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
          <input id="email" type="text" class="form-control" name="name" placeholder="Username"
                 value="{$NAME}">
        </div>
        <br/>
        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
          <input id="password" type="password" class="form-control" name="password" placeholder="Password"
                 value="">
        </div>
        <br/>
        <app-error></app-error>

        <div class="form-group">
          <!-- Button -->
          <div class="col-sm-12 controls text-center">
            <button type="submit" class="btn btn-primary"><i
              class="glyphicon glyphicon-log-in"></i> Anmelden
            </button>
          </div>
        </div>
      </div>
</form>
<div class="panel-footer">
<div class="text-center">lalaMoneyflow {$VERSION} - &copy by Oliver Lehmann</div>
</div>
    </div>
  </div>
</div>
{literal}
<script language="JavaScript">
  document.loginform.name.focus();
</script>
{/literal}
		<br>
{$FOOTER}
