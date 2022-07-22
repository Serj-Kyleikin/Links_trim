<?php

namespace application\core;

use libraries\ScanURL;
use libraries\Log;
use PDO;

class Model {

	protected $connection;      // Дескриптор подключения
    protected $log;             // Объект логирования
	protected $scanner;         // Объект сканера ссылок

    public $address;
	public $letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

	public function __construct($route = []) {

        $connection = require $_SERVER['DOCUMENT_ROOT'] . '/configurations/connection.php';

        $options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
		 ];

		 try { 
			 $this->connection = new PDO('mysql:host='.$connection['host'].';dbname='.$connection['db_name'].'', $connection['user'], $connection['password'], $options);
		 } catch (\PDOException $e) {
			 
		 }

		if($route == []) {

			$this->getlibraries();
			$this->getURL();

		} else $this->checkURL($route);
	}

	// Загрузка библиотек

	public function getlibraries() {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/libraries/Log.php';       // Логирование
		$this->log = new Log;

		require_once $_SERVER['DOCUMENT_ROOT'] . '/libraries/ScanURL.php';   // Сканирование ссылок
		$this->scanner = new ScanURL;
	}

    // Проверка нужен ли редирект

	public function checkURL($route) {

		if($route != 'main') {

			$address['link'] = $route;

			$check = $this->connection->prepare('SELECT * FROM links WHERE short_link=:link ORDER BY short_link LIMIT 1');
			$check->execute($address);

			$result = $check->fetch(PDO::FETCH_ASSOC);
			$url = ($result != '') ? html_entity_decode($result['original_link']) : '/';

			header('location: ' . $url);
		}
	}

	// Поиск переданного URL в БД

	public function getURL() {

		$url = strtolower($_POST['url']);
		$checkURI = $this->scanner->scanUrl($url);
		
		if($checkURI == 'clear') {

			$check['link'] = $this->correctURL($url);

			$checkURL = $this->connection->prepare('SELECT short_link FROM links WHERE original_link=:link ORDER BY original_link LIMIT 1');
			$checkURL->execute($check);
			$result = $checkURL->fetch(PDO::FETCH_ASSOC)['short_link'];

			if($result != '') echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $result;
			else $this->saveURL($check['link']);

		} elseif($checkURI == 'infected') {
			echo 0;
		} else {
			$this->log->write('api_errors.txt', 'Статус ответа: ' . $checkURI);
			echo 1;
		}
	}

	// Формирование единого вида для всех вариантов URL

	public function correctURL($url) {

		if(preg_match('#^http#', $url)) {
			if(!preg_match('#://www#', $url)) $url = 'http://www.' . explode('://', $url)[1];
		} elseif(!preg_match('#^www\W#', $url)) {
			$url = 'http://www.' . $url;
		}

		return htmlentities($url, ENT_QUOTES, 'UTF-8');
	}

	// Сохранение URL

	public function saveURL($url) {

		// Получение последнего значения из БД

		$get = $this->connection->prepare('SELECT short_link FROM links ORDER BY short_link DESC LIMIT 1');
		$get->execute();

		$last = $get->fetch(PDO::FETCH_ASSOC)['short_link'];

		// Обработка последнего значения из БД

		$address = ($last == '') ? 'aaaaa' : $this->formURL($last);

		// Запись новой ссылки

		$data['short_link'] = $address;
		$data['original_link'] = $url;

		$sentLink = $this->connection->prepare("INSERT INTO links (original_link, short_link) VALUES(:original_link, :short_link)");
		$sentLink->execute($data);

		echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $address;
	}

	// Формирование нового URL

	public function formURL($last) {

		$this->address = str_split($last);
		$this->scan(4);
		return implode('', $this->address);
	}

	// Поиск изменяемого значения

	public function scan($key) {

		foreach($this->letters as $l_key => $letter) {
			if($this->address[$key] == $letter) {

				if($letter == 'z') {
					$this->scan($key - 1);
				} else {
					$this->address[$key] = $this->letters[$l_key + 1];
					for($i = $key; $i < 5; $i++) if($i != $key) $this->address[$i] = 'a';
            		break;
				}
			}
		}
	}
}