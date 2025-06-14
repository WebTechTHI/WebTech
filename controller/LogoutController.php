
<?php
class LogoutController{
    public function handleRequest() {
        
        session_unset();
        session_destroy();

        require "view/LogoutView.php";
    }
}