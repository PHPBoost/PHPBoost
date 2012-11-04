<?php
require_once '../kernel/framework/util/Bench.class.php';

function process_array_ref(array &$array) { $count = count($array); for ($i = 0; $i < $count; $i++); }
function process_array_cp(array $array) { $count = count($array); for ($i = 0; $i < $count; $i++); return $array; }
function process_array_ref_with_modifs(array &$array) { $count = count($array); for ($i = 0; $i < $count; $i++) { $array[] = $i; } }
function process_array_cp_with_modifs(array $array) { $count = count($array); for ($i = 0; $i < $count; $i++) { $array[] = $i; } return $array; }

function string_by_ref(&$string) { $string2 = $string; }
function string_by_cp($string) { $string2 = $string; }

function bench_array($array_iterations, $array_size) {
	echo '<h1>Array</h1>';
	$bench = new Bench();
	$bench->start();
	for ($i = 0; $i < $array_iterations; $i++) {
		$array = range(0, $array_size);
		process_array_ref($array);
	}
	$bench->stop();
	echo 'Ref: ' . $bench->to_string() . '<hr />';

	$bench = new Bench();
	$bench->start();
	for ($i = 0; $i < $array_iterations; $i++) {
		$array = range(0, $array_size);
		$array = process_array_cp($array);
	}
	$bench->stop();
	echo 'Copy: ' . $bench->to_string() . '<hr />';

	$bench = new Bench();
	$bench->start();
	for ($i = 0; $i < $array_iterations; $i++) {
		$array = range(0, $array_size);
		process_array_ref_with_modifs($array);
	}
	$bench->stop();
	echo 'Ref & modif: ' . $bench->to_string() . '<hr />';

	$bench = new Bench();
	$bench->start();
	for ($i = 0; $i < $array_iterations; $i++) {
		$array = range(0, $array_size);
		$array = process_array_cp_with_modifs($array);
	}
	$bench->stop();
	echo 'Copy & modif: ' . $bench->to_string() . '<hr />';
}

function bench_string($string_size, $string_iteration) {
	echo '<h1>String</h1>';
	$string = '';
	for ($i = 0; $i < $string_size; $i++) {
		$string .= '.';
	}

	$bench = new Bench();
	$bench->start();
	for ($i = 0; $i < $string_iteration; $i++) {
		string_by_ref($string);
	}
	$bench->stop();
	echo 'Ref: ' . $bench->to_string() . '<hr />';

	$bench = new Bench();
	$bench->start();
	for ($i = 0; $i < $string_iteration; $i++) {
		string_by_cp($string);
	}
	$bench->stop();
	echo 'Copy: ' . $bench->to_string() . '<hr />';
}


$array_iterations = 1000;
$array_size = 5000;
bench_array($array_iterations, $array_size);

$string_size = 100000;
$string_iteration = 1000;
bench_string($string_size, $string_size);

?>