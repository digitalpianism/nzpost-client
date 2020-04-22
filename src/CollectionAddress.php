<?php

namespace DigitalPianism\NzPostClient;

class CollectionAddress extends NzPostClient implements CollectionAddressInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'collectionaddress/v1/addresses/';

    /**
     * @param string $addressId
     * @param int $count
     * @return array
     * @throws NzPostClientAPIException
     */
    public function collect($addressId, $count = 1)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'count' =>  $count
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $addressId
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }
}