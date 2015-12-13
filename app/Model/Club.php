<?php class Club extends AppModel {

    var $actsAs = ['Containable'];
    
    public $hasMany = array('ClubMembership','ClubEvent');
    
    public $hasAndBelongsToMany = array(
        'User'=>array(
            'className' => 'User',
            'joinTable' => 'users_clubs',
            'foreign_key' => 'club_id',
            'associationForeignKey' => 'user_id',
            'unique'=>true
            
        )
    );
    
}