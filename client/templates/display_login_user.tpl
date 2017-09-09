{$HEADER}
		<br><br>
<div class="mj-parent">
  <div class="col-md-4 col-md-offset-4 mj-child">

    <form action="{$ENV_INDEX_PHP}" method="POST" data-toggle="validator" name="login">
      <input type="hidden" name="action" value="login_user">
      <input type="hidden" name="realaction" value="login">
      <input type="hidden" name="request_uri" value="{$REQUEST_URI}">

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title text-center"><h3>{#TEXT_300#}</h3></div>
      </div>

      <div class="panel-body">

        <div class="text-center">
          <h4>{#TEXT_84#}</h4>
        </div>
        <br>

{section name=ERROR loop=$ERRORS}
        <div class="alert alert-danger">
          {$ERRORS[ERROR]}
        </div>
{/section}

        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="email" type="text" class="form-control" name="name" placeholder="{#TEXT_85#}" value="{$NAME}" required data-error="{#TEXT_317#}">
          </div>
          <div class="help-block with-errors"></div>
        </div>

        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="password" type="password" class="form-control" name="password" placeholder="{#TEXT_86#}" value="" required data-error="{#TEXT_318#}">
          </div>
          <div class="help-block with-errors"></div>
        </div>

        <div class="form-group">
          <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-log-in"></i> {#TEXT_88#}</button>
          </div>
        </div>
      </div>

      <div class="panel-footer text-center">
        moneyjinn {$VERSION} - &copy; by Oliver Lehmann
      </div>
    </div>
    </form>
  </div>
</div>
{literal}
<script>
  document.login.name.focus();
  $('form[name=login]').validator();
</script>
{/literal}
		<br>
{$FOOTER}
