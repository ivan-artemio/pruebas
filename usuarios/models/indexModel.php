<?php

class indexModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getUsuarios()
    {
        $condicion ="";
        if($_SESSION['rol'] == 15){//director
            $id_usuario = $_SESSION['id_usuario'];  
            $idPlantel = $this->_db->query("SELECT us.id_plantel FROM da_usuarios us WHERE us.id = $id_usuario LIMIT 1");
            $id = $idPlantel->fetch();
            $id_plantel = $id[0];   
            $id_plantel = (int) $id_plantel;            
            if($id_plantel > 0){
                $condicion .= " AND u.role = 16 AND p.id_plantel = $id_plantel ";
            }
        }else if($_SESSION['rol'] == 14){//dir Acad
            $condicion .= " AND u.role != 1 ";
        }

        $usuarios = $this->_db->query(
                "SELECT u.*,r.role, (SELECT denominacion FROM da_usuarios_accesos WHERE id_usr=u.id LIMIT 1) AS acceso, pl.nombre as plantel   
                    FROM da_roles r, da_usuarios u
                    LEFT JOIN da_planteles pl ON pl.id_plantel = u.id_plantel
                    LEFT JOIN da_profesores p ON p.id_prof = u.id_profesor
                    LEFT JOIN da_directores d ON d.id_director = u.id_profesor
                    WHERE u.role = r.id_role $condicion "
                );
        return $usuarios->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    public function getUsuario($usuarioID)
    {
         $usuarios = $this->_db->query(
                "SELECT u.usuario,r.role FROM da_usuarios u, da_roles r ".
                "WHERE u.role = r.id_role and u.id = $usuarioID"
                );
        return $usuarios->fetch();
    }
    
    public function getPermisosUsuario($usuarioID)
    {
        $acl = new ACL($usuarioID);
        return $acl->getPermisos();
    }
    
    public function getPermisosRole($usuarioID)
    {
        $acl = new ACL($usuarioID);
        return $acl->getPermisosRole();
    }
    
    public function eliminarPermiso($usuarioID, $permisoID)
    {
        $this->_db->query(
                "DELETE FROM da_permisos_usuario WHERE ".
                "usuario = $usuarioID and permiso = $permisoID"
                );
    }
    
    public function editarPermiso($usuarioID, $permisoID, $valor)
    {
        $this->_db->query(
                "REPLACE INTO da_permisos_usuario set ".
                "usuario = $usuarioID , permiso = $permisoID, valor ='$valor'"
                );
    }
	public function DeleteUsuario($post)
    {	
		try 
		{
			$usuario = $this->_db->prepare("DELETE FROM da_usuarios WHERE id = :id LIMIT 1");
			$res = $usuario->execute(array(
				':id' => $post["id"],
			));
			if($usuario->errorCode() == 0) 
			{
					echo $post["id"];
			} else {
				$errors = $usuario->errorInfo();
				echo($errors[2]);
			}
		}
		catch( PDOException $Exception ) 
		{
			echo $Exception->getMessage( );
			throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
		}	
    }
	public function putStatus($post)
    {
		if ($_POST['estado']=='true')
				$estado = 1;
		else
				$estado = 0;
		//print_r($post);
		try {	
			$usuario = $this->_db->prepare("UPDATE da_usuarios SET estado=:estado WHERE id = :id LIMIT 1");
			$res = $usuario->execute(array(
				':estado' => $estado,
				':id' => $post["id"],
			));			
			if($usuario->errorCode() == 0) {
				echo 1;
			} else {
				$errors = $usuario->errorInfo();
				echo($errors[2]);
			}
		}catch( PDOException $Exception ) {
			echo $Exception->getMessage( );
			throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
		}			
	}
}

?>
