<?php 
class Session
/**
 * data(key, value) => set session
 * data(key) => get session
 */
{
    static public function data($key = '', $value = '')
    {
        $sessionKey = self::isInvalid();
        if (!empty($value)) {
            if (!empty($key)) {
                $_SESSION[$sessionKey][$key] = $value; // set Session
                return true;
            }
            return false;
        } else {
            if (empty($key)) {
                if (isset($_SESSION[$sessionKey])) {
                    return $_SESSION[$sessionKey];
                }
            }
            if ( isset($_SESSION[$sessionKey][$key]) ) {
                return $_SESSION[$sessionKey][$key]; // get Session
            }
        }
    }

    // Xóa session với key (delete(key))
    // Xóa hết session delete()
    static public function delete($key = '')
    {
        $sessionKey = self::isInvalid();
        if (!empty($key)) {
            if (isset($_SESSION[$sessionKey][$key])) {
                unset($_SESSION[$sessionKey][$key]);
                return true;
            }
            return false;
        } else {
            unset($_SESSION[$sessionKey]);
            return true;
        }
        return false;
    }

    // Flash Data
    // set Flash data => like set session
    //  get Flash data => like get session, delete luon session sau khi get
    static public function flash($key = '', $value = '')
    {
       $dataFlash = self::data($key, $value);
       if (empty($value)) {
        self::delete($key);
       }
       return $dataFlash;
    }



    // Hiển thị lỗi
    static public function showErrors($message)
    {
       $data = ['message' => $message];
       App::$app->loadError('exception', $data); 
       die();
    }

    // Hiển thị lỗi phần Session
    static function isInvalid() 
    {
        global $config;

        if (!empty($config['session'])) {
            $sessionConfig = $config['session'];
            if ( !empty($sessionConfig['session_key']) ) {
                $sessionKey = $sessionConfig['session_key'];
                return $sessionKey;
            } else {
                self::showErrors('Thiếu cấu hình session_key. Vui lòng kiểm tra lại!');
            }
        } else {
            self::showErrors('Thiếu cấu hình session. Vui lòng kiểm tra lại!');
        }
    }
}

?>