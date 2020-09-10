<?php

class perfilModel extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function verificarUsuario($usuario)
    {
        $id = $this->_db->query(
                "SELECT id, codigo FROM da_usuarios WHERE usuario = '$usuario'"
                );        
        return $id->fetch();
    }

    public function verificarSiYaExisteUsuario($usuario, $idUsuario)
    {
        $id = $this->_db->query(
                "SELECT id, codigo FROM da_usuarios WHERE usuario = '$usuario' AND id !=  $idUsuario"
                );        
        return $id->fetch();
    }
    
    public function verificarEmail($email)
    {
        $id = $this->_db->query(
                "SELECT id FROM da_usuarios WHERE email = '$email'"
                );
        
        if($id->fetch()){
            return true;
        }        
        return false;
    }
    
    public function registrarUsuario($nombre, $usuario, $password, $email,$role)
    {
    	$random = rand(1782598471, 9999999999);		
        $this->_db->prepare(
                "INSERT into da_usuarios values" .
                "(null, :nombre, :usuario, :password, :email, :role, 1, now(), :codigo)"
                )
                ->execute(array(
                    ':nombre' => $nombre,
                    ':usuario' => $usuario,
                    ':password' => Hash::getHash('sha1', $password, HASH_KEY),
                    ':email' => $email,
					':role' => $role,
                    ':codigo' => $random
                ));
    }
    
    public function getUsuario($id, $codigo)
	{
		$usuario = $this->_db->query(
					"SELECT * from da_usuarios where id = $id and codigo = '$codigo'"
					);					
		return $usuario->fetch();
	}
	
	public function getUserByID($id)
	{
		$usuario = $this->_db->query(
					"SELECT * from da_usuarios where id = $id"
					);					
		return $usuario->fetch();
	}
	
	public function activarUsuario($id, $codigo)
	{
		$this->_db->query(
					"UPDATE da_usuarios set estado = 1 " .
					"where id = $id and codigo = '$codigo'"
					);
	}
	public function getRoles(){
		$roles = $this->_db->query(
					"SELECT id_role, role FROM da_roles ORDER BY id_role DESC"
					);				
		return $roles->fetchAll();
	}
	public function getMaestros(){
		$roles = $this->_db->query("SELECT id_prof, nombre_prof FROM da_profesores");				
		return $roles->fetchAll();
	}		
	public function getDirectores(){
		$roles = $this->_db->query("SELECT id_director, nombre FROM da_directores");				
		return $roles->fetchAll();
	}
	public function putUsuario($post)
    {	

		if($this->existeUsuario($post["idUsuario"]) AND $post["idUsuario"] != 0)
		{			
			try {

				//modifico al profesor o director de su respectiva tabla
				if($post["role"] == 16 OR $post["role"] == 15){
					$idProf_dir = $this->getIDProfesor($post["idUsuario"]);
					$this->modificarNombreProf_o_direct($post, $idProf_dir);
		    	}

				//si no hay contraseña 
				if(empty($post["pass"])){
					$usuario = $this->_db->prepare("UPDATE da_usuarios SET nombre=:nombre, usuario=:usuario, email=:email, imagen=:nombreImagen WHERE id = :id LIMIT 1");	
					$res = $usuario->execute(
						array(                   				   
						':nombre' => $post["nombre"],  
						':usuario' => $post["usuario"],         
						':email' => $post["email"],
						//':role' => $post["role"],
						':nombreImagen' => $post["nombreImagen"],
						':id' => $post["idUsuario"]						
					));	
				//si hay contraseña
				}else{					

					$usuario = $this->_db->prepare("UPDATE da_usuarios SET nombre=:nombre, usuario=:usuario, email=:email, pass=:password, imagen=:nombreImagen WHERE id = :id LIMIT 1");	
					$res = $usuario->execute(
						array(                   				   
						':nombre' => $post["nombre"], 
						':usuario' => $post["usuario"],                                        
						':email' => $post["email"],
						//':role' => $post["role"],
						':nombreImagen' => $post["nombreImagen"],
						':password' => Hash::getHash('sha1', $post["pass"], HASH_KEY),
						':id' => $post["idUsuario"]
					));	
				}
				
									
				if($usuario->errorCode() == 0) {
					$response = 1;
					$nuevoId = $post["idUsuario"];
					$urlImg = BASE_URL_IMG_PERFIL . $post["nombreImagen"];
					//echo json_encode($arrayResp);
				} else {
					$errors = $usuario->errorInfo();
					echo($errors[2]);
				}
			}catch( PDOException $Exception ) {
				echo $Exception->getMessage( );
				throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ));
			}
		}
		else
		{
				$jsondata['response'] = 3;
	            $jsondata['mensajeValidacion'] = "El usuario no existe";
	            echo json_encode($jsondata);
	            exit;
		}	   

        $jsondata['response'] = $response; //define si es actualizacion (1) o registro de nuevo usuario (2)
        $jsondata['urlImg'] = $urlImg;
        if(isset($nuevoId))
        	$jsondata['nuevoId'] = $nuevoId;                            
        echo json_encode($jsondata);
		

	}
	
	public function existeUsuario($id){
		$usuario = $this->_db->prepare("SELECT id FROM da_usuarios 
		WHERE id=:id_usuario LIMIT 1");
		$usuario->execute(array(':id_usuario' => $id));
		
		$row = $usuario->fetch();
		if($row)
			return(true);
		else 
			return(false);		
	}
	public function nexUsuario(){
		$usuario = $this->_db->query("SELECT max(id) AS idMayor FROM da_usuarios LIMIT 1");
		$row = $usuario->fetch();
		$id = $row["idMayor"] + 1;
        return $id;	
	}

	public function getNombreProfe($id_profe=0){    	
		$res = $this->_db->query("SELECT nombre_prof FROM da_profesores WHERE id_prof = $id_profe LIMIT 1");
		$array_nom = $res->fetch();
		$nombre = $array_nom["nombre_prof"];
        return $nombre;	
	}
	public function getNombreDirector($id_direct=0){    	
		$res = $this->_db->query("SELECT nombre FROM da_directores WHERE id_director = $id_direct LIMIT 1");
		$array_nom = $res->fetch();
		$nombre = $array_nom["nombre"];
        return $nombre;	
	}	
	public function editarNombreProf_o_direct($post){                          

		//Solo obtengo el plantel 
		if($_SESSION['rol'] == 1 OR $_SESSION['rol'] == 14){
			$idPlantel = $post["idPlantel"];
		}elseif($_SESSION['rol'] == 15){
			$idCoord = $this->getIDProfesor($_SESSION['id_usuario']); 
			$idPlantel = $this->getIdPlantelCoord($idCoord);
		}	
		
		if($post["role"] == 16){
			$planeacion = $this->_db->prepare("INSERT INTO da_profesores (nombre_prof, id_plantel, jornada, total_hr_base, hr_fg_jornada, hr_adicionales, hr_fg, hr_apoyo, hr_extra_comple, total_base, total_carga, curp, RFC, grado_estudio, esc_egreso, estatus_grado, especialidad, esp_maestria, esp_doctorado, academia, alta, activo, observaciones) VALUES (:nombre, :id_plantel, '', 0,0,0,0,0,0,0,0,'','',0,'',0,'','','',0,'',0,'')");
        	$res =$planeacion->execute(
                    array(   
                       ':id_plantel' => $idPlantel, 
                       ':nombre' => $post["nombre"]                                                                  
                    ));
    	}else if($post["role"] == 15){
    		$planeacion = $this->_db->prepare("INSERT INTO da_directores (nombre, id_plantel, cargo) VALUES (:nombre, :id_plantel, :cargo)");
        	$res =$planeacion->execute(
                    array(   
                       ':id_plantel' => $idPlantel, 
                       ':nombre' => $post["nombre"],
                       ':cargo' => 'COORDINADOR ACADÉMICO'                                                                  
                    ));
    	}
        
        $nuevoId = $this->_db->lastInsertId();//ultimo id insertado

        return $nuevoId;
      
    }
    
	public function modificarNombreProf_o_direct($post, $idProf_dir){                          

		//Solo obtengo el plantel 
		if($_SESSION['rol'] == 1 OR $_SESSION['rol'] == 14){
			$idPlantel = $post["idPlantel"];
		}elseif($_SESSION['rol'] == 15){
			$idCoord = $this->getIDProfesor($_SESSION['id_usuario']); 
			$idPlantel = $this->getIdPlantelCoord($idCoord);
		}elseif($_SESSION['rol'] == 16){
			$idCoord = $this->getIDProfesor($_SESSION['id_usuario']); 
			$idPlantel = $this->getIdPlantelProf($idCoord);
		}	
		
		if($post["role"] == 16){
			$planeacion = $this->_db->prepare("UPDATE da_profesores SET nombre_prof=:nombre, id_plantel=:plantel WHERE id_prof=:idProfesor LIMIT 1");
                $res =$planeacion->execute(
                            array( 
                               ':idProfesor' => $idProf_dir, 
                               ':nombre' => $post["nombre"],                                                         
                               ':plantel' => $idPlantel            
                            ));
    	}else if($post["role"] == 15){
    		$planeacion = $this->_db->prepare("UPDATE da_directores SET nombre=:nombre, id_plantel=:plantel, cargo=:cargo WHERE id_director=:idDir LIMIT 1");
                $res =$planeacion->execute(
                            array( 
                               ':idDir' => $idProf_dir, 
                               ':nombre' => $post["nombre"],  
                               ':plantel' => $idPlantel,                                                       
                               ':cargo' => 'COORDINADOR ACADÉMICO'            
                            ));
    	}
        
        $nuevoId = $this->_db->lastInsertId();//ultimo id insertado

        return $nuevoId;
      
    }
    public function getIDProfesor($id_usuario)
    {    
        $res = $this->_db->query("SELECT usu.id_profesor FROM da_usuarios usu WHERE usu.id = $id_usuario LIMIT 1");        
        $row = $res->fetch();
        return $row[0];        
    }

    public function getIdPlantelCoord($idPlantel)
    {   
        $id = (int) $idPlantel;    
        $ficha = $this->_db->query("SELECT d.id_plantel FROM da_directores d WHERE d.id_director = $id LIMIT 1");       
        $row = $ficha->fetch();
        return $row[0];
    }

	public function getListaPlanteles($idUsuario, $rol)
    {      
    	$id_plantel = 0;
        if($rol == 16){ //rol profesor
            $res = $this->_db->prepare("SELECT pro.id_plantel FROM da_usuarios usu
                                        INNER JOIN da_profesores pro ON usu.id_profesor = pro.id_prof
                                        WHERE usu.id = :id_usuario LIMIT 1");
            $res->execute(array(':id_usuario' => $idUsuario)); 
	        $id_plantel = $res->fetch();
	        $id_plantel = $id_plantel["id_plantel"];
        }else if($rol == 15){ //rol direccion plantel
            $res = $this->_db->prepare("SELECT dir.id_plantel FROM da_usuarios usu
                                        INNER JOIN da_directores dir ON usu.id_profesor = dir.id_director
                                        WHERE usu.id = :id_usuario LIMIT 1");
            $res->execute(array(':id_usuario' => $idUsuario)); 
	        $id_plantel = $res->fetch();
	        $id_plantel = $id_plantel["id_plantel"];
        }   
        


        $cadena="<option value='0'>Seleccione...</option>";
        $plantel= $this->_db->prepare("SELECT id_plantel, nombre FROM da_planteles ");
        $plantel->execute();
        $row = $plantel->fetchAll();        
        foreach($row as $i) {
            if($i["id_plantel"]==$id_plantel) 
                $s = " selected "; 
            else 
                $s = "";
            $cadena=$cadena."<option $s value='".$i["id_plantel"]."'>".
                $i["nombre"]."</option>";
        }
        return($cadena);
    }  

    public function getIdPlantelProf($id_profesor)
    {   
        $id = (int) $id_profesor;    
        $ficha = $this->_db->query("SELECT id_plantel FROM da_profesores WHERE id_prof = $id LIMIT 1");        
        $row = $ficha->fetch();
        return $row[0];
    }


	/*public function getDatosMaestros($id_profe=0)
    {	    	    	 	    
    	$cadena=" ";
        $carreras= $this->_db->prepare("SELECT nombre_prof FROM profesores WHERE id_prof = $id_profe LIMIT 1");
        $carreras->execute();
		$row = $carreras->fetch();		

		$jsondata = array();								
		$jsondata['nombre'] = $row['nombre_prof'];
		/*$jsondata['usuario'] = $row['usuario'];	
		$jsondata['email'] = $row['email'];	*/
		/*echo json_encode($jsondata);

    }*/
	
}

?>
