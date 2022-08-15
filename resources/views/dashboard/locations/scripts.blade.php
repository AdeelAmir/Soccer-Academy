<script type="text/javascript">
    let LocationLevelsCounter = 0;
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        if ($("#AddPlayerLocationPage").length > 0) {
          MakeLocationLevels();
        }
        // Edit Location Page
        if ($("#EditPlayerLocationPage").length > 0) {
            $(".hide-data-repeater-btn").attr('disabled', true);
        }

        MakeLocationsTable();
    });

    function MakeLocationsTable() {
        let Table = $("#locationsTable");
        if (Table.length > 0) {
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
                    "url": "{{route('locations.load')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'id'},
                    {data: 'header', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'status', orderable: false},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function EditLocation(e) {
        let id = e.split('||')[1];
        window.open('{{url('locations/edit/')}}' + '/' + btoa(id), '_self');
    }

    function DeleteLocation(e) {
        let id = e.split('||')[1];
        $("#deleteLocationId").val(id);
        $("#deleteLocationModal").modal('toggle');
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

    function ChangeLocationStatus(Checked, id) {
        if (Checked === true) {
          $("#locationStatusMessage").text('Are you sure you want to turned on the location?');
          $("#statusLocationId").val(id);
          $("#statusLocationStatus").val(Checked);
        } else {
          $("#locationStatusMessage").text('Are you sure you want to turned off the location?');
          $("#statusLocationId").val(id);
          $("#statusLocationStatus").val(Checked);
        }
        $("#statusConfirmationModal").modal('toggle');
    }

    function dismissStatusConfirmationModal(){
        $("#statusConfirmationModal").modal('toggle');
        $('#locationsTable').DataTable().ajax.reload();
    }

    function ConfirmChangeLocationStatus() {
        var id = $("#statusLocationId").val();
        var Checked = $("#statusLocationStatus").val();
        $.ajax({
            type: "post",
            url: "{{route('locations.update.status')}}",
            data: { Checked : Checked, id : id }
        }).done(function (data) {
            $('#locationsTable').DataTable().ajax.reload();
            $("#statusConfirmationModal").modal('toggle');
        });
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditLocation() {
      $("#name").prop('disabled', false);
      $("#state").prop('disabled', false);
      $("#status").prop('disabled', false);
      $("#street").prop('disabled', false);
      $("#city").prop('disabled', false);
      $("#zipcode").prop('disabled', false);
      $("#category").prop('disabled', false);
      $("#level").prop('disabled', false);
      $(".hide-data-repeater-btn").attr('disabled', false);
      $("#editLocationBtn").hide();
      $("#submitBtn").show();
      $("#editConfirmationModal").modal('toggle');
    }

    /* Location Levels - Start */
    function MakeLocationLevels(){
      LocationLevelsCounter++;
      let locationLevel =
      '<div class="row" id="locationLevel_'+ LocationLevelsCounter +'">'+
          '<div class="col-md-4 mb-2">'+
              '<label for="level">Level</label>'+
              '<select class="form-control" name="level" id="level_' + LocationLevelsCounter + '">'+
              '  <option value="">Select</option>';
              for(let i=0; i < levels.length; i++)
              {
                  locationLevel += '<option value="'+levels[i]['id']+'">'+ levels[i]['title'] +'</option>';
              }
        locationLevel +=
              '</select>'+
          '</div>'+
          '<div class="col-md-4 mb-2">'+
          '    <label for="category">Category</label>'+
          '    <select class="form-control" name="category[]" id="category_'+ LocationLevelsCounter +'" multiple>';
          for(let i=0; i < categories.length; i++)
          {
              locationLevel += '<option value="'+categories[i]['id']+'">'+ categories[i]['title'] +'</option>';
          }
          locationLevel +=
          '    </select>'+
          '</div>'+
          '<div class="col-md-4 mb-2">'+
          '    <label> &nbsp;</label>'+
          '    <div>'+
          '        <span class="btn btn-danger btn-sm" id="removeLocationLevel_'+ LocationLevelsCounter +'" onclick="RemoveLocationLevel(this.id);">'+
          '            <span class="far fa-trash-alt mr-1"></span> Delete'+
          '        </span>'+
          '    </div>'+
          '</div>'
      '</div>';

      $("#LocationLevelsBlock").append(locationLevel);
      $("#level_"+LocationLevelsCounter).select2();
      $("#category_"+LocationLevelsCounter).select2();
    }

    function RemoveLocationLevel(id)
    {
      let values = id.split("_");
      $('#locationLevel_' + values[1]).remove();
    }

    $("#addLocationForm").submit(function(e){
      e.preventDefault();
      <?php
        $Url = url('locations/store');
      ?>
      let Title = $("#name").val();
      let State = $("#state").val();
      let City = $("#city").val();
      let Street = $("#street").val();
      let ZipCode = $("#zipcode").val();
      let LevelArray = [];
      let CategoryArray = [];

      for (let i = 1; i < (LocationLevelsCounter + 1); i++) {
        let subLevelArray = [];
        let subCategoryArray = [];
        let _Level = $("#level_" + i).val();
        let _Category = $("#category_" + i).val();

        if (_Level || _Category) {

          if (_Level) {
            for (let b = 0; b < _Level.length; b++) {
              LevelArray.push(_Level[b]);
            }
          }

          if (_Category) {
            for (let b = 0; b < _Category.length; b++) {
              subCategoryArray.push(_Category[b]);
            }
          }

          CategoryArray.push(subCategoryArray);
        }
      }

      LevelArray = JSON.stringify(LevelArray);
      CategoryArray = JSON.stringify(CategoryArray);

      if (Title !== '' && State !== '' && Street !== '' && ZipCode !== '') {
        // Now submit form
        let form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', "{{$Url}}");
        let csrfVar = $('meta[name="csrf-token"]').attr('content');
        $(form).append("<input name='_token' value='" + csrfVar + "' type='hidden'>");
        $(form).append("<input name='name' value='" + Title + "' type='hidden'>");
        $(form).append("<input name='state' value='" + State + "' type='hidden'>");
        $(form).append("<input name='city' value='" + City + "' type='hidden'>");
        $(form).append("<input name='street' value='" + Street + "' type='hidden'>");
        $(form).append("<input name='zipcode' value='" + ZipCode + "' type='hidden'>");
        $(form).append("<input name='level' value='" + LevelArray + "' type='hidden'>");
        $(form).append("<input name='category' value='" + CategoryArray + "' type='hidden'>");
        document.body.appendChild(form);
        form.submit();
      } else {
        alert('Some fields are missing');
        return;
      }
    });

    // Edit Location - Start
    function MakeEditLocationLevels() {
      let _LocationLevelsCounter = $("#_locationLevelCounter").val();
      _LocationLevelsCounter++;
      let locationLevel =
      '<div class="row" id="locationLevel_'+ _LocationLevelsCounter +'">'+
          '<div class="col-md-4 mb-2">'+
              '<label for="level">Level</label>'+
              '<select class="form-control" name="level" id="level_' + _LocationLevelsCounter + '">'+
              '  <option value="">Select</option>';
              for(let i=0; i < levels.length; i++)
              {
                  locationLevel += '<option value="'+levels[i]['id']+'">'+ levels[i]['title'] +'</option>';
              }
        locationLevel +=
              '</select>'+
          '</div>'+
          '<div class="col-md-4 mb-2">'+
          '    <label for="category">Category</label>'+
          '    <select class="form-control" name="category[]" id="category_'+ _LocationLevelsCounter +'" multiple>';
          for(let i=0; i < categories.length; i++)
          {
              locationLevel += '<option value="'+categories[i]['id']+'">'+ categories[i]['title'] +'</option>';
          }
          locationLevel +=
          '    </select>'+
          '</div>'+
          '<div class="col-md-4 mb-2">'+
          '    <label> &nbsp;</label>'+
          '    <div>'+
          '        <span class="btn btn-danger btn-sm" id="removeLocationLevel_'+ _LocationLevelsCounter +'" onclick="RemoveLocationLevel(this.id);">'+
          '            <span class="far fa-trash-alt mr-1"></span> Delete'+
          '        </span>'+
          '    </div>'+
          '</div>'
      '</div>';

      $("#EditLocationLevelsBlock").append(locationLevel);
      $("#_locationLevelCounter").val(_LocationLevelsCounter);
      $("#level_" + _LocationLevelsCounter).select2();
      $("#category_" + _LocationLevelsCounter).select2();
    }

    function RemoveLocationLevel(id)
    {
      let values = id.split("_");
      $('#locationLevel_' + values[1]).remove();
    }

    $("#editLocationForm").submit(function(e){
      e.preventDefault();
      <?php
        $Url = url('locations/update');
      ?>
      let _LocationLevelsCounter = $("#_locationLevelCounter").val();
      _LocationLevelsCounter = parseInt(_LocationLevelsCounter);

      let LocationId = $("#hiddenLocationId").val();
      let Title = $("#name").val();
      let State = $("#state").val();
      let City = $("#city").val();
      let Street = $("#street").val();
      let ZipCode = $("#zipcode").val();
      let Status = 0;
      let LevelArray = [];
      let CategoryArray = [];

      if ($('#status').is(':checked')) {
        Status = 1;
      }

      for (let i = 1; i < (_LocationLevelsCounter + 1); i++) {
        let subLevelArray = [];
        let subCategoryArray = [];
        let _Level = $("#level_" + i).val();
        let _Category = $("#category_" + i).val();

        if (_Level || _Category) {

          if (_Level) {
            for (let b = 0; b < _Level.length; b++) {
              LevelArray.push(_Level[b]);
            }
          }

          if (_Category) {
            for (let b = 0; b < _Category.length; b++) {
              subCategoryArray.push(_Category[b]);
            }
          }

          CategoryArray.push(subCategoryArray);
        }
      }

      LevelArray = JSON.stringify(LevelArray);
      CategoryArray = JSON.stringify(CategoryArray);

      if (Title !== '' && State !== '' && Street !== '' && ZipCode !== '') {
        // Now submit form
        let form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', "{{$Url}}");
        let csrfVar = $('meta[name="csrf-token"]').attr('content');
        $(form).append("<input name='_token' value='" + csrfVar + "' type='hidden'>");
        $(form).append("<input name='id' value='" + LocationId + "' type='hidden'>");
        $(form).append("<input name='name' value='" + Title + "' type='hidden'>");
        $(form).append("<input name='state' value='" + State + "' type='hidden'>");
        $(form).append("<input name='city' value='" + City + "' type='hidden'>");
        $(form).append("<input name='street' value='" + Street + "' type='hidden'>");
        $(form).append("<input name='zipcode' value='" + ZipCode + "' type='hidden'>");
        $(form).append("<input name='status' value='" + Status + "' type='hidden'>");
        $(form).append("<input name='level' value='" + LevelArray + "' type='hidden'>");
        $(form).append("<input name='category' value='" + CategoryArray + "' type='hidden'>");
        document.body.appendChild(form);
        form.submit();
      } else {
        alert('Some fields are missing');
        return;
      }
    });
    // Edit Location - End

    /* Location Levels - End */
</script>
