<?php

namespace ProductsPack\Loop;

use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Argument\Argument;
use ProductsPack\Model\ProductPackQuery;

/**
 * Class ProductPackLoop
 * Definition of the ProductPack loop of ProductsPack module
 * 
 * @package ProductsPack\Loop
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class ProductPackLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{
    public $countable = true;

    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('pack_id'),
            Argument::createIntListTypeArgument('product_id')
        );
    }

    public function buildModelCriteria()
    {
        $search = ProductPackQuery::create();

        $packId = $this->getPackId();
        $productId = $this->getProductId();

        if (!is_null($packId)) {
            $search->filterByPackId($packId);
        }
        if (!is_null($productId)) {
            $search->filterByProductId($productId);
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $productPack) {
            
            $loopResultRow = new LoopResultRow($productPack);

            $loopResultRow
                ->set("PACK_ID", $productPack->getPackId())
                ->set("PRODUCT_ID", $productPack->getProductId());

            $loopResult->addRow($loopResultRow);
        }
        
        return $loopResult;
    }

}
