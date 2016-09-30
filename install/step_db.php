<div id="widget-features" class="widget">
  <div class="widget-header">
    <h1>Installer - Configure Database Connection</h1>
  </div>
  
  <div class="row">
    <form method="post" class="form-horizontal col-lg-8 col-lg-offset-2" autocomplete="off">
      <input type="hidden" name="step" value="db">

      <div class="form-group">
        <label for="inputHost" class="col-lg-2 control-label">Host</label>
        <div class="col-lg-10">
          <input type="text" name="host" class="form-control" id="inputHost" placeholder="Host" value="localhost" />
        </div>
      </div>
      <div class="form-group">
        <label for="inputPort" class="col-lg-2 control-label">Port</label>
        <div class="col-lg-10">
          <input type="text" name="port" class="form-control" id="inputPort" placeholder="Port" value="3306" />
        </div>
      </div>
      <div class="form-group">
        <label for="inputUsername" class="col-lg-2 control-label">Username</label>
        <div class="col-lg-10">
          <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Username" value="root" />
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword" class="col-lg-2 control-label">Password</label>
        <div class="col-lg-10">
          <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" />
        </div>
      </div>
      <div class="form-group">
        <label for="inputDatabase" class="col-lg-2 control-label">Database</label>
        <div class="col-lg-10">
          <input type="text" name="database" class="form-control" id="inputDatabase" placeholder="Database" value="cactus_cms" />
        </div>
      </div>


      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Next</button>
        </div>
      </div>
    </form>
  </div>
</div>
