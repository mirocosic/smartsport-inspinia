<?php class HomeController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
        
        $this->layout = 'Home';
    }
    
    function index(){
        
    }
}