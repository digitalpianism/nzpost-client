<?php

namespace DigitalPianism\NzPostClient;

interface ShippingOptionsInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function domestic($data);


    /**
     * @param array $data
     * @return array
     */
    public function international($data);
}