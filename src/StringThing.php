<?php

namespace Maxoplata;

class StringThing
{
	private $pattern = [];
	private $opFunctionMap = null;

	private $patternOpMap = [
		'split-halves' => '0',
		'reverse' => '1',
		'shift' => '2',
		'swap-case' => '3',
		'rotate' => '4',
	];

	public function __construct($pattern = ['split-halves', 'reverse', 'shift', 'swap-case', 'rotate'])
	{
		$patternString = implode('', array_map(function ($op) {
			return $this->patternOpMap[$op];
		}, $pattern));

		foreach ($this->errorChecks() as $error) {
			if (strpos($patternString, $error['match']) !== false) {
				throw new \Exception("Error: ${error['description']}");
			}
		};

		$this->pattern = $pattern;
		$this->opFunctionMap = $this->opFunctionMap();
	}

	public function encode($text)
	{
		return array_reduce($this->pattern, function ($newText, $op) {
			return isset($this->opFunctionMap[$op]) ? $this->opFunctionMap[$op]($newText, false) : $newText;
		}, $text);
	}

	public function decode($text)
	{
		return array_reduce(array_reverse($this->pattern), function ($newText, $op) {
			return isset($this->opFunctionMap[$op]) ? $this->opFunctionMap[$op]($newText, true) : $newText;
		}, $text);
	}

	private function opFunctionMap()
	{
		return [
			'split-halves' => function ($text, $decode = false) {
				return substr(
					$text,
					$decode
						? floor(strlen($text) / 2)
						: ceil(strlen($text) / 2),
				) . substr(
					$text,
					0,
					$decode ? floor(strlen($text) / 2) : ceil(strlen($text) / 2),
				);
			},
			'reverse' => function ($text, $decode = false) {
				return strrev($text);
			},
			'shift' => function ($text, $decode = false) {
				return $this->shift($text, !$decode ? 1 : -1);
			},
			'swap-case' => function ($text, $decode = false) {
				return implode(
					'',
					array_map(function ($char) {
						return ctype_upper($char) ? strtolower($char) : strtoupper($char);
					}, str_split($text)),
				);
			},
			'rotate' => function ($text, $decode = false) {
				return $decode
					? substr($text, 1) . substr($text, 0, 1)
					: substr($text, -1) . substr($text, 0, -1)
				;
			},
		];
	}

	private function shift($text, $shift = 1)
	{
		$chars = array_map(function ($i) {
			return chr($i + 32);
		}, range(0, 94));

		$charsMaxIndex = count($chars) - 1;

		return implode('', array_map(function ($char) use ($chars, $charsMaxIndex, $shift) {
			$index = array_search($char, $chars);

			if ($index !== false) {
				$newIndex = $index + $shift;

				if ($newIndex > $charsMaxIndex) {
					return $chars[$newIndex - $charsMaxIndex - 1];
				} elseif ($newIndex < 0) {
					return $chars[$charsMaxIndex + $newIndex + 1];
				}

				return $chars[$newIndex];
			}

			return $char;
		}, str_split($text)));
	}

	private function errorChecks()
	{
		return [
			[
				'description' => "Using 'split-halves' back-to-back results in the original string",
				'match' => '00',
			],
			[
				'description' => "Using 'reverse' back-to-back results in the original string",
				'match' => '11',
			],
			[
				'description' => "Using 'shift' 95 times results in the original string. Using 'shift' more than 95 times is the same as using using it X - 95 times.",
				'match' => str_repeat('2', 95),
			],
			[
				'description' => "Using 'swap-case' back-to-back results in the original string",
				'match' => '33',
			],
		];
	}
}
