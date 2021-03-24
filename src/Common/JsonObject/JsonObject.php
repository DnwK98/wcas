<?php

declare(strict_types=1);

namespace App\Common\JsonObject;

use App\Common\JsonObject\Exception\JsonParseException;
use DateTime;
use Iterator;
use JsonSerializable;

class JsonObject implements Iterator, JsonSerializable
{
    private $data;

    public static function ofArray(array $array): self
    {
        $object = new self();
        $object->data = $array;

        return $object;
    }

    public static function ofJson(string $json): self
    {
        $data = \json_decode($json, true);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new JsonParseException();
        }
        if (!is_array($data)) {
            throw new JsonParseException();
        }

        return self::ofArray($data);
    }

    private function __construct($object = null)
    {
        $this->data = $object;
    }

    public function current()
    {
        return new JsonObject(current($this->data));
    }

    public function next()
    {
        next($this->data);
    }

    public function key()
    {
        if (is_array($this->data)) {
            return key($this->data);
        }

        return null;
    }

    public function valid()
    {
        return is_array($this->data) ? isset($this->data[key($this->data)]) : false;
    }

    public function rewind()
    {
        if (is_array($this->data)) {
            reset($this->data);
        }
    }

    public function getJson(?string $offset = null, ?JsonObject $default = null): JsonObject
    {
        $value = $this->get($offset);
        $default = null === $default ? new JsonObject(null) : $default;

        return null === $value ? $default : new JsonObject($value);
    }

    public function getString(?string $offset = null, ?string $default = null): ?string
    {
        $value = $this->get($offset);
        $value = is_scalar($value) ? $value : $default;

        return null === $value ? $default : (string)$value;
    }

    public function getFloat(?string $offset = null, ?float $default = null): ?float
    {
        $value = $this->get($offset);
        $value = is_scalar($value) ? $value : $default;

        return null === $value ? $default : (float)$value;
    }

    public function getInt(?string $offset = null, ?int $default = null): ?int
    {
        $value = $this->get($offset);
        $value = is_scalar($value) ? $value : $default;

        return null === $value ? $default : (int)$value;
    }

    public function getBool(?string $offset = null, ?bool $default = null): ?bool
    {
        $value = $this->get($offset);

        return null === $value ? $default : (bool)$value;
    }

    public function getArray(?string $offset = null, ?array $default = null): ?array
    {
        $value = $this->get($offset);
        $value = is_array($value) ? $value : $default;

        return null === $value ? $default : $value;
    }

    public function getDateTime(?string $offset = null, ?DateTime $default = null): ?DateTime
    {
        $value = $this->getString($offset);
        if (empty($value)) {
            return $default;
        }
        $dateTime = date_create($value);

        return false === $dateTime ? $default : $dateTime;
    }

    public function isset(?string $offset = null)
    {
        $value = $this->get($offset);

        return isset($value);
    }

    private function get(?string $offset)
    {
        if (null === $offset) {
            return $this->data;
        }
        $array = $this->data;
        $fields = explode('.', $offset);

        foreach ($fields as $field) {
            if (!isset($array[$field])) {
                return null;
            }
            $array = $array[$field];
        }

        return $array;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
