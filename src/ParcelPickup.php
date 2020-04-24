<?php

namespace DigitalPianism\NzPostClient;

class ParcelPickup extends NzPostClient implements ParcelPickupInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parcelpickup/v3/bookings/';

    /**
     * Creates pick up event for requested Pace or CourierPost service.
     * 
     * @param array $data
     * @return array Array of Result Elements
     * @throws NzPostClientAPIException
     */
    public function booking(array $data)
    {

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT;

        $responseBody = $this->sendApiRequest($request, json_encode($data));

        return $responseBody['results'];
    }
}