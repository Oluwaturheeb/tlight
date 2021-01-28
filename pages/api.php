<?php require_once 'Autoload.php';

API::post('create', function () {
	print_r(API::data());
});

API::put('create', function () {
	print_r(API::data());
});

API::delete('create/__id/__items', function () {
	print_r(API::data());
});

API::get('create/__id/__item', function () {
	print_r(API::data());
});



?>