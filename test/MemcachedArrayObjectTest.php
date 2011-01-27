<?php
require_once 'PHPUnit/Framework.php';

require_once '../src/MemcachedArrayObject.php';

class MemcachedArrayObjectTest extends PHPUnit_Framework_TestCase {
	public function testInit() {
		$map = new MemcachedArrayObject();
	}

	public function testGet() {
		$map = new MemcachedArrayObject();
		$value = $map['TEST']; 
		$this->assertEquals("", $value);
	}

	public function testSet() {
		$map = new MemcachedArrayObject();
		$map['TEST'] = "";
	}

	public function testSetAndGet() {
		$map = new MemcachedArrayObject();
		$map['TEST'] = "OK";
		$this->assertEquals("OK", $map['TEST']);
		$map['TEST'] = "";
		$this->assertEquals("", $map['TEST']);
	}

	public function testExplicitServer() {
		$map = new MemcachedArrayObject(array("127.0.0.1:11211"));
		$map['TEST'] = "SERVER";
		$this->assertEquals("SERVER", $map['TEST']);
		$map['TEST'] = "";
		$this->assertEquals("", $map['TEST']);
	}

	public function testDeadServerConnection() {
		$map = new MemcachedArrayObject(array("1.1.1.1:11211"));
		try {
			$map['TEST'] = "SERVER";
		}
		catch(Exception $e) {
			echo $e->getMessage();
		}
	}
}

?>
