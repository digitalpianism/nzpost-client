<?php

namespace DigitalPianism\NzPostClient;

interface ParcelPickupInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function booking(array $data);
}