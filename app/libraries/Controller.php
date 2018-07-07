<?php 
    /*
    *   Base Controller 
    *   Loades the models and views
    */
    class Controller{

        //Load Model
        public function model($model){

            //Requeir model file
            require_once "../app/models/".$model.".php";
            //Instatiate Models
            return new $model();
        }
    

    //Load View
    public function view($view,$data=[]){

        if(file_exists('../app/views/'.$view.'.php'))
        {
            require_once '../app/views/'.$view.'.php';
        }

        else{
            
            die('View does not exist!!!');
        }

    }
}
?>