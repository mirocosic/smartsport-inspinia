<?php class ClubsController extends AppController {
    
    var $uses = ['Club','User','ClubMembership','ClubGroup','ClubGroupMembership','ClubEvent'];
    
     public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = false;
        $this->autoRender = false;
    }
    
    public function index(){
       
        $clubs = $this->Club->find('all',[
            'fields'=>['Club.id','Club.name']
        ]);
      
        return json_encode($clubs);
        
    }
    
    public function view(){
        $this->layout = 'Home';
        $this->autoRender = 'view';
    }
    
    public function members(){
        $this->layout = 'Home';
        $this->autoRender = 'members';
    }
    
    public function groups(){
        $this->layout = 'Home';
        $this->autoRender = 'members';


        $this->ClubGroup->contain('User');
        $clubGroups = $this->ClubGroup->find('all', [
            'conditions'=>['ClubGroup.club_id'=>$this->Session->read('Auth.Club_id')],
            'contain'=>['User.name','User.surname','User.id']
        ]);

        $this->set('clubGroups',$clubGroups);

        $members = $this->Club->find('first',[
            'conditions'=>['Club.id'=>$this->Session->read('Auth.Club_id')],
            'contain'=>['User.id','User.name','User.surname']
        ]);
        $this->set('members',$members['User']);
    }

    function addClubGroup(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        if (empty($this->request->data['name'])){
            $response['success'] = false;
            $response['message'] = __("Please enter group name");
            return json_encode($response);
        }

        $saveData = array(
            'ClubGroup'=>array(
                'name'=>$this->request->data('name'),
                'club_id'=>$this->request->data('club_id')
            )
        );

        if ($this->ClubGroup->save($saveData)){
            $response['success'] = true;
            $response['message'] = __('Group successfully saved');

        } else {
            $response['success'] = false;
            $response['message'] = __('Error while saving. Please contact your Administrator.');
        }

        return json_encode($response);
    }

    function deleteClubGroup(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        $this->ClubGroup->id = $this->request->data('id');

        if($this->ClubGroup->deleteAll(['ClubGroup.id'=>$this->request->data('id')])){
            $response['success'] = true;
            $response['message'] = __('Deleted');
            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Oops!';
            return json_encode($response);
        }
    }

    function updateClubGroups(){


        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        $users = array();
        $group_id = $this->request->data['group_id'];
        $group_id = str_replace('ClubGroup-','',$group_id);

        foreach($this->request->data['users'] as $user){
            $user =  str_replace('club-group-user-id-','',$user);

            array_push($users, $user);
        }
        $data = array('ClubGroup'=>array('id'=>$group_id),'User'=>array('User'=>$users));

        $this->log($data, 'Default');

        /*
                $data = array(
                    0=>array(
                        'User'=>array('User'=>array(4,5,6,7)),
                        'ClubGroup'=>array('id'=>1)
                    ),
                    1=>array(
                        'User'=>array('User'=>array(4,5,6,7)),
                        'ClubGroup'=>array('id'=>2)
                    ),
                );
        */
        if($this->ClubGroup->saveAll($data)){
            $response['success'] = true;
            $response['message'] = 'Saved!';
            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Ooops!';
            return json_encode($response);
        }
    }

    public function events(){
        $this->layout = 'Home';
        $this->autoRender = 'Events';

        $this->set('clubGroups',$this->ClubGroup->find('list')); // add club id condition, check docs

        $this->set('clubMembers',$this->User->find('list')); // add club id condition, check docs

        $events = $this->ClubEvent->find('all',[
            'conditions' => ['ClubEvent.club_id'=>$this->Session->read('Auth.Club_id')],
            'contain' => [
                'User.id','User.fullname',
                'ClubGroup'=>['User.id','User.fullname']
            ]
        ]);

        $this->set('events',$events);
    }

    function createClubEvent(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        $this->request->data['ClubEvent']['club_id'] = $this->Session->read('Auth.Club_id');

        $saveData = [
            'ClubEvent' => $this->request->data['ClubEvent'],
            'User' => ['User'=>$this->request->data['User']['id']],
            'ClubGroup' => ['ClubGroup'=> $this->request->data['ClubGroup']['id'] ]
        ];



        if($this->ClubEvent->saveAll($saveData)){
            $response['success'] = true;
            $response['message'] = __('Yep! Saved!');
            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }
    }

    function add(){
        
    }
    
    function edit($id = null){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }
        
        if (empty($this->request->data['Club_id'])){
            $this->Club->create();
        } else {
            $saveData['Club']['id'] = $this->request->data['Club_id'];
        }
        
        $saveData['Club']['name'] = trim($this->request->data['Club_name']);
       
        if ($this->Club->saveAll($saveData)){
            $response['success'] = true;
            $response['message'] = __('Club successfully saved.');
        } else {
            $response['success'] = false;
            $response['message'] = __('Error while saving. Please contact your Administrator.');

        }
    
        return json_encode($response);
    }
    
    function delete(){
        $response['success'] = true;
        $response['message'] = 'Yessss! Gone! Actually, this is not working yet ;)';
        return json_encode($response);
    }
    
    function getMembers(){
        if (empty($this->request->query['club_id'])){
            return json_encode(array('success'=>false,'message'=>'Club id empty'));
        }
        
        $result = $this->Club->find('first',[
            'conditions'=>['Club.id'=>$this->request->query['club_id']],
            'contain'=>['User']
        ]);
        
        return json_encode($result['User']);
       // debug($result['User']);
        
    }
    
    function addMember(){
        if (empty($this->request->data['club_id']) || empty($this->request->data['user_id'])){
            $response['success'] = false;
            $response['message'] = __('Please select a user');
            return json_encode($response);
        }
        
        
        
        $saveData['ClubMembership']['user_id'] = $this->request->data['user_id'];
        $saveData['ClubMembership']['club_id'] = $this->request->data['club_id'];
        
        if($this->ClubMembership->save($saveData)){
            $response['success'] = true;
            $response['message'] = '';
        } else {
            $response['success'] = false;
            $response['message'] = __('Error while saving. Please contact your Administrator.');
        }
        
        
        
        return json_encode($response);
    }
    
    function removeMember(){
        if (empty($this->request->data['users_club_id'])){
            $response['success'] = false;
            $response['message'] = __('Please select a user');
            return json_encode($response);
        }
        
        if ($this->ClubMembership->delete($this->request->data['users_club_id'])){
             $response['success'] = true;
            $response['message'] = '';
        } else {
            $response['success'] = false;
            $response['message'] = __('Unable to delete'); 
        }
         return json_encode($response);
    }
}