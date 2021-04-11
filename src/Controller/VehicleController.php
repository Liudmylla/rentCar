<?php

namespace App\Controller;

use App\Model\VehicleManager;

class VehicleController extends AbstractController
{

    /**
     * List items
     */
    public function index(): string
    {
        $itemManager = new VehicleManager();
        $items = $itemManager->selectAll('description');

        return $this->twig->render('Vehicle/index.html.twig', ['items' => $items]);
    }


    /**
     * Show informations for a specific item
     */
    public function show( int $id)
    { 
       
        $itemManager = new VehicleManager();
        $item = $itemManager->selectOneVehicleById($id);
       

        return $this->twig->render('Vehicle/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $itemManager = new VehicleManager();
        $item = $itemManager->selectOneVehicleById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);
            header('Location: /vehicle/show/' . $id);
        }

        return $this->twig->render('Vehicle/edit.html.twig', [
            'item' => $item,
        ]);
    }


    /**
     * Add a new item
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $itemManager = new VehicleManager();
            $id = $itemManager->insert($item);
            //           header('Location:/vehicle/show/' . $id);
            header('Location:/vehicle/index/');
        }

        return $this->twig->render('Vehicle/add.html.twig');
    }


    /**
     * Delete a specific item
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemManager = new VehicleManager();
            $itemManager->deleteVehicle($id);
            header('Location:/vehicle/index');
        }
    }
}
