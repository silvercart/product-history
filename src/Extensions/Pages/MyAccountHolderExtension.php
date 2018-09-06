<?php

namespace SilverCart\ProductHistory\Extensions\Pages;

use SilverCart\ProductHistory\Model\Product\ProductHistory;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\View\ArrayData;

/**
 * Extension for MyAccountHolder.
 * 
 * @package SilverCart
 * @subpackage ProductHistory_Extensions_Pages
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 06.09.2018
 * @copyright 2018 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class MyAccountHolderExtension extends DataExtension
{
    /**
     * Updates the breadcrumb items.
     * 
     * @param \SilverStripe\ORM\ArrayList $breadcrumbItems Items to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function updateBreadcrumbItems($breadcrumbItems)
    {
        $ctrl = Controller::curr();
        if ($ctrl->hasMethod('isProductHistoryView')
         && $ctrl->isProductHistoryView()) {
            $title = DBText::create();
            $title->setValue(ProductHistory::singleton()->fieldLabel('RecentlyViewedProducts'));
            $breadcrumbItems->push(ArrayData::create([
                'MenuTitle' => $title,
                'Title'     => $title,
                'Link'      => $ctrl->ProductHistoryLink(),
            ]));
        }
    }
}