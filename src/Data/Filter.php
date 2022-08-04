<?php

namespace App\Data;

use Symfony\Component\Validator\Constraints as Assert;


class Filter
{

    /**
     * q
     *
     * @var string
     */
    public string $q;



    /**
     * categories
     *
     * @var array
     */
    public array $categories = [];

    /**
     * fabricants
     *
     * @var array
     */
    public array $fabricants = [];


    /**
     * min
     *
     * @var int
     */
    #[Assert\LessThan(propertyPath: 'max', message: "Le prix minimum doit être inférieure au prix maximum")]
    public int $min;


    /**
     * max
     *
     * @var int
     */
    public int $max;


    /**
     * order
     *
     * @var mixed
     */
    public mixed $order;
}
