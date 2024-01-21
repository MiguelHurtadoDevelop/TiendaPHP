<?php

namespace Models;

use PDO;
use PDOException;
use Lib\BaseDatos;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Pedido
{

    private int $id;
    private int $usuario_id;
    private string $provincia;
    private string $localidad;
    private string $direccion;
    private string $coste;
    private string $estado;
    private string $fecha;
    private string $hora;
    


    private BaseDatos $db;

    /**
     * @param BaseDatos $db
     */
    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getProvincia(): string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): void
    {
        $this->provincia = $provincia;
    }

    public function getLocalidad(): string
    {
        return $this->localidad;
    }

    public function setLocalidad(string $localidad): void
    {
        $this->localidad = $localidad;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getCoste(): string
    {
        return $this->coste;
    }

    public function setCoste(string $coste): void
    {
        $this->coste = $coste;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getHora(): string
    {
        return $this->hora;
    }

    public function setHora(string $hora): void
    {
        $this->hora = $hora;
    }

    public function getDb(): BaseDatos
    {
        return $this->db;
    }

    public function setDb(BaseDatos $db): void
    {
        $this->db = $db;
    }

    public function create(){
        $id= NULL;
        $usuario_id= $this->getUsuarioId();
        $provincia= $this->getProvincia();
        $localidad= $this->getLocalidad();
        $direccion= $this->getDireccion();
        $coste= $this->getCoste();
        $estado= $this->getEstado();
        $fecha= $this->getFecha();
        $hora= $this->getHora();

        try{
            $ins = $this->db->prepara("INSERT INTO pedidos VALUES (:id, :usuario_id, :provincia, :localidad, :direccion, :coste, :estado, :fecha, :hora)");
            $ins->bindValue(':id', $id);
            $ins->bindValue(':usuario_id', $usuario_id);
            $ins->bindValue(':provincia', $provincia);
            $ins->bindValue(':localidad', $localidad);
            $ins->bindValue(':direccion', $direccion);
            $ins->bindValue(':coste', $coste);
            $ins->bindValue(':estado', $estado);
            $ins->bindValue(':fecha', $fecha);
            $ins->bindValue(':hora', $hora);

            $ins->execute();


            
            $result = true;
        } catch (PDOException $error) {
                $result = false;
        }

            $ins->closeCursor();
            $ins = null;

            return $result;

    }

    public function createLineaPedido( $pedido_id, $producto_id, $unidades){
        

        try{
            $ins = $this->db->prepara("INSERT INTO lineas_pedidos VALUES (NULL, :pedido_id, :producto_id, :unidades)");
            $ins->bindValue(':pedido_id', $pedido_id);
            $ins->bindValue(':producto_id', $producto_id);
            $ins->bindValue(':unidades', $unidades);

            $ins->execute();
            $result = true;
        }
        catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    public function getLast(){
        $sql = $this->db->prepara("SELECT * FROM pedidos ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $pedido = $sql->fetch(PDO::FETCH_OBJ);

        return $pedido;
    }


    

    public function updateEstado(){
        try{
            $id = $this->getId();
            $estado = $this->getEstado();
            $ins = $this->db->prepara("UPDATE pedidos SET estado = :estado WHERE id = :id");
            $ins->bindValue(':id', $id);
            $ins->bindValue(':estado', $estado);

            $ins->execute();
            $result = true;
        } catch (PDOException $error) {
                $result = false;
        }

            $ins->closeCursor();
            $ins = null;

            return $result;
    }

    public function enviarCorreo($cuerpoCorreo){

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            // Datos personales
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;
            $mail->Username = "miguelhurtado.developer@gmail.com";
            $mail->Password = "jjjs bxps hqli ngsi";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            // Remitente
            $mail->setFrom('miguelhurtado.developer@gmail.com', 'La tiendita');
            // Destinatario, opcionalmente también se puede especificar el nombre
            $destinatario = $_SESSION['login']->email;
            
            $nombreDestinatario = $_SESSION['login']->nombre;
            $mail->addAddress($destinatario, $nombreDestinatario);
            // Copia
            $mail->addCC('miguelhurtado.developer@gmail.com');
            // Copia oculta
            $mail->addBCC('miguelhurtado.developer@gmail.com', 'Miguel Hurtado');

            $mail->isHTML(true);
            // Asunto
            $mail->Subject = 'Pedido realizado con éxito';
            // Contenido HTML
            $mail->Body = $cuerpoCorreo;
            $mail->AltBody = 'El texto como elemento de texto simple';
            
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->send();
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
        }
    }

    public function getAll()
    {
        $sql = $this->db->prepara("SELECT * FROM pedidos");
        $sql->execute();
        $pedidos = $sql->fetchAll(PDO::FETCH_OBJ);



        
        return $pedidos;
    }

    public function getByUser($usuario_id)
    {
        
        $sql = $this->db->prepara("SELECT * FROM pedidos WHERE usuario_id = :usuario_id");
        $sql->bindValue(':usuario_id', $usuario_id);
        $sql->execute();
        $pedidos = $sql->fetchAll(PDO::FETCH_OBJ);

        return $pedidos;

    }

    public function desconecta()
    {
        $this->db->desconectar();
    }

}