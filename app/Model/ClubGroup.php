<?php class ClubGroup extends AppModel {

    public $actsAs = array('Containable');
    
    var $belongsTo = array('Club');
    public $hasMany = array('ClubGroupMembership');

    var $hasAndBelongsToMany = array(
        'User' => array(
            'joinTable' => 'users_club_groups',
            'foreign_key' => 'club_group_id',
            'associationForeignKey' => 'user_id',

        )
    );


}