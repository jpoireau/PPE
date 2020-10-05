<?php
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            
            //On établit la connexion
            $bdd = new PDO("mysql:host=$servername;dbname=festival", $username, $password);
        ?>