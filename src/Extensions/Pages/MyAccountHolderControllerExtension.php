<?php

namespace SilverCart\ProductHistory\Extensions\Pages;

use SilverCart\Model\Product\Product;
use SilverCart\ProductHistory\Model\Product\ProductHistory;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

/**
 * Extension for the SilverCart MyAccountHolderController.
 * 
 * @package SilverCart
 * @subpackage ProductHistory_Extensions_Pages
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 05.09.2018
 * @copyright 2018 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class MyAccountHolderControllerExtension extends Extension
{
    /**
     * List of allowed actions.
     *
     * @var array
     */
    private static $allowed_actions = [
        'removefromproducthistory',
        'showproducthistory',
    ];
    
    /**
     * Updates the sub navigation.
     * 
     * @param array &$elements Elements to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function updateSubNavigation(&$elements)
    {
        $originalElements = $elements['SubElements'];
        $newElements      = ArrayList::create();
        $newElements->merge($originalElements);
        $newElements->push(ArrayData::create([
            'Link'        => $this->owner->ProductHistoryLink(),
            'LinkingMode' => $this->owner->isProductHistoryView() ? 'current' : 'link',
            'Title'       => ProductHistory::singleton()->fieldLabel('RecentlyViewedProducts'),
            'MenuTitle'   => ProductHistory::singleton()->fieldLabel('RecentlyViewedProducts'),
        ]));
        $elements['SubElements'] = $newElements;
    }
    
    /**
     * Returns whether the current view is a product history view.
     * 
     * @return boolean
     */
    public function isProductHistoryView()
    {
        $isProductHistoryView = false;
        if (in_array($this->owner->getAction(), self::$allowed_actions)) {
            $isProductHistoryView = true;
        }
        return $isProductHistoryView;
    }
    
    /**
     * Renders the product history.
     * 
     * @return string
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function removefromproducthistory(HTTPRequest $request)
    {
        $removedProduct = null;
        $productID      = (int) $request->param('ID');
        $product        = Product::get()->byID($productID);
        if ($product instanceof Product
         && $product->exists()
         && ProductHistory::remove_from_history($product)) {
            $removedProduct = $product;
        }
        return $this->owner->customise(['RemovedProduct' => $removedProduct])->renderWith([get_class($this->owner->data()) . '_showproducthistory', 'Page']);
    }
    
    /**
     * Renders the product history.
     * 
     * @return string
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function showproducthistory()
    {
        return $this->owner->render();
    }
}