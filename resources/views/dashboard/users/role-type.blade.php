<div class="modal fade" id="roleTypeUserModal" >
  <div class="modal-dialog-centered">
    <div class="modal-dialog" role="document" style="width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleTypeUserModalLabel">Role</h5>
            </div>
            <div class="modal-body">
                <div class="form-group text-center" style="margin-top: 20px;">
                  @foreach($Roles as $roles)
                  <a href="{{route('users.add', [$roles->id])}}">
                    <button type="button" class="btn btn-primary btn-lg" name="user_role_type_btn" id="user_role_type_btn"
                            style="width: 70%;">{{$roles->title}}</button>
                  </a>
                  @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
  </div>
</div>
