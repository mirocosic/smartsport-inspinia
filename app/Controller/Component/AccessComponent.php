<?php
class AccessComponent extends Component{
    var $components = array('Acl', 'Auth');
    var $user; 

    function startup(Controller $controller = null){
        $this->user = $this->Auth->user();
    }
    
    private function checkCache($aco, $action='*'){
        
        $value=Cache::read($this->Auth->user('id').':'.$aco.':'.$action,'permissions');
        if($value===false){
            if($this->Acl->check(array('User'=>array('id'=>$this->Auth->user('id'))), $aco, $action)){
                $value = 1;
            }else{
                $value = 0;
            }              
            Cache::write($this->Auth->user('id').':'.$aco.':'.$action,$value,'permissions');
        }
        return $value == 1 ? true:false;
    }


    function multiCheck($root,$permArr,$and=false){
        $return=false;
		$root=rtrim($root,'/');
        foreach($permArr as $perm){
            $result=$this->check($root.'/'.$perm);
            if($and){
                if(!$result) return false;
                $return=true;
            }else{
                if($result) return true;
            }
        }
        return $return;
    }
    
    
    
    function check($aco, $action='*'){ 
        if(!empty($this->user) && $this->checkCache($aco, $action)){
            return true;
        } else {
            return false;
        }
    }
    
    function checkHelper($aro, $aco, $action = "*"){
        /*    
        App::import('Component', 'Acl');
        $acl = new AclComponent();        
        return $acl->check($aro, $aco, $action);
         * */
         
        return $this->Acl->check($aro, $aco, $action);
    } 
    
    
    function checkChildren($aco, $action='*'){
        if(!empty($this->user) && $this->Acl->check(array('User'=>$this->Auth->user('id')), $aco, $action)){
            return true;
        } else {
            return false;
        }
    }
    function checkLocal() {
        if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
            return true;
        }

        return false;
    }
    
    function aclCheck($aro, $aco, $action = "*"){
        //TODO: cache
        return $this->Acl->check($aro, $aco, $action);
    }
    
    
    
    function checkExact($aro, $aco, $action = "*"){        
		if (!$aro || !$aco) {
			return false;
		}
        //TODO: get $permKeys from Permissions model
		//$permKeys = $this->getAcoKeys($this->schema());
        
//        $AroModel = & ClassRegistry::init('Aro');
//        $AcoModel = & ClassRegistry::init('Aco');
        $PermissionModel = & ClassRegistry::init('Permission');
        
		$aroPath = $PermissionModel->Aro->node($aro);
		$acoPath = $PermissionModel->Aco->node($aco);
//		$aroPath = $AroModel->node($aro);
//		$acoPath = $AcoModel->node($aco);

		if (!$aroPath) {
			$this->log(__d('cake_dev',
					"%s - Failed ARO node lookup in permissions check. Node references:\nAro: %s\nAco: %s",
					'DbAcl::check()',
					print_r($aro, true),
					print_r($aco, true)),
				E_USER_WARNING
			);
			return false;
		}

		if (!$acoPath) {
			$this->log(__d('cake_dev',
					"%s - Failed ACO node lookup in permissions check. Node references:\nAro: %s\nAco: %s",
					'DbAcl::check()',
					print_r($aro, true),
					print_r($aco, true)),
				E_USER_WARNING
			);
			return false;
		}
//TODO: add check with permissions module
//		if ($action !== 'allow' && !in_array('_' . $action, $permKeys)) {
//			$this->log(__d('cake_dev', "ACO permissions key %s does not exist in %s", $action, 'DbAcl::check()'), E_USER_NOTICE);
//			return false;
//		}
		
		$acoIDs = Hash::extract($acoPath, '{n}.' . $PermissionModel->Aco->alias . '.id');

        //debug($aroPath);
        
		$count = count($aroPath);
		for ($i = 0; $i < 1; $i++) {
//		for ($i = 0; $i < $count; $i++) {
			$permAlias = $PermissionModel->alias;

            
			$perms = $PermissionModel->find('all', array(
				'conditions' => array(
					"{$permAlias}.aro_id" => $aroPath[$i][$PermissionModel->Aro->alias]['id'],
					"{$permAlias}.aco_id" => $acoIDs[0]
				),
				'order' => array($PermissionModel->Aco->alias . '.lft' => 'desc'),
				'recursive' => 0
			));
            
           // debug($perms);

			if (empty($perms)) {
				continue;
			}
			$perms = Hash::extract($perms, '{n}.' . $PermissionModel->alias);
			foreach ($perms as $perm) {				
                switch ($perm['_' . $action]) {
                    case -1:
                        return -1;
                    case 0:
                        continue;
                    case 1:
                        return 1;
                }
				
			}
		}
		return 0;
	
    }
}
