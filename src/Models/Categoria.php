<?php

// Espacio de nombres para la clase Categoria en el directorio Models
namespace Models;

// Importa la clase BaseDatos del espacio de nombres Lib
use Lib\BaseDatos;

// Importa las clases PDO y PDOException
use PDO;
use PDOException;

// Definición de la clase Categoria
class Categoria
{
    // Propiedades de la clase
    private ?string $id;         // Identificador (puede ser nulo)
    private string $nombre;       // Nombre de la categoría
    private BaseDatos $db;        // Instancia de la clase BaseDatos para la conexión a la base de datos

    // Constructor de la clase
    public function __construct()
    {
        // Inicializa la instancia de BaseDatos en la propiedad $db
        $this->db = new BaseDatos();
    }

    // Métodos de acceso para la propiedad $id
    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    // Métodos de acceso para la propiedad $nombre
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    // Método para la creación de una nueva categoría
    public function create(): bool
    {
        // Obtiene el nombre y verifica si ya existe una categoría con ese nombre
        $nombre = $this->getNombre();
        $coincidencia = $this->buscaNombre($nombre);
        $result = false;

        // Si no hay coincidencia, intenta insertar la nueva categoría
        if (!$coincidencia) {
            try {
                // Prepara la sentencia SQL para la inserción
                $ins = $this->db->prepara("INSERT INTO categorias (nombre) VALUES (:nombre)");

                // Asocia el parámetro :nombre con el valor de $nombre
                $ins->bindValue(':nombre', $nombre);

                // Ejecuta la sentencia SQL
                $ins->execute();

                // Establece el resultado como verdadero
                $result = true;
            } catch (PDOException $error) {
                // En caso de error, establece el resultado como falso
                $result = false;
            } finally {
                // Cierra el cursor y la conexión
                $ins->closeCursor();
                $ins = null;
            }
        }

        // Retorna el resultado
        return $result;
    }

    // Método para la actualización de una categoría existente
    public function update()
    {
        // Obtiene el id y el nombre de la categoría
        $id = $this->getId();
        $nombre = $this->getNombre();
        $result = false;

        // Intenta actualizar la categoría en la base de datos
        try {
            // Prepara la sentencia SQL para la actualización
            $update = $this->db->prepara("UPDATE categorias SET nombre=:nombre WHERE id=:id");

            // Asocia los parámetros :id y :nombre con los valores correspondientes
            $update->bindValue(':id', $id);
            $update->bindValue(':nombre', $nombre);

            // Ejecuta la sentencia SQL
            $update->execute();

            // Establece el resultado como verdadero
            $result = true;
        } catch (PDOException $error) {
            // En caso de error, establece el resultado como falso
            $result = false;
        } finally {
            // Cierra el cursor y la conexión
            $update->closeCursor();
            $update = null;
        }

        // Retorna el resultado
        return $result;
    }

    // Método para la eliminación de una categoría
    public function delete()
    {
        // Obtiene el id de la categoría a eliminar
        $id = $this->getId();
        $result = false;

        // Intenta eliminar la categoría de la base de datos
        try {
            // Prepara la sentencia SQL para la eliminación
            $delete = $this->db->prepara("DELETE FROM categorias WHERE id=:id");

            // Asocia el parámetro :id con el valor correspondiente
            $delete->bindValue(':id', $id);

            // Ejecuta la sentencia SQL
            $delete->execute();

            // Establece el resultado como verdadero
            $result = true;
        } catch (PDOException $error) {
            // En caso de error, establece el resultado como falso
            $result = false;
        } finally {
            // Cierra el cursor y la conexión
            $delete->closeCursor();
            $delete = null;
        }

        // Retorna el resultado
        return $result;
    }

    // Método estático para obtener una categoría por su id
    public static function getById($id)
    {
        // Crea una nueva instancia de la clase Categoria
        $categoria = new Categoria();

        // Prepara la sentencia SQL para la selección por id
        $select = $categoria->db->prepara("SELECT * FROM categorias WHERE id=:id");

        // Asocia el parámetro :id con el valor proporcionado
        $select->bindValue(':id', $id, PDO::PARAM_STR);

        // Inicializa el resultado como falso
        $result = false;

        // Intenta ejecutar la sentencia SQL
        try {
            $select->execute();

            // Verifica si se obtuvo un resultado y si hay una sola fila
            if ($select && $select->rowCount() == 1) {
                // Obtiene los datos como un array asociativo
                $result = $select->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $err) {
            // En caso de error, establece el resultado como falso
            $result = false;
        }

        // Retorna el resultado
        return $result;
    }

    // Método para cerrar la conexión a la base de datos
    public function desconecta()
    {
        $this->db->close();
    }

    // Método estático para obtener todas las categorías
    public static function getAll()
    {
        // Crea una nueva instancia de la clase Categoria
        $categoria = new Categoria();

        // Realiza una consulta para obtener todas las categorías ordenadas por id
        $categoria->db->consulta("SELECT * FROM categorias ORDER BY id DESC;");

        // Extrae todos los resultados como un array
        $categorias = $categoria->db->extraer_todos();

        // Cierra la conexión a la base de datos
        $categoria->db->close();

        // Retorna el array de categorías
        return $categorias;
    }

    // Método para buscar una categoría por su nombre
    public function buscaNombre($nombre)
    {
        // Prepara la sentencia SQL para la búsqueda por nombre
        $select = $this->db->prepara("SELECT * FROM categorias WHERE nombre=:nombre");

        // Asocia el parámetro :nombre con el valor proporcionado
        $select->bindValue(':nombre', $nombre, PDO::PARAM_STR);

        // Inicializa el resultado como falso
        $result = false;

        // Intenta ejecutar la sentencia SQL
        try {
            $select->execute();

            // Verifica si se obtuvo un resultado y si hay una sola fila
            if ($select && $select->rowCount() == 1) {
                // Obtiene los datos como un objeto
                $result = $select->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $err) {
            // En caso de error, establece el resultado como falso
            $result = false;
        }

        // Retorna el resultado
        return $result;
    }

    // Método para validar el nombre de una categoría
    public function validaCategoria($nombre)
    {
        // Inicializa la validez como verdadera
        $isValid = true;
        $error = '';

        // Comprueba si el nombre está vacío
        if (empty($nombre)) {
            $isValid = false;
            $error = "El nombre es requerido";
        } else {
            // Comprueba si el nombre solo contiene letras y espacios en blanco
            if (!preg_match("/^[a-zA-Z ]*$/", $nombre)) {
                $isValid = false;
                $error = "Solo se permiten letras y espacios en blanco";
            } else {
                // Comprueba si el nombre tiene menos de 50 caracteres
                if (strlen($nombre) > 50) {
                    $isValid = false;
                    $error = "El nombre no puede tener más de 50 caracteres";
                }
            }
        }

        // Retorna un array con la validez y el mensaje de error
        return ['isValid' => $isValid, 'error' => $error];
    }

    // Método para sanear el nombre de una categoría
    public function sanearCategoria($nombre)
    {
        // Sanea el nombre de la categoría utilizando FILTER_SANITIZE_STRING
        $nombreSaneado = filter_var($nombre, FILTER_SANITIZE_STRING);

        // Retorna el nombre saneado
        return $nombreSaneado;
    }
}
