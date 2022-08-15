<style>
    .cntr {
        bottom: 0;
        left: 0;
        margin: auto;
        max-height: 500px;
        max-width: 600px;
        min-width: 300px;
        position: fixed;
        right: 0;
        top: 0;
    }
</style>
<div class="modal fade" id="changePasswordModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog-centered ">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <form action="{{route('users.password.update')}}" method="post" enctype="multipart/form-data" id="changePasswordForm">
                    @csrf
                    <input type="hidden" name="id" id="changePasswordUserId" />
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="newPassword">New Password</label>
                                  <input type="password" class="form-control"  name="newPassword" id="newPassword"
                                         placeholder="New Password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm Password</label>
                                    <input type="password" class="form-control"  name="confirmPassword" id="confirmPassword"
                                           placeholder="New Password" required/>
                                    <span id="changePasswordError"
                                          class="text-danger" style="display:none;">Passwords not matched</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    let myInput = $("#newPassword");

    let letter = $("#letter");

    let capital = $("#capital");

    let number = $("#number");

    let length = $("#length");

    myInput.onkeyup = function() {

        var lowerCaseLetters = /[a-z]/g;

        if(myInput.value.match(lowerCaseLetters)) {

            letter.classList.remove("invalid");

            letter.classList.add("valid");

        } else {

            letter.classList.remove("valid");

            letter.classList.add("invalid");

        }

        var upperCaseLetters = /[A-Z]/g;

        if(myInput.value.match(upperCaseLetters)) {

            capital.classList.remove("invalid");

            capital.classList.add("valid");

        } else {

            capital.classList.remove("valid");

            capital.classList.add("invalid");

        }

        var numbers = /[0-9]/g;

        if(myInput.value.match(numbers)) {

            number.classList.remove("invalid");

            number.classList.add("valid");

        } else {

            number.classList.remove("valid");

            number.classList.add("invalid");

        }

        if(myInput.value.length >= 8) {

            length.classList.remove("invalid");

            length.classList.add("valid");

        } else {

            length.classList.remove("valid");

            length.classList.add("invalid");

        }

    }
</script>