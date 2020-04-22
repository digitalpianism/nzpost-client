<?php

namespace DigitalPianism\NzPostClient;

interface ParcelLabelInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function createDomestic($data);

    /**
     * @param array $data
     * @return array
     */
    public function createInternational($data);

    /**
     * @param int $consignementId
     * @return array
     */
    public function getLabelStatus($consignementId);

    /**
     * @param int $consignementId
     * @return array
     */
    public function getRelatedLabelStatus($consignementId);

    /**
     * @param int $consignementId
     * @param string $format
     * @param int $page
     * @return array
     */
    public function downloadLabel($consignementId, $format, $page);
}