<?php 
use \Firebase\JWT\JWT;
class Controller_Roles extends Controller_Rest
{
    private $key = "qwertyuiop";
    private $getEmail = "";
                                    //Crear usuario
    public function post_create()
    {
        try {
            if ( ! isset($_POST['type']) ) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'credentials error',
                    'data' => []
                ));
                return $json;
            }
            $input = $_POST;
            
                $roles = new Model_Roles();
                $roles->tipo= $input['type'];
               
                
            
            
           
                if ($roles->type == "" )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'empty fields',
                        'data' => []
                    ));
                }
                else
                {
                    $roles->save();
                    
                    
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Rol created ok',
                        'data' => $roles
                    ));
                    return $json;
                }
            
            
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
    
    
    }    