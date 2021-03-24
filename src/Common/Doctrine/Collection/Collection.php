<?php


namespace App\Common\Doctrine\Collection;


use Doctrine\Common\Collections\ArrayCollection;

class Collection extends ArrayCollection
{
    /**
     * Adds static factory for chaining methods
     *
     * @param array $array
     * @return Collection
     */
    public static function Collect(array $array): Collection
    {
        return new self($array);
    }
}