<?php class ClubMembership extends AppModel {
    
    public $useTable = 'users_clubs';
   
    public $belongsTo = array(
        'User', 'Club'
    );
}