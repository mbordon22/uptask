<?php

//Conexion a la base de datos
$conn = new mysqli("localhost", "root", "", "uptask");

//Codificacion UTF8
$conn-> set_charset("utf8");