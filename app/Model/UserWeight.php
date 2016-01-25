<?php class UserWeight extends AppModel {

    var $actsAs = ['Containable'];
    var $belongsTo = ['User'];
}