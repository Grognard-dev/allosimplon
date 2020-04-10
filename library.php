<?php 
function e($string, $flags=ENT_QUOTES){
    return htmlspecialchars ($string,$flags);
}

function flash(){
    global $flash;
    if(isset($flash)){
        ?>
        <div role='alert' class="m-4 max-w-screen-md">
        <div class='bg-red-500 text-white font-bold rounded-t px-4 py-2 '>
        danger
        </div>
        <div class='border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700 '>
        <p>
        <?=e($flash)?>
        </p>
        </div>
        </div>
        <?php
    }
}