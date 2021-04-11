<?php

namespace App\Controller;

use App\Model\ReservationManager;
use App\Model\VehicleManager;
use DateTime;

class ReservationController extends AbstractController
{
    private int $agencyId;
    private int $categoryId;
    private string $dateStart;
    private string $dateEnd;

    public function PostValidation()
    {
        if (isset($_POST) && !empty($_POST)) {
            $dateStart = new DateTime($_POST['datestart']);
            $dateEnd = new DateTime($_POST['dateend']);
            $dateCurrent = new DateTime();
            $dateS = $dateStart->getTimestamp();
            $dateE = $dateEnd->getTimestamp();
            $dateC = $dateCurrent->getTimestamp();
            $errMessage = '';
            if ($dateS <= $dateC) {
                $errMessage = "Start date must be after current date !";
            } elseif ($dateS >= $dateE) {
                $errMessage = "Start date must be before end date !";
            }

            // // if(!isset($_SESSION)){
            // //     session_start();
            // //     $this->twig->addGlobal('session', $_SESSION);
            // // }

            $_SESSION['reservation'] = [
            'agencyId' => $_POST['agency_id'],
            'datestart' => $_POST['datestart'],
            'dateend' => $_POST['dateend'],
            'categoryId' => $_POST['category_id']
            ];
            if ($errMessage !== '') {
                $_SESSION['reservation'] ['error'] = $errMessage;
                header('Location: /');
            }
            $this->agencyId = $_POST['agency_id'];
            $this->dateStart = $_POST['datestart'];
            $this->dateEnd = $_POST['dateend'];
            $this->categoryId = $_POST['category_id'];
        } else {
            if (isset($_SESSION['reservation'])) {
                $this->agencyId = $_SESSION['reservation']['agencyId'];
                $this->dateStart = $_SESSION['reservation']['datestart'];
                $this->dateEnd = $_SESSION['reservation']['dateend'];
                $this->categoryId = $_SESSION['reservation']['categoryId'];
            }
        }
    }


    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $itemManager = new ReservationManager();
        $item = $itemManager->selectOneVehicle($id);

        return $this->twig->render('Reservation/show.html.twig', ['item' => $item]);
    }

    /**
     * Validate a specific item
     */
    public function validation(int $id): string
    {
        $vehicleManager = new VehicleManager;
        $vehicle = $vehicleManager->selectOneVehicleById($id);
        $itemManager = new ReservationManager();
        $item = $itemManager->selectOneVehicle($id);
      

        if (isset($_SESSION['reservation'])) {
            $item['dateStart'] = $_SESSION['reservation'] ['datestart'];
            $item['dateEnd'] = $_SESSION['reservation'] ['dateend'];
            $item['totalCost'] = $this->calcCost($item['price'], $item['dateStart'], $item['dateEnd']);
            $item['nbDays'] = $item['totalCost'] / $item['price'];
            $_SESSION['reservation'] ['agencyId'] = $item['agency_id'];
            $_SESSION['reservation'] ['agency'] = $item['name'];
            $_SESSION['reservation'] ['brand'] = $item['brand'];
            $_SESSION['reservation'] ['model'] = $item['model'];
            $_SESSION['reservation'] ['image'] = $vehicle['image'];
            $_SESSION['reservation'] ['color'] = $item['color'];
            $_SESSION['reservation'] ['price'] = $item['price'];
            $_SESSION['reservation'] ['categname'] = $item['description'];
            $_SESSION['reservation'] ['category'] = $item['category'];
            $_SESSION['reservation'] ['totalCost'] = $item['totalCost'];
        };
        // TODO validations (length, format...)

        // if validation is ok, update and redirection
        return $this->twig->render('Reservation/validation.html.twig', [
            'item' => $item
        ]);
    }
    /**
         * Validate a specific item
         */
    public function calcCost(float $dayCost, string $dateStart, string $dateEnd): float
    {
        $date1 = new DateTime($dateStart);
        $date2 = new DateTime($dateEnd);

        $interval = $date1->diff($date2);
        $totalCost = $interval->days * $dayCost;
        return $totalCost;
    }

    /**
     * Insert the rent table
     */
    public function payment(int $id): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['loginId']) || empty($_SESSION['loginId'])) {
                $_SESSION['position'] = '/reservation/payment/' .$id;
                header('Location: /user/login');
                exit();
            };
            // clean $_POST data
            $currentDate = new DateTime('now');
            $formatDate = $currentDate->format('Y-m-d');
            // var_dump($_SESSION);
            // die();
            $item ['userId'] = $_SESSION['loginId'];
            $item ['vehicleId'] = $id;
            $item ['reduction'] = 0;
            $item ['dateCreation'] = $formatDate;
            $item ['dateStart'] = $_SESSION['reservation'] ['datestart'];
            $item ['dateEnd'] = $_SESSION['reservation'] ['dateend'];
            $item ['totalAmount'] = $_SESSION['reservation'] ['totalCost'];
            $item ['agency'] = $_SESSION['reservation'] ['agency'];

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $reservationManager = new ReservationManager();
            $id = $reservationManager->insert($item);

            $item ['description'] = $_SESSION['reservation'] ['categname'];
            $item ['brand'] = $_SESSION['reservation'] ['brand'];
            $item ['model'] = $_SESSION['reservation'] ['model'];
            $item ['image'] = $_SESSION['reservation'] ['image'];


            return $this->twig->render('Reservation/payment.html.twig', [
            'item' => $item
            ]);
        };
    }
    public function inInterval(string $date, array $rent): bool
    {
        $dateStart = new DateTime($rent['date_start']);
        $dateEnd = new DateTime($rent['date_end']);
        $dateVerif = new DateTime($date);

        $hdateStart = $dateStart->getTimestamp();
        $hdateEnd = $dateEnd->getTimestamp();
        $hdateVerif = $dateVerif->getTimestamp();
        if ($hdateVerif >= $hdateStart && $hdateVerif <= $hdateEnd) {
            // echo "date verif " . $date . " >= rented date start ". $rent['date_start'] . " and <= date end  " . $rent['date_end'] . "<br>";
            // echo "date verif " . $hdateVerif . " >= rented date start ". $hdateStart . " and <= date end " . $hdateEnd . "<br>";
        } else {
            // echo "ok --> date verif " . $date . " < rented date start ". $rent['date_start'] . " or > date end  " . $rent['date_end'] . "<br>";
            // echo "ok --> date verif " . $hdateVerif . " < rented date start ". $hdateStart . " or > date end " . $hdateEnd . "<br>";
        };
        
        return $hdateVerif >= $hdateStart && $hdateVerif <= $hdateEnd;
    }
    /**
    * List items
    */
    public function index(): string
    {
        $itemManager = new ReservationManager();
        $this->PostValidation();
        $items = $itemManager->selectAgencyVehicles($this->agencyId, $this->categoryId);
        $availableVehicles = [];
        foreach ($items as $key => $vehicle) {
            $vehicleId = $vehicle['id_vehicle'];

            $rentHistory = $itemManager->selectRentedHistory($vehicleId);
            $available = 'ok';

            foreach ($rentHistory as $key2 => $rent) {
                if ($available === 'ok') {
                    $condition1 = $this->inInterval($this->dateStart, $rent);
                    $condition2 = $this->inInterval($this->dateEnd, $rent);
                    $available = ($condition1 || $condition2) ? 'no' : 'ok';
                    // echo "- $available - <br>";
                }
            }
            
            if ($available === 'ok') {
                $availableVehicles[$key] = $vehicle;
            }
        }
        return $this->twig->render('Reservation/index.html.twig', ['items' => $availableVehicles]);
    }
}
