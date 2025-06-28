
<?php
//LAURIN SCHNITZER 

class LogoutController{
  
    public function handleRequest() {
        
        session_unset();     //Inhalte löschen aus der session
        session_destroy();  //session selbst löschen (session destroy lscht nicht inhalte von session deswegen unset wichtig)
          //mit inhalte meine ich die variablen ===> dh. die variablen der session werden ncith geleert im skript sondern existieren noch weiter !!!!

        require "view/LogoutView.php";
    }
}