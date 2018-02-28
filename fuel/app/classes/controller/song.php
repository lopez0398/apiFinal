<?php 
use \Firebase\JWT\JWT;

class Controller_Song extends Controller_Rest
{
    private $key = "qwertyuiop";
   
                                    
    public function post_create()
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
            
            if ($rol != 1)
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Access denied',
                    'data' => []
                ));
                return $json;
            }
            else
            {    
                if ( ! isset($_POST['artist']) || ! isset($_POST['url']) || ! isset($_POST['tittle'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Credentials error',
                        'data' => []
                    ));
                    return $json;
                }
                $input = $_POST;
                
                    $songs = new Model_Songs();
                    $songs->artista= $input['artist'];
                    $songs->url= $input['url'];
                    $songs->titulo= $input['tittle'];
                    $songs->reproducciones= 0;
                     if ($songs->artista == "" || $songs->titulo == "" || $songs->url == ""  )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'empty fields',
                            'data' => []
                        ));
                    }
                    else
                    {
                        $songs->save();
                        
                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'created song ok',
                            'data' => $songs
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
                    'message' => $e->getLine(),
                    'data' => []
                ));
                return $json;
        }        
    }
    
    public function get_replaysSong()
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
        foreach ($users as $key => $user)
        {
            $rol = $user->id_rol;
        }
            
        if ($rol != 1)
        {
                $input = $_GET;
                $canciones = Model_Songs::find('all', array(
                            'where' => array(
                                array('id', $input['id']),
                                
                       
                            )
                         ));
                foreach ($songs as $key => $song) {
                    $song->reproducciones += 1;
                    $song->save();
                    # code...
                }
                $this->borrarCancion($song->id,$dataJwtUser->id);
                $this->añadirCancion($song->id,$dataJwtUser->id);
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'listened song',
                    'data' => $songs
                ));
                return $json;
        }
        else
        {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'only users can listen the song',
                    'data' => []
                ));
        }
    }
    private function deleteSong($song,$user)
    {
        $listas = Model_Lists::find('all', array(
                            'where' => array(
                                array('id_user', $user),
                                array('tittle')
                            )
                         ));
        if (! empty($lists)) {
            # code...
        
            foreach ($lists as $key => $list) 
            {
                # code...
            }
            $delete = Model_Add::find('all', array(
                                'where' => array(
                                    array('id_list', $list->id),
                                    array('id_song', $song)
                                    
                           
                                )
            ));
            if(! empty($delete))
            {
                foreach ($delete as $key => $delete) {
                   
                }
                try{
                    $delete->delete();
                }
                catch (Exception $e)
                {
                }
            }
            $emptyLastListenedSong = Model_Add::find('all', array(
                                'where' => array(
                                    array('id_list', $list->id)
                                    
                                    
                           
                                )
            ));
            foreach ($emptyLastListenedSong as $key => $value) {
                # code...
            }
            if(empty($emptyLastListenedSong))
            {
                $list ->delete();
            }
        }
       
    }
    // private function addSong($song,$user)
    // {
    //     $listas = Model_Lists::find('all', array(
    //                         'where' => array(
    //                             array('id_user', $user),
    //                             array('tittle', 'last listened')
                                
                       
    //                         )
    //                      ));
       
    //     foreach ($lists as $key => $list) 
    //     {
            
    //     }
    //     $delete = Model_Add::find('all', array(
    //                         'where' => array(
    //                             array('id_list', $list->id),
    //                             array('id_song', $song)
                                
                       
    //                         )
    //     ));
    //     if(! empty($delete))
    //     {
    //         foreach ($delete as $key => $delet) {
    //             # code...
    //         }
    //         try{
    //             $delet->delete();
    //         }
    //         catch (Exception $e)
    //         {
    //         }
    //     }
    //         $add= new Model_Anyadir();
    //         $add->id_list = $list->id;
    //         $add->id_song = $song;
    //         $add->save();

    // }
      private function newSong($song)
    {
        $lists = Model_Lists::find('all', array(
                            'where' => array(
                               
                                array('tittle', 'not listened songs')
                            )
                         ));
       
        foreach ($lists as $key => $list) 
        {
           $add= new Model_Add();
            $add->id_list = $list->id;
            $add->id_song = $song;
            $add->save();
         
        }
    
    }
    
}    