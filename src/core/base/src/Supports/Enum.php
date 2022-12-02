<?php

namespace TVHung\Base\Supports;

use BadMethodCallException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

abstract class Enum
{
    /**
     * Store existing constants in a static cache per object.
     */
    protected static array $cache = [];

    protected static $langPath = 'core/base::enums';

    protected mixed $value;

    /**
     * @var array
     */
    protected static array $all = [];

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     * @throws UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        if ($value instanceof static) {
            $this->value = $value->getValue();

            return;
        }

        if ($value !== null && ! $this->isValid($value)) {
            Log::error('Value ' . $value . ' is not part of the enum ' . get_called_class());
        }

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Check if is valid enum value
     */
    public static function isValid($value): bool
    {
        return in_array($value, static::toArray(), true);
    }

    public static function toArray(bool $includeDefault = false): array
    {
        $class = get_called_class();
        if (! isset(static::$cache[$class])) {
            try {
                $reflection = new ReflectionClass($class);
                static::$cache[$class] = $reflection->getConstants();
            } catch (ReflectionException $error) {
                info($error->getMessage());
            }
        }

        $result = static::$cache[$class];

        if (isset($result['__default']) && ! $includeDefault) {
            unset($result['__default']);
        }

        return apply_filters(BASE_FILTER_ENUM_ARRAY, $result, get_called_class());
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     */
    public static function keys(): array
    {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values(): array
    {
        $values = [];

        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array $arguments
     *
     * @return static
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $array = static::toArray();
        if (isset($array[$name]) || array_key_exists($name, $array)) {
            return new static($array[$name]);
        }

        throw new BadMethodCallException('No static method or enum constant ' . $name . ' in class ' . get_called_class());
    }

    /**
     * @return array
     */
    public static function labels(): array
    {
        $result = [];

        foreach (static::toArray() as $value) {
            $result[$value] = static::getLabel($value);
        }

        return $result;
    }

    /**
     * @param string|null $value
     * @return string
     */
    public static function getLabel(?string $value): ?string
    {
        $key = sprintf(
            '%s.%s',
            static::$langPath,
            $value
        );

        $label = Lang::has($key) ? trans($key) : static::$all[$value] ?? $value;

        return apply_filters(BASE_FILTER_ENUM_LABEL, $label, get_called_class());
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return false|int|string
     */
    public function getKey()
    {
        return static::search($this->value);
    }

    /**
     * Return key for value
     *
     * @param string|int $value
     *
     * @return false|int|string
     */
    public static function search($value)
    {
        return array_search($value, static::toArray(), true);
    }

    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * @param Enum|null $enum
     * @return bool True if Enums are equal, false if not equal
     */
    final public function equals(Enum $enum = null): bool
    {
        return $enum !== null && $this->getValue() === $enum->getValue() && get_called_class() === get_class($enum);
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize(): array
    {
        return $this->getValue();
    }

    public function label(): ?string
    {
        return self::getLabel($this->getValue());
    }

    /**
     * @return HtmlString
     */
    public function toHtml()
    {
        return new HtmlString(apply_filters(BASE_FILTER_ENUM_HTML, $this->value, get_called_class()));
    }
}
