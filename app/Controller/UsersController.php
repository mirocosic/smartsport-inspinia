<?php class UsersController extends AppController {
    
    var $components = ['Email'];
    var $uses = ['User','ClubMembership','UserWeight','Image'];
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login','logout','register');
    }
    
    public function login() {
        $this->layout = "Login";
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $this->layout = false;
            
            $this->request->data['User']['username'] = $this->request->data['username'];
            $this->request->data['User']['password'] = $this->request->data['password'];
            
            if ($this->Auth->login()) {
               // return $this->redirect($this->Auth->redirectUrl());
               $user = $this->User->find('first', array(
                   'conditions'=>array(
                       'User.id'=>$this->Auth->user('id')
                       ),
                   'contain'=>['ClubMembership']
                  
                   )
                       );

                $profileImg = $this->Image->find('first',[
                    'conditions'=>[
                        'Image.user_id'=>$this->Auth->user('id'),
                        'Image.default'=>true]
                ]);


              // wow, this is soooo bad :)) - miro
                // not that bad, just incomplete
              $this->Session->write('Auth.Club_id', $user['ClubMembership'][0]['club_id']);

              if($profileImg){
                  $this->Session->write('Auth.ProfileImg', $profileImg['Image']['name']);
              } else {
                  $this->Session->write('Auth.ProfileImg', 'user.jpg');
              }

                
               $response['success'] = true;
               $response['message'] = 'Login successful!';
               $response['name'] = $this->Auth->user('name');
               $response['redirect'] = $this->Auth->redirectUrl();
               return json_encode($response);
            } else {
                $response['success'] = false;
                $response['message'] = 'Login failed!';
                return json_encode($response);
            }
           // $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }
    
    public function logout() {
        $this->Session->destroy();
        return $this->redirect($this->Auth->logout());
    }
    
    public function register(){
        $this->layout = "Register";
        
        if ($this->request->is('post')) {
    
            if (empty($this->request->data['email']) || empty($this->request->data['password'])){
                $response['success'] = false;
                $response['message'] = __('Bad request sent!');
                return json_encode($response);
            } else {
                $registerCode = $this->genRandomString();
                $uid = $this->genRandomString(12,true, false);
                
                App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
                $passwordHasher = new BlowfishPasswordHasher();
                
                $this->User->create();
                $data = [];
                $data['User']['username'] = $this->request->data['email'];
                $data['User']['password'] = $passwordHasher->hash($this->request->data['password']);
                $data['User']['email'] = $this->request->data['email'];
                $data['User']['code'] = $registerCode;
                $data['User']['uid'] = $uid;
                $data['User']['activated'] = 0;
               

                if ($this->User->save($data)) {
                    $this->Email->to = $this->request->data['email'];
                    $this->Email->subject = __('SmartSport registration');
                    $this->Email->replyTo = 'mirocosic@gmail.com';
                    $this->Email->from = 'SmartSport';
                    $this->Email->send('Hi!\n'
                        .'Molimo kliknite na slijedeći link da bi potvrdili svoju registraciju.'
                        .'http://demosmartsonnew.agramservis.hr/users/confirm_registration/'.$uid.'/'.$registerCode
                    );
                     
                    $response['success'] = true;
                    $response['message'] = __('We sent you an email with confirmation link inside. \n Please check your email and follow the link.');
                    return json_encode($response); 
                    
                    } else {
                        $response['success'] = false;
                        $response['message'] = __("There's trouble on our side. \n Please try again in a few moments.");
                        return json_encode($response);  
                    }
               }
        }
    }
   
    public function reset_password(){
        $this->layout = "ResetPassword";
       
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $this->layout = false;
       
        if (!isset($this->request->data['email'])) {
            $response['success'] = false;
            $response['message'] = __('Bad request sent').'!';
            return json_encode($response);
           
        } else {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->data['email'])));

            if ((!empty($user)) && ($user['User']['username'] == $this->request->data['email'])){
                 
                App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
                $passwordHasher = new BlowfishPasswordHasher();
                
                $new_pass = $this->genRandomString();
                
                $data['User']['password'] = $passwordHasher->hash($new_pass);
                $data['User']['id'] = $user['User']['id'];
                $this->User->save($data);

                $this->Email->smtpOptions = array(
                    'port' => '25',
                    'timeout' => '30',
                    'tls' => true,
                    'host' => 'owa.agramgroup.net',
                    'username' => 'miro.cosic@agramservis.hr',
                    'password' => 'c0j0nE$666',
                        //   'client' => 'smtp_helo_hostname'  
                );
                $this->Email->delivery = 'smtp';
                $this->Email->to = $user['User']['username'];
                $this->Email->subject = __('Forgot password');
                $this->Email->replyTo = 'mirocosic@gmail.com';
                $this->Email->from = 'smart@sport.com';
                //$this->Email->_debug = true;
                
                $text = 'Hi! <br/><br/>'
                        .'We got you a brand new password!<br/>'
                        .'<b>'.$new_pass.'</b><br/>'
                        .'Please take care of it better this time ;)<br/><br/>'
                        .'Cheers!<br/>'
                        .'SmartSport Team';
                
                $this->Email->send($text);
                
                
                $response['success'] = true;
                $response['message'] = __("We sent you a brand new password to your email address.\n Check your mail!");
                return json_encode($response);
               
            } else {
                $response['success'] = false;
                $response['message'] = __("We didn't find a user with this email.\n Are you sure you got it right?");
                return json_encode($response);
            }
        }
    
        }
    }
    
    function add(){
        
        App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
        $passwordHasher = new BlowfishPasswordHasher();
         
         
         if ($this->request->is('post')) {
            $this->User->create();
            $this->request->data['User']['password'] = $passwordHasher->hash($this->request->data['User']['password']);
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
        
    }
    
    function edit($id = null){
        
        $this->layout = false;
        $this->autoRender = false;
        
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }
        
        if (empty($this->request->data['User_id'])){
            $this->User->create();
        } else {
            $saveData['User']['id'] = $this->request->data['User_id'];

        }
        
        $saveData['User']['name'] = trim($this->request->data['User_name']);
        $saveData['User']['surname'] = trim($this->request->data['User_surname']);
        $saveData['User']['mail'] = trim($this->request->data['User_mail']);
        $saveData['User']['username'] = trim($this->request->data['User_mail']);
        $saveData['User']['oib'] = trim($this->request->data['User_oib']);
        $saveData['User']['group_id'] = trim($this->request->data['User_group_id']);

        $saveData['UsersClub']['club_id'] = $this->request->data['User_club_id'];
        
        
        if (!empty($this->request->data['User_password'])){
            App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
            $passwordHasher = new BlowfishPasswordHasher();
        
           $saveData['User']['password'] = $passwordHasher->hash($this->request->data['User_password']);
        }
        
        
        if ($this->User->saveAll($saveData)){

          //  $saveData['ClubMembership']['user_id'] = $this->User->id;
          //  $saveData['ClubMembership']['club_id'] = $this->request->data['User_club_id'];
         //   $this->User->ClubMembership->save($saveData);
            
            $response['success'] = true;
            $response['message'] = __('User successfully saved.');
        } else {
            $response['success'] = false;
            $response['message'] = __('Error while saving. Please contact your Administrator.');

        }
    
        return json_encode($response);
    }
    
    function index(){
        $this->layout = 'Home';
        $this->autoRender = false;
        
        $users = $this->User->find('all',[
            'fields'=>['User.id','User.username','User.name','User.surname','User.mail','User.oib']
        ]);
       
        return json_encode($users);
        
    }
    
    function view($id = null){
        if ($id == null){
            throw new NotFoundException;
        }
        
        $user = $this->User->find('first',[
            'conditions'=>['User.id'=>$id]
        ]);
        
        if ($user){
            $this->set('user',$user);
        } else {
            throw new NotFoundException;
        }
    }
    
    function delete(){
        $this->layout = false;
        $this->autoRender = false;
       
        if (empty($this->request->data['user_id'])){
            $response['success'] = false;
            $response['message'] = 'Empty id sent!';
            return json_encode($response);
        }
        
       // $this->User->id = $this->request->data['user_id'];
        if ($this->User->delete($this->request->data['user_id'])){
            $response['success'] = true;
            $response['message'] = 'Yessss! Gone!';
        } else {
           $response['success'] = false;
            $response['message'] = 'Error deleting user.'; 
        }
        
        return json_encode($response);
    }

    function weight(){
        $this->layout = 'Home';
    }

    function addWeight(){
        $this->layout = false;
        $this->autoRender = false;
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        if (empty($this->request->data['weight'])){
            return json_encode(array('success'=>false, 'message'=>__('Enter some weight...')));
        }

        $data = [
            'UserWeight'=>[
                'user_id'=>$this->Auth->user('id'),
                'weight'=>$this->request->data('weight'),
            ]
        ];

        if ($this->UserWeight->save($data)){
            return json_encode(array('success'=>true, 'message'=>__("Success")));
        } else {
            return json_encode(array('success'=>false, 'message'=>__('Error while saving data')));
        }

    }

    function getWeightData(){
        $this->layout = false;
        $this->autoRender = false;
        if (empty($this->request->data)){
            $response['success'] = false;
            $response['message'] = 'Empty data sent!';
            return json_encode($response);
        }

        $result = $this->UserWeight->find('all',[
            'conditions'=>['UserWeight.user_id'=>$this->request->data['user_id']],
            'fields'=>['UserWeight.created', 'UserWeight.weight']
        ]);

        $data = array();
        foreach ($result as $item){
            $row['date'] = $item['UserWeight']['created'];
            $row['weight']= $item['UserWeight']['weight'];
            array_push($data, $row);
        }

        return json_encode($data);

    }

    function profile(){
        $this->layout = 'Home';
    }
    
}