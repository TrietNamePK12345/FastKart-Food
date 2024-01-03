<?php
trait QueryBuilder
{

    public $tableName = '';
    public $where = '';
    public $operator = '';
    public $selectField = '*';
    public $limit = '';
    public $orderBy = '';
    public $join = '';


    
    //  TABLE
    public function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    // INSERT 
    public function insert($data)
    {
        $tableName = $this->tableName;
        $insertStatus = $this->insertData($tableName, $data);
        return $insertStatus;
    }

    // UPDATE
    public function update($data)
    {
        $whereUpdate = str_replace('WHERE', '', $this->where);
        $whereUpdate = trim($whereUpdate);
        $tableName = $this->tableName;
        $statusUpdate = $this->updateData($tableName, $data, $whereUpdate);
        return $statusUpdate;
    }

    // DELETE
    public function delete()
    {
        $whereDelete = str_replace('WHERE', '', $this->where);
        $whereDelete = trim($whereDelete);
        $tableName = $this->tableName;
        $statusDelete = $this->deleteData($tableName, $whereDelete);
        return $statusDelete;
    }

    // LastID : sau khi insert thì sẽ hiển thị id mới tạo
    public function lastId()
    {
        return $this->lastInsertId();
    }


    //  LIMIT
    public function limit($number, $offset = 0)
    {

        $this->limit = "LIMIT $offset, $number";
        return $this;
    }

    // ORDER BY id ASC
    // $this->db->orderBy('id', DESC) TH1
    // $this->db->orderBy('id ASC', name DESC) TH2
    public function orderBy($field, $type = 'ASC')
    {
        $fieldArr = array_filter(explode(',', $field));
        // Toán tử điều kiện (ternary opretaor)
        $this->orderBy = (count($fieldArr) >= 2) ? "ORDER BY ".implode(', ', $fieldArr) : "ORDER BY ".$field." ".$type;
        return $this;
    }

    // INNER JOIN
    public function join($tableName, $relationship)
    {
        $this->join .= "INNER JOIN $tableName ON $relationship".' ';
        return $this;
    }

    // WHERE
    public function where($field, $compare, $value)
    {
        if (empty($this->where)) {
            $this->operator = 'WHERE';
        } else {
            $this->operator = 'AND';
        }
        $this->where .= "$this->operator $field $compare '$value'";
        return $this;
    }

    // WHERE OR
    public function orWhere($field, $compare, $value)
    {
        if (empty($this->where)) {
            $this->operator = 'WHERE';
        } else {
            $this->operator = ' OR';
        }
        $this->where .= "$this->operator $field $compare '$value'";
        return $this;
    }

    // WHERE LIKE
    public function whereLike($field, $value)
    {
        if (empty($this->where)) {
            $this->operator = 'WHERE';
        } else {
            $this->operator = ' AND';
        }
        $this->where .= "$this->operator $field LIKE '$value'";
        return $this;
    }


    // SELECT
    public function select($field)
    {
        $this->selectField = $field;
        return $this;
    }

    // GET
    public function get()
    {
        $sqlQuery = "SELECT $this->selectField FROM  $this->tableName $this->join $this->where $this->orderBy $this->limit";
        $query = $this->query($sqlQuery);
        $this->resetQuery();
        if (!empty($query)) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // FIRST
    public function first()
    {
        $sqlQuery = "SELECT $this->selectField FROM  $this->tableName $this->join $this->where $this->orderBy  $this->limit";
        $query = $this->query($sqlQuery);
        $this->resetQuery();
        if (!empty($query)) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function resetQuery()
    {
        $this->tableName = '';
        $this->where = '';
        $this->operator = '';
        $this->selectField = '*';
        $this->limit = '';
        $this->orderBy = '';
        $this->join = '';
    }
}
