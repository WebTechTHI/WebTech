<?php
session_start();

//Prüfen ob page paramenter da is
$page = $_GET["page"] ?? "home";


switch ($page) {
    case "registration":
        require_once "controller/RegistrationController.php";
        $controller = new RegistrationController();
        $controller->handleRequest();
        exit; //damit restliche html nicht noch auch geladen wird

    case "login":
        require_once "controller/LoginController.php";
        $controller = new LoginController();
        $controller->handleRequest();
        exit;

    case "user":
        require_once "controller/UserController.php";
        $controller = new UserController();
        $controller->handleRequest();
        exit;


    case "logout":
        require_once "controller/LogoutController.php";
        $controller = new LogoutController();
        $controller->handleRequest();
        exit;

    case "about":
        require_once "controller/AboutController.php";
        $controller = new AboutController();
        $controller->handleRequest();
        exit;

    case "category":
        require_once "controller/CategoryController.php";
        $controller = new CategoryController();
        $controller->handleRequest();
        exit;

    case "product":
        require_once "controller/ProductController.php";
        $controller = new ProductController();
        $controller->handleRequest();
        exit;

    case "wishlist":
        require_once "controller/WishlistController.php";
        $controller = new WishlistController();
        $controller->handleRequest();
        exit;

    case "":
    case "home":
        require_once "controller/HomeController.php";
        $controller = new HomeController();
        $controller->handleRequest();
        exit;

    case "admin":
        $action = $_GET['action'] ?? 'index';
        require_once "controller/AdminController.php";
        $controller = new AdminController();
        $controller->handleRequest($action);
        exit;

    case "cart":
        require_once "controller/CartController.php";
        $controller = new CartController();
        $controller->handleRequest();
        exit;


    default:
        require_once "controller/FileNotFoundController.php";
        $controller = new FileNotFoundController();
        $controller->handleRequest();
        exit;
}
?>