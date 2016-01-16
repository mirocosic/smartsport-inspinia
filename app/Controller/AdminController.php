<?php class AdminController extends AppController {

    var $components = array('Acl','Access');
    var $uses = array('Aco','User');
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'Home';
        $this->Auth->allow();
    }
    
    function index(){
      
    }
   
   
    function users(){
       
    }
    
    function clubs(){}

    function applyAco() {
        $this->autoRender = false;
        $this->layout = false;
        $aco = & $this->Acl->Aco;
        //51 in proc
        //52 history


        $list = array('index');


        foreach ($list as $text) {
            $permissions[] = array(
                'parent_id'=>7,
                'alias' => $text
            );
        }

     //   print_r($permissions);

        foreach ($permissions as $data) {
            $aco->clear();
            $aco->save($data);
        }
    }

    function testAco() {
        $this->autoRender = false;
        $this->layout = false;
        $user = $this->Auth->user();
        //print_r($user);
        if (

            //$this->Access->check('app/logistics/awaiting/import_file')
        $this->Access->check('controllers')

        ) {
            echo "DOZVOLJENO";
        } else
            echo "zabranjeno!";
            debug($_SESSION);


        //print_r($this->User->hasAndBelongsToMany);
    }

    function install() {
        $group =& $this->User->Group;

        //Allow admins to everything
        $group->id = 1;
        $this->Acl->allow($group, 'controllers');

        //allow users
      //  $group->id = 2;
       // $this->Acl->deny($group, 'controllers');
       /* $this->Acl->allow($group, 'controllers/Messages');
        $this->Acl->allow($group, 'controllers/MessageLists');
        $this->Acl->allow($group, 'controllers/Products');
        $this->Acl->allow($group, 'controllers/Widgetviews');*/
    }
    
}