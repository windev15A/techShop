<?php 

use Symfony\Component\Validator\Constraints as Assert;


class Filter
{
    
    /**
     * q
     *
     * @var string
     */
    public $q;


        
    /**
     * categories
     *
     * @var array
     */
    public $categories = [];  
      
    /**
     * fabricants
     *
     * @var array
     */
    public $fabricants = [];

    
    /**
     * min
     *
     * @var int
     */
    #[Assert\LessThan(
        propertyPath: 'max',
        message: "Le prix minimum doit être inférieure au prix maximum"
    )]
    public $min;  
    
    
    /**
     * max
     *
     * @var int
     */
    public $max;

    
    /**
     * order
     *
     * @var mixed
     */
    public $order;

}