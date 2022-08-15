<script type="text/javascript">
    let DeletedDocuments = [];

    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        // Bootstrap DatePicker
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: 'TRUE',
            autoclose: true,
        });

        $("form.userInfoForm").on("keypress", function (event) {
            let keyPressed = event.keyCode || event.which;
            if (keyPressed === 13) {
                event.preventDefault();
                return false;
            }
        });

        // User Change Password
        $("form#changePasswordForm").submit(function (e) {
            // Check for Password Match
            let NewPassword = $("#newPassword").val();
            let ConfirmPassword = $("#confirmPassword").val();
            if (NewPassword === ConfirmPassword) {
                $("#changePasswordError").hide();
            } else {
                $("#changePasswordError").show();
                e.preventDefault(e);
            }
        });

        if ($(".trainingdays").length) {
            document.getElementsByClassName('trainingdays')[0].oninput = function () {
                var max = parseInt(this.max);
                var min = parseInt(this.min);
                if (parseInt(this.value) > max) {
                    this.value = max;
                    return;
                }
                if (parseInt(this.value) < min) {
                    this.value = min;
                    return;
                }
            }
        }

        // Edit User Form - Start
        let coll = document.getElementsByClassName("collapsible");
        let i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function () {
                this.classList.toggle("active1");
                var content = this.nextElementSibling;
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
            coll[i].click();
        }
        // Edit User Form - End

        $("#logo").on("change", function (e) {
            let fileName = document.getElementById("logo").value;
            let idxDot = fileName.lastIndexOf(".") + 1;
            let extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile === "jpeg" || extFile === "png" || extFile === "jpg" || extFile === "JPEG" || extFile === "PNG" || extFile === "JPG") {
                $("#previewImg").attr('src', URL.createObjectURL(e.target.files[0]));
            } else {
                $("#logo").val('');
            }
        });

        // Parent Address Info
        if ($("#AddUserPage").length > 0) {
            let UserRole = '<?= $UserRole ?>';
            if (parseInt(UserRole) === 5) {
                getParentAddressInfo();
            }
        }

        // Edit User Page
        if ($("#EditUserPage").length > 0) {
            $(".hide-data-repeater-btn").attr('disabled', true);
            $(".pointerEvents").css('pointer-events', 'none');
        }

        MakeUsersTable();
    });

    function openUserRoleTypeModal() {
        $("#roleTypeUserModal").modal('toggle');
    }

    /* Users Table - Start */
    function MakeUsersTable() {
        let FullName = $("#fullNameFilter").val();
        let Phone = $("#phoneFilter").val();
        let State = $("#stateFilter option:selected").val();
        let City = $("#cityFilter option:selected").val();
        let ZipCode = $("#zipCodeFilter").val();
        let Table = $("#usersTable");
        let user_role = $("#user_role option:selected").val();
        let status = $("#user_status option:selected").val();
        let location = $("#location option:selected").val();

        if (Table.length > 0) {
            Table.dataTable().fnDestroy();
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 25,
                "lengthMenu": [
                    [25, 50, 100, 200],
                    ['25', '50', '100', '200']
                ],
                "ajax": {
                    "url": "{{route('users.load')}}",
                    "type": "POST",
                    "data": {
                        "Name": FullName,
                        "Phone": Phone,
                        "State": State,
                        "City": City,
                        "ZipCode": ZipCode,
                        "UserRole": user_role,
                        "Status": status,
                        "location" : location
                    }
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'checkbox', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'name', orderable: false},
                    {data: 'contact_info', orderable: false},
                    {data: 'status'},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }).on('page.dt', function () {
                $("#allUsersCheckBox").prop('checked', false);
                $("#deleteAllUsersBtn").hide();
                $("#broadcastAllUsersBtn").hide();
                $("#banAllUsersBtn").hide();
                $("#activeAllUsersBtn").hide();
            }).on('length.dt', function (e, settings, len) {
                $("#allUsersCheckBox").prop('checked', false);
                $("#deleteAllUsersBtn").hide();
                $("#broadcastAllUsersBtn").hide();
                $("#banAllUsersBtn").hide();
                $("#activeAllUsersBtn").hide();
            }).on('draw.dt', function () {
                $(".allUsersCheckBox").addClass('d-none');
                $(".allUsersActionCheckBoxColumn").addClass('w-0');
            });
        }
    }

    let userActionCheckboxCounter = 0;

    function HandleUserAction() {
        if (userActionCheckboxCounter === 0) {
            $(".allUsersCheckBox").removeClass('d-none');
            $(".allUsersActionCheckBoxColumn").removeClass('w-0');
            $(".allUsersActionCheckBoxColumn").attr('style', 'padding', '10px');
            /*$("#deleteAllUsersBtn").show();
            $("#broadcastAllUsersBtn").show();
            $("#banAllUsersBtn").show();
            $("#activeAllUsersBtn").show();*/
            userActionCheckboxCounter = 1;
        } else {
            $(".allUsersCheckBox").addClass('d-none');
            $(".allUsersActionCheckBoxColumn").addClass('w-0');
            $(".allUsersActionCheckBoxColumn").attr('style', 'padding', '0');
            $("#deleteAllUsersBtn").hide();
            $("#broadcastAllUsersBtn").hide();
            $("#banAllUsersBtn").hide();
            $("#activeAllUsersBtn").hide();
            userActionCheckboxCounter = 0;
        }
    }

    function CheckAllUserRecords(e) {
        let Status = $(e).prop('checked');
        if (Status) {
            /*check all*/
            $(".checkAllBox").each(function (i, obj) {
                $(obj).prop('checked', true);
            });
            $("#deleteAllUsersBtn").show();
            $("#broadcastAllUsersBtn").show();
            $("#banAllUsersBtn").show();
            $("#activeAllUsersBtn").show();
        } else {
            /*un check all*/
            $(".checkAllBox").each(function (i, obj) {
                $(obj).prop('checked', false);
            });
            $("#deleteAllUsersBtn").hide();
            $("#broadcastAllUsersBtn").hide();
            $("#banAllUsersBtn").hide();
            $("#activeAllUsersBtn").hide();
        }
    }

    function CheckIndividualUserCheckbox() {
        let count = 0;
        $(".checkAllBox").each(function (i, obj) {
            if ($(obj).prop('checked')) {
                count++;
            }
        });
        if (count === 0) {
            /*Not Selected*/
            $("#deleteAllUsersBtn").hide();
            $("#broadcastAllUsersBtn").hide();
            $("#banAllUsersBtn").hide();
            $("#activeAllUsersBtn").hide();
        } else {
            /*Some Selected*/
            $("#deleteAllUsersBtn").show();
            $("#broadcastAllUsersBtn").show();
            $("#banAllUsersBtn").show();
            $("#activeAllUsersBtn").show();
        }
    }

    function DeleteMultipleUsers() {
        $("#deleteUserModal").modal('toggle');
        let deleteSelectedUsersFormUrl = $("#deleteSelectedUsersFormUrl").val();
        $('#usersForm').attr('action', deleteSelectedUsersFormUrl);
    }

    function BroadcastMultipleUsers() {
        $("#userBroadcastModal").modal('toggle');
        let broadcastSelectedUsersFormUrl = $("#broadcastSelectedUsersFormUrl").val();
        $('#usersForm').attr('action', broadcastSelectedUsersFormUrl);
    }

    function BanMultipleUsers() {
        $("#userBanModal").modal('toggle');
        let banSelectedUsersFormUrl = $("#banSelectedUsersFormUrl").val();
        $('#usersForm').attr('action', banSelectedUsersFormUrl);
    }

    function ActiveMultipleUsers() {
        $("#userActiveModal").modal('toggle');
        let activeSelectedUsersFormUrl = $("#activeSelectedUsersFormUrl").val();
        $('#usersForm').attr('action', activeSelectedUsersFormUrl);
    }

    function submitBanForm() {
        if ($("#ban_reason").val() === '') {
            $("#ban_reason_error").show();
        } else {
            $("#ban_reason_error").hide();
            $("#usersForm").submit();
        }
    }

    /* Users Table - End */

    function ShowPhone2() {
        $("#phone2Field").css('display', 'initial');
    }

    function HidePhone2() {
        $("#phone2Field").css('display', 'none');
    }

    function ShowSocialMedia2() {
        $("#socialMedia2Field").css('display', 'initial');
    }

    function HideSocialMedia2() {
        $("#socialMedia2").val('');
        $("#socialMedia2Field").css('display', 'none');
    }

    function ShowSocialMedia3() {
        $("#socialMedia3Field").css('display', 'initial');
    }

    function HideSocialMedia3() {
        $("#socialMedia3").val('');
        $("#socialMedia3Field").css('display', 'none');
    }

    function LoadStateCountyCity() {
        let state = '';
        if ($("#state").length) {
            state = $("#state option:selected").val();
        }
        if ($("#citySection").length) {
            $("#citySection").show();
        }
        LoadCities(state);
    }

    function LoadCities(state) {
        $.ajax({
            type: "post",
            url: "{{route('common.load.cities')}}",
            data: {State: state}
        }).done(function (data) {
            data = JSON.parse(data);
            if ($("#city").length > 0) {
                $("#city").html('').html(data).select2();
            }
            if ($("#cityFilter").length > 0) {
                $("#cityFilter").html('').html(data).select2();
            }
        });
    }

    function limitKeypress(event, value, maxLength) {
        if (value !== undefined && value.toString().length >= maxLength) {
            event.preventDefault();
        }
    }

    function limitZipCodeCheck() {
        let value = $('#zipcode').val();
        if (value.toString().length < 5) {
            $('#zipcode').focus();
        }
    }

    function NumberDocumentNumbers() {
        let QuestionNumber = 0;
        const elements = document.querySelectorAll('.add_document_label');
        if (elements.length > 0) {
            Array.from(elements).forEach((element, index) => {
                // conditional logic here.. access element
                QuestionNumber++;
                element.innerHTML = "Document " + QuestionNumber;
            });
        }
    }

    function CheckForDocumentName(e, value) {
        let Parent = $(e).parent().parent().parent().find('div.documentNameOthersSection');
        let ExpirationDate = $(e).parent().parent().parent().find('div.documentExpirationDateSection');
        if (value === 'Others') {
            Parent.show();
            ExpirationDate.hide();
        } else if (value === 'State ID' || value === 'Passport') {
            Parent.hide();
            ExpirationDate.show();
        } else {
            Parent.hide();
            ExpirationDate.hide();
        }
    }

    function HideAllRolesRequiredFields() {
        /*Manager*/
        $(".managerRequiredFields").hide();
        $("#managerLocations").removeAttr('data-validate').removeAttr('required');
        /*Coach*/
        $(".coachRequiredFields").hide();
        $("#coachLevels").removeAttr('data-validate').removeAttr('required');
        $("#coachCategories").removeAttr('data-validate').removeAttr('required');
        $("#coachLocations").removeAttr('data-validate').removeAttr('required');
        /*Parent*/
        $(".parentRequiredFields").hide();
        /*Athletes*/
        $(".athletesRequiredFields").hide();
        $("#athletesParent").removeAttr('data-validate').removeAttr('required');
        $("#athletesLevel").removeAttr('data-validate').removeAttr('required');
        $("#athletesCategory").removeAttr('data-validate').removeAttr('required');
        $("#athletesPosition").removeAttr('data-validate').removeAttr('required');
        /*For All Except Player*/
        $("#phone1").attr('data-validate', 'required').attr('required', 'true');
    }

    function HideAllRolesRequiredFields1() {
        /*Manager*/
        $(".managerRequiredFields").hide();
        $("#managerLocations").removeAttr('required');
        /*Coach*/
        $(".coachRequiredFields").hide();
        $("#coachLevels").removeAttr('required');
        $("#coachCategories").removeAttr('required');
        $("#coachLocations").removeAttr('required');
        /*Parent*/
        $(".parentRequiredFields").hide();
        /*Athletes*/
        $(".athletesRequiredFields").hide();
        $("#athletesParent").removeAttr('required');
        $("#athletesLevel").removeAttr('required');
        $("#athletesCategory").removeAttr('required');
        $("#athletesPosition").removeAttr('required');
        /*For All Except Player*/
        $("#phone1").attr('required', 'true');
    }

    function CheckUserRole(e) {
        var value = $("#role option:selected").val();
        if (value === '3') {
            HideAllRolesRequiredFields();
            $(".managerRequiredFields").show();
            $("#managerLocations").attr('data-validate', 'required').attr('required', 'true');
        } else if (value === '4') {
            HideAllRolesRequiredFields();
            $(".coachRequiredFields").show();
            $("#coachLevels").attr('data-validate', 'required').attr('required', 'true');
            $("#coachCategories").attr('data-validate', 'required').attr('required', 'true');
            $("#coachLocations").attr('data-validate', 'required').attr('required', 'true');
        } else if (value === '5') {
            HideAllRolesRequiredFields();
            $(".parentRequiredFields").show();
        } else if (value === '6') {
            HideAllRolesRequiredFields();
            $(".athletesRequiredFields").show();
            $("#athletesParent").attr('data-validate', 'required').attr('required', 'true');
            $("#athletesLevel").attr('data-validate', 'required').attr('required', 'true');
            $("#athletesCategory").attr('data-validate', 'required').attr('required', 'true');
            $("#athletesPosition").attr('data-validate', 'required').attr('required', 'true');
            $("#phone1").removeAttr('data-validate').removeAttr('required');
            SetUserCategory();
        } else {
            HideAllRolesRequiredFields();
        }
    }

    function CheckEditUserRole(e) {
        var value = $("#role option:selected").val();
        if (value === '3') {
            HideAllRolesRequiredFields1();
            $(".managerRequiredFields").show();
            $("#managerLocations").attr('required', 'true');
        } else if (value === '4') {
            HideAllRolesRequiredFields1();
            $(".coachRequiredFields").show();
            $("#coachLevels").attr('required', 'true');
            $("#coachCategories").attr('required', 'true');
            $("#coachLocations").attr('required', 'true');
        } else if (value === '5') {
            HideAllRolesRequiredFields1();
            $(".parentRequiredFields").show();
        } else if (value === '6') {
            HideAllRolesRequiredFields1();
            $(".athletesRequiredFields").show();
            $("#athletesParent").attr('required', 'true');
            $("#athletesLevel").attr('required', 'true');
            $("#athletesCategory").attr('required', 'true');
            $("#athletesPosition").attr('required', 'true');
            $("#phone1").removeAttr('required');
            SetUserCategory();
        } else {
            HideAllRolesRequiredFields1();
        }
    }

    function SetUserCategory() {
        let Role = $("#role option:selected").val();
        let DOb = $("#dob").val();
        if (Role === '6' && DOb !== '') {
            $.ajax({
                type: "post",
                url: "{{route('users.fetch.category')}}",
                data: {Dob: DOb}
            }).done(function (data) {
                $("#athletesCategory").val(data).trigger('change');
            });
        }
    }

    function getParentAddressInfo() {
        let UserRole = '<?= $UserRole ?>';
        let ParentId = '';
        if (parseInt(UserRole) === 5) {
            ParentId = $("#athletesParent").val();
        } else {
            ParentId = $("#athletesParent option:selected").val();
        }

        if (ParentId !== '') {
            $.ajax({
                type: "post",
                url: "{{route('users.fetch.parentaddressinfo')}}",
                data: {ParentId: ParentId}
            }).done(function (data) {
                let values = JSON.parse(data);
                $("#phone1").val(values.phone1);
                $("#state").html('').html(JSON.parse(values.state)).select2();
                $("#citySection").show();
                $("#city").html('').html(JSON.parse(values.city)).select2();
                $("#street").val(values.street);
                $("#zipcode").val(values.zipcode);
            });
        }
    }

    // Edit User
    function EditUser(e) {
        let id = e.split('||')[1];
        window.open('{{url('users/edit/')}}' + '/' + btoa(id), '_self');
    }

    function checkConfirmation() {
        $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditLevel() {
        let UserRole = '<?= $UserRole ?>';
        $("#role").prop('disabled', false);
        $("#firstName").prop('disabled', false);
        $("#middleName").prop('disabled', false);
        $("#lastName").prop('disabled', false);
        $("#dob").prop('disabled', false);
        $("#managerLocations").prop('disabled', false);
        $("#coachLevels").prop('disabled', false);
        $("#coachCategories").prop('disabled', false);
        $("#coachLocations").prop('disabled', false);
        $("#parentProfession").prop('disabled', false);
        $("#athletesHeightFt").prop('disabled', false);
        $("#athletesHeightInches").prop('disabled', false);
        $("#athletesWeight").prop('disabled', false);
        if (parseInt(UserRole) != 5) {
            $("#athletesLevel").prop('disabled', false);
            $("#athletesCategory").prop('disabled', false);
            $("#athletesPosition").prop('disabled', false);
        }
        $("#athletesTrainingDays").prop('disabled', false);
        $("#athletesParent").prop('disabled', false);
        $("#athletesRelationship").prop('disabled', false);
        $("#athletesInsuranceName").prop('disabled', false);
        $("#athletesDoctorName").prop('disabled', false);
        $("#athletesDoctorPhoneNumber").prop('disabled', false);
        $("#athletesPolicyNumber").prop('disabled', false);
        $("#athletesAllergies").prop('disabled', false);
        $("#email").prop('disabled', false);
        $("#phone1").prop('disabled', false);
        $("#phone2").prop('disabled', false);
        $("#socialMedia").prop('disabled', false);
        $("#socialMedia2").prop('disabled', false);
        $("#socialMedia3").prop('disabled', false);
        $("#city").prop('disabled', false);
        $("#state").prop('disabled', false);
        $("#street").prop('disabled', false);
        $("#zipcode").prop('disabled', false);
        $("#documentName").prop('disabled', false);
        $("#documentNameOthers").prop('disabled', false);
        $("#documentNumbers").prop('disabled', false);
        $("#documentFile").prop('disabled', false);
        $("#DeleteDocumentBtn").prop('disabled', false);
        $("#AddNewDocumentBtn").prop('disabled', false);
        $("#editUserBtn").hide();
        $("#submitUserFormBtn").show();
        $(".hide-data-repeater-btn").attr('disabled', false);
        $(".pointerEvents").css('pointer-events', '');
        $("#editConfirmationModal").modal('toggle');
    }

    function DeleteUser(e) {
        let id = e.split('||')[1];
        $("#deleteUserId").val(id);
        $("#deleteUserModal").modal('toggle');
    }

    function RemoveDocument(Id) {
        let Values = Id.split('||');
        let $confirm = confirm('Are you sure you want to remove this document?');
        if ($confirm) {
            $("#documentRow_" + Values[1]).remove();
            DeletedDocuments.push(Values[2]);
            $("#documentsDeleted").val(JSON.stringify(DeletedDocuments));
        }
    }

    function MakeUserActivityTable(e) {
        let id = e.split('||')[1];
        $("#userAcitivityModal").modal('toggle');
        let Table = $("#userActivityTable");
        if (Table.length > 0) {
            Table.DataTable().clear().destroy();
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 25,
                "lengthMenu": [
                    [25, 50, 100, 200],
                    ['25', '50', '100', '200']
                ],
                "ajax": {
                    "url": "{{route('users.activity.all')}}",
                    "type": "POST",
                    "data": {
                        "UserId": id
                    }
                },
                'columns': [
                    {data: 'id'},
                    {data: 'user'},
                    {data: 'message'},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function ChangePassword(e) {
        let id = e.split('||')[1];
        $("#changePasswordUserId").val(id);
        $("#changePasswordModal").modal('toggle');
    }

    // Power - Start
    function PowerUser(e) {
        let id = e.split('||')[1];
        $("#powerTypeUserId").val(id);
        $("#powerTypeUserModal").modal('toggle');
    }

    function submitUserPowerType(type) {
        let userId = $("#powerTypeUserId").val();
        let url = '<?= url("users/power/"); ?>' + "/" + userId + "/" + type;
        window.location.href = url;
    }

    function CheckPowerFeature(value, feature, userId) {
        $.ajax({
            type: "post",
            url: "{{route('users.power.feature.update')}}",
            data: {UserId: userId, feature: feature, status: value}
        }).done(function (data) {
            // display results
        });
    }

    // Power - End

    // User Document Verification - Start
    function documentVerification(id) {
        if ($("#" + id).is(":checked")) {
            let values = id.split("_");
            $("#userDocumentIndex").val(values[1]);
            $("#userDocumentId").val(values[2]);
            $("#documentVerificationModal").modal('toggle');
        }
    }

    function updateDocumentVerificationStatus(status) {
        let documentIndex = $("#userDocumentIndex").val();
        let documentId = $("#userDocumentId").val();
        let comment = $("#comment").val();
        let user_id = $("#hiddenUserId").val();
        if (comment === '') {
            $("#comment-error").text('').text('Comment is required').show();
            return false;
        } else {
            $("#comment-error").text('').hide();
        }
        $.ajax({
            type: "post",
            url: "{{route('users.document.verify')}}",
            data: {DocumentId: documentId, Status: status, Comment: comment, UserId: user_id}
        }).done(function (data) {
            if (data === 'Success') {
                if (status === 1) {
                    $("#documentApproved_" + documentId).show();
                    $("#documentVerificationBlock_" + documentIndex + "_" + documentId).hide();
                } else if (status === 2) {
                    $("#documentRejected_" + documentId).show();
                    $("#documentVerificationBlock_" + documentIndex + "_" + documentId).hide();
                }
            }
            $("#documentVerificationModal").modal('toggle');
        });
    }

    // User Document Verification - End

    /*User Filter*/
    function UserFilterBackButton() {
        $("#beforeTablePage").removeClass("col-md-2");
        $("#tablePage").removeClass("col-md-8").addClass("col-md-9");
        $("#filterPage").show();
    }

    function UserFilterCloseButton() {
        $("#beforeTablePage").addClass("col-md-2");
        $("#tablePage").removeClass("col-md-9").addClass("col-md-8");
        $("#filterPage").hide();
    }

    function CheckLeadFilterState(e) {
        if (e.value === '0') {
            $("#cityFilter").val('').trigger('change');
            $("#_leadFilterCityBlock").hide();
        } else {
            $("#_leadFilterCityBlock").show();
        }
    }

    function LoadFilterStateCountyCity() {
        let state = $("#stateFilter option:selected").val();
        LoadCities(state);
    }
    /*User Filter*/

    /*Ban - Active Users*/
    function banUser(id) {
        let values = id.split('_');
        $("#BanActiveUserId").val(values[1]);
        $("#userBanActiveModalLabel").text('Ban User');
        $("#BanActiveType").val('ban');
        $("#BanActiveDiv1").hide();
        $("#BanActiveDiv2").show();
        $("#userBanActiveModal").modal('toggle');
    }

    function activeUser(id) {
        let values = id.split('_');
        $("#BanActiveUserId").val(values[1]);
        $("#userBanActiveModalLabel").text('Active User');
        $("#BanActiveType").val('active');
        $("#BanActiveDiv1").show();
        $("#BanActiveDiv2").hide();
        $("#userBanActiveModal").modal('toggle');
    }

    function BanActiveUser(e) {
        let UserId = $("#BanActiveUserId").val();
        let Type = $("#BanActiveType").val();
        let Reason = $("#ban_active_reason").val();
        if(Type === 'ban' && Reason === '') {
            $("#ban_active_reason_error").show();
            return;
        } else {
            $("#ban_active_reason_error").hide();
        }
        $.ajax({
            type: "post",
            url: "{{route('users.ban-active')}}",
            data: {UserId: UserId, Type: Type, Reason: Reason}
        }).done(function (data) {
            $('#usersTable').DataTable().ajax.reload();
            $("#ban_active_reason").val('');
            $("#userBanActiveModal").modal('toggle');
        });
    }
    /*Ban - Active Users*/
</script>
