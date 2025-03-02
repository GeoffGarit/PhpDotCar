<?php 

namespace GeoffGarit\PhpDotCar;

use PHPUnit\Framework\TestCase;
use GeoffGarit\PhpDotCar\Reader;

/**
*  @author GeoffGarit
*/
class ReaderTest extends TestCase
{
	private const ARRAY_KEYS = [
		'ImageFiles',
		'Family',
		'Copyright',
		'Version',
		'Variant',
		'TrimThumbnail',
		'TrimPricing',
		'Trim',
		'Model',
		'TrimDealerPricing',
	];
	private Reader $reader;

	public function setUp(): void
	{
		$this->reader = new Reader();
	}

	/**
	 * @dataProvider getCars
	 *
	 * @param $path
	 */
	public function testParseCar($path): void
	{
		$parsedCar = $this->reader->parseCar($path);

		$this->assertIsArray($parsedCar);
		$this->assertEquals(self::ARRAY_KEYS, array_keys($parsedCar));
		$this->assertEquals('Bymand I', $parsedCar['Model']['Name']);
	}

	/**
	 * Data for parseCar
	 *
	 * @return array
	 */
	public function getCars(): array
	{
		return [
			['./tests/data/bymand.car'],
		];
	}
}