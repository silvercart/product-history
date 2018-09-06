<?php

namespace SilverCart\ProductHistory\Extensions\Pages;

use SilverCart\ProductHistory\Model\Product\ProductHistory;
use SilverStripe\Core\Extension;

/**
 * Extension for ProductGroupPageController.
 * 
 * @package SilverCart
 * @subpackage ProductHistory_Extensions_Pages
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 30.08.2018
 * @copyright 2018 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class ProductGroupPageControllerExtension extends Extension
{
    /**
     * Updates a products popularity score if necessary.
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public function onBeforeRenderProductDetailView()
    {
        ProductHistory::update_history($this->owner->getProduct());
    }
}