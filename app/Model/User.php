<?php
class User extends AppModel {
    public $belongsTo = array('Group');
    public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
    
    public $hasMany = array('ClubMembership','ClubGroupMembership','MembershipFee','UserWeight','Image');
    
    public $hasAndBelongsToMany = array(
        'Club'=>array(
            'className' => 'Club',
            'joinTable' => 'users_clubs',
            'foreign_key' => 'user_id',
            'associationForeignKey' => 'club_id',
            'unique'=>true
            
        )
    );

    public $virtualFields = array(
        'fullname' => "CONCAT(User.name, ' ', User.surname)"
    );

    public $displayField = 'fullname';

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }

        $this->log(print_r($this->data, true), 'GROUP');

        if (!$groupId) {
            return null;
        }


        return array('Group' => array('id' => $groupId));
        //return array('model' => 'Group', 'foreign_key' => $groupId);

    }

    function afterSave($created, $options = array()) {
        if (!$created) {
            $parent = $this->parentNode();
            $parent = $this->node($parent);
            $node = $this->node();
            $aro = $node[0];
            $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
            $this->Aro->save($aro);
        }
    }

    function CheckOIB($oib) {
	if ( strlen($oib) == 11 ) {
            if ( is_numeric($oib) ) {
                    $a = 10;
                    for ($i = 0; $i < 10; $i++) {
                            $a = $a + intval(substr($oib, $i, 1), 10);
                            $a = $a % 10;
                            if ( $a == 0 ) { $a = 10; }
                            $a *= 2;
                            $a = $a % 11;
                    }
                    $kontrolni = 11 - $a;
                    if ( $kontrolni == 10 ) { $kontrolni = 0; }
                    return $kontrolni == intval(substr($oib, 10, 1), 10);
            } else {
                    return false;
            }
	} else {
		return false;	
	}
    }
/*
    function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }
*/
}