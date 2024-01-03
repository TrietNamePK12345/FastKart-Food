<?php 
class HtmlHelper
{
    static function formOpen($method = '', $action = '')
    {
        echo '<form method="'.$method.'" action = "'.$action.'"> ';
    }

    static function formClose()
    {
        echo '</form>';
    }

    static function input($wrapBefore = '', $wrapAfter = '', $type = '', $name = '', $value = '', $class = '', $id = '', $placeholder = '')
    {
        echo $wrapBefore;
        echo '<input type = "'.$type.'" name = "'.$name.'" value = "'.$value.'" class = "'.$class.'" id="'.$id.'" placeholder = "'.$placeholder.'" /><br>';
        echo $wrapAfter;
    }


    static function submit($label = '', $class = '')
    {
        echo "<button type='submit' class='$class'>$label</button>";
    }
}

?>