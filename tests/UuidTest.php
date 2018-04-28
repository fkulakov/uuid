<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Uuid\Uuid;

/**
 * Class UuidTest
 */
class UuidTest extends TestCase
{
    private const UUID_REGEXP = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/';

    public function testFromSource()
    {
        $source    = random_bytes(16);
        $prev_uuid = Uuid::source($source)->generate();
        foreach (array_fill(0, 10, $source) as $similar_source) {
            $uuid = Uuid::source($similar_source)->generate();
            $this->assertEquals($uuid, $prev_uuid);
            $this->assertRegExp(self::UUID_REGEXP, $uuid);
            $prev_uuid      = $uuid;
            $another_source = random_bytes(16);
            $uuid           = Uuid::source($another_source)->generate();
            $this->assertNotEquals($uuid, $prev_uuid);
            $this->assertRegExp(self::UUID_REGEXP, $uuid);
        }
    }

    public function testNamespaceBasedUuids()
    {
        $correct_namespaces  = [
            '6ba7b811-9dad-11d1-80b4-00c04fd430c8',
            '6ba7b812-9dad-11d1-80b4-00c04fd430c8',
            '6ba7b814-9dad-11d1-80b4-00c04fd430c8',
            '6ba7b815-9dad-11d1-80b4-00c04fd430c8',
            '6ba7b816-9dad-11d1-80b4-00c04fd430c8',
            '6ba7b817-9dad-11d1-80b4-00c04fd430c8',
            '',
            'some namespace'
        ];
        $source              = random_bytes(16);
        $prev_namespace_uuid = Uuid::source($source)->generate();
        foreach ($correct_namespaces as $correct_namespace) {
            $uuid = Uuid::source($source)->setNamespace($correct_namespace)->generate();
            $this->assertRegExp(self::UUID_REGEXP, $uuid);
            $next_uuid = Uuid::source($source)->setNamespace($correct_namespace)->generate();
            $this->assertRegExp(self::UUID_REGEXP, $next_uuid);
            $this->assertEquals($uuid, $next_uuid);
            $this->assertNotEquals($uuid, $prev_namespace_uuid);
            $prev_namespace_uuid = $uuid;
        }
    }

    public function testRandom()
    {
        $prev_uuid = Uuid::random()->generate();
        foreach (range(0, 10) as $value) {
            $uuid = Uuid::random()->generate();
            $this->assertNotEquals($uuid, $prev_uuid);
            $this->assertRegExp(self::UUID_REGEXP, $uuid);
        }
    }
}
