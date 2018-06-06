<?php declare(strict_types=1);

namespace Uuid;

/**
 * Class Uuid
 *
 * @package Uuid
 */
final class Uuid
{
    public const NAMESPACE_DNS  = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_URL  = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_OID  = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';

    private const BYTES_LENGTH  = 16;
    private const VERSION       = 0x50;
    private const CLEAR_VARIANT = 0x3F;
    private const RFC_VARIANT   = 0x80;
    private const CLEAR_VERSION = 0x0F;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $namespace;

    /**
     * Uuid constructor.
     *
     * @param string $source
     */
    private function __construct(string $source)
    {
        $this->source = $source;
        $this->setNamespace(self::NAMESPACE_DNS);
    }

    /**
     * @param string $namespace
     *
     * @return Uuid
     */
    public function setNamespace(string $namespace): Uuid
    {
        $namespace       = preg_replace('/^urn:uuid:/is', '', $namespace);
        $namespace       = preg_replace('/[^a-f0-9]/is', '', $namespace);
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $source
     *
     * @return Uuid
     */
    public static function source(string $source): Uuid
    {
        return new self($source);
    }

    /**
     * @return Uuid
     */
    public static function random(): Uuid
    {
        $source = random_bytes(self::BYTES_LENGTH);

        return new self($source);
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        $binary_namespace = pack('H*', $this->namespace);
        $hash             = sha1($binary_namespace . $this->source, true);
        $uuid             = substr($hash, 0, self::BYTES_LENGTH);
        $uuid[6]          = chr(ord($uuid[6]) & self::CLEAR_VERSION | self::VERSION);
        $uuid[8]          = chr(ord($uuid[8]) & self::CLEAR_VARIANT | self::RFC_VARIANT);
        $uuid             = $this->separateUuid($uuid);

        return $uuid;
    }

    /**
     * @param string $uuid
     *
     * @return string
     */
    private function separateUuid(string $uuid): string
    {
        $result = bin2hex(substr($uuid, 0, 4));
        $result .= '-' . bin2hex(substr($uuid, 4, 2));
        $result .= '-' . bin2hex(substr($uuid, 6, 2));
        $result .= '-' . bin2hex(substr($uuid, 8, 2));
        $result .= '-' . bin2hex(substr($uuid, 10, 6));

        return $result;
    }
}
