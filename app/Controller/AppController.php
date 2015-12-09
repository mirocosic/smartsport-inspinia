<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
   
    public $components = array(
        'Acl',
        'Session',
        'Cookie',
        'Auth'=>array(
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                    
                )
            ),
            
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            ),
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'loginRedirect' => '/home',
        ),
        'DebugKit.Toolbar'
    );
    
   function beforeFilter() {
       
       // remove after testing
        $this->Auth->allow();
       
        $this->Auth->unauthorizedRedirect = false;
        $this->Auth->authError = "Access Denied";
       
        $this->_setLanguage();
        //upaliti ako ima problema sa Auth i localization - i saznati kak radi :D
        //$this->Auth->logoutRedirect = array( 'controller' => 'static', 'action' => 'index', 'language'=>$this->Session->read('Config.language'));
        //$this->Auth->loginRedirect = array( 'controller' => 'static', 'action' => 'dashboard', 'language'=>$this->Session->read('Config.language'));
        //this->Auth->loginAction = array( 'controller'=>'users', 'action'=>'login', 'language'=>$this->Session->read('Config.language'));
        
        
       
    }
    
    private function _setLanguage() {
        if ($this->Cookie->read('lang')){
           
            CakeLog::write('Language', 'Cookie');
            Configure::write('Config.language', $this->Cookie->read('lang'));
            $this->Session->write('Config.language', $this->Cookie->read('lang'));
        } else if($this->Session->check('Config.Language')){
            
            CakeLog::write('Language', 'Session');
            Configure::write('Config.language', $this->Session->read('Config.Language'));
            $this->Cookie->write('lang', $this->params['language'], false, '20 days');
        } else {
          
            CakeLog::write('Language', 'Else');
            $this->Session->write('Config.language', Configure::read('Config.Language'));
            $this->Cookie->write('lang', Configure::read('Config.Language'), false, '20 days');
        }
        
         
        
        if (isset($this->params['language'])) {
           
            CakeLog::write('Language', 'Params set');
            //then update the value in Session and the one in Cookie
            $this->Session->write('Config.language', $this->params['language']);
            $this->Cookie->write('lang', $this->params['language'], false, '20 days');
            
            Configure::write('Config.language', $this->Cookie->read('lang'));
        } else {
            CakeLog::write('Language', 'Params not set');
        }
    }
    
    function genRandomString($length = 10, $numbers = true, $letters = true) {
        $chars = '';
        if ($numbers){$chars .= '0123456789';}
        if ($letters){$chars .= 'ABCDEFGHIJKLMNOPRSTUVWXYZabcdefghijklmnopqrstuvwxyz';}
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $chars[mt_rand(0, strlen($chars))];
        }
        return $string;
    }

/*
 //override redirect
    public function redirect( $url, $status = NULL, $exit = true ) {
        if (!isset($url['language']) && $this->Session->check('Config.language')) {
            $url['language'] = $this->Session->read('Config.language');
        }
        parent::redirect($url,$status,$exit);
    }
*/
}
