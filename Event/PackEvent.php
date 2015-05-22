<?php

namespace ProductsPack\Event;

use Thelia\Core\Event\ActionEvent;

/**
 * Class PackEvent
 * @package ProductsPack\Event
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class PackEvent extends ActionEvent
{

    protected $isActive;
    protected $productId;

    public function __construct(
        $isActive,
        $productId
    ) {
        $this->isActive = $isActive;
        $this->productId = $productId;
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

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
}
