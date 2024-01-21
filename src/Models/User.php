<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class User
{
    private string $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;
    private BaseDatos $db;

    /**
     * User constructor.
     *
     * @param string      $id
     * @param string      $nombre
     * @param string      $apellidos
     * @param string      $email
     * @param string      $password
     * @param string      $rol
     */
    public function __construct(string $id, string $nombre, string $apellidos, string $email, string $password, string $rol)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->db = new BaseDatos();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * Crea una instancia de User a partir de un array de datos.
     *
     * @param array $data
     *
     * @return User
     */
    public static function fromArray(array $data): User
    {
        return new User(
            $data['id'] ?? '',
            $data['nombre'] ?? '',
            $data['apellidos'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? ''
        );
    }

    /**
     * Desconecta la base de datos.
     */
    public function desconecta(): void
    {
        $this->db->close();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @return bool
     */
    public function create(): bool
    {
        $id = null;
        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $rol = 'user';

        try {
            $ins = $this->db->prepara("INSERT INTO usuarios (id, nombre, apellidos, email, password, rol) values (:id, :nombre, :apellidos, :email, :password, :rol)");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':password', $password);
            $ins->bindValue(':rol', $rol);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    /**
     * Actualiza los datos del usuario en la base de datos.
     *
     * @return bool
     */
    public function update(): bool
    {
        $id = $this->getId();
        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $rol = $this->getRol();

        try {
            $ins = $this->db->prepara("UPDATE usuarios SET nombre=:nombre, apellidos=:apellidos, email=:email, password=:password, rol=:rol WHERE id=:id");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':password', $password);
            $ins->bindValue(':rol', $rol);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    /**
     * Obtiene todos los usuarios de la base de datos.
     *
     * @return array
     */
    public static function getAll(): array
    {
        $db = new BaseDatos();
        $select = $db->prepara("SELECT * FROM usuarios");
        $select->execute();
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select = null;
        $db->close();

        return $result;
    }

    /**
     * Inicia sesión y verifica las credenciales del usuario.
     *
     * @return false|array
     */
    public function login()
    {
        $email = $this->getEmail();
        $password = $this->getPassword();
        $result = false;

        try {
            $datosUsuario = $this->buscaMail($email);

            if ($datosUsuario !== false && $datosUsuario !== null) {
                $verify = password_verify($password, $datosUsuario->password);

                if ($verify) {
                    $result = $datosUsuario;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } catch (PDOException $error) {
            $result = false;
        }

        return $result;
    }

    /**
     * Obtiene un usuario por su ID.
     *
     * @param string $id
     *
     * @return User|false
     */
    public static function getById(string $id)
    {
        $db = new BaseDatos();
        $select = $db->prepara("SELECT * FROM usuarios WHERE id=:id");
        $select->bindValue(':id', $id, PDO::PARAM_STR);
        $result = false;

        try {
            $select->execute();

            if ($select && $select->rowCount() == 1) {
                $userData = $select->fetch(PDO::FETCH_ASSOC);
                $result = User::fromArray($userData);
            }
        } catch (PDOException $err) {
            $result = false;
        }

        return $result;
    }

    /**
     * Busca un usuario por su dirección de correo electrónico.
     *
     * @param string $email
     *
     * @return false|object
     */
    public function buscaMail(string $email)
    {
        $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
        $select->bindValue(':email', $email, PDO::PARAM_STR);
        $result = false;

        try {
            $select->execute();

            if ($select && $select->rowCount() == 1) {
                $result = $select->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $err) {
            $result = false;
        }

        return $result;
    }

    /**
     * Valida los datos del usuario antes de la creación o actualización.
     *
     * @param array $userData
     *
     * @return array
     */
    public function validarUsuario(array $userData): array
    {
        $result = [
            'isValid' => true,
            'errorNombre' => '',
            'errorApellidos' => '',
            'errorEmail' => '',
            'errorPassword' => '',
        ];

        // Validar nombre
        if (empty($userData['nombre'])) {
            $result['isValid'] = false;
            $result['errorNombre'] = 'El nombre es requerido';
        } else {
            if (!preg_match("/^[a-zA-Z ]*$/", $userData['nombre'])) {
                $result['isValid'] = false;
                $result['errorNombre'] = 'Solo se permiten letras y espacios en blanco';
            } else {
                if (strlen($userData['nombre']) > 50) {
                    $result['isValid'] = false;
                    $result['errorNombre'] = 'El nombre no puede tener más de 50 caracteres';
                }
            }
        }

        // Validar apellidos
        if (empty($userData['apellidos'])) {
            $result['isValid'] = false;
            $result['errorApellidos'] = 'Los apellidos son requeridos';
        } else {
            if (!preg_match("/^[a-zA-Z ]*$/", $userData['apellidos'])) {
                $result['isValid'] = false;
                $result['errorApellidos'] = 'Solo se permiten letras y espacios en blanco';
            } else {
                if (strlen($userData['apellidos']) > 50) {
                    $result['isValid'] = false;
                    $result['errorApellidos'] = "Los apellidos no pueden tener más de 50 caracteres";
                }
            }
        }
    
        // Validar email
        if (empty($userData['email'])) {
            $result['isValid'] = false;
            $result['errorEmail'] = "El email es requerido";
        } else {
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $result['isValid'] = false;
                $result['errorEmail'] = "El email no es válido";
            } 
        }
    
        // Validar password
        if (empty($userData['password'])) {
            $result['isValid'] = false;
            $result['errorPassword'] = "La contraseña es requerida";
        } else {
            if (strlen($userData['password']) < 8) {
                $result['isValid'] = false;
                $result['errorPassword'] = "La contraseña debe tener al menos 8 caracteres";
            }
        }
    
        return $result;
    }

    /**
     * Valida los datos del usuario antes del inicio de sesion.
     *
     * @param array $userData
     *
     * @return array
     */
    public function validarLogin($userData){
        $result = ['isValid' => true, 'errorEmail' => '', 'errorPassword' => ''];
    
        // Validar email
        if (empty($userData['email'])) {
            $result['isValid'] = false;
            $result['errorEmail'] = "El email es requerido";
        } else {
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $result['isValid'] = false;
                $result['errorEmail'] = "El email no es válido";
            } 
        }
    
        return $result;
    }

    /**
     * Sanitiza el nombre del usuario.
     *
     * @param string $nombre
     *
     * @return string
     */
    public function sanearNombre($nombre){
        $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);

        return $nombre;
    }

    /**
     * Sanitiza los apellidos del usuario.
     *
     * @param string $apellidos
     *
     * @return string
     */
    public function sanearApellidos($apellidos){
        $apellidos = filter_var($apellidos, FILTER_SANITIZE_STRING);

        return $apellidos;
    }

    /**
     * Sanitiza el email del usuario.
     *
     * @param string $email
     *
     * @return string
     */
    public function sanearEmail($email){
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }

    /**
     * Sanitiza la contraseña del usuario.
     *
     * @param string $password
     *
     * @return string
     */
    public function sanearPassword($password){
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        return $password;
    }

}