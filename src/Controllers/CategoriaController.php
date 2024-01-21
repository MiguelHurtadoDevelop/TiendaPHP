<?php

namespace Controllers;

use Lib\Pages;
use Utils\Utils;
use Models\Categoria;

class CategoriaController
{
    private Pages $pages;

    /**
     * Constructor de la clase CategoriaController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Obtener todas las categorías.
     *
     * @return array|null Array de categorías o null si no se encuentran.
     */
    public static function obtenerCategorias(): ?array
    {
        return Categoria::getAll();
    }

    /**
     * Renderizar la página de gestión de categorías.
     */
    public function gestionarCategoria()
    {
        $this->pages->render('/categoria/categoriaAdmin');
    }

    /**
     * Renderizar la página para agregar una categoría.
     */
    function addCategory()
    {
        $this->pages->render('/categoria/añadirCategoria');
    }

    /**
     * Añadir una nueva categoría basada en el envío del formulario.
     */
    public function AnadirCategoria()
    {
        // Verificar si el formulario se envió mediante POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar si se proporcionaron datos de categoría
            if ($_POST['categoria']) {
                $nombre = $_POST['categoria'];
                $categoria = new Categoria();

                // Validar la categoría
                $validacion = $categoria->validaCategoria($nombre);

                if ($validacion['isValid']) {
                    // Sanitizar y establecer el nombre de la categoría
                    $nombre = $categoria->sanearCategoria($nombre);
                    $categoria->setNombre($nombre);

                    // Si la categoría es válida, crearla en la base de datos
                    $save = $categoria->create();
                    if ($save) {
                        $_SESSION['CategoriaAñadida'] = "complete";
                        $this->pages->render('categoria/categoriaAdmin');
                    } else {
                        $_SESSION['CategoriaAñadida'] = "failed";
                        $this->pages->render('categoria/añadirCategoria', ['Categoria' => $nombre]);
                    }
                } else {
                    // Si la categoría no es válida, guardarla en la sesión
                    $_SESSION['CategoriaAñadida'] = "failed";
                    $_SESSION['ErrorCategoria'] = $validacion['error'];
                    $_SESSION['Categoria'] = $nombre;
                    // Redirigir a la vista de agregar categoría
                    $this->pages->render('categoria/añadirCategoria', ['Categoria' => $nombre]);
                }
            } else {
                $_SESSION['CategoriaAñadida'] = "failed";
                $this->pages->render('categoria/añadirCategoria', ['Categoria' => $nombre]);
            }
        } else {
            $_SESSION['CategoriaAñadida'] = "failed";
            $this->pages->render('categoria/añadirCategoria', ['Categoria' => $nombre]);
        }

        // Desconectarse de la base de datos
        $categoria->desconecta();
    }

    /**
     * Renderizar la página de edición de categorías.
     */
    public function editarCategoria($id)
    {
        if (!isset($id)) {
            $_SESSION['CategoriaActualizada'] = "failed";
            $this->pages->render('categoria/categoriaAdmin');
        }else{
            $categoria = Categoria::getById($id);
            $this->pages->render('categoria/editarCategoria', ['Categoria' => $categoria]);
        }
       
    }



    /**
     * Actualizar una categoría basada en el envío del formulario.
     */
    public function actualizarCategoria()
    {
        // Verificar si el formulario se envió mediante POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar si se proporcionaron datos de categoría
            if ($_POST['categoria']) {
                $categoriaData = $_POST['categoria'];
                $nombre = $categoriaData['nombre'];
                $id = $categoriaData['id'];
                $categoria = new Categoria();
                $categoria->setId($id);

                // Validar la categoría
                $validacion = $categoria->validaCategoria($nombre);

                if ($validacion['isValid']) {
                    // Sanitizar y establecer el nombre de la categoría
                    $nombre = $categoria->sanearCategoria($nombre);
                    $categoria->setNombre($nombre);

                    // Si la categoría es válida, actualizarla en la base de datos
                    $save = $categoria->update();
                    if ($save) {
                        $_SESSION['CategoriaActualizada'] = "complete";
                        $this->pages->render('categoria/categoriaAdmin');
                    } else {
                        $_SESSION['CategoriaActualizada'] = "failed";
                        $this->pages->render('categoria/editarCategoria', ['Categoria' => $categoriaData]);
                    }
                } else {
                    // Si la categoría no es válida, guardarla en la sesión
                    $_SESSION['CategoriaActualizada'] = "failed";
                    $_SESSION['ErrorCategoria'] = $validacion['error'];
                    $_SESSION['Categoria'] = $categoriaData;
                    // Redirigir a la vista de edición de categoría
                    $this->pages->render('categoria/editarCategoria', ['Categoria' => $categoriaData]);
                }
            } else {
                $_SESSION['CategoriaActualizada'] = "failed";
                $this->pages->render('categoria/editarCategoria', ['Categoria' => $categoriaData]);
            }
        } else {
            $_SESSION['CategoriaActualizada'] = "failed";
            $this->pages->render('categoria/editarCategoria', ['Categoria' => $categoriaData]);
        }

        // Desconectarse de la base de datos
        $categoria->desconecta();
    }

    /**
     * Borrar una categoría basada en el ID proporcionado.
     */
    public function borrarCategoria($id)
    {
        if (isset($id)) {
            $categoria = new Categoria();
            $categoria->setId($id);
            $delete = $categoria->delete();

            if ($delete) {
                $_SESSION['CategoriaBorrada'] = "complete";
            } else {
                $_SESSION['CategoriaBorrada'] = "failed";
            }
        } else {
            $_SESSION['CategoriaBorrada'] = "failed";
        }

        // Desconectarse de la base de datos
        $categoria->desconecta();
        $this->pages->render('categoria/categoriaAdmin');
    }
}
