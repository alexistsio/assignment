<?php
class User extends Model{
    public function Test(){


        $stmt = $this->conn->prepare("SELECT * FROM alx_user");
        $stmt->execute();
        $result = $stmt->fetchAll();



        $data = $result;

//        while ($row = $stmt->fetch()) {
//            $data[] = $row;
//            echo 1;
//        }

        return $data;
    }

    public function loginOwner($data){

        $stmt = $this->conn->prepare("SELECT owner_id, password FROM alx_owner WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->rowCount()) {
            $user_data = $stmt->fetch();
            if(hash_equals($user_data['password'], crypt($data['password'], $user_data['password']))){
                $access_token = md5(uniqid().rand(1000000, 9999999));
                $stmt = $this->conn->prepare("INSERT INTO alx_access_token (owner_id,access_token) VALUES (?,?)");
                $stmt->execute([$user_data['owner_id'],$access_token]);
                return $access_token;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public function createOwner($data){
        $salt= '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 50);
        $password = crypt($data['password'], $salt);

        $stmt = $this->conn->prepare("INSERT INTO alx_owner (name,password,email) VALUES (?,?,?)");
        $stmt->execute([$data['name'],$password,$data['email']]);
    }

    public function CheckOwner($email){
        $stmt = $this->conn->prepare("SELECT email FROM alx_owner WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }

    public function validateAccessToken($access_token){
        $stmt = $this->conn->prepare("SELECT * FROM alx_access_token WHERE access_token = ?");
        $stmt->execute([$access_token]);
        if ($stmt->rowCount())
            return ["status" => true, "owner_id" => $stmt->fetch()['owner_id']];
        else
            return ["status" => false];
    }

}