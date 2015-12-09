<?php class Club extends AppModel {
    
    public $hasMany = array('ClubMembership');
    
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