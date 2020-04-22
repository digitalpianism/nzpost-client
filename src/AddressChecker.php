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
     * Searching for address by address details
     * @param array $addressLines Address Line 1 ... Line 5
     * @param string $type Type of addresses to search: 'Postal|Physical|All'
     * @param int $max Maximum number of results to return.
     * @return array
     * @throws NzPostClientAPIException
     */
    public function find(array $addressLines, $type = 'All', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5(implode($addressLines) . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'type' => $type,
            'address_line_1' => (isset($addressLines[0]) ? $addressLines[0] : NULL),
            'address_line_2' => (isset($addressLines[1]) ? $addressLines[1] : NULL),
            'address_line_3' => (isset($addressLines[2]) ? $addressLines[2] : NULL),
            'address_line_4' => (isset($addressLines[3]) ? $addressLines[3] : NULL),
            'address_line_5' => (isset($addressLines[4]) ? $addressLines[4] : NULL),
            'max' => $max,
        ]);

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
     * @param $dpid
     * @param string $type Type of addresses to search: 'Postal|Physical|All'
     * @param int $max
     * @return mixed
     * @throws NzPostClientAPIException
     */
    public function details($dpid, $type = 'All', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($dpid . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'dpid' => $dpid,
            'type' => $type,
            'max' => $max,
        ]);

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
     * @param $query
     * @param string $type Type of addresses to search: 'Postal|Physical|All'
     * @param int $max
     * @return array
     * @throws NzPostClientAPIException
     */
    public function suggest($query, $type = 'All', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'q' => $query,
            'type' => $type,
            'max' => $max
        ]);

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
     * @param $query
     * @param string $orderRoadsFirst
     * @param int $max
     * @return array
     * @throws NzPostClientAPIException
     */
    public function suggestPartial($query, $orderRoadsFirst = 'N', $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $orderRoadsFirst . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'q' => $query,
            'order_roads_first' => $orderRoadsFirst,
            'max' => $max,
        ]);

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
     * @param $uniqueId
     * @param int $max
     * @return array
     * @throws NzPostClientAPIException
     */
    public function partialDetails($uniqueId, $max = 10)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($uniqueId . strval($max));

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