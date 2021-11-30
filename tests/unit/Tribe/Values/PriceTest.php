<?php

namespace Tribe\Values;

class PriceClassStub extends Abstract_Currency {

	public function set_up_currency_details() {
		return;
	}
}

class PriceTest extends \Codeception\Test\Unit {

	public function test_get_initial_value_returns_unchanged() {
		$initial_value = 10;
		$price         = new PriceClassStub( $initial_value );
		$this->assertEquals( $initial_value, $price->get_initial_representation() );
		$price->set_value( 25 );
		$this->assertEquals( $initial_value, $price->get_initial_representation() );
	}

	/**
	 * @dataProvider numerical_values
	 */
	public function test_normalize_clears_number_formatting( $value, $expected ) {
		$price      = new PriceClassStub();
		$normalized = $price->normalize( $value );
		$this->assertEquals( $expected, $normalized );
	}

	/**
	 * @dataProvider numerical_values
	 */
	public function test_set_value_updates_all_available_formats( $value, $float, $integer, $default_currency_format, $decimal, $string ) {
		$price = new PriceClassStub();
		$price->set_value( $value );
		$this->assertEquals( $float, $price->get_float() );
		$this->assertEquals( $integer, $price->get_integer() );
		$this->assertEquals( $decimal, $price->get_decimal() );
		$this->assertEquals( $string, $price->get_string() );
		$this->assertEquals( $default_currency_format, $price->get_currency() );
	}

	/**
	 * @dataProvider numerical_values
	 */
	public function test_sub_total_multiplies_values( $value, $float, $integer, $default_currency_format, $decimal, $string ) {
		$price = new PriceClassStub();
		$price->set_value( $value );
		$price->sub_total( $float );

		$this->assertEquals( (float) ($integer * $float), $price->get_normalized_value() );
	}

	public function numerical_values() {
		return [
			[ 10, 10.0, 1000, '$10.00', 10.0, '10.00' ],
			[ 10.0, 10.0, 1000, '$10.00', 10.0, '10.00' ],
			[ '10', 10.0, 1000, '$10.00', 10.0, '10.00' ],
			[ 'R$ 10', 10.0, 1000, '$10.00', 10.0, '10.00' ],
			[ 'R$10', 10.0, 1000, '$10.00', 10.0, '10.00' ],
			[ '$ 1.234,56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '$1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1,234.56 .د.م.', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ], // this is RTL
			[ '1,234.56 ฿', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1,234.56 ₺', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1,234.56 ﷼', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 $', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 Ft', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 kr', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 Kč', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 p.', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 zł', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1.234,56 ₫', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ 'HK$ 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ 'kr. 1.234,56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ 'R.1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ 'R$ 1.234,56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ 'RM 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '£1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '¥ 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '¥ 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '₩ 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '₪ 1.234,56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '€1.234,56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '₱ 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '₹ 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '元 1,234.56', 1234.56, 123456, '$1,234.56', 1234.56, '1,234.56' ],
			[ '1e+3', 1000.00, 100000, '$1,000.00', 1000.0, '1,000.00' ],
			[ 'abc.df', 0.0, 0, '$0.00', 0.0, '0.00' ],
			[ 'abcdf', 0.0, 0, '$0.00', 0.0, '0.00' ],
		];
	}
}
