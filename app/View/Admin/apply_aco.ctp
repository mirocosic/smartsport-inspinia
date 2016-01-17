<?
    if(!empty($message)){echo $message;}
    //if(!empty($perms)){}
    if(empty($lastID)){$lastID = '';}


    echo $this->Form->create();
    echo $this->Form->input('parent', array('label'=>'Parent ID','value'=>$lastID));
    echo $this->Form->input('alias', array('label'=>'Aco name '));
    echo $this->Form->submit('Submit');
