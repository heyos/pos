<?php

class ControladorPlantilla{

	static public function ctrPlantilla(){

		include "vistas/plantilla.php";

	}

	public static function baseUrl(){

        $base = dirname($_SERVER["SCRIPT_NAME"]);
        $base = str_replace('\\','/',$base);
        
        if ($base == '/') { 
            $base = NULL; 
        }

        define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].$base.'/');
    }


}