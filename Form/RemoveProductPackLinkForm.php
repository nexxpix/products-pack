<?php

namespace ProductsPack\Form;

use Symfony\Component\Validator\Constraints;
use Thelia\Form\BaseForm;

/**
 * Class RemoveProductPackLinkForm
 * Build form to remove the link between a product and a pack it belongs to
 *
 * @package ProductsPack\Form
 * @author Etienne PERRIERE <eperriere@openstudio.fr> - Nexxpix - OpenStudio
 */
class RemoveProductPackLinkForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add("productId", "number",
                array(
                    "constraints" => array(
                        new Constraints\NotBlank()
                    )
                )
            )
            ->add("packId", "number",
                array(
                    "constraints" => array(
                        new Constraints\NotBlank()
                    )
                )
            );
    }

    public function getName()
    {
        return "productspack_productpack_remove";
    }
}