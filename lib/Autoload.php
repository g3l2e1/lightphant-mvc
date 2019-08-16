<?php 

class Autoload {
  public function __construct() {
    spl_autoload_extensions('.php');
    spl_autoload_register(array($this, 'load'));  
  }
  private function load($className) {
    $extension = spl_autoload_extensions();
    $structure = unserialize(LP_STRUCTURE);
    
    $require = null;
    for ($i = 0; $i < count($structure); $i ++) {
      if (file_exists($structure[$i] . $className . $extension)) {
        $require = $structure[$i] . $className . $extension;
      }
    }

    if(file_exists($className . $extension)){
      $require = $className . $extension;
    }

    if ($require == null) {
    	throw new Exception("Autoload Error: Classe {$className} não existe ou está em uma estrtura não incluida na constante LP_STRUCTURE em config.php", 1);    	
    }

    require_once($require);
  }
}

$autoload = new Autoload();
