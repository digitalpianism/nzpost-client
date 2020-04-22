<?php

namespace DigitalPianism\NzPostClient;

interface ParcelTrackInterface
{
    /**
     * @param string $trackingReference
     * @return array
     */
    public function track($trackingReference);
}