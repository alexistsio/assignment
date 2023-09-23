<?php


class Loader
{
	protected $registry;

	public function Model($path){
		include(DIR_MODEL . $path . '.php');
	}

	public function Controller($path,$registry){
		if (file_exists(DIR_CONTROLLER . $path[0] . '.php')) {
			$this->registry = $registry;
            $controllername = explode('/',$path[0]);
            $controllername = implode('',$controllername);
            require(DIR_CONTROLLER . $path[0] . '.php');
			$object = $controllername .'Controller';
			$object = ucfirst($object);
            if (isset($path[1]))
			    $registry->set('key',$path[1]);
            else
                $registry->set('key',null);
			$page = new $object($registry);

//			if (count($path)==1)
//			    $action="get";
//          else
//              $action = $path[1];
            $action="get";

            if(method_exists($page,$action))
                return $page->$action();
            else
                return ['code' => '404', 'data' => ['message' => 'Resource not found']];

		}
		else return ['code' => '404', 'data' => ['message' => 'Resource not found']];

	}


}