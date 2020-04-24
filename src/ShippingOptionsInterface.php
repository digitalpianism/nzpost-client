<?php

namespace DigitalPianism\NzPostClient;

interface ShippingOptionsInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function domestic(array $data);


    /**
     * @param array $data
     * @return array
     */
    public function international(array $data);
}