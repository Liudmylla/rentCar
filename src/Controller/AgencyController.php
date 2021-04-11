<?php

namespace App\Controller;

use App\Model\AgencyManager;

class AgencyController extends AbstractController
{
    /**
     * List items
     */
    public function index(): string
    {
        $itemManager = new AgencyManager();
        $items = $itemManager->selectAll('name');

        return $this->twig->render('Agency/index.html.twig', ['items' => $items]);
    }


    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $itemManager = new AgencyManager();
        $item = $itemManager->selectOneById($id);

        return $this->twig->render('Agency/show.html.twig', ['item' => $item]);
    }


    /**
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $itemManager = new AgencyManager();
        $item = $itemManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);
            header('Location: /agency/show/' . $id);
        }

        return $this->twig->render('Agency/edit.html.twig', [
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
            $itemManager = new AgencyManager();
            $id = $itemManager->insert($item);
            header('Location:/agency/show/' . $id);
        }

        return $this->twig->render('Agency/add.html.twig');
    }


    /**
     * Delete a specific item
     */
    // public function delete(int $id)
    // {

    //     echo "Pas possible !";
    //     // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     //     $itemManager = new AgencyManager();
    //     //     $itemManager->delete($id);
      //      header('Location:/agency/index');
        // }
        //}
     }

