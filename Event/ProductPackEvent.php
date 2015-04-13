<?php

namespace ProductsPack\Event;

use Thelia\Core\Event\ActionEvent;

/**
 * Class ProductPackEvent
 * @package ProductsPack\Event
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class ProductPackEvent extends ActionEvent {

    protected $packId;
    protected $productId;

    function __construct(
        $packId,
        $productId
    ) {
        $this->packId = $packId;
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getPackId()
    {
        return $this->packId;
    }

    /**
     * @param mixed $packId
     */
    public function setPackId($packId)
    {
        $this->packId = $packId;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }



}