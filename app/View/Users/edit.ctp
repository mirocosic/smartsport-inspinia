<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <h3 class="form-signin-heading text-center"><?=__('Edit user');?></h3>
        <div class="form-group">
            
        <?php 
            $options = ['0'=>'not set', '1'=>'Admin','2'=>'User'];
            echo $this->Form->hidden('id',['value'=>$this->request->data['User']['id']]);
            echo $this->Form->input('username', [
                'class'=>'form-control','placeholder'=>__('Username'),
                'label'=>__('Username'),
                'value'=>$this->request->data['User']['username']
                ]);
            echo $this->Form->input('password', [
                'class'=>'form-control',
                'placeholder'=>__('Password'),
                'label'=>__('Password'),
                'value'=>''
                ]);
            echo $this->Form->input('mail', [
                'class'=>'form-control',
                'placeholder'=>__('E-mail'),
                'label'=>__('E-mail'),
                'value'=>$this->request->data['User']['mail']
                ]);
            echo $this->Form->input('name', [
                'class'=>'form-control',
                'placeholder'=>__('Name'),
                'label'=>__('Name'),
                'value'=>$this->request->data['User']['name']
                ]);
            echo $this->Form->input('surname', [
                'class'=>'form-control',
                'placeholder'=>__('Surname'),
                'label'=>__('Surname'),
                'value'=>$this->request->data['User']['surname']
                ]);
            echo $this->Form->select('group_id', $options, [
                'label'=>__('Group'),
                'value'=>$this->request->data['User']['group_id']
                ]);
        ?>
        </div>
        <?= $this->Form->submit(__('Save'));?>
        <?php echo $this->Session->flash(); ?>
    </fieldset>