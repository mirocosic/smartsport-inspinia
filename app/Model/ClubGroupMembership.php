<?php class ClubGroupMembership extends AppModel {

    public $useTable = 'users_club_groups';

    public $belongsTo = array(
        'User', 'ClubGroup'
    );
}