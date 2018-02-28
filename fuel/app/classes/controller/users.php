<?php 
use \Firebase\JWT\JWT;
class Controller_Users extends Controller_Rest
{
    private $key = "qwertyuiop";
    private $getEmail = "";
                                    //Crear usuario
    public function post_create()
    {
     
        try {
               $roles = Model_Users::find('all', array(
                    'where' => array(
                        array('id_rol', 1),
                       
                    )          
                ));
                if(empty($roles))
                {
                    $json = $this->response(array(
                    'code' => 400,
                    'message' => 'to use the app at least must exist an admin',
                    'data' => []
                ));
                return $json;
                }
                else
                {
                    if ( ! isset($_POST['username']) && ! isset($_POST['password'])&& ! isset($_POST['repeatPassword']) && ! isset($_POST['email'])) 
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'credentials error',
                            'data' => []
                        ));
                        return $json;
                    }
                   
                    $input = $_POST;
                    if ($input['password'] != $input['repeatPassword'])
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'password and repeatPassword must match',
                            'data' => []
                        ));
                        
                    }
                   
                    else
                    {
                        $user = new Model_Usuarios();
                        $user->username = $input['username'];
                        $user->password = $input['password'];
                        $user->email = $input['email'];
                    
                        $user->id_rol = 2;
                        $user->id_device = random_int(0, 1000000);
                        
                    
                    
                   
                        if ($user->username == "" || $user->email == "" || $user->password == "")
                        {
                            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'empty fields',
                                'data' => []
                            ));
                        }
                        else
                        {
                            $user->save();
                        
                            $dataToken = array(
                                    "id" => $user->id,
                                    "username" => $user->username,
                                    "password" => $user->password,
                                    "email" => $user->email,
                                    "id_rol" => $user->id_rol,
                                   
                                );
                            $token = JWT::encode($dataToken, $this->key);
                            $this->privacityDefault($user->id);
                            $this->listDefault($user->id);
                            $json = $this->response(array(
                                'code' => 200,
                                'message' => 'Usuer created ok',
                                'data' => $token
                            ));
                            return $json;
                        }
                    }
                }
            
        } 
        catch (Exception $e) 
        {
            if($e->getCode() == 23000)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
               
                ));
                return $json;
            }
            else
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
            }  
        }        
    }
     public function post_createAdmin()
    {
     
        try {
                    if ( ! isset($_POST['username']) && ! isset($_POST['password'])&& ! isset($_POST['repeatPassword']) && ! isset($_POST['email'])) 
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'credentials error, try again',
                            'data' => []
                        ));
                        return $json;
                    }
                    $input = $_POST;
                    if ($input['password'] != $input['repeatPassword'])
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'password and repeatPassword doesnt match',
                            'data' => []
                        ));
                        
                    }
                  
                    else
                    {
                        $user = new Model_Users();
                        $user->username = 'admin';
                        $user->password = '1234';
                        $user->email = 'admin@admin.es';
                        
                        $user->id_rol = 1;
                        $user->id_device = random_int(0, 2000000);
               
                            $user->save();
                        
                            $dataToken = array(
                                    "id" => $user->id,
                                    "username" => $user->username,
                                    "password" => $user->password,
                                    "email" => $user->email,
                                    "id_rol" => $user->id_rol,
                                    
                                );
                            $token = JWT::encode($dataToken, $this->key);
                           
                            $json = $this->response(array(
                                'code' => 200,
                                'message' => 'Admin creado correctamente',
                                'data' => $token
                            ));
                            return $json;
                        }     
            
        } 
        catch (Exception $e) 
        {
            if($e->getCode() == 23000)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
            }
            else
            {
                $json = $this->response(array(
                    'code' => 500,
               // 'message' => $e->getCode()
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
            }  
        }        
    }
                                   
    public function get_users()
    {
        
       $users = Model_Users::query()->get();
        $json = $this->response(array(
            'code' => 200,
            'message' => 'this is the list of users',
            'data' => $users
        ));
        return $json;
    }
   
    
                                      //Eliminar usuario
    public function post_delete()
    {
        $user = Model_Users::find($_POST['id']);
        $userName = $user->name;
        $user->delete();
        $json = $this->response(array(
            'code' => 200,
            'message' => 'deleted user',
            'name' => $userName
        ));
            return $json;
    }
                                    //login del usuario
    public function get_login()
    {
    try {
     
            if ( empty($_GET['username']) || empty($_GET['password']))
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'empty fields',
                    'data' => []
                ));
            }
            $input = $_GET;
            $users = Model_Users::find('all', array(
                        'where' => array(
                            array('username', $input['username']),
                            array('password', $input['password']),
                        )          
                    ));
            
           
            
            
            if ( ! empty($users) )
            {
                
                foreach ($users as $key => $value)
                {
                    $id = $users[$key]->id;
                    $name = $users[$key]->username;
                    $password = $users[$key]->password;
                    $id_rol = $users[$key]->id_rol;
                    
                }
                foreach ($users as $key => $user) 
                {
               
                    $user-> save();
                }
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'incorrect user or password',
                    'data' => []
                    ));
            }
                
            $dataToken = array(
                "id" => $id,
                "username" => $name,
                "password" => $password,
                "id_rol" => $id_rol,
                
                );
            $token = JWT::encode($dataToken, $this->key);
            return $this->response(array(
                'code' => 200,
                'message'=> 'Login ok',
                'data' => $token
                ));
                        
        } 
        catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => 'server error',
                    'data' => $e->getMessage()
                    //'message' => $e->getMessage(),
                ));
                return $json;
            }
        }
                                     //Cambiar la contraseña
    public function get_recoveryPassword()
    {
        try {
            if ( empty($_GET['email'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' =>  'empty email',
                    'data' => []
                ));
                return $json;
            }
            // Validación de e-mail
            $input = $_GET;
            $users = Model_Usuarios::find('all', array(
                'where' => array(
                    array('email', $input['email'])
                )
            ));
            if ( ! empty($users) )
            {
                foreach ($users as $key => $value)
                {
                    $id = $users[$key]->id;
                    $email = $users[$key]->email;
                }
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'email doesnt exist',
                    'data' => []
                    ));
            }
            
                $tokendata = array(
                    'id' => $id,
                    'email' => $email
                   
                );
                $token = JWT::encode($tokendata, $this->key);
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Email ok',
                    'data' => $token
                ));
                return $json;
            
        }
        catch (Exception $e)
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));
            return $json;
        }
    }
    public function post_changePassword()
    {
        try
        {
            $header = apache_request_headers();
            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }
            if (empty($_POST['password'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' =>  'No puede haber campos vacios',
                        'data' => []
                    ));
                    return $json;
                }
            if (($_POST['password']) != ($_POST['repeatPassword'])){
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Password and repeatPassword must match',
                        'data' => []
                    ));
                    return $json;
                }
                $input = $_POST;
                $user = Model_Usuarios::find($dataJwtUser->id);
                $user->password = $input['password'];
               
                $user->save();
                                
                $json = $this->response(array(
                    'code' => 200,
                    'message' =>  'Password changed ok',
                    'data' => []
                ));
                return $json;
              
           
        }
        catch (Exception $e)
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));
            return $json;
        }
    }
    function post_modifyUser()
    {
        try {
            try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
        
      
                $users = Model_Usuarios::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));
            }    
            catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
                   
            }
            
                    $input = $_POST;
                    $users = Model_Users::find('all', array(
                            'where' => array(
                                array('id', $dataJwtUser->id)
                            )
                     ));
                    
                    $user = Model_Users::find($dataJwtUser->id);
                    if(empty($user)){
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'User not found',
                            'data' => []
                        //'message' => $e->getMessage(),
                        ));
                        return $json;
                    }
                    if($input['password'] != '')
                    {  $user->password = $input['password'];
                    }
                     
                     if($input['birthday'] != '')
                    {
                         $user->birthday = $input['birthday'];
                    }
                     if($input['city'] != '')
                    {
                         $user->city = $input['city'];
                    }
                    if($input['description'] != '')
                    {
                         $user->description = $input['description'];
                    }
                  
                     if($input['password'] != '')
                    {
                        $this->uploadImage();
                    }
                    $user->save();             
                    $json = $this->response(array(
                        'code' => 200,
                        'message' =>  'changes ok',
                        'data' => $users
                     ));
                    return $json;
                
        } catch (Exception $e) {
            if($e->getCode() == 23000)
            {
                return $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                    ));
            }
        }
    }
    public function uploadImage()
    {
        try{
            $header = apache_request_headers();
            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }
        // Custom configuration for this upload
        $config = array(
            'path' => DOCROOT . 'assets/img',
            'randomize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        );
        // process the uploaded files in $_FILES
        Upload::process($config);
        // if there are any valid files
        if (Upload::is_valid())
        {
            
            // save them according to the config
            Upload::save();
            foreach(Upload::get_files() as $file)
            {
                $user = Model_Users::find($dataJwtUser->id);
                $user->profile_photo = 'http://' . $_SERVER['SERVER_NAME'] . '/appmusicfinal/public/assets/img/' . $file['saved_as'];
                $user->save();   
            }
        }
        return $this->response(array(
            'code' => 200,
            'message' => 'updated info',
            'data' => [$user]
        ));
        // and process any errors
        
        foreach (Upload::get_errors() as $file)
        {
            return $this->response(array(
                'code' => 500,
                'message' => 'image doesnt updated',
                'data' => []
            ));
        }
      
        }catch (Exception $e){
            return $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));
        }
    }
   

}    