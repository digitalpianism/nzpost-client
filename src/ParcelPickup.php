<?php

namespace DigitalPianism\NzPostClient;

class ParcelPickup extends NzPostClient implements ParcelPickupInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parcelpickup/v3/bookings/';

    /**
     * @param string $trackingReference
     * @return array
     * @throws NzPostClientAPIException
     */
    public function booking($data)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }
}