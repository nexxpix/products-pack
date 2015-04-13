<?php

namespace ProductsPack\Loop;

use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Argument\Argument;
use ProductsPack\Model\PackQuery;

/**
 * Class PackLoop
 * Definition of the Pack loop of ProductsPack module
 * 
 * @package ProductsPack\Loop
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class PackLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{
    public $countable = true;

    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createBooleanTypeArgument('is_active'),
            Argument::createIntListTypeArgument('product_id')
        );
    }

    public function buildModelCriteria()
    {
        $search = PackQuery::create();

        $id = $this->getId();
        $isActive = $this->getIsActive();
        $productId = $this->getProductId();

        if (!is_null($id)) {
            $search->filterById($id);
        }
        if (!is_null($isActive)) {
            $search->filterByIsActive($isActive);
        }
        if (!is_null($productId)) {
            $search->filterByProductId($productId);
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $pack) {

            $loopResultRow = new LoopResultRow($pack);

            $loopResultRow
                ->set("ID", $pack->getId())
                ->set("IS_ACTIVE", $pack->getIsActive())
                ->set("PRODUCT_ID", $pack->getProductId());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}