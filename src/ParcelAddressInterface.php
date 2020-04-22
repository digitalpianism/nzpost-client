<?php

namespace DigitalPianism\NzPostClient;

interface ParcelAddressInterface
{
    /**
     * @param string $query
     * @param int $count
     * @return array
     */
    public function searchDomestic($query, $count);


    /**
     * @param string $addressId
     * @return array
     */
    public function getDomesticAddressById($addressId);


    /**
     * @param string $dpid
     * @return array
     */
    public function getDomesticAddressByDpid($dpid);

    /**
     * @param string $query
     * @param int $count
     * @return array
     */
    public function suggestDomesticSuburb($query, $count);

    /**
     * @param string $latitude
     * @param string $longitude
     * @param int $count
     * @return array
     */
    public function collectionLocations($latitude, $longitude, $count);

    /**
     * @param string $query
     * @param string $countryCode
     * @param int $count
     * @return array
     */
    public function searchInternational($query, $countryCode, $count);

    /**
     * @param string $addressId
     * @return array
     */
    public function getInternationalAddressById($addressId);

    /**
     * @param string $addressId
     * @return array
     */
    public function getAustralianAddressById($addressId);
}