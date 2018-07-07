<?php 
    //Simple page function
    function redirect($page)
    {
        header('Location:'.URLROOT.'/'.$page);
    }