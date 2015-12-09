<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <h3 class="form-signin-heading text-center"><?=__('Add user');?></h3>
        <div class="form-group">
            
        <?php 
            $groups = ['1'=>'Admin','2'=>'User'];
            $clubs = ['1' => 'RK Miro', '2'=>'FC Pero'];
        
            echo $this->Form->input('username', ['class'=>'form-control','placeholder'=>__('Username'),'label'=>__('Username')]);
            echo $this->Form->input('password', ['class'=>'form-control','placeholder'=>__('Password'),'label'=>__('Password')]);
            echo $this->Form->input('mail', ['class'=>'form-control','placeholder'=>__('E-mail'),'label'=>__('E-mail')]);
            echo $this->Form->input('name', ['class'=>'form-control','placeholder'=>__('Name'),'label'=>__('Name')]);
            echo $this->Form->input('surname', ['class'=>'form-control','placeholder'=>__('Surname'),'label'=>__('Surname')]);
            echo $this->Form->select('group_id', $groups, ['label'=>__('Group')]);
            echo $this->Form->select('club_id', $clubs, ['label'=>__('Club')]);
        ?>
        </div>
        <?= $this->Form->submit(__('Create'));?>
        <?php echo $this->Session->flash(); ?>
    </fieldset>