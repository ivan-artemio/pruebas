<?php

class indexController extends usuariosController
{
    private $_usuarios;
    
    public function __construct() 
    {
        parent::__construct();
		$this->forzarLogin();
        $this->_usuarios = $this->loadModel('index');
    }
    
    public function index()
    {
		$this->_acl->acceso('seeuser');
		//if(!Session::get('ingresar')){  $this->redireccionar('usuarios/login');  exit;}
        
		$this->_view->setJs(array('prueba'));
        $this->_view->assign('titulo', 'Usuarios');
       // $this->_view->assign('usuarios', $this->_usuarios->getUsuarios());    
        $this->_view->renderizar('index', 'inicio');
    }
    
    public function permisos($usuarioID)
    {		
        $id = $this->filtrarInt($usuarioID);
        
        if(!$id){
            $this->redireccionar('usuarios');
        }
        
        if($this->getInt('guardar') == 1){
            $values = array_keys($_POST);
            $replace = array();
            $eliminar = array();
            
            for($i = 0; $i < count($values); $i++){
                if(substr($values[$i],0,5) == 'perm_'){
                    $permiso = (strlen($values[$i]) - 5);
                    
                    if($_POST[$values[$i]] == 'x'){
                        $eliminar[] = array(
                            'usuario' => $id,
                            'permiso' => substr($values[$i], -$permiso)
                        );
                    }
                    else{
                        if($_POST[$values[$i]] == 1){
                            $v = 1;
                        }
                        else{
                            $v = 0;
                        }
                        
                        $replace[] = array(
                            'usuario' => $id,
                            'permiso' => substr($values[$i], -$permiso),
                            'valor' => $v
                        );
                    }
                }
            }
            
            for($i = 0; $i < count($eliminar); $i++){
                $this->_usuarios->eliminarPermiso(
                        $eliminar[$i]['usuario'],
                        $eliminar[$i]['permiso']);
            }
            
            for($i = 0; $i < count($replace); $i++){
                $this->_usuarios->editarPermiso(
                        $replace[$i]['usuario'],
                        $replace[$i]['permiso'],
                        $replace[$i]['valor']);
            }
        }
        
        $permisosUsuario = $this->_usuarios->getPermisosUsuario($id);
        $permisosRole = $this->_usuarios->getPermisosRole($id);
        
        if(!$permisosUsuario || !$permisosRole){
            //$this->redireccionar('usuarios');
        }else{
			$this->_view->assign('permisos', array_keys($permisosUsuario));
			$this->_view->assign('usuario', $permisosUsuario);
			$this->_view->assign('role', $permisosRole);
		}
        
        $this->_view->assign('titulo', 'Permisos de usuario');        
        $this->_view->assign('info', $this->_usuarios->getUsuario($id));        
        $this->_view->renderizar('permisos', 'usuarios');
    }
	public function eliminarUsuario(){
		//$this->_acl->verificaPermiso('deleteuser');
		$this->_usuarios->DeleteUsuario($_POST);
	}
	public function guardarStatus()
	{	
		$this->_usuarios->putStatus($_POST);
		echo "";
	}
    public function traerUsuarios(){
        
        $datos = $this->_usuarios->getUsuarios();
        // $datos = $this->_planeacion->buscarMenus($_POST);

        $last = end($datos);
        if (!empty($datos)) {
            $contenido = '{"data": [';
            foreach($datos AS $i){

                if($i['estado'] == 1){
                    $checked = "checked";
                    $disabled = "";
                }else{
                   $checked = ""; 
                   $disabled = "disabled";
                }                

                $contenido .= '{
                              "DT_RowId": "fila_'.$i['id'].'",
                              "id": '.$i['id'].',
                              "Usuario": "'.$i['usuario'].'",
                              "Nombre": "'.$i['nombre'].'",
                              "Role": "'.$i['role'].'",
                              "Plantel": "'.$i['plantel'].'",  
                              "Estado": "<input type=\"checkbox\" name=\"switch\" id=\"' . $i['id'] . '\" data-size=\"small\" data-on-text=\"Activo\" data-off-text=\"Inactivo\" data-label-width=\"0\" onChange=\"guardaEstado(this.checked, this.id)\" ' . $checked . '>",                                                         
                              "Editar": "<div style=\"width:66px\" class=\"btn-group\"> <a onclick=\"abrirEditarUsuario('.$i['id'].');return false;\" id=\"btnEdit_'.$i['id'].'\" class=\" btn btn-default\" title=\"Editar\" href=\"#\"> <span class=\"glyphicon glyphicon-pencil\" ></span></a><button id=\"btnOpciones_'.$i['id'].'\" type=\"button\" class=\" btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" title=\"Más opciones \"><span class=\"caret\"></span><span class=\"sr-only\">Toggle Dropdown</span> </button><ul class=\"dropdown-menu\" role=\"menu\"><li><a href=\"#\" onClick=\"eliminar(' . $i['id'] . ', \' '.$i['nombre'].' \');return false;\">Eliminar</a> </li></ul></div>"
                            }';
                            //width:105px
                            //'.BASE_URL.'usuarios/registro/editarUsuario/'.$i['id'].'
                if ($last != $i) 
                        $contenido .= ',';
                  /* $contenido = array ("data" => ["Folio"=> $i['id_planeacion'], "Carrera"=>"Morada", "Asignatura"=>"roja", "Plantel"=>"Carrera", "Fecha"=>"Morada", "Estatus"=>"roja", "Opciones"=>"roja"]
                    );   */        
            }
            $contenido .= ']}';
        }else{
            $contenido = '{
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    
        
        echo $contenido ;

       // print_r(json_encode($contenido));
    }

}

?>
