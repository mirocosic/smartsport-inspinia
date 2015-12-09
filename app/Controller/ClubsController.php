<?php class ClubsController extends AppController {
    
    var $uses = ['Club','User','ClubMembership'];
    
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