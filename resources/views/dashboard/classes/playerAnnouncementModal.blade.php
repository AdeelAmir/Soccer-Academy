<div class="modal fade" id="playerAnnouncementModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Announcement</h4>
                </div>
                <form action="{{route('classes.announcement.player')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="2">
                    <div class="modal-body mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="announcement_for">Announcement For</label>
                                <select class="form-control" name="announcement_for" id="announcement_for" onclick="checkAnnouncementFor(this.value);" required>
                                    <option value="">Select</option>
                                    <option value="1">Class</option>
                                    <option value="2">Player</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="announcementClassesBlock" style="display:none;">
                                <label for="coach">Class</label>
                                <select class="form-control select2" name="classes[]" id="announcement_classes" multiple>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}">{{$class->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="announcementPlayersBlock" style="display:none;">
                                <label for="coach">Players</label>
                                <select class="form-control select2" name="player[]" id="announcement_player" multiple>
                                    @foreach($players as $player)
                                    <?php
                                    $PlayerName = "";
                                    if ($player->middleName != "") {
                                        $PlayerName = $player->firstName . " " . $player->middleName . " " . $player->lastName;
                                    } else {
                                        $PlayerName = $player->firstName . " " . $player->lastName;
                                    }
                                    ?>
                                    <option value="{{$player->id}}">{{$PlayerName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="broadcast_message">Message</label>
                                <textarea class="form-control" name="message" id="player_announcement_message" rows="5" required></textarea>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="dtpickerdemo" class="control-label">Expiration Date and Time:</label>
                                <div class='input-group date' id='expiration_date_time'>
                                    <input type='text' class="form-control" name="expiration_date_time" autocomplete="off" required />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
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
