<?php
class Request
{
    /**
     * 1. Method
     * 2. Body
     * 
     */
    //  Tạo thuộc tính 
    private $__rules = [], $__messages = [], $__errors = [];
    public $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    // KIỂM TRA XEM PHƯƠNG THỨC NÀY CÓ PHẢI POST KHÔNG ?
    public function isPost()
    {
        if ($this->getMethod() == 'post') {
            return true;
        }
        return false;
    }

    // KIỂM TRA XEM PHƯƠNG THỨC NÀY CÓ PHẢI GET KHÔNG ?
    public function isGet()
    {
        if ($this->getMethod() == 'get') {
            return true;
        }
        return false;
    }

    // LẤY GIÁ TRỊ TỪ CÁC FIELD
    /**
     * Hàm filter_input có bộ lọc nhằm lọc đi các kí tự đặc biệt.
     */
    public function getFields()
    {
        $dataFields = [];

        // Lấy dữ liệu đối với pt Get
        if ($this->isGet()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    if (is_array($value)) {
                        $dataFields[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $dataFields[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        // Lấy dữ liệu đối với pt Post
        if ($this->isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    if (is_array($value)) {
                        $dataFields[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $dataFields[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        return $dataFields;
    }

    //  VALIDATE FORM

    // Set rules
    public function rules($rules = [])
    {
        $this->__rules = $rules;
    }

    // Set message
    public function message($message)
    {
        $this->__messages = $message;
    }

    // Run validate
    public function validate()
    {
        // true sẽ chấp thuận, false sẽ loại.
        $this->__rules = array_filter($this->__rules);

        $checkValidate = true;

        if (!empty($this->__rules)) {

            $dataFields = $this->getFields();

            foreach ($this->__rules as $fieldName => $rulesItem) {
                $rulesItemArr = explode('|', $rulesItem);

                foreach ($rulesItemArr as $rules) {

                    $ruleName = null;
                    $ruleValue = null;

                    $rulesArr = explode(':', $rules);
                    $ruleName = reset($rulesArr);

                    if (count($rulesArr) > 1) {
                        $ruleValue = end($rulesArr);
                    }

                    if ($ruleName == 'required') {
                        if (empty(trim($dataFields[$fieldName]))) {
                            $this->setErrors($fieldName, $ruleName);
                            $checkValidate = false;
                        }
                    }

                    if ($ruleName == 'min') {
                        if (strlen(trim($dataFields[$fieldName])) < $ruleValue) {
                            $this->setErrors($fieldName, $ruleName);
                        }
                    }

                    if ($ruleName == 'max') {
                        if (strlen(trim($dataFields[$fieldName])) > $ruleValue) {
                            $this->setErrors($fieldName, $ruleName);
                            $checkValidate = false;
                        }
                    }

                    if ($ruleName == 'email') {
                        if (!filter_var($dataFields[$fieldName], FILTER_VALIDATE_EMAIL) > $ruleValue) {
                            $this->setErrors($fieldName, $ruleName);
                            $checkValidate = false;
                        }
                    }

                    if ($ruleName == 'match') {
                        if (trim($dataFields[$fieldName]) != trim($dataFields[$ruleValue])) {
                            $this->setErrors($fieldName, $ruleName);
                            $checkValidate = false;
                        }
                    }

                    if ($ruleName == 'unique') {
                        $tableName = null;
                        $fieldCheck = null;
                        if (!empty($rulesArr[1])) {
                            $tableName = $rulesArr[1]; // table user
                        }

                        if (!empty($rulesArr[2])) {
                            $fieldCheck = $rulesArr[2]; // email column
                        }

                        if (!empty($tableName) && !empty($fieldCheck)) {

                            if (count($rulesArr)) {
                                $checkExist = $this->db->query("SELECT $fieldCheck FROM $tableName WHERE $fieldCheck = '$dataFields[$fieldName]'")->rowCount();
                            } elseif (count($rulesArr) == 4) {
                                if (!empty($rulesArr[3]) && preg_match('~.+?\=.+?~is', $rulesArr[3])) {
                                    $conditionWhere = $rulesArr[3];
                                    $conditionWhere = str_replace('=', '<>', $conditionWhere);
                                    $checkExist = $this->db->query("SELECT $fieldCheck FROM $tableName WHERE $fieldCheck = '$dataFields[$fieldName]' AND $conditionWhere")->rowCount();
                                }
                            }

                            if (!empty($checkExist)) {
                                $this->setErrors($fieldName, $ruleName);
                                $checkValidate = false;
                            }
                        }
                    }
                }
            }
        }
        $sessionKey = Session::isInvalid();
        Session::flash($sessionKey.'_errors', $this->errors());
        Session::flash($sessionKey.'_old', $this->getFields());
        return $checkValidate;
    }

    // Get errors
    public function errors($fieldName = '')
    {
        if (!empty($this->__errors)) {
            if (empty($fieldName)) {
                $errorsArr = [];
                foreach ($this->__errors as $key => $error) {
                    $errorsArr[$key] = reset($error);
                }
                return $errorsArr;
            }

            return reset($this->__errors[$fieldName]);
        }

        return false;
    }

    // Set errors
    public function setErrors($fieldName, $ruleName)
    {
        $this->__errors[$fieldName][$ruleName] = $this->__messages[$fieldName . '.' . $ruleName];
    }
}
