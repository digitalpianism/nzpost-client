<?php

namespace DigitalPianism\NzPostClient;

class ShippingOptions extends NzPostClient implements ShippingOptionsInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'shippingoptions/2.0/';

    /**
     * The domestic method is used to retrieve a list of valid shipping options available from a pickup address to a delivery address within New Zealand.
     *
     * @param array $data
     * @return array Details regarding the services applicable to the input parameters
     * @throws NzPostClientAPIException
     */
    public function domestic(array $data)
    {
        $params = http_build_query(
            $data,
            "?",
            "&",
            PHP_QUERY_RFC3986
        );

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        return $responseBody['services'];
    }

    /**
     * The international method is used to retrieve a list of valid shipping options available for a delivery address outside of New Zealand.
     *
     * @param array $data
     * @return array Array listing applicable service codes
     * @throws NzPostClientAPIException
     */
    public function international($data)
    {
        $params = http_build_query(
            $data,
            "?",
            "&",
            PHP_QUERY_RFC3986
        );

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'international?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        return $responseBody['services'];
    }
}