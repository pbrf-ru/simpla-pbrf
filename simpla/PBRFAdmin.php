<?php

require_once('api/Simpla.php');
require_once('api/Orders.php');
require_once('pbrf/pbrf.php');
$simpla = new Simpla();

//данные отправителя
$from_f7 = array(
	"surname" => "Фамилия",
	"name" => "Имя",
	"patronomic" => "Отчество",

	"country" => "страна",
	"city" => "город",
	"street" => "улица",
	"build" => "дом",
	"appartment" => "кв-ра",
	"zip" => "индекс"
);
//юредическое лицо
$whom_f112 = array(
	"surname" => "Фамилия",
	"name" => "Имя",
	"patronomic" => "Отчество",

	"country" => "страна",
	"city" => "город",
	"street" => "улица",
	"build" => "дом",
	"appartment" => "кв-ра",
	"zip" => "индекс",

	"inn" => "ИНН",
	"kor_account" => "Кор/счет",
	"bank_name" => "Название банка",
	"current_account" => "рас/счет",
	"bik" => "БИК"
);

//создаем класс и указываем ключ доступа к апи pbrf
$pbrf = new PBRF("YOU_ACCESS_TOKEN");

//получаем данные по нужному заказу
$filter["id"] = $_POST["id"];
$order = $_POST["arr"];

//уникальные данные для отдельного бланка
switch($_POST["blank"]){
	case 'F112':
		//создаем данные для получателя
		if($order["country"] != "") 
			$from_contry = $order["country"] . ", ";
		else 
			$from_contry = "";
		if($order["region"] != "") 
			$from_region = $order["region"];
		else 
			$from_region = "";
		if($order["zip"] != 0)
			$from_zip = $order["zip"];
		else
			$from_zip = "";
		
		if($whom_f112["country"] != "") 
			$whom_contry = $whom_f112["country"] . ", ";
		else 
			$whom_contry = "";
		if($whom_f112["region"] != "") 
			$whom_region = "обл. " . $whom_f112["region"] . ", ";
		else 
			$whom_region = "";

		$data['whom_name'] = $whom_f112["surname"] . " " . $whom_f112["name"] . " " . $whom_f112["patronomic"];
		$data['whom_city'] = $whom_contry . $whom_region . "г. " . $whom_f112["city"];
		$data['whom_street'] = $whom_f112["street"];
		$data['whom_build'] = $whom_f112["build"];
		$data['whom_appartment'] = $whom_f112["appartment"];
		$data['whom_zip'] = $whom_f112["zip"];

		$data['from_region'] = $from_contry . $from_region;
		$data["from_name"] = $order["name"] . " " . $order["patronomic"];
		$data["from_surname"] = $order["surname"]; 

		$data['from_city'] = "г. " . $order["city"];
		$data['from_street'] = $order["street"];
		$data['from_build'] = $order["build"];
		$data['from_appartment'] = $order["appartment"];
		$data['from_zip'] = $order["zip"];

		$data["sum_num"] = $order["sum_num"]; //

		$data["inn"] = $whom_f112["inn"];
		$data["kor_account"] = $whom_f112["kor_account"];
		$data["current_account"] = $whom_f112["current_account"];
		$data["bik"] = $whom_f112["bik"];
		$data["bank_name"] = $whom_f112["bank_name"];
		$data["message_part1"] = "Оплата заказа № " . $_POST["id_order"];

	break;

	case 'F7':
		if($from_f7["country"] != "") 
			$from_contry = $from_f7["country"] . ", ";
		else 
			$from_contry = "";
		if($from_f7["region"] != "") 
			$from_region = "обл. " . $from_f7["region"] . ", ";
		else 
			$from_region = "";

		//создаем данные для отправления
		if($order["country"] != "") 
			$whom_contry = $order["country"] . ", ";
		else 
			$whom_contry = "";
		if($order["region"] != "") 
			$whom_region = $order["region"] . ", ";
		else 
			$whom_region = "";
		if($order["zip"] != 0)
			$whom_zip = $order["zip"];
		else
			$whom_zip = "";

		//*
		$data = array( 
			'whom_city' => $whom_contry . $whom_region . "г. " . $order["city"],
			'whom_street' => "ул. " . $order["street"],
			'whom_build' => $order["build"],
			'whom_appartment' => $order["appartment"],
			'whom_zip' => $whom_zip,

			'from_surname' => $from_f7["surname"],
			'from_name' => $from_f7["name"] . " " . $from_f7["patronomic"],
			'from_city' => $from_contry . $from_region . "г. " . $from_f7["city"],
			'from_street' => $from_f7["street"],
			'from_build' => $from_f7["build"],
			'from_appartment' => $from_f7["appartment"],
			'from_zip' => $from_f7["zip"],

			'type_blank' => "a",
			'declared_value' => $order["declared_value"],
			'COD_amount' => $order["cod_amount"],

			'whom_surname' => $order["surname"],
			'whom_name' => $order["name"] . " " . $order["patronomic"]
		);
	break;
}

//получаем ответ от запроса апи
$result = $pbrf->getBlank($_POST["type"], $_POST["blank"], $data);

$res = json_decode($result);

//если есть ошибка по апи вывести ее
if(isset($res->error))	exit($result);

$result = array("error" => 0, "url" => $result);
exit(json_encode($result));

