<?php 
use \Firebase\JWT\JWT;
class Controller_List extends Controller_Rest
{
    private $key = "qwertyuiop";
   
                                    //Crear usuario
    public function post_create()
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
            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }
            
            if ($rol == 1)
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Only users can create editable lists',
                    'data' => []
                ));
                return $json;
            }
            else
            {    
                if (  ! isset($_POST['tittle'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Credentials error',
                        'data' => []
                    ));
                    return $json;
                }
            
                $input = $_POST;
                
                    $listas = new Model_Listas();
                    $lists->editable= 1;
                    $lists->id_user = $dataJwtUser->id;
                    $lists->tittle = $input['tittle'];
                    
                    if ($lists->id_user == "" || $lists->tittle == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'empty fields',
                            'data' => []
                        ));
                    }
                    else
                    {
                    if ($lists->tittle == "no listened songs" || $lists->tittle == "last listened"   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'name already exist',
                            'data' => []
                        ));
                    }
                        $listas->save();
                        
                        
                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'created song',
                            'data' => $lists
                        ));
                        return $json;
                    }
            }
            
            
        } 
        catch (Exception $e) 
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
    public function get_lists()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
        
      
                $users = Model_Users::find('all', array(
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
        $input = $_GET;
        $decena = $input['decena_list']-1;
        if($input['decena_list'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['decena_list'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }
       $listas = Model_Lists::query()->where('id_user', $dataJwtUser->id)->offset( $decena * 10)->limit(10)->get();
      
       foreach ($lists as $key => $list) {
           $namelista[] = $list->tittle;
           
       }
        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de lists',
            'data' => $namelist
        ));
        return $json;
        //return $this->response(Arr::reindex($users));
    }
    public function post_modList()
    {
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
        if (  ! isset($_POST['tittle']) || ! isset($_POST['id'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Credentials error',
                        'data' => []
                    ));
                    return $json;
                }
        $input = $_POST;
        $listas = Model_Lists::find('all', array(
                        'where' => array(
                            array('id_user', $dataJwtUser->id),
                            array('id', $input['id']),
                            
                   
                        )
                     ));
        
        if(empty($lists))
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'list not found',
                        'data' => []
                    ));
                    return $json;
        }
      
        foreach ($listas as $key => $lista) 
        {
            if($list->editable == 1)
            {
            $lista->titulo = $input['tittle'];
            $lista->save();
            }
            else
            {
                $json = $this->response(array(
                        'code' => 400,
                        'message' => 'list not editable',
                        'data' => []
                    ));
                    return $json;
            }
        }
        $json = $this->response(array(
            'code' => 200,
            'message' => 'all lists',
            'data' => $lists
        ));
        return $json;
        //return $this->response(Arr::reindex($users));
    }
    public function post_delete()
    {
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
        if (  ! isset($_POST['id'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Credentials error',
                        'data' => []
                    ));
                    return $json;
                }
        $input = $_POST;
        $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id_user', $dataJwtUser->id),
                            array('id', $input['id']),
                            
                   
                        )
                     ));
        if (! empty($lists))
        {
            foreach ($lists as $key => $list) {
            $borrar = $list;
            }
            $list->delete();
          
            $json = $this->response(array(
                'code' => 200,
                'message' => 'deleted list',
                'data' => []
            ));
            return $json;
        }
        else
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Id not found',
                'data' => []
            ));
            return $json;
        }
        
    }
    
}    