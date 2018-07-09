<?php 

class Users extends Controller{

    public function __construct()
    {
        $this->userModel=$this->model('User');
    }

    public function register(){
        //Check for Post
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

           //Sanitize Post

           $_POST= filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
           
            //Process Form

            $data =[

                'name' => trim($_POST['name']),
                'email'=>trim($_POST['email']),
                'password'=>trim($_POST['password']),
                'confirm_password'=>trim($_POST['confirm_password']),
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_error'=>''

            ];

            //Validate Email

            if(empty($data['email']))
            {
                $data['email_err']='Please Enter Email!';

            }
            else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
               
                $data['email_err']="Invalid email address";

            } else{
                //Check email from DB
                if($this->userModel->findUserByEmail($data["email"]))
                {
                    $data['email_err']="Email already taken!";
                }
            }
            //Validate Name

            if(empty($data['name']))
            {
                $data['name_err']='Please Enter name!';

            }

            //Validate Password

            if(empty($data['password']))
            {
                $data['password_err']='Please Enter password!';

            }
            else if(strlen($data['password']) < 6)
            {
                $data['password_err']='Psssword must be at least 6 characters!';
            }

            //Validate Confirm Password

            if(empty($data['confirm_password']))
            {
                $data['confirm_password_err']='Please Confirm password!';

            }

            else if($data['password'] != $data['confirm_password'])
            {
                $data['confirm_password_err'] ='Password do not match!';
            }

            //Make Sure the err are empty
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) )
            {
                //Valid form
                
                //Hash Password
                $data['password']= password_hash( $data['password'] , PASSWORD_DEFAULT);

                //Register User

                if ($this->userModel->register($data))
                {
                    flash("register_success","You are registered and now you can login");
                    redirect("users/login");

                }
                    else
                    {
                        die('Something Went Wrong!');
                    }
            }
            else{
                //Load View with err
                $this->view('users/register', $data);

            }

            }
            else{
                //Init Data

                $data =[

                    'name' => '',
                    'email'=>'',
                    'password'=>'',
                    'confirm_password'=>'',
                    'name_err'=>'',
                    'email_err'=>'',
                    'password_err'=>'',
                    'confirm_password_error'=>''

                ];

                //Load view
                $this->view('users/register',$data);

            }
    }



    public function login(){
        //Check for Post
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Sanitize Post

            $_POST= filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //Process Form

            $data =[

                'email'=>trim($_POST['email']),
                'password'=>trim($_POST['password']),                
                'email_err'=>'',
                'password_err'=>''
            ];

            //Validate Email

            if(empty($data['email']))
            {
                $data['email_err']='Please Enter Email!';

            }
            else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
               
                $data['email_err']="Invalid email address";

            }
            

            //Validate Password

            if(empty($data['password']))
            {
                $data['password_err']='Please Enter password!';

            }

            //check for user /email

            if($this->userModel->findUserByEmail($data['email']))
            {
                 //User found
                
            }

            else
            {
                //User not found
                $data['email_err']='User not found';
            }

           //Make Sure the err are empty
           if(empty($data['email_err']) && empty($data['password_err']) )
           {
               //Valid form
               //Checked and set loged in user
               $loggedInUser=$this->userModel->login($data['email'],$data['password']);

               if($loggedInUser)
               {
                //Creat Session
                $this->createUserSession($loggedInUser);

               }else{
                   //Password is wrong
                   $data['password_err']='Incorect Password';

                   $this->view('users/login',$data);
               }
           }
           else
           {
            //Load View with err
            $this->view('users/login', $data);

            }

            }
            else{
                //Init Data

                $data =[

                    'email'=>'',
                    'password'=>'',
                    'email_err'=>'',
                    'password_err'=>''

                ];

                //Load view
                $this->view('users/login',$data);

            }
    }

    public function createUserSession($user)
    {
        $_SESSION['user_id']=$user->id;
        $_SESSION['user_email']=$user->email;
        $_SESSION['user_name']=$user->name;
        redirect('pages/index');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }

    public function isLoggedIn(){
        if(isset($_SESSION['user_id']))
        {
            return true;
        }
        else{
            return false;
        }
    }
}