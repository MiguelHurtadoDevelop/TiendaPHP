<?php
namespace Lib;

use PDO;
use PDOException;

class BaseDatos {
    private $conexion;
    private mixed $resultado; // mixed es una novedad en PHP, puede ser de cualquier tipo.
    private string $servidor;
    private string $usuario;
    private string $pass;
    private string $base_datos;

    /**
     * Constructor de la clase BaseDatos.
     */
    function __construct() {
        // Configuración de los datos de la base de datos a partir de variables de entorno.
        $this->servidor = $_ENV['DB_HOST'];
        $this->usuario = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->base_datos = $_ENV['DB_DATABASE'];

        // Establecer la conexión al construir el objeto.
        $this->conexion = $this->conectar();
    }

    /**
     * Método privado para establecer la conexión a la base de datos.
     *
     * @return PDO Objeto de conexión PDO.
     */
    private function conectar(): PDO {
        try {
            // Opciones de configuración de la conexión PDO.
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES Utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );

            // Crear una nueva conexión PDO.
            $conexion = new PDO("mysql:host={$this->servidor};dbname={$this->base_datos}", $this->usuario, $this->pass, $opciones);
            return $conexion;
        } catch(PDOException $e) {
            // Manejar errores de conexión.
            echo "Ha surgido un error y no se puede conectar a la base de datos. Detalle: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Ejecutar una consulta SQL.
     *
     * @param string $consultasQL Consulta SQL a ejecutar.
     */
    public function consulta(string $consultasQL): void {
        $this->resultado = $this->conexion->query($consultasQL);
    }

    /**
     * Extraer un solo registro del conjunto de resultados.
     *
     * @return mixed Array asociativo con los datos del registro o false si no hay más registros.
     */
    public function extraer_registro(): mixed {
        return ($fila = $this->resultado->fetch(PDO::FETCH_ASSOC)) ? $fila : false;
    }

    /**
     * Extraer todos los registros del conjunto de resultados.
     *
     * @return array Array asociativo con todos los registros.
     */
    public function extraer_todos(): array {
        return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener el número de filas afectadas por la última consulta.
     *
     * @return int Número de filas afectadas.
     */
    public function filasAfectadas(): int {
        return $this->resultado->rowCount();
    }

    /**
     * Cerrar la conexión a la base de datos.
     */
    public function close() {
        if ($this->conexion !== null) {
            $this->conexion = null;
        }
    }

    /**
     * Preparar una sentencia SQL para su ejecución.
     *
     * @param string $pre Sentencia SQL a preparar.
     * @return PDOStatement Objeto de sentencia PDO preparado.
     */
    public function prepara($pre) {
        return $this->conexion->prepare($pre);
    }

    /**
     * Obtener el último ID insertado en la base de datos.
     *
     * @return string Último ID insertado.
     */
    public function lastInsertId(): string {
        return $this->conexion->lastInsertId();

    }
}

