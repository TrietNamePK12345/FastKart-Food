<?php 
class ParamsMiddleware extends Middlewares
{
    public function handle()
    {
        // $url = Route::getFullUrl();
        // echo $url;
       if (!empty( $_SERVER['QUERY_STRING'])) {
        $response = new Response();
        $response->redirect(Route::getFullUrl());
       }
    }
}
 
?>