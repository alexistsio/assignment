<?php
class UserController extends Controller {

    public function Get(){



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $this->register();
        }elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $response = $this->login();
        }else{
            $response = ['code' => '404', 'data' => ['message' => 'Resource not found']];
        }

        return $response;

	}

	protected function login(){
        $access_token = $this->user->loginOwner($this->request_data);
        if ($access_token){
            return ['code' => '201', 'data' => ['access_token' => $access_token]];
        }else{
            return ['code' => '422', 'data' => ['message' => 'The given data was invalid']];
        }
    }

	protected function register(){
        $validation = $this->validate();
        if (empty($validation)){
            $this->user->createOwner($this->request_data);
            return ['code' => '201', 'data' => ['message' => 'Owner Created Successfully']];
        }else{
            return ['code' => '422', 'data' => ['message' => 'The given data was invalid', 'errors' => $validation]];
        }
    }

    protected function validate(){
        $required_fields = ['name','email','password'];

        $errors = [];
        foreach ($required_fields as $required_field){
            if (!isset($this->request_data[$required_field])){
                $errors[] = 'Please fulfill all the required fields';
                break;
            }
        }

        if (empty($errors)){

            if (!filter_var($this->request_data['email'], FILTER_VALIDATE_EMAIL))
                $errors[] = 'Invalid email';

            if (strlen($this->request_data['password']) < 5 || strlen($this->request_data['password']) > 25)
                $errors[] = 'Password must be between 5 and 25 characters';

            if (strlen($this->request_data['name']) < 5 || strlen($this->request_data['name']) > 25)
                $errors[] = 'Name must be between 5 and 25 characters';

            if($this->user->CheckOwner($this->request_data['email']))
                $errors[] = 'Owner already exist';
        }

        return $errors;
    }



}