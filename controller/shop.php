<?php
class ShopController extends Controller
{

    public function Get(){

        $this->loader->model('shop');
        $shop = new Shop($this->registry);
        $this->shop = $shop;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->owner_id){
                $response = $this->createShop();
            }else{
                $response = ['code' => '401', 'data' => ['message' => 'Access token is missing or invalid']];
            }

        }elseif ($_SERVER['REQUEST_METHOD'] === 'PUT'){
            if (!is_null($this->key) && $this->owner_id){
                $response = $this->editShop();
            }else{
                $response = ['code' => '404', 'data' => ['message' => 'Resource not found']];
            }
        }elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            if (!is_null($this->key) && $this->owner_id){
                $response = $this->deleteShop();
            }else{
                $response = ['code' => '404', 'data' => ['message' => 'Resource not found']];
            }
        }elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $response = $this->getShops();
        }else{
            $response = ['code' => '404', 'data' => ['message' => 'Resource not found']];
        }

        return $response;

    }

    protected function getShops(){
        if (isset($this->request_data['owner_id']))
            $owner_id = $this->request_data['owner_id'];
        else
            $owner_id = $this->owner_id;

        $shops = $this->shop->getShops($owner_id,$this->request_data);
        return ['code' => '201', 'data' => $shops];
    }

    public function createShop(){
        $validation = $this->validate();
        if (empty($validation)){
            $shop_id = $this->shop->createShop($this->request_data);
            return ['code' => '201', 'data' => ['message' => 'Shop Created Successfully', 'id' => $shop_id]];
        }else{
            return ['code' => '422', 'data' => ['message' => 'The given data was invalid', 'errors' => $validation]];
        }
    }

    public function editShop(){
        $validation = $this->validateEdit();

        if (empty($validation)){
            $this->shop->editShop($this->key,$this->request_data);
            return ['code' => '201', 'data' => ['message' => 'Shop Edited Successfully']];
        }else{
            return ['code' => '422', 'data' => ['message' => 'The given data was invalid', 'errors' => $validation]];
        }
    }

    public function deleteShop(){
        $validation = $this->validateOwner();
        if (empty($validation)){
            $this->shop->deleteShop($this->key);
            return ['code' => '201', 'data' => ['message' => 'Shop Deleted Successfully']];
        }else{
            return ['code' => '422', 'data' => ['message' => 'The given data was invalid', 'errors' => $validation]];
        }
    }

    protected function validate(){
        $required_fields = ['category_id','description','open_hours','city'];

        $errors = [];
        foreach ($required_fields as $required_field){
            if (!isset($this->request_data[$required_field])){
                $errors[] = 'Please fulfill all the required fields';
                break;
            }
        }

        if (empty($errors)){
            if (strlen($this->request_data['description']) < 5)
                $errors[] = 'Description must be longer than 5 characters';

            if (strlen($this->request_data['open_hours']) < 5)
                $errors[] = 'Open hours must be longer than 5 characters';

            if (strlen($this->request_data['city']) < 5 || strlen($this->request_data['city']) > 150)
                $errors[] = 'City must be between 5 and 150 characters';

            if (isset($this->request_data['address'])){
                if (strlen($this->request_data['address']) < 5 || strlen($this->request_data['address']) > 255)
                    $errors[] = 'Address must be between 5 and 255 characters';
            }

            if(!$this->shop->CheckCategory($this->request_data['category_id']))
                $errors[] = 'Category doesnt exist (1:food , 2:clothing , 3:furniture)';
        }

        return $errors;
    }

    protected function validateOwner(){
        $errors = [];

        if (!is_null($this->key)){
            if ($this->shop->CheckShop($this->key)){
                if (!$this->shop->checkShopOwner($this->key,$this->owner_id)){
                    $errors[] = 'You dont have the permission to edit this shop';
                }
            }else{
                $errors[] = 'Shop id not exist';
            }
        }else{
            $errors[] = 'Please select shop';
        }

        return $errors;
    }

    protected function validateEdit(){
        $errors = [];
        $errors = $this->validateOwner();

        if (empty((array)$this->request_data)){
            $errors[] = 'No data given';
        }

        if (empty($errors)) {
            if (isset($this->request_data['description'])) {
                if (strlen($this->request_data['description']) < 5)
                    $errors[] = 'Description must be longer than 5 characters';
            }
            if (isset($this->request_data['open_hours'])) {
                if (strlen($this->request_data['open_hours']) < 5)
                    $errors[] = 'Open hours must be longer than 5 characters';
            }
            if (isset($this->request_data['city'])) {
                if (strlen($this->request_data['city']) < 5 || strlen($this->request_data['city']) > 150)
                    $errors[] = 'City must be between 5 and 150 characters';
            }
            if (isset($this->request_data['address'])) {
                if (strlen($this->request_data['address']) < 5 || strlen($this->request_data['address']) > 255)
                    $errors[] = 'Address must be between 5 and 255 characters';
            }
            if (isset($this->request_data['category_id'])) {
                if (!$this->shop->CheckCategory($this->request_data['category_id']))
                    $errors[] = 'Category doesnt exist (1:food , 2:clothing , 3:furniture)';
            }
        }

        return $errors;
    }
}