<?php

namespace Maxoplata\Tests;

use PHPUnit\Framework\TestCase;
use Maxoplata\StringThing;

class StringThingTest extends TestCase
{
	private $myString = 'This is my string';

	public function testDefaultPattern(): void
	{
		$myStringThing = new StringThing();
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('ZN!TJ!TJIuHOJSUT!', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testSplitHalves(): void
	{
		$myStringThing = new StringThing(['split-halves']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('y stringThis is m', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testReverse(): void
	{
		$myStringThing = new StringThing(['reverse']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('gnirts ym si sihT', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testShift(): void
	{
		$myStringThing = new StringThing(['shift']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('Uijt!jt!nz!tusjoh', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testSwapCase(): void
	{
		$myStringThing = new StringThing(['swap-case']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('tHIS IS MY STRING', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testRotate(): void
	{
		$myStringThing = new StringThing(['rotate']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('gThis is my strin', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testShiftShift(): void
	{
		$myStringThing = new StringThing(['shift', 'shift']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('Vjku"ku"o{"uvtkpi', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testRotateRotate(): void
	{
		$myStringThing = new StringThing(['rotate', 'rotate']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('ngThis is my stri', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	public function testShiftSplitHalvesShiftReverseShiftSwapCaseRotate(): void
	{
		$myStringThing = new StringThing(['shift', 'split-halves', 'shift', 'reverse', 'shift', 'swap-case', 'rotate']);
		$encoded = $myStringThing->encode($this->myString);

		$this->assertEquals('|P#VL#VLKwJQLUWV#', $encoded);
		$this->assertEquals($this->myString, $myStringThing->decode($encoded));
	}

	// Errors

	public function testSplitHalvesSplitHalves(): void
	{
		$this->expectException(\Exception::class);

		$myStringThing = new StringThing(['split-halves', 'split-halves']);
	}

	public function testReverseReverse(): void
	{
		$this->expectException(\Exception::class);

		$myStringThing = new StringThing(['reverse', 'reverse']);
	}

	public function testShift95TimesPlus(): void
	{
		$this->expectException(\Exception::class);

		$myStringThing = new StringThing(array_map(function () {
			return 'shift';
			}, range(0, 94)));
	}

	public function testSwapCaswSwapCase(): void
	{
		$this->expectException(\Exception::class);

		$myStringThing = new StringThing(['swap-case', 'swap-case']);
	}
}
