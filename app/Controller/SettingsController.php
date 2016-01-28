<? class SettingsController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();

        $this->layout = 'Home';

    }

    function index(){

    }
}