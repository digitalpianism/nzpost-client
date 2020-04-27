<?php

namespace DigitalPianism\NzPostClient;

/**
 * Class AddressChecker
 * @package DigitalPianism\NzPostClient
 */
class AddressChecker extends NzPostClient implements AddressCheckerInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'addresschecker/1.0/';

    /**
     * Cache key prefix
     * @var string
     */
    protected $cachePrefix = 'nz_post_client_addresschecker_';

    /**
     * Takes an address fragment, and returns a set of addresses that match the fragment.
     * 
     * @param array $addressLines Address Line 1 ... Line 5
     * @param string $type Type of addresses to search: 'Postal|Physical|All'
     * @param int $max Maximum number of results to return. (default: 10)
     * @return array Array of addresses objects. The number of objects will not exceed the 'max' value defined
     * @throws NzPostClientAPIException
     */
    public function find(array $addressLines, $type = 'All', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    implode($addressLines)
                    . $type
                    . strval($max)
                );

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $data = [
            'type' => $type,
            'address_line_1' => (isset($addressLines[0]) ? $addressLines[0] : NULL),
            'address_line_2' => (isset($addressLines[1]) ? $addressLines[1] : NULL),
            'address_line_3' => (isset($addressLines[2]) ? $addressLines[2] : NULL),
            'address_line_4' => (isset($addressLines[3]) ? $addressLines[3] : NULL),
            'address_line_5' => (isset($addressLines[4]) ? $addressLines[4] : NULL),
            'max' => $max,
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $this->validate($data, $schemaPath);

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'find?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }

    /**
     * Takes an address dpid, and returns detailed information about the matching address.
     *
     * @param string $dpid The delivery point identifier.
     * @param string $type Type of addresses to search: 'Postal|Physical|All'
     * @param int $max Maximum number of results to return (defaults to 10).
     * @return array Details of the requested address.
     * @throws NzPostClientAPIException
     */
    public function details($dpid, $type = 'All', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $dpid
                    . $type
                    . strval($max)
                );

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }
        
        $data = [
            'dpid' => $dpid,
            'type' => $type,
            'max' => $max,
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $this->validate($data, $schemaPath);

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'details?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['details'], $this->ttl);
        }

        return $responseBody['details'];
    }

    /**
     * Takes an address fragment, and returns a set of addresses that match the fragment.
     *
     * @param string $query Address fragment to query
     * @param string $type Type of addresses to search: 'Postal|Physical|All'
     * @param int $max Maximum number of results to return (default: 10)
     * @return array Contains array of address objects. The number of object will not exceed the 'max' value defined.
     * @throws NzPostClientAPIException
     */
    public function suggest($query, $type = 'All', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $query
                    . $type
                    . strval($max)
                );

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $data = [
            'q' => $query,
            'type' => $type,
            'max' => $max
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $this->validate($data, $schemaPath);

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'suggest?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }

    /**
     * Takes an address fragment, and returns a set of partial addresses that match the fragment.
     *
     * @param string $query Partial Address fragment to query.
     * @param string $orderRoadsFirst Partial addresses can include cities and roads. This option, if set to Y, will order roads before cities. Either Y, or N (default).
     * @param int $max Maximum number of results to return. (default: 10)
     * @return array Contains array of address objects. The number of objects will not exceed the 'max' value defined.
     * @throws NzPostClientAPIException
     */
    public function suggestPartial($query, $orderRoadsFirst = 'N', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $query
                    . $orderRoadsFirst
                    . strval($max)
                );

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }
        
        $data = [
            'q' => $query,
            'order_roads_first' => $orderRoadsFirst,
            'max' => $max,
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $this->validate($data, $schemaPath);

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'suggest_partial?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['addresses'], $this->ttl);
        }

        return $responseBody['addresses'];
    }

    /**
     * Takes a partial's unique_id and returns detailed information about the matching partial address.
     *
     * @param string $uniqueId The unique identifier of the partial address.
     * @param int $max Maximum number of results to return (defaults to 10).
     * @return array Details of the requested address.
     * @throws NzPostClientAPIException
     */
    public function partialDetails($uniqueId, $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $uniqueId
                    . strval($max)
                );

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'unique_id' => $uniqueId,
            'max' => $max,
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'partial_details?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['details'], $this->ttl);
        }

        return $responseBody['details'];
    }
}