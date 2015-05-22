<?php

namespace ProductsPack\Form;

use ProductsPack\ProductsPack;
use ProductsPack\Model\PackQuery;
use ProductsPack\Model\ProductPackQuery;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Form\BaseForm;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Class ChangePackStatusForm
 * Build form to change a pack status
 *
 * @package ProductsPack\Form
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class ChangePackStatusForm extends BaseForm
{
    public function getName()
    {
        return "productspack_pack_changestatus";
    }

    protected function buildForm()
    {
        $this->formBuilder
        ->add(
            "productId",
            "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array($this,
                                    "checkExistingLink"
                                )
                            )
                        )
                    )
                ),
                "label" => $this->translator->trans('Is this product a pack', [], ProductsPack::DOMAIN.'.bo.default')
            )
        )
        ->add(
            "isPack",
            "checkbox",
            array(
                "constraints" => array(
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array($this,
                                    "checkDifferentState"
                                )
                            )
                        )
                    )
                ),
                "label" => $this->translator->trans('Is this product a pack', [], ProductsPack::DOMAIN.'.bo.default')
            )
        );
    }

    // Check if the product - pack combination already exists
    public function checkExistingLink($value, ExecutionContextInterface $context)
    {
        $productPackQuery = ProductPackQuery::create()->findByProductId($value);

        if (count($productPackQuery) !== 0) {
            $context->addViolation($this->translator->trans("This product already belongs to one or several pack(s). You can't make packs of packs", [], ProductsPack::DOMAIN.'.bo.default'));
        }
    }

    public function checkDifferentState($value, ExecutionContextInterface $context)
    {
        $packQuery = PackQuery::create()
            ->filterByProductId($this->getForm()->getData()['productId'])
            ->filterByIsActive($value, Criteria::EQUAL)
            ->find();

        if (count($packQuery) !== 0) {
            $context->addViolation($this->translator->trans("The product is already what you want it to be", [], ProductsPack::DOMAIN.'.bo.default'));
        }
    }
}
