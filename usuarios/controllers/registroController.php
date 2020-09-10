<?php
class registroController extends Controller
{
    private $_registro;
    
    public function __construct() {
        parent::__construct();
        $this->forzarLogin();
        $this->_registro = $this->loadModel('registro');
        //$this->_view->setTemplate('test');
    }
    
	public function editarUsuario()
    {	
    	//print_r($_SESSION);
    	if(isset($_POST['idUsuario'])){
    		$idUsuario = $_POST['idUsuario'];
    	}else{
    		$idUsuario = 0;
    	}
    	
    	$rol = 0;

		$this->_acl->acceso('edituser');			
		
		//Trae roles
		$res = $this->_registro->getRoles();
		$c=0;
		foreach($res as $i){
			$arrayId [$c] = $i["id_role"];
			$arrayDe [$c] = $i["role"];
			$c++;
		}
		$this->_view->assign('role_id', $arrayId);
		$this->_view->assign('role_denomina', $arrayDe);



		//Trae maestros
		/*$res = $this->_registro->getMaestros();
		$c=0;
		foreach($res as $i){
			$arrayId [$c] = $i["id_prof"];
			$arrayNom [$c] = $i["nombre_prof"];
			$c++;
		}
		$this->_view->assign('profe_id', $arrayId);
		$this->_view->assign('profe_nombre', $arrayNom);

		//Trae directores
		$res = $this->_registro->getDirectores();
		$c=0;
		foreach($res as $i){
			$arrayIdDir [$c] = $i["id_director"];
			$arrayNomDir [$c] = $i["nombre"];
			$c++;
		}
		$this->_view->assign('id_director', $arrayIdDir);
		$this->_view->assign('nombre_director', $arrayNomDir);
		*/
		//--------------------------------------

		if(!empty($idUsuario)){ //si es diferente a 0 entra
			$datos = $this->_registro->getUserByID($idUsuario);		
			$this->_view->assign('usuario',$datos["usuario"]);
			$this->_view->assign('nombre',$datos["nombre"]);
			$this->_view->assign('email',$datos["email"]);
			$this->_view->assign('img',$datos["imagen"]);
			$this->_view->assign('hiddenImage',$datos["imagen"]);
			$this->_view->assign('idUsuario',$idUsuario);	
			$rol = $datos["role"];
			

			$this->_view->assign('role_selec', $rol);	
			$this->_view->assign('profe_selec', $datos["id_profesor"]);
			//$this->_view->assign('plantel_selec', $idPlantel); //
		}else{
			$this->_view->assign('role_selec', 16);
		}
		
		$listaPlanteles = $this->_registro->getListaPlanteles($idUsuario, $rol);
		$this->_view->assign('listaPlanteles', $listaPlanteles);
		

		$this->_view->assign('titulo', "Registro de usuarios");
		$this->_view->assign('baseurl', BASE_URL_IMG_PERFIL);
		$this->_view->renderizar('index', 'inicio');	//si es diferente a inicio va a llamar al widget			
	}
	//--------------guardarUsuario
	public function guardarUsuario()
	{	
		if($this->getInt('enviar') == 1){
			
            //$this->_view->assign('datos', $_POST);
            $validarForm = 0;  

            if($_SESSION['rol'] == 15 OR $_SESSION['rol'] == 16){
				$rol = 16; //rol profesor
	        }else{
	        	$rol = $this->getInt('role');
	        } //no se usa para actualizar
			
			/*if($this->getInt('role') == 16){
				if(!$this->getInt('profesor')){
					$arrayResp[0] =  'Debe seleccionar el nombre del profesor';
					echo json_encode($arrayResp);
	                exit;
	            }
	        }elseif($this->getInt('role') == 15){
				if(!$this->getInt('director')){
					$arrayResp[0] =  'Debe seleccionar el nombre del director';
					echo json_encode($arrayResp);
	                exit;
	            }
	        }else{*/

	        /*if($this->_registro->verificarEmail($this->getPostParam('email'))){
				$arrayResp[0] =  "Esta direccion de correo ya esta registrada";
				echo json_encode($arrayResp);
				exit;
			}	*/	
           
           //Contraseña
			if(!$this->getInt('idUsuario') OR $this->getInt('validar_pass')){ //si no existe usuario entra (si es nuevo) o usuario selecciono generar nueva contraseña
						
				if($this->getPostParam('pass')){ //si escribio cola contraseña
					$clave = $this->getPostParam('pass');
					if(strlen($clave) < 6){
						$mensaje =  "La contraseña debe tener longitud mínima de 6 caracteres";
			            $validarForm = 1;
			            $idCampo = "pass";
					}
					/*if(!preg_match('`[a-zA-Z]`',$clave)){
						$arrayResp[0] =  "La clave debe tener al menos una letra";
						echo json_encode($arrayResp);
						exit;
					}*/
					//if(!preg_match('`[A-Z]`',$clave) OR !preg_match('`[0-9]`',$clave)){
					//	$arrayResp[0] =  "La clave debe tener al menos una letra mayúscula y un caracter numérico";
					//	echo json_encode($arrayResp);
					//	exit;
					//}				
					if($clave != $this->getPostParam('confirmar')){
						$mensaje =  "Las contraseñas no coinciden";
			            $validarForm = 1;
			            $idCampo = "confirmar";
					}
				}else{ //si dejo vacio el campo contraseña
					$mensaje =  "Debe introducir su contraseña";
		            $validarForm = 1;
		            $idCampo = "pass";
				}				  								
			}							

	       	

	        //Verificar nombre de usuario
			$usuario = $this->getPostParam('usuario');
			if(!$usuario){
				$mensaje =  'Debe introducir su nombre de usuario';
	            $validarForm = 1;
	            $idCampo = "usuario";
			}else if(!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)){ //comparo si los caracteres q estan en la variable, estan en el primer valor				
				$mensaje =  'Usuario invalido. El nombre de usuario solo puede contener letras, números y guiones bajos. ';
	            $validarForm = 1;
	            $idCampo = "usuario";
				
			}

			if($this->getInt('idUsuario')){   // actualizacion       
				if($this->_registro->verificarSiYaExisteUsuario($this->getAlphaNum('usuario'), $this->getInt('idUsuario'))){
					$mensaje =  'El usuario <b>' . $this->getAlphaNum('usuario') . '</b> ya existe';
		            $validarForm = 1;
		            $idCampo = "usuario";
				}
			}else{ //registro nuevo
				if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
					$mensaje =  'El usuario <b>' . $this->getAlphaNum('usuario') . '</b> ya existe';
		            $validarForm = 1;
		            $idCampo = "usuario";
				}
			}

			if(!$this->getSql('nombre')){
		    	$mensaje =  'Debe introducir su nombre';
	            $validarForm = 1;
	            $idCampo = "nombre";
            }
             if($this->getPostParam('email')){
	            if(!$this->validarEmail($this->getPostParam('email'))){
					$mensaje =  "La direccion de email es inv&aacute;lida ";
		            $validarForm = 1;
		            $idCampo = "email";
	            }
	        }

			//Verifico que haya selecciono plantel
	        //$idPlantel = $this->getInt('plantel');
	        $idPlantel = 0;
	        if(isset($_POST['plantel'])){ //si el campo rol es maestro o coord, checo que haya seleccionado el plantel
    	        //if($_SESSION['rol'] == 1 OR $_SESSION['rol'] == 14 OR $_SESSION['rol'] == 15){
	        		$idPlantel = $_POST['plantel'];
    				if($idPlantel == 0){
    	                $mensaje =  "Debe seleccionar el plantel";
			            $validarForm = 1;
			            $idCampo = "plantel";
    	            }
    	        //}            
	       	}


			/*if ($_FILES['imagen']['size'] > 0)
			{
				$validaImagen = $this->validaImagen();
			}*/						
			
		
			
			
			/*
			$nombreImagen ="";
			if(isset($validaImagen)){
				if($validaImagen){
					
					$nombre_archivo_ant = $_FILES['imagen']['name'];
					list($nombre, $ext) = explode(".", $nombre_archivo_ant);//Se divide el nombre para extraer la extensión 
					
					if(!$this->getInt('idUsuario')){//si el usuario es 0, optenemos el siguiente id de usuario de la BD para asignarselo al nombre de la imagen
						$nombreImg = $this->_registro->nexUsuario();
					}else
						$nombreImg = $this->getInt('idUsuario');
					
					$nombre_archivo_post = $nombreImg . "." . $ext;
					$ruta = BASE_FILE_IMG_PERFIL .  $nombre_archivo_post;
					//if (!file_exists($ruta)){
					$arrayResp[0] = $ruta;
					//echo json_encode($arrayResp);
					//exit();
						$resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
						if ($resultado){
							$nombreImagen = $nombre_archivo_post;
						} else {
							$arrayResp[0] =  "ocurrio un error al guardar la imagen.";
							echo json_encode($arrayResp);
						}
					//} else {
					//	echo $_FILES['imagen']['name'] . ", este archivo existe";
					//}					
					
					$url = $ruta;
					$infoImg = getimagesize($url);
						$infoWidth = $infoImg[0];
						$infoHeight = $infoImg[1];
						$tipoArchivo = $infoImg['mime'];
						$type = exif_imagetype($url);
						
					switch ($type) { 
						case 1 : //gif
							$src_image = imageCreateFromGif($url); 
							break; 
						case 2 : //jpg
							$src_image = imageCreateFromJpeg($url); 
							break; 
						case 3 : //png
							$src_image = imageCreateFromPng($url); 
							break; 
					}   
					//$src_image = imagecreatefromjpeg($url);
					//que parte de la imagen original se tomara
					$src_w = $infoHeight ;
					$src_h = $infoHeight;
					
					$src_x = -($infoHeight-$infoWidth)/2;
					$src_y = 0;
					
					$dst_x = 0;
					$dst_y = 0;
					//tamaño de la imagen de salida
					$dst_w = 160;
					$dst_h = 160;
					
					$dst_image = imagecreatetruecolor($dst_w, $dst_h);
					if($type==3){
						imagealphablending( $dst_image, false );
						imagesavealpha( $dst_image, true );
					}
				}

				//imagecopyresampled($dst_image, $src_original, 0, 0, $x1, $y1, $ancho, $alto, $ancho, $alto); 
				imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				header('Content-type: ' . $tipoArchivo);
				//imagejpeg($dst_image, null, 100);
				//guarda imagen
				switch ($type) { 
					case 1 : //gif
						imagegif($dst_image, $url,100);						
						break; 
					case 2 : //jpg
						imagejpeg($dst_image, $url, 100);
						break; 
					case 3 : //png
						imagepng($dst_image, $url, 9);	
						break; 
				} 
				
				imagedestroy($dst_image);
				imagedestroy($src_image);					
			}else
				if($_POST["hiddenImage"])
					$nombreImagen = $_POST["hiddenImage"];
				else
					$nombreImagen = "default.jpg";	
			*/		
			if($validarForm == 1){
	            $jsondata = array();                                
	            $jsondata['response'] = 3;
	            $jsondata['mensajeValidacion'] = $mensaje; 
	            $jsondata['idCampo'] = $idCampo;                            
	            echo json_encode($jsondata);
	        }else{
	           	$datosValidados = array(
					"idUsuario" => $this->getInt('idUsuario'),
					"nombre" => $this->getSql('nombre'),
					"usuario" => $this->getAlphaNum('usuario'),
					"pass" => $this->getPostParam('pass'),
					"email" => $this->getPostParam('email'),
					"role" => $rol,
					"id_profesor" => $this->getInt('profesor'),
					"id_director" => $this->getInt('director'),
					"nombreImagen" => "default.jpg", //$nombreImagen,
					"idPlantel" =>$idPlantel
				);
				$this->_registro->putUsuario($datosValidados);
	         	
	         	//
				
	        }
			
            
           // $this->_view->assign('_mensaje', 'Registro Completado del suario efectuado. Puede agregar otro usuario.');
        } 
		//$res = $this->_registro->putUsuario($_POST);
		//echo "";
	}
	
	
	
	//----------------index (no esta en uso)
    public function index($id=0)
    {
		/*if(Session::get('autenticado')){
            $this->redireccionar();
        }*/
		$this->_acl->acceso('adduser');        
        $this->_view->assign('titulo', 'Registro de usuario');		
		$res = $this->_registro->getRoles();
		$this->_view->assign('idUsuer',$id);
		
		$c=0;
		foreach($res as $i){
			$arrayId [$c] = $i["id_role"];
			$arrayDe [$c] = $i["role"];
			$c++;
		}
		
		$this->_view->assign('role_id', $arrayId);
		$this->_view->assign('role_denomina', $arrayDe);
		$this->_view->assign('role_selec', $this->getPostParam('role'));			
        
        if($this->getInt('enviar') == 1){
            $this->_view->assign('datos', $_POST);
            
            if(!$this->getSql('nombre')){
                $this->_view->assign('_error', 'Debe introducir su nombre');
                $this->_view->renderizar('index', 'registro');
                exit;
            }            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->assign('_error', 'Debe introducir su nombre usuario');
                $this->_view->renderizar('index', 'registro');
                exit;
            }            
            if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->assign('_error', 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe');
                $this->_view->renderizar('index', 'registro');
                exit;
            }            
            if(!$this->validarEmail($this->getPostParam('email'))){
                $this->_view->assign('_error', 'La direccion de email es inv&aacute;lida');
                $this->_view->renderizar('index', 'registro');
                exit;
            }            
            if($this->_registro->verificarEmail($this->getPostParam('email'))){
                $this->_view->assign('_error', 'Esta direccion de correo ya esta registrada');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if(!$this->getSql('pass')){
                $this->_view->assign('_error', 'Debe introducir su password');
                $this->_view->renderizar('index', 'registro');
                exit;
            }            
            if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                $this->_view->assign('_error', 'Los passwords no coinciden');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            /*
            $this->getLibrary('class.phpmailer');
            $mail = new PHPMailer();
			*/
            $this->_registro->registrarUsuario(
                    $this->getSql('nombre'),
                    $this->getAlphaNum('usuario'),
                    $this->getSql('pass'),
                    $this->getPostParam('email'),
					$this->getPostParam('role')
                    );            
            $usuario = $this->_registro->verificarUsuario($this->getAlphaNum('usuario'));
			
            if(!$usuario){
                $this->_view->assign('_error', 'Error al registrar el usuario');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
			/*
            $mail->From = 'www.mvc.dlancedu.com';
            $mail->FromName = 'Tutorial MVC';
            $mail->Subject = 'Activacion de cuenta de usuario';
            $mail->Body = 'Hola <strong>' . $this->getSql('nombre') . '</strong>,' .
                        '<p>Se ha registrado en www.mvc.dlancedu.com para activar ' .
                        'su cuenta haga clic sobre el siguiente enlace:<br>' .
                        '<a href="' . BASE_URL .'registro/activar/' . 
                        $usuario['id'] . '/' . $usuario['codigo'] . '">' .
                        BASE_URL .'registro/activar/' . 
                        $usuario['id'] . '/' . $usuario['codigo'] .'</a>' ;

            $mail->AltBody = 'Su servidor de correo no soporta html';
            $mail->AddAddress($this->getPostParam('email'));
            $mail->Send();
            */
            $this->_view->assign('datos', false);
            $this->_view->assign('_mensaje', 'Registro Completado del suario efectuado. Puede agregar otro usuario.');
        }                
        $this->_view->renderizar('index', 'registro');
    }

    public function activar($id, $codigo)
    {
        if(!$this->filtrarInt($id) || !$this->filtrarInt($codigo)){
            $this->_view->assign('_error', 'Esta cuenta no existe');
            $this->_view->renderizar('activar', 'registro');
            exit;   
            }

        $row = $this->_registro->getUsuario(
                            $this->filtrarInt($id),
                            $this->filtrarInt($codigo)
                            );

        if(!$row){
            $this->_view->assign('_error', 'Esta cuenta no existe');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }

        if($row['estado'] == 1){
            $this->_view->assign('_error', 'Esta cuenta ya ha sido activada');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }

        $this->_registro->activarUsuario(
                            $this->filtrarInt($id),
                            $this->filtrarInt($codigo)
                            );

        $row = $this->_registro->getUsuario(
                            $this->filtrarInt($id),
                            $this->filtrarInt($codigo)
                            );

        if($row['estado'] == 0){
            $this->_view->assign('_error', 'Error al activar la cuenta, por favor intente mas tarde');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }

        $this->_view->assign('_mensaje', 'Su cuenta ha sido activada');
        $this->_view->renderizar('activar', 'registro');
    }
	//BASE_URL_SITE
	public function validaImagen(){
		
		if ($_FILES["imagen"]["error"] > 0){
			
			$arrayResp[0] =  "Ha ocurrido un error al subir la imagen";
			echo json_encode($arrayResp);
			exit();
			return FALSE;
		} else {
			$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
			$limite_kb = 10000;// kb
		
			if (!in_array($_FILES['imagen']['type'], $permitidos)){
				$arrayResp[0] =  "La extensión del archivo es inválida<br>";
				echo json_encode($arrayResp);
				exit();
				return FALSE;
			}
			if(!($_FILES['imagen']['size'] <= $limite_kb * 1024)){
				$arrayResp[0] =  "El archivo excede el tamaño de $limite_kb Kilobytes<br>";
				echo json_encode($arrayResp);
				exit();
				return FALSE;
			}
			if(strlen($_FILES['imagen']['name'])>= 200 ){
				$arrayResp[0] =  "El nombre del archivo es demasiado largo<br>";
				echo json_encode($arrayResp);
				exit();
				return FALSE;
			}				
		}
		return true;
	}

	/*public function obtenerDatosProfesor(){	
    	$semestres = $this->_registro->getDatosMaestros($_POST["id_profe"]);	    	
    }*/
}

?>
