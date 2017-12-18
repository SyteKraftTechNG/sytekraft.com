<?php

include_once 'application/init.php';

echo "<h1>This is SyteKraft MODEL generator.</h1>";

DataSource::init();

$tables = DataSource::tables();
echo "<pre>";
$dir = APP. "/models/";
$apiDir = APP. "/apis/";
echo exec('whoami');

if (!file_exists($dir)) {
	chmod(APP, 0777);
	mkdir($dir, 0777, true);
}

if (!file_exists($apiDir)) {
	chmod(APP, 0777);
	mkdir($apiDir, 0777, true);
}


foreach ($tables as $table) {
	$name = ucfirst($table);
	$name = Word::singularize($name);
	$apiName = Word::pluralize($name). "API";

	$openerLines = $constructorLines = $propertyLines = $getterSetterLines = array();
	$openerLines[] = "<?php";
	$openerLines[] = "class $name extends Model";
	$openerLines[] = "{";
	$opener = join(PHP_EOL, $openerLines);
	
	$constructorLines[] = "	function __construct(\$id = 0) {";
	$constructorLines[] = "		parent::__construct(__CLASS__, \$id);";
	$constructorLines[] = "	}";
	$constructor = join(PHP_EOL, $constructorLines);
	
	$fields = DataSource::fieldNames($table);
	
	foreach ($fields as $field) {
		if ($field != 'id') {
			$propertyLines[] = "	private $". $field. ";";
			
			$suffix = ucfirst($field);
			
			$getter = "	public function get$suffix() {". PHP_EOL;
			$getter .= "		return \$this->{$field};". PHP_EOL;
			$getter .= "	}". PHP_EOL;
			
			$setter = "	public function set$suffix(\${$field}) {". PHP_EOL;
			$setter .= "		\$this->{$field} = \${$field};". PHP_EOL;
			$setter .= "	}". PHP_EOL;
			
			$axmut = $getter. PHP_EOL;
			$axmut .= $setter;
			$getterSetterLines[] = $axmut;
		}
	}
	$properties = join(PHP_EOL, $propertyLines);
	$getterSetters = join(PHP_EOL, $getterSetterLines);
	$trait = "	use CommonDataModel;";
	
	$out = $opener. PHP_EOL. PHP_EOL;
	$out .= $properties. PHP_EOL. PHP_EOL;
	$out .= $trait. PHP_EOL. PHP_EOL;
	$out .= $constructor. PHP_EOL. PHP_EOL;
	$out .= $getterSetters. PHP_EOL;
	
	$out .= "}". PHP_EOL;

	$filename = $dir . $name . ".php";
	if (!file_exists($filename)) {
		$file = fopen($filename, 'w') or die('Cannot open file:  '. $filename);

		$time = time();
		if ($file) {
			if (ob_get_level() == 0) ob_start();
			echo "Creating $name class... ";
			fwrite($file, $out);

			ob_flush();
			flush();
			sleep(1);
			$dtime = time() - $time;
			echo "$name class created in {$dtime}ms. <br>";
			$time = time();
		}

		chmod($filename, 0755);
	}

	$apiOpenerLines = $apiConstructorLines = array();
	$apiOpenerLines[] = "<?php";
	$apiOpenerLines[] = "class $apiName extends API";
	$apiOpenerLines[] = "{";
	$apiOpener = join(PHP_EOL, $apiOpenerLines);

	$apiConstructorLines[] = "	function __construct(\$uri) {";
	$apiConstructorLines[] = "		parent::__construct(\$uri);";
	$apiConstructorLines[] = "		\$this->loadModel();";
	$apiConstructorLines[] = "	}";
	$apiConstructor = join(PHP_EOL, $apiConstructorLines);

	$trait = "	use CommonDataAPI;";

	$out = $apiOpener. PHP_EOL. PHP_EOL;
	$out .= $trait. PHP_EOL. PHP_EOL;
	$out .= $apiConstructor. PHP_EOL. PHP_EOL;

	$out .= "}". PHP_EOL;

	$filename = $apiDir . $apiName . ".php";
	if (!file_exists($filename)) {
		$file = fopen($filename, 'w') or die('Cannot open file:  '. $filename);

		$time = time();
		if ($file) {
			if (ob_get_level() == 0) ob_start();
			echo "Creating $apiName class... ";
			fwrite($file, $out);

			ob_flush();
			flush();
			sleep(1);
			$dtime = time() - $time;
			echo "$apiName class created in {$dtime}ms. <br>";
			$time = time();
		}

		chmod($filename, 0755);
	}


}
