<?
class ErrorsController extends AppController {
    public $name = 'Errors';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('error403');
    }

    public function error404() {
        //$this->layout = 'default';
    }

    public function error403() {
        //$this->layout = 'default';
    }
}