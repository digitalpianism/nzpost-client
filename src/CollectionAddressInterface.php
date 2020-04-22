<?php

namespace DigitalPianism\NzPostClient;

interface CollectionAddressInterface
{
    /**
     * @param string $addressId
     * @param int $count
     * @return array
     */
    public function collect($addressId, $count = 1);
}