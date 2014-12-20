<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR;

class LocalizedCurrencyTest extends \PHPUnit_Framework_TestCase
{
	static private $currency;
	static private $localized;

	static public function setUpBeforeClass()
	{
		self::$currency = new Currency(get_repository(), 'IEP');
		self::$localized = new LocalizedCurrency(self::$currency, get_repository()->locales['fr']);
	}

	public function test_get_name()
	{
		$this->assertEquals("livre irlandaise", self::$localized->name);
		$this->assertEquals("livre irlandaise", self::$localized->get_name(1));
		$this->assertEquals("livres irlandaises", self::$localized->get_name(10));
	}

	public function test_get_symbol()
	{
		$this->assertEquals("£IE", self::$localized->symbol);
	}

	public function test_localize()
	{
		$localized = self::$currency->localize('en-US');
		$this->assertInstanceOf('ICanBoogie\CLDR\LocalizedCurrency', $localized);
		$this->assertEquals("Irish Pound", $localized->name);
	}

	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format($currency_code, $locale_code, $number, $expected)
	{
		$currency = new Currency(get_repository(), $currency_code);
		$localized = $currency->localize($locale_code);
		$this->assertEquals($expected, $localized->format($number));
	}

	public function provide_test_format()
	{
		return array(

			array('IEP', 'fr', 123456.789, "123 456,79 £IE"),
			array('IEP', 'en', 123456.789, "IEP123,456.79"),
			array('EUR', 'fr', 123456.789, "123 456,79 €"),
			array('EUR', 'en', 123456.789, "€123,456.79"),
			array('USD', 'fr', 123456.789, "123 456,79 \$US"),
			array('USD', 'en', 123456.789, "\$123,456.79"),

		);
	}
}