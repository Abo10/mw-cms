<div class="row">
    <div class="col-md-12">
        <h2>step3</h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <form action="?menu=action" class="form-horizontal" method="post">
            <input type="hidden" name="action" value="step3">

            <div class="form-group">
                <label for="inputEmail5" class="col-sm-4 control-label">admin login</label>
                <div class="col-sm-8">
                    <input type="text" name="admin_login" class="form-control" id="inputEmail5">
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail6" class="col-sm-4 control-label">admin pass</label>
                <div class="col-sm-8">
                    <input type="password" name="admin_pass" class="form-control" id="inputEmail6">
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail7" class="col-sm-4 control-label">admin email</label>
                <div class="col-sm-8">
                    <input type="email" name="admin_email" class="form-control" id="inputEmail7">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">finish</button>
        </form>
    </div>
    <div class="col-sm-6">text</div>
</div>
<form action="?menu=action" method="post" class="pull-right">
    <input type="hidden" name="action" value="back_to_step2">
    <button type="submit" class="btn btn-primary">back</button>
</form>