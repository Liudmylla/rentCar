<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    //* Add a new user

    public function add()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $userManager = new UserManager();
            $id = $userManager->insert($item);
            // if(!isset($_SESSION)){
            //     session_start();
            // }
            $_SESSION['loginId'] = $id;
            header('Location:/');
        }

        return $this->twig->render('User/add.html.twig');
    }

    public function canAccess(): bool
    {
        // if(!isset($_SESSION)){
        //     session_start();
        //     $this->twig->addGlobal('session', $_SESSION);
        // }
        if (isset($_SESSION['loginRole']) && 'admin' === $_SESSION['loginRole']) {
            return true;
        }
        if (isset($_SESSION['loginId']) && !empty($_SESSION['loginId'])) {
            $id = $_SESSION['loginId'];
            if ('admin' === $this->getRole($id)) {
                $_SESSION['loginRole'] = 'admin';

                return true;
            }
            $_SESSION['loginRole'] = 'user';

            return false;
        }

        return false;
    }

    public function getRole(int $id): string
    {
        $itemManager = new UserManager();
        $item = $itemManager->selectOneById($id);

        return $item['role'];
    }

    /**
     * List items.
     */
    public function index(): string
    {
        $itemManager = new UserManager();
        $items = $itemManager->selectAll('lastname');

        return $this->twig->render('User/index.html.twig', ['items' => $items]);
    }

    /**
     * Show informations for a specific item.
     */
    public function show(int $id): string
    {
        $userManager = new UserManager();
        $item = $userManager->selectOneById($id);

        return $this->twig->render('User/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific item.
     */
    public function edit(int $id): string
    {
        $userManager = new UserManager();
        $item = $userManager->selectOneById($id);

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $userManager->update($item);
            header('Location: /user/show/'.$id);
        }

        return $this->twig->render('User/edit.html.twig', [
            'item' => $item,
        ]);
    }

    public function login()
    {
        $errorMessage = '';

        if (!empty($_POST)) {
            $itemManager = new UserManager();
            $item = $itemManager->selectOneByEmail($_POST['email'], $_POST['password']);
            if ($item) {
                $_SESSION['firstname'] = $item['firstname'];
                $_SESSION['lastname'] = $item['lastname'];
                $_SESSION['role'] = $item['role'];
                $_SESSION['email'] = $item['email'];
                $_SESSION['password'] = $item['password'];
                $_SESSION['loginId'] = $item['id'];

                if ('admin' === $_SESSION['role']) {
                    header('Location: /vehicle/index');

                    exit();
                }

                if ('user' === $_SESSION['role']) {
                    if (isset($_SESSION['position'])) {
                        // $retour = 'Location: ' . $_SESSION['position'];
                        header('Location: /reservation/index');
                    } else {
                        header('Location: /');
                        exit();
                    }
                }
            } else {
                $errorMessage = 'Veuillez inscrire vos identifiants svp !';
                header('Location: /user/signin');
            }
        
           

            exit();
        }

        return $this->twig->render('User/login.html.twig');
    }

    // this function creates a new user if not exist the email
    // else error msg
    public function signin()
    {
        // si login id et pwd ok
        //alors

        //sinon

        $itemManager = new UserManager();

        if (isset($_POST['email']) && !is_null($_POST['email'])) {
            $item = $itemManager->selectOneByEmail($_POST['email'], $_POST['password']);
            if ($item['email'] == $_POST['email']) {
                // if(!isset($_SESSION)){
                //     session_start();
                //     $this->twig->addGlobal('session', $_SESSION);
                // }
                $_SESSION['role'] = $item['role'];
            }
            header('Location: /');
        }

        return $this->twig->render('User/signin.html.twig');
    }

    /**
     * Delete a specific User .
     */
    public function delete(int $id)
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $itemManager = new UserManager();
            $itemManager->delete($id);
            header('Location:/User/index');
        }
    }


    /**
         * Disconnect Session .
         */

   
    public function disconnect()
    {
        session_destroy();
        header('Location:/');
        exit();
    }
}
