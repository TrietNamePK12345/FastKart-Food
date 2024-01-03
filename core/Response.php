<?
class Response{

    public function redirect($uri = '')
    {
        $pattern = '~^(http|https)~is';
        if (preg_match($pattern, $uri)) {
            $url = $uri;
        } else {
            $url = _WEB_ROOT.'/'.$uri;
        }
       
        header("Location:".$url);
        exit();
    }
}


?>