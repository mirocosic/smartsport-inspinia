<? class AllianceController extends AppController {

    var $uses = ['Club,User'];

    function beforeFilter(){
        parent::beforeFilter();
        $this->layout = 'Home';

    }

    function clubs(){

    }
}