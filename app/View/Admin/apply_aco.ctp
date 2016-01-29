<?
    if(!empty($message)){echo $message;}
    //if(!empty($perms)){}
    if(empty($lastID)){$lastID = '';}



    echo $this->Form->create();
    echo $this->Form->input('parent', array('label'=>'Parent ID','value'=>$lastID));
    echo 'Parent alias ';
    echo $this->Form->select('parent_id',$acos_lvl_1);
    echo $this->Form->input('alias', array('label'=>'Child alias '));
    echo $this->Form->submit('Submit');
