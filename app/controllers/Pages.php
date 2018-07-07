<?php
    class Pages extends Controller {

        public function __construct(){

        
        }

        

        public function index()
        {

            $data=[

                'tittle'=>'Home Page',
                'description'=> 'Simple Social Network On php framework',
            ];


           $this->view('pages/index', $data);

        }

        

        public function about(){
            
            $data=[

                'tittle'=>'About Us',
                'description'=>'This a Test page about php MVC Framework',
            ];

            $this->view('pages/about',$data);
           
        }

    }