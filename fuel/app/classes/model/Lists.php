<?php 
class Model_Lists extends Orm\Model
{
    protected static $_table_name = 'listas';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'titulo' => array(
            'data_type' => 'varchar'   
        ),
        'id_user' => array(
            'data_type' => 'int'   
        ),
        'editable' => array(
            'data_type' => 'int'   
        ),
        
    );
}