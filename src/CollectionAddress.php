<?php

namespace DigitalPianism\NzPostClient;

class CollectionAddress extends NzPostClient implements CollectionAddressInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'collectionaddress/v1/addresses/';

    /**
     * Cache key prefix
     * @var string
     */
    protected $cachePrefix = 'nz_post_client_collectionaddress_';

    /**
     * This resource uses an address_id to retrieve address details for the address_id and a list of nearby collection points.
     * The address_Id can be obtained using the “Search for Address” resource in the ParcelAddress API.
     * @see DigitalPianism\NzPostClient\ParcelAddress::searchDomestic()
     * 
     * @param string $addressId NZ Post address identifier as URI parameter
     * @param int $count The number of parcel collection delivery location address records to be returned. Default is to return the closest location. (min:1,max:10,default:1)
     * @return array Address object with address details
     * @throws NzPostClientAPIException
     */
    public function collect($addressId, $count = 1)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix
                . __FUNCTION__
                . md5(
                    $addressId
                    . strval($count)
                );

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
            . '?'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody['address'], $this->ttl);
        }

        return $responseBody['address'];
    }
}