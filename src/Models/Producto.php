<?php

namespace Models;

use PDO;
use PDOException;
use Lib\BaseDatos;

class Producto
{

    private int $id;
    private int $categoriaId;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private int $stock;
    private string $oferta;
    private string $fecha;
    private string $imagen;

    private BaseDatos $db;

    /**
     * @param BaseDatos $db
     */
    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    public function getId():int
    {
        return $this->id;
    }
    public function setId(int $id):void
    {
        $this->id = $id;
    }

    

    public function getCategoriaId()
    {
        return $this->categoriaId;
    }

    public function setCategoriaId( $categoriaId): void
    {
        $this->categoriaId = $categoriaId;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getOferta(): string
    {
        return $this->oferta;
    }

    public function setOferta(string $oferta): void
    {
        $this->oferta = $oferta;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getDb(): BaseDatos
    {
        return $this->db;
    }

    public function setDb(BaseDatos $db): void
    {
        $this->db = $db;
    }






    public function desconecta(){
        $this->db->close();
    }

    public function delete(){
        $id=$this->getId();

        try {

            $ins = $this->db->prepara("DELETE FROM productos WHERE id = :id");
            $ins->bindValue(':id', $id);
            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;

        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }



    public function create(): bool {
        $id = NULL;
        $categoriaId = $this->getCategoriaId();
        $nombre = $this->getNombre();
        $descripcion = $this->getDescripcion();
        $precio = $this->getPrecio();
        $stock = $this->getStock();
        $oferta = $this->getOferta();
        $fecha = $this->getFecha();
        $imagen = $this->getImagen();

        try {
            $ins = $this->db->prepara("INSERT INTO productos (id, categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen) VALUES (:id, :categoriaId, :nombre, :descripcion, :precio, :stock, :oferta, :fecha, :imagen)");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':categoriaId', $categoriaId);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':descripcion', $descripcion);
            $ins->bindValue(':precio', $precio);
            $ins->bindValue(':stock', $stock);
            $ins->bindValue(':oferta', $oferta);
            $ins->bindValue(':fecha', $fecha);
            $ins->bindValue(':imagen', $imagen);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    public function update(): bool {
        $id = $this->getId();
        $categoriaId = $this->getCategoriaId();
        $nombre = $this->getNombre();
        $descripcion = $this->getDescripcion();
        $precio = $this->getPrecio();
        $stock = $this->getStock();
        $oferta = $this->getOferta();
        $fecha = $this->getFecha();
        $imagen = $this->getImagen();

        try {
            $ins = $this->db->prepara("UPDATE productos SET categoria_id = :categoriaId, nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, oferta = :oferta, fecha = :fecha, imagen = :imagen WHERE id = :id");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':categoriaId', $categoriaId);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':descripcion', $descripcion);
            $ins->bindValue(':precio', $precio);
            $ins->bindValue(':stock', $stock);
            $ins->bindValue(':oferta', $oferta);
            $ins->bindValue(':fecha', $fecha);
            $ins->bindValue(':imagen', $imagen);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    public function updateStock($id, $stock): bool {
        try {
            $ins = $this->db->prepara("UPDATE productos SET stock = :sotck WHERE id = :id");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':sotck', $stock);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    public static function getAll(){
        $producto = new Producto();
        $producto->db->consulta("SELECT * FROM productos ORDER BY id ASC;");
        $categorias = $producto->db->extraer_todos();
        $producto->db->close();

        return $categorias;
    }

    public static function getById($id){
        $producto = new Producto();
        $producto->db->consulta("SELECT * FROM productos WHERE id = $id;");
        $categoria = $producto->db->extraer_registro();
        $producto->db->close();

        return $categoria;
    }

    public static function getByCategoria($id){
        $producto = new Producto();
        $producto->db->consulta("SELECT * FROM productos WHERE categoria_id = $id;");
        $categoria = $producto->db->extraer_todos();
        $producto->db->close();

        return $categoria;
    }

        /**
     * Valida los datos del producto.
     *
     * @param string $nombre
     * @param string $descripcion
     * @param int $categoriaId
     * @param float $precio
     * @param int $stock
     * @param float $oferta
     *
     * @return array
     */
    public function validarProducto($nombre, $descripcion, $precio, $stock, $oferta){
        $result = ['isValid' => true, 'errors' => []];

        // Validar nombre
        if (empty($nombre)) {
            $result['isValid'] = false;
            $result['errors']['nombre'] = "El nombre del producto es requerido";
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $nombre)) {
            $result['isValid'] = false;
            $result['errors']['nombre'] = "El nombre del producto solo puede contener letras y espacios";
        }

        // Validar descripcion
        if (empty($descripcion)) {
            $result['isValid'] = false;
            $result['errors']['descripcion'] = "La descripción del producto es requerida";
        } elseif (!preg_match("/^[a-zA-Z0-9 ]*$/", $descripcion)) {
            $result['isValid'] = false;
            $result['errors']['descripcion'] = "La descripción del producto solo puede contener letras, números y espacios";
        }


        // Validar precio
        if (empty($precio) || !is_numeric($precio) || $precio < 0) {
            $result['isValid'] = false;
            $result['errors']['precio'] = "El precio del producto no es válido";
        }

        // Validar stock
        if (!isset($stock) || !is_numeric($stock) || $stock < 0) {
            $result['isValid'] = false;
            $result['errors']['stock'] = "El stock del producto no es válido";
        }

        // Validar oferta
        if (!isset($oferta) || !is_numeric($oferta)) {
            $result['isValid'] = false;
            $result['errors']['oferta'] = "La oferta del producto debe ser un número";
        }

        return $result;
    }

/**
 * Sanitiza los datos del producto.
 *
 * @param $nombre
 * @param $descripcion
 * @param $categoriaId
 * @param $precio
 * @param $stock
 * @param $oferta
 * @param $fecha
 * @param $imagen
 *
 * @return array
 */

public function sanearProducto( $nombre, $descripcion, $categoriaId, $precio, $stock, $oferta, $fecha, $imagen){
    $descripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
    $precio = filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_var($stock, FILTER_SANITIZE_NUMBER_INT);
    $oferta = filter_var($oferta, FILTER_SANITIZE_STRING);
    $fecha = filter_var($fecha, FILTER_SANITIZE_STRING);
    $imagen = filter_var($imagen, FILTER_SANITIZE_URL);

    $productData = [
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'categoriaId' => $categoriaId,
        'precio' => $precio,
        'stock' => $stock,
        'oferta' => $oferta,
        'fecha' => $fecha,
        'imagen' => $imagen
    ];

    return $productData;
}

    

function subirFoto($fichero):bool {
    
    $nombreDirectorio = "img/";

    if(!is_dir($nombreDirectorio)){
        mkdir($nombreDirectorio, 0777, true);
    }

    if(is_uploaded_file($fichero["tmp_name"])){
        $nombreFichero = $fichero["name"];
        $nombreCompleto = $nombreDirectorio.$nombreFichero;

        if(is_file($nombreCompleto)){
            $idUnico = time();
            $nombreFichero = $idUnico."-". $nombreFichero;
        }

        $res= move_uploaded_file($fichero["tmp_name"], $nombreDirectorio.$nombreFichero);
        if($res){
            return true;
        }else{
            return false;
        }
    }
    return true;
}
}