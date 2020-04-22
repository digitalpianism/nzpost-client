<?php

namespace DigitalPianism\NzPostClient;

class ParcelTrack extends NzPostClient implements ParcelTrackInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parceltrack/3.0/parcels/';

    /**
     * @param string $trackingReference
     * @return array
     * @throws NzPostClientAPIException
     */
    public function track($trackingReference)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $trackingReference;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }
}