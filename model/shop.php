<?php
class Shop extends Model{
    public function getShops($owner_id,$data){
        $return = [];
        $sql = "SELECT s.shop_id, s.address, s.category_id, ac.name as category_name, s.city, s.description, s.open_hours, s.owner_id, ao.name AS owner_name 
                FROM alx_shop s 
                LEFT JOIN alx_owner ao on s.owner_id = ao.owner_id
                LEFT JOIN alx_category ac on s.category_id = ac.category_id
        ";

        $prepare_values = [];
        $where = [];
        if ($owner_id){
            $where[] = 's.owner_id = ? ';
            $prepare_values[] = $owner_id;
        }

        if (isset($data['description'])){
            $where[] = 's.description LIKE ? ';
            $prepare_values[] = '%'.$data['description'].'%';
        }

        if (isset($data['city'])){
            $where[] = 's.city LIKE ? ';
            $prepare_values[] = '%'.$data['city'].'%';
        }

        if (isset($data['category_id'])){
            $where[] = 's.category_id = ? ';
            $prepare_values[] = $data['category_id'];
        }

        if (!empty($where))
            $sql .= 'WHERE ' . implode(' AND ',$where);

        $total = $this->getTotalShops($data);
        $return['total'] = (int)$total;
        if (isset($data['limit']) && isset($data['page'])){
            if ($data['page'] > 0)
                $start = ($data['page'] - 1) * $data['limit'];
            else
                $start = 0;

            $sql .= ' LIMIT ' . (int)$start . ',' . (int)$data['limit'];
            $return['pages'] = ceil($total/$data['limit']);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($prepare_values);
        $result = $stmt->fetchAll();

        $return['data'] = $result;

        return $return;
    }

    public function getTotalShops($data = []){
        $sql = "SELECT COUNT(s.shop_id) AS cc FROM alx_shop s ";

        $prepare_values = [];
        $where = [];
        if ($this->owner_id){
            $where[] = 's.owner_id = ? ';
            $prepare_values[] = $this->owner_id;
        }

        if (isset($data['description'])){
            $where[] = 's.description LIKE ? ';
            $prepare_values[] = '%'.$data['description'].'%';
        }

        if (isset($data['city'])){
            $where[] = 's.city LIKE ? ';
            $prepare_values[] = '%'.$data['city'].'%';
        }

        if (isset($data['category_id'])){
            $where[] = 's.category_id = ? ';
            $prepare_values[] = $data['category_id'];
        }

        if (!empty($where))
            $sql .= 'WHERE ' . implode(' AND ',$where);

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($prepare_values);
        $result = $stmt->fetch();
        return $result['cc'];
    }

    public function createShop($data){


        $insert_fields = ['owner_id','category_id','description','open_hours','city'];
        $insert_values = ['?','?','?','?','?'];
        $prepare_data = [$this->owner_id,$data['category_id'],$data['description'],$data['open_hours'],$data['city']];
        if (isset($data['address'])){
            $insert_fields[] = 'address';
            $prepare_data[] = $data['address'];
            $insert_values[] = '?';
        }

        $sql = "INSERT INTO alx_shop (" . implode(',',$insert_fields) . ") VALUES (" . implode(',',$insert_values) . ")";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($prepare_data);
        return $this->conn->lastInsertId();
    }

    public function editShop($shop_id,$data){
        $prepare_values = [];
        $sql  = "UPDATE alx_shop SET ";

        $update = [];
        foreach ($data as $key => $value){
            $update[] = $key . ' = ?';
            $prepare_values[] = $value;
        }
        $sql .= implode(', ',$update);
        $sql .= ' WHERE shop_id = ?';

        $prepare_values[] = $shop_id;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($prepare_values);
    }

    public function deleteShop($shop_id){
        $stmt = $this->conn->prepare("DELETE FROM alx_shop WHERE shop_id = ?");
        $stmt->execute([$shop_id]);
    }

    public function checkCategory($category_id){
        $stmt = $this->conn->prepare("SELECT category_id FROM alx_category WHERE category_id = ?");
        $stmt->execute([$category_id]);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }

    public function checkShop($shop_id){
        $stmt = $this->conn->prepare("SELECT shop_id FROM alx_shop WHERE shop_id = ?");
        $stmt->execute([$shop_id]);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }

    public function checkShopOwner($shop_id,$owner_id){
        $stmt = $this->conn->prepare("SELECT shop_id FROM alx_shop WHERE shop_id = ? AND owner_id = ?");
        $stmt->execute([$shop_id,$owner_id]);
        if ($stmt->rowCount())
            return true;
        else
            return false;
    }
}