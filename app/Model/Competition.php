<?php class Competition extends AppModel {
    
    var $belongsTo = array(
        'CompetitionType'=>array(
            'foreignKey'=>'type_id'
        )
    );
    
    var $hasMany = array('Event');
    
}