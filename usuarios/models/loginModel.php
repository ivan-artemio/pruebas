<?php

class loginModel extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    /*
    public function getUsuario($usuario, $password)
    {
        
        $datos = $this->_db->query(
			"SELECT usuarios . * , usuarios_accesos.uri AS acceso, usuarios_accesos.directorio
			FROM usuarios
			LEFT JOIN usuarios_accesos ON usuarios_accesos.id_usr = usuarios.id
			WHERE usuarios.usuario =  '$usuario'
 " .
                "and pass = '" . Hash::getHash('sha1', $password, HASH_KEY) ."' LIMIT 1"
                );
        
        return $datos->fetch();
    }
    */

       public function getUsuario($usuario, $password)
    {
        
        $datos = $this->_db->query(
            "SELECT  * 
             FROM da_usuarios 
             WHERE usuario =  '$usuario' 
                   and pass = '" . Hash::getHash('sha1', $password, HASH_KEY) ."' LIMIT 1");
        
        return $datos->fetch();
    }



    public function getAccesos($idRole)
    {
       // echo "----->".$idRole;
      $prepAccesoPaginas = $this->_db->prepare("
        SELECT paginas.URI AS URI,secciones.pag_rama AS subpagina,secciones.pathdir,secciones.denominacion  
        FROM da_secciones_role_edicion       
        LEFT JOIN da_secciones ON secciones.id = secciones_role_edicion.fk_id_seccion
        LEFT JOIN da_paginas ON secciones.idpagina = paginas.id
        WHERE fk_id_role=:idrole ");
     $resAccesoPaginas = $prepAccesoPaginas->execute(array(':idrole' => $idRole));
     return $prepAccesoPaginas->fetchAll(PDO::FETCH_ASSOC);        
    }



}

?>
