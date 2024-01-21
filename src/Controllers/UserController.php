<?php
namespace Controllers;

use Models\User;
use Lib\Pages;
use Utils\Utils;

class UserController
{
    private Pages $pages;

    /**
     * Constructor de la clase UserController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Obtener todos los usuarios.
     *
     * @return array Array de usuarios.
     */
    public static function obtenerUsuarios()
    {
        $usuarios = User::getAll();
        return $usuarios;
    }

    /**
     * Renderizar la página de gestión de usuarios.
     */
    public  function gestionarUsuarios()
    {
        $this->pages->render('/User/gestionarUsuarios');
    }

    /**
     * Hacer que un usuario sea administrador.
     */
    public function hacerAdmin($id)
    {
        if (!empty($id)) {
            $usuario = User::getById($id);
            $usuario->setRol('admin');
            $usuario->update();
        }
        $this->pages->render('/User/gestionarUsuarios');
    }

    /**
     * Renderizar la página de registro.
     */
    public function registrarse()
    {
        $this->pages->render('/User/registro');
    }

    /**
     * Procesar el formulario de registro y crear un nuevo usuario.
     */
    public function registro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!empty($_POST['data'])) {
                $userData = $_POST['data'];

                $usuarioNuevo = User::fromArray($userData);

                // Validar el usuario
                $validacion = $usuarioNuevo->validarUsuario($userData);

                if ($validacion['isValid']) {

                    $usuarioNuevo->setNombre($usuarioNuevo->sanearNombre($userData['nombre']));
                    $usuarioNuevo->setApellidos($usuarioNuevo->sanearApellidos($userData['apellidos']));
                    $usuarioNuevo->setEmail($usuarioNuevo->sanearEmail($userData['email']));
                    $usuarioNuevo->setPassword($usuarioNuevo->sanearPassword(password_hash($userData['password'], PASSWORD_BCRYPT, ['cost'=>4])));
 
                    // Si el usuario es válido, crea el usuario en la base de datos
                    $save = $usuarioNuevo->create();
                    
                    if ($save) {
                        $_SESSION['register'] = "complete";
                        if(isset($_SESSION['login']) && $_SESSION['login']->rol == "admin"){
                            $this->pages->render('/User/gestionarUsuarios');
                        }else{
                            $this->pages->render('/User/Login');
                        }
                        
                    } else {
                        $_SESSION['register'] = "failed";
                        $this->pages->render('/User/registro', ['usuario' => $userData]);
                    }

                    $usuarioNuevo->desconecta();
                } else {
                    // Si el usuario no es válido, guarda el usuario en la sesión
                    $_SESSION['register'] = "failed";
                    $_SESSION['errorNombre'] = $validacion['errorNombre'];
                    $_SESSION['errorApellidos'] = $validacion['errorApellidos'];
                    $_SESSION['errorEmail'] = $validacion['errorEmail'];
                    $_SESSION['errorPassword'] = $validacion['errorPassword'];
                    
                    // Redirige a la vista de registro
                    $this->pages->render('/User/registro', ['usuario' => $userData]);
                }
            }
        } 
    }

    /**
     * Renderizar la página de inicio de sesión.
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['data']) {
                $login = $_POST['data'];

                $usuario = User::fromArray($login);

                $validacion = $usuario->validarLogin($login);
                if ($validacion['isValid']) {
                    $usuario->setEmail($usuario->sanearEmail($login['email']));
                    $usuario->setPassword($usuario->sanearPassword($login['password']));

                    $verify = $usuario->login();

                    if ($verify != false) {
                        $_SESSION['login'] = $verify;
                        $this->pages->render('/dashboard/index');
                    } else {
                        $_SESSION['login'] = "failed";
                    }
                } else {
                    $_SESSION['login'] = "failed";
                    $_SESSION['errorEmail'] = $validacion['errorEmail'];
                    $_SESSION['errorPassword'] = $validacion['errorPassword']; 
                    $this->pages->render('/User/login', ['login' => $login]); 
                }
            } else {
                $_SESSION['login'] = "failed";
                $this->pages->render('/User/login', ['login' => $login]);
            }
        }

        $this->pages->render('/User/login');
    }

    /**
     * Renderizar la página de edición de usuario.
     */
    public function editarUsuario()
    {
        $this->pages->render('/User/editarUsuario');
    }

    /**
     * Actualizar la información del usuario.
     */
    public function actualizarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!empty($_POST['data'])) {
                $userData = $_POST['data'];

                $usuarioActualizado = User::fromArray($userData);

                // Validar el usuario
                $validacion = $usuarioActualizado->validarUsuario($userData);

                if ($validacion['isValid']) {

                    $usuarioActualizado->setNombre($usuarioActualizado->sanearNombre($userData['nombre']));
                    $usuarioActualizado->setApellidos($usuarioActualizado->sanearApellidos($userData['apellidos']));
                    $usuarioActualizado->setEmail($usuarioActualizado->sanearEmail($userData['email']));
                    $usuarioActualizado->setPassword($usuarioActualizado->sanearPassword(password_hash($userData['password'], PASSWORD_BCRYPT, ['cost'=>4])));
    
                    // Si el usuario es válido, actualiza el usuario en la base de datos
                    $update = $usuarioActualizado->update();
    
                    if ($update) {
                        $_SESSION['UsuarioActualizado'] = "complete";
                        $_SESSION['login']->nombre = $userData['nombre'];
                        $_SESSION['login']->apellidos = $userData['apellidos'];
                        $_SESSION['login']->email = $userData['email'];
                        $this->pages->render('/User/editarUsuario');
                    } else {
                        $_SESSION['UsuarioActualizado'] = "failed";
                        $this->pages->render('/User/editarUsuario');
                    }
    
                    $usuarioActualizado->desconecta();
                } else {
                    // Si el usuario no es válido, guarda el usuario en la sesión
                    $_SESSION['UsuarioActualizado'] = "failed";
                    $_SESSION['errorNombre'] = $validacion['errorNombre'];
                    $_SESSION['errorApellidos'] = $validacion['errorApellidos'];
                    $_SESSION['errorEmail'] = $validacion['errorEmail'];
                    $_SESSION['errorPassword'] = $validacion['errorPassword'];
    
                    // Redirige a la vista de actualización
                    $this->pages->render('/User/editarUsuario');
                }
            }
        } 
        
        $this->pages->render('/User/editarUsuario');
    }

    /**
     * Cerrar sesión del usuario.
     */
    public function logout()
    {
        Utils::deleteSession('login');
        header("Location:".BASE_URL);
    }
}