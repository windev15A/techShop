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
    public ?string $q = null;



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
    public ?int $min = 0;


    /**
     * max
     *
     * @var int
     */
    public ?int $max = 5000;


    /**
     * order
     *
     * @var mixed
     */
    public int $order = 1;

    public function __sleep()
    {
        return [];
    }
}
