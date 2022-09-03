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

        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        $url = $protocol.$domain;

        define('BASE_URL',$url.$base.'/');

    }


}