<?php class ClubEvent extends AppModel {

    var $actsAs = ['Containable'];

    var $belongsTo = ['Club'];

    var $hasAndBelongsToMany = [
        'User' => [
            'joinTable' => 'users_club_events',
            'foreign_key' => 'club_event_id',
            'associationForeignKey' => 'user_id',
        ],
        'ClubGroup' => [
            'joinTable' => 'club_events_club_groups',
            'foreign_key' => 'club_event_id',
            'associationForeignKey' => 'club_group_id'
        ]
    ];

}