<?php 
class Model_Add extends Orm\Model
{
    protected static $_table_name = 'listas_contienen_canciones';
    protected static $_primary_key = array('id_list','id_song');
    protected static $_properties = array(
        'id_list',
        'id_song' 
       
        
    );
   
}