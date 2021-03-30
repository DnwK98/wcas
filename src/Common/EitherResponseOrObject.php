<?php

declare(strict_types=1);

namespace App\Common;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @psalm-template T
 */
class EitherResponseOrObject
{
    public ?JsonResponse $response = null;

    /**
     * @var mixed
     * @paslm-var T
     */
    public $object;

    /**
     * @return mixed|null
     * @psalm-return T
     */
    public function getObject()
    {
        return $this->object;
    }

    public function __construct(?JsonResponse $response = null, $object = null)
    {
        $this->response = $response;
        $this->object = $object;
    }

    public static function response(JsonResponse $response): self
    {
        return new self($response);
    }

    /**
     * @template T1
     * @psalm-param T1 $object
     * @psalm-return self<T1>
     *
     * @param mixed $object
     *
     * @return self
     */
    public static function object($object): self
    {
        return new self(null, $object);
    }
}
