<?php 
function e($string, $flags=ENT_QUOTES){
    return htmlspecialchars ($string,$flags);
}

function flash(){
    global $flash;
    if(isset($flash)){
        echo e($flash);
    }
}