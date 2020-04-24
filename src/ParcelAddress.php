<?php

namespace DigitalPianism\NzPostClient;

class ParcelAddress extends NzPostClient implements ParcelAddressInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parceladdress/2.0/';

    /**
     * Cache key prefix
     * @var string
     */
    protected $cachePrefix = 'nz_post_client_parceladdress_';

    /**
     * Returns a list of suggested domestic addresses for an address fragment.
     * @param string $query Address fragment to search for suggestions. Valid input characters for this parameter include alphanumeric characters and a forward slash ('/'). All other characters will be ignored.
     * @param int $count The number of matching address records to be returned. Note that the maximum value for the parameter is 10.
     * @return array Contains array of address objects. The number of object will not exceed the ‘Count’ value defined.
     * @throws NzPostClientAPIException
     */
    public function searchDomestic($query, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $query
                    . strval($count)
                );

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
            . 'domestic/addresses?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }


    /**
     * Returns the detailed information for a single domestic address identifier.
     * @param string $addressId NZ Post address identifier
     * @return array Address object with address details
     * @throws NzPostClientAPIException
     */
    public function getDomesticAddressById($addressId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5($addressId);

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
            $this->Cache->set($cacheKey, $responseBody['address'], $this->ttl);
        }

        return $responseBody['address'];
    }


    /**
     * Returns the detailed information for a single dpid.
     * @param string $dpid NZ Post address identifier
     * @return array Address object with address details
     * @throws NzPostClientAPIException
     */
    public function getDomesticAddressByDpid($dpid)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5($dpid);

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
            $this->Cache->set($cacheKey, $responseBody['address'], $this->ttl);
        }

        return $responseBody['address'];
    }

    /**
     * Returns a list of suggested domestic suburbs for a suburb fragment.
     *
     * @param string $query Suburb fragment to search for suggestions. Valid input characters for this parameter include alphanumeric characters. All other characters will be ignored.
     * @param int $count The number of matching suburbs to be returned. Note that the maximum value for the parameter is 10.
     * @return array Contains array of suburb detail objects. The number of object will not exceed the ‘count’ value defined.
     * @throws NzPostClientAPIException
     */
    public function suggestDomesticSuburb($query, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $query
                    . strval($count)
                );

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
            . 'domestic/suburbs?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['suburbs'], $this->ttl);
        }

        return $responseBody['suburbs'];
    }

    /**
     * Returns a list of parcel collection points nearest to the coordinates provided.
     *
     * @param string $latitude The latitude of the delivery address. This value is returned from the Get Address Details resource. The results returned are ordered by increasing distance from the supplied coordinates.
     * @param string $longitude The longitude of the delivery address. This value is returned from the Get Address Details resource. The results returned are ordered by increasing distance from the supplied coordinates.
     * @param int $count Limits the number of results return to this number.
     * @return array Contains array of collection location objects. The number of objects will not exceed the ‘count’ value defined.
     * @throws NzPostClientAPIException
     */
    public function collectionLocations($latitude, $longitude, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $latitude
                    . $longitude
                    . strval($count)
                );

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
            . 'domestic/pcdlocations?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['locations'], $this->ttl);
        }

        return $responseBody['locations'];
    }

    /**
     * Returns a list of suggested addresses for a given country code and address fragment.
     *
     * @param string $query Address fragment to search for suggestions
     * @param string $countryCode Country code for address country
     * @param int $count The maximum number of matching results to be returned
     * @return array Contains array of address objects. The number of object will not exceed the ‘Count’ value defined.
     * @throws NzPostClientAPIException
     */
    public function searchInternational($query, $countryCode, $count)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $query
                    . $countryCode
                    . strval($count)
                );

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
            . 'international/addresses?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }

    /**
     * Returns the detailed information for a single international address identifier.
     * Merchants should consider the use of the Australian Address Lookup resource available in this API in addition to Get International Address Detail resource. The Australian Address Lookup resource is useful when the merchant sends a regular volume of parcel to Australian destinations and also consumes the ParcelLabel functionality.
     * The Australian Address Lookup resource maps the Google Places address data to the ParcelLabel request fields for easier consumption.
     *
     * @link https://developers.google.com/places/web-service/details
     * @param string $addressId International address identifier used by Google Places
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getInternationalAddressById($addressId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5($addressId);

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
            $this->Cache->set($cacheKey, $responseBody['result'], $this->ttl);
        }

        return $responseBody['result'];
    }

    /**
     * This resource is intended to be used in conjunction with the Get International Address Detail resource.
     * The Australian Address Lookup returns the detailed information for a single address identifier for an address in Australia.
     * The details returned from Google Places is mapped to NZ Post standard address fields.
     * The Australian Address Lookup resource is useful to the merchant who sends a regular volume of parcel to Australian destinations and also consumes the ParcelLabel functionality.
     *
     * @param string $addressId International address identifier used by Google Places.
     * @return array Address object with address details
     * @throws NzPostClientAPIException
     */
    public function getAustralianAddressById($addressId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5($addressId);

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
            $this->Cache->set($cacheKey, $responseBody['address'], $this->ttl);
        }

        return $responseBody['address'];
    }
}