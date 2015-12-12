<?php class ClubsController extends AppController {
    
    var $uses = ['Club','User','ClubMembership','ClubGroup'];
    
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

        if($this->ClubGroup->delete()){
            $response['success'] = true;
            $response['message'] = __('Deleted');
            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Oops!';
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