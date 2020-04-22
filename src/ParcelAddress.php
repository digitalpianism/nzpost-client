<?php

namespace DigitalPianism\NzPostClient;

class ParcelAddress extends NzPostClient implements ParcelAddressInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parceladdress/2.0/';

    /**
     * @param $query
     * @param int $count
     * @return array
     * @throws NzPostClientAPIException
     */
    public function searchDomestic($query, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'q' => $query,
            'count' => $count
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic/addresses/'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }


    /**
     * @param $addressId
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getDomesticAddressById($addressId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic/addresses/'
            . $addressId;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }


    /**
     * @param $dpid
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getDomesticAddressByDpid($dpid)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic/addresses/dpid/'
            . $dpid;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param $query
     * @param int $count
     * @return array
     * @throws NzPostClientAPIException
     */
    public function suggestDomesticSuburb($query, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'q' => $query,
            'count' => $count
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic/suburbs/'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param string $latitude
     * @param string $longitude
     * @param int $count
     * @return array
     * @throws NzPostClientAPIException
     */
    public function collectionLocations($latitude, $longitude, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'count' => $count
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic/pcdlocations/'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param string $query
     * @param string $countryCode
     * @param int $count
     * @return array
     * @throws NzPostClientAPIException
     */
    public function searchInternational($query, $countryCode, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'q' => $query,
            'country_code'  =>  $countryCode,
            'count' => $count
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'international/addresses/'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }

    /**
     * @param $addressId
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getInternationalAddressById($addressId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'international/addresses/'
            . $addressId;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param $addressId
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getAustralianAddressById($addressId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'australia/addresses/'
            . $addressId;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }
}