<?php 
use \Firebase\JWT\JWT;
class addController extends Controller_Rest
{
    private $key = "qwertyuiop";
   
                                    //Crear usuario
    public function post_add()
    {
        
        try {
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
            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }
            
            if ($rol == 1)
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'only users can add songs to lists',
                    'data' => []
                ));
                return $json;
            }
            else
            {   
                if (  ! isset($_POST['id_lista'])|| ! isset($_POST['id_cancion'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'credentials error',
                        'data' => []
                    ));
                    return $json;
                }
                $input = $_POST;
                
                    $listsSongs = new Model_Anyadir();
                    $listsSongs->id_list = $input['id_list'];
                    $listsSongs->id_song = $input['id_cancion'];
                   
                    if ($listsSongs->id_list == "" || $listsSongs->id_song == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'empty fields',
                            'data' => []
                        ));
                    }
                    else
                    {
                        $listas = Model_Listas::find('all', array(
                            'where' => array(
                                array('id', $input['id_list']),
                            )
                         ));
                        if(empty($listas))
                        {
                             $json = $this->response(array(
                                'code' => 400,
                                'message' => 'list not found',
                                'data' => []
                            ));
                        }
                        foreach ($listas as $key => $lista) {
                           
                           
                        }
                        if ($lista->editable == 2 || $lista->id_usuario != $dataJwtUser->id)
                        {
                            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'list not editable',
                                'data' => []
                            ));
                        }
                        else
                        {
                            $listasCanciones->save();
                            
                            
                            $json = $this->response(array(
                                'code' => 200,
                                'message' => 'song added to '. $list->tittle. 'ok',
                                'data' => $listasCanciones
                            ));
                            return $json;
                        }
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
    public function get_songs()
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
         
        $add = Model_Add::find('all', array(
                        'where' => array(
                            array('id_lista', $input['id_lista']),
                            
                            
                   
                        )
                     ));
        if (empty($add))
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'list not found',
                'data' => []
            ));
            return $json;
        }
        else
        {
           
            foreach ($add as $key => $addedTo) {
                  $songs= Model_Songs::query()->where('id',$addedTo->id_song)->get();
                  foreach ($songs as $key => $song) {
                  }

                  $correct[] = $song->tittle;
                # code...
            }
            $salida = array_slice($correct, $decena*10,($decena+1)*10);
             
             $listas= Model_Lists::query()->where('id',$addedTo->id_list)->get();
                  foreach ($lists as $key => $list) {
                      # code...
                  }
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'every song of the list '.$list->tittle,
                    'data' => $exit
                ));
                return $json;
        }
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
        $input = $_POST;
        if (  ! isset($_POST['id_cancion'])|| ! isset($_POST['id_lista']) ) 
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'credentials error',
                            'data' => []
                        ));
                        return $json;
                    }
        $listas = Model_Lists::find('all', array(
                        'where' => array(
                            
                            array('id', $input['id_list']),
                            
                   
                        )
                     ));
        if (empty($listas))
        {
            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'list not found ',
                                'data' => []
                            ));
        } 
        else
        {
            foreach ($lists as $key => $list) {
                
            }
            if ($list->editable == 2 || $list->id_user != $dataJwtUser->id)
                            {
                                $json = $this->response(array(
                                    'code' => 400,
                                    'message' => 'list not editable',
                                    'data' => []
                                ));
                            }
            else
            {
                 
             
                $add = Model_Add::find('all', array(
                                'where' => array(
                                    array('id_list', $list->id),
                                    array('id_song', $input['id_song']),
                                )
                             ));
                if(! empty($add))
                {
                    foreach ($add as $key => $add) {

                    }
                    Model_Add::find($add);
                    try
                    {
                        $songs = Model_Songs::find('all', array(
                                'where' => array(
                                    
                                    array('id', $input['id_song']),
                                )
                             ));
                        foreach ($songs as $key => $song) 
                        {
                            # code...
                            $detailSong = $song->tittle;
                        }
                        $detailList = $list->tittle;
                        $add->delete(); 
                    }
                    catch (Exception $e)
                    {
                    }
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => $detailSong. ' deleted '. $detailList,
                        'data' => []
                    ));
                    return $json;
                }
                else
                {
                    $json = $this->response(array(
                                'code' => 400,
                                'message' => 'song not found',
                                'data' => []
                            ));
                            return $json;
                }
            } 
        }   

    }
  } 