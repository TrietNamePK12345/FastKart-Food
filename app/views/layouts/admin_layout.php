<?php 
$this->render('blocks/admin/header');
$this->render('blocks/admin/sidebar');
$this->render('blocks/admin/navbar');
$this->render($content, $sub_content);
$this->render('blocks/admin/footer');
$this->render('blocks/admin/script');
?>