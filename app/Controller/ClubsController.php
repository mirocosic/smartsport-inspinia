<?php class ClubsController extends AppController {
    
    var $uses = ['Club','User','UsersClub', 'ClubMembership','ClubGroup','ClubGroupMembership','ClubEvent','ClubEventMembership',
    'MembershipFee'];
    
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

        $this->Club->contain();
        $this->set('club',$this->Club->findById($this->Session->read('Auth.Club_id')));
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
            'contain'=>[
                'User'=>[
                    'id','name','surname',
                    'Image'=>[
                        'conditions'=>[
                            'default'=>true
                        ]
                    ]
                ]
            ]
        ]);
        $this->set('members',$members['User']);
    }

    function fees($month = false, $year = false, $club_group_id = false){
        $this->layout = 'Home';
        $this->autoRender = 'fees';

        if (!$month){$month = date('m');}
        if (!$year){$year = date('Y');}

        $result = $this->ClubMembership->find('all',[
            'conditions'=>['ClubMembership.club_id'=>$this->Session->read('Auth.Club_id')],
            'fields'=>'ClubMembership.user_id'
        ]);
        $userIds = array();
        foreach ($result as $item){
            array_push($userIds,$item['ClubMembership']['user_id']);
        }

        $result = $this->User->find('all',[
            'conditions'=>['User.id'=>$userIds],
            'contain'=>[
                'MembershipFee'=>[
                    'conditions'=>[
                        'MONTH(MembershipFee.date)'=>date('m', strtotime($year.'/'.$month.'/'.'01')),
                        'YEAR(MembershipFee.date)'=>date('Y', strtotime($year.'/'.$month.'/'.'01'))
                    ]
                ],
                'ClubGroupMembership'=>[
                    'conditions'=>[
                        'ClubGroupMembership.club_group_id'=>$club_group_id
                    ]
                ]
            ]
        ]);

        // znaci filter po clubgroups. ako nije definirana grupa znaci svi, znaci ne filtriram
        if ($club_group_id){
            foreach($result as $key => $value){
                if (empty($value['ClubGroupMembership'])){
                    unset($result[$key]);
                }
            }
        }



        $clubGroups = $this->ClubGroup->find('list', [
            'conditions'=>['ClubGroup.club_id'=>$this->Session->read('Auth.Club_id')],
            'fields'=>['ClubGroup.id','ClubGroup.name']
        ]);

        if($club_group_id){
            $this->set('selectedClubGroup',$club_group_id);
        } else {
            $this->set('selectedClubGroup',0);
        }

        $this->set('clubGroups',$clubGroups);
        $this->set('month',date('m.Y', strtotime($year.'/'.$month.'/'.'01')));
        $this->set('fees',$result);

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
            ],
            'order'=>'ClubEvent.date DESC'
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

        $this->request->data['ClubEvent']['date'] = date('Y-m-d H:i:s',strtotime( $this->request->data['ClubEvent']['date']));

        if (empty($this->request->data['User']['id'])){
            $this->request->data['User']['id'] = array();
        }

        //save all users from groups
        if (!empty($this->request->data['ClubGroup']['id'])){
            foreach($this->request->data['ClubGroup']['id'] as $clubGroupId){
                $groupUsers = $this->ClubGroup->find('first',array(
                    'conditions'=>array('ClubGroup.id'=>$clubGroupId),
                    'contain'=>array('User.id')
                ));
                foreach($groupUsers['User'] as $user){
                    array_push($this->request->data['User']['id'],$user['id']);
                }
            }
        }

        $this->log(print_r($this->request->data, true),'clubevent');

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

    function deleteClubEvent(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = __('Empty data sent!');
            return json_encode($response);
        }

        if ($this->ClubEvent->delete($this->request->data['event_id'], true)){
            $response['success'] = true;
            $response['message'] = __('Yep! Gone!');
            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = __('Ooops! Something went wrong!');
           return json_encode($response);
        }

    }

    function updateAttendance(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }
        //update field where ids match
        $record = $this->ClubEventMembership->find('first',[
           'conditions'=>[
               'ClubEventMembership.user_id'=>$this->request->data['user_id'],
               'ClubEventMembership.club_event_id'=>$this->request->data['event_id'],
            ]
        ]);
        if($this->request->data['attended'] == 'true'){$attended = true;} else {$attended = false;}
        if ($record) {
            $this->ClubEventMembership->clear();
            $this->ClubEventMembership->id = $record['ClubEventMembership']['id'];
            $saveData = array(
                'id'=>$record['ClubEventMembership']['id'],
                'attended'=>$attended
            );

            $this->ClubEventMembership->save($saveData);

        } else {
            $response['success'] = false;
            $response['message'] = 'Error finding record';
            return json_encode($response);
        }

        $response['success'] = true;
        $response['message'] = 'Update success';
        return json_encode($response);



    }

    function updateFee(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        if($this->request->data['paid'] == 'true'){$paid = true;} else {$paid = false;}

        $date_array = explode('.',$this->request->data('date'));


        $data = [
            'MembershipFee'=>[
                'id'=>$this->request->data['fee_id'],
                'user_id'=>$this->request->data['user_id'],
                'date'=>$date_array[1].'-'.$date_array[0].'-01',
                'paid'=>$paid
            ]
        ];

        if ($this->MembershipFee->save($data)){
            $response['success'] = true;
            $response['message'] = 'Update success';
            if (empty($this->request->data['fee_id'])){
                $response['new_id'] = $this->MembershipFee->id;
            } else {
                $response['new_id'] = false;
            }


            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Error while saving';
            return json_encode($response);
        }


    }

    function updateFeeNote(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        $date_array = explode('.',$this->request->data('date'));

        $data = [
            'MembershipFee'=>[
                'id'=>$this->request->data['fee_id'],
                'user_id'=>$this->request->data['user_id'],
                'note'=>$this->request->data['note'],
                'date'=>$date_array[1].'-'.$date_array[0].'-01'
            ]
        ];

        if ($this->MembershipFee->save($data)){
            if (empty($this->request->data['fee_id'])){
                $response['new_id'] = $this->MembershipFee->id;
            } else {
                $response['new_id'] = false;
            }
            $response['success'] = true;
            $response['message'] = 'Update success';
            return json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Error while saving';
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
            $saveData['Club']['uuid'] = uniqid();
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

    function createMember(){
        $this->layout = false;
        $this->autoRender = false;

        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        //if (empty($this->request->data['User_id'])){
            $this->User->create();
       // } else {
         //   $saveData['User']['id'] = $this->request->data['User_id'];

       // }

        $saveData['User']['name'] = trim($this->request->data['User_name']);
        $saveData['User']['surname'] = trim($this->request->data['User_surname']);
        $saveData['User']['mail'] = trim($this->request->data['User_mail']);
        $saveData['User']['username'] = trim($this->request->data['User_mail']);
        $saveData['User']['oib'] = trim($this->request->data['User_oib']);
        $saveData['User']['group_id'] = trim($this->request->data['User_group_id']);

        if (!empty($this->request->data['User_password'])){
            App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
            $passwordHasher = new BlowfishPasswordHasher();

            $saveData['User']['password'] = $passwordHasher->hash($this->request->data['User_password']);
        }

        if ($this->User->saveAll($saveData)){

              $saveData['ClubMembership']['user_id'] = $this->User->id;
              $saveData['ClubMembership']['club_id'] = $this->request->data['User_club_id'];
              $this->User->ClubMembership->save($saveData);

            $response['success'] = true;
            $response['message'] = __('User successfully saved.');
        } else {
            $response['success'] = false;
            $response['message'] = __('Error while saving. Please contact your Administrator.');

        }

        return json_encode($response);
    }

    function editMember(){
        $this->layout = false;
        $this->autoRender = false;

        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        $saveData['User']['id'] = $this->request->data['User_id'];
        $saveData['User']['name'] = trim($this->request->data['User_name']);
        $saveData['User']['surname'] = trim($this->request->data['User_surname']);
        $saveData['User']['mail'] = trim($this->request->data['User_mail']);
        $saveData['User']['username'] = trim($this->request->data['User_mail']);
        $saveData['User']['oib'] = trim($this->request->data['User_oib']);

        if (!empty($this->request->data['User_password'])){
            App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
            $passwordHasher = new BlowfishPasswordHasher();
            $saveData['User']['password'] = $passwordHasher->hash($this->request->data['User_password']);
        }

        if ($this->User->saveAll($saveData)){

            $response['success'] = true;
            $response['message'] = __('User successfully saved.');
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

    function editInfo(){
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = __('Empty data sent');
            return json_encode($response);
        }

        $data = [
            'Club'=>[
                'id'=>$this->Session->read('Auth.Club_id'),
                'name'=>$this->request->data('name'),
                'address'=>$this->request->data('address'),
                'city'=>$this->request->data('city'),
                'zip_code'=>$this->request->data('zip_code'),
                'mail'=>$this->request->data('mail'),
                'phone'=>$this->request->data('phone'),
                'oib'=>$this->request->data('oib'),
            ]
        ];

        if ($this->Club->save($data)){
            $response['success'] = true;
            $response['message'] = __("Saved!");
        } else {
            $response['success'] = false;
            $response['message'] = __('Error while saving. Please contact your Administrator.');
        }
        return json_encode($response);
    }
}