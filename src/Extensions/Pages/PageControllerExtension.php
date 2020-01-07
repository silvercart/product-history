<?php

namespace SilverCart\ProductHistory\Extensions\Pages;

use SilverCart\Dev\Tools;
use SilverCart\Model\Pages\CheckoutStepController;
use SilverCart\Model\Pages\Page;
use SilverCart\ProductHistory\Model\Product\ProductHistory;
use SilverStripe\Core\Extension;

/**
 * Extension for the SilverCart PageController.
 * 
 * @package SilverCart
 * @subpackage ProductHistory_Extensions_Pages
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 05.09.2018
 * @copyright 2018 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class PageControllerExtension extends Extension
{
    /**
     * Determines whether to show the product hostory in footer or not.
     *
     * @var bool
     */
    private static $show_product_history_in_footer = true;
    
    /**
     * Updates the HTML content to render before the footer.
     * 
     * @param string &$content Content to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function updateBeforeFooterContent(&$content) : void
    {
        if ($this->showProductHistoryInFooter()) {
            $content .= $this->owner->renderWith(self::class . '_Footer');
        }
    }
    
    /**
     * Returns whether to show the product history in footer or not.
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function showProductHistoryInFooter() : bool
    {
        $showProductHistoryInFooter = true;
        if (($this->owner->hasMethod('isProductHistoryView')
          && $this->owner->isProductHistoryView())
         || $this->owner->config()->show_product_history_in_footer === false
         || $this->owner instanceof CheckoutStepController
        ) {
            $showProductHistoryInFooter = false;
        }
        return $showProductHistoryInFooter;
    }
    
    /**
     * Returns the customers product history sorted by the LastView date.
     * If there is a currently logged in customer, the result will be a DataList.
     * If there is no currently logged in customer, the result will be an
     * ArrayList created from session.
     * 
     * @return \SilverStripe\ORM\SS_List
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public function ProductHistory()
    {
        return ProductHistory::get_history();
    }
    
    /**
     * Returns the product history link.
     * 
     * @return string
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public function ProductHistoryLink()
    {
        $link = '';
        $page = Tools::PageByIdentifierCode(Page::IDENTIFIER_MY_ACCOUNT_HOLDER);
        if ($page instanceof Page) {
            $link = $page->Link('showproducthistory');
        }
        return $link;
    }
    
    /**
     * Returns the link to remove a product from history.
     * 
     * @param int $productID ID of the product to remove from history
     * 
     * @return string
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public function RemoveFromHistoryLink($productID)
    {
        $link = '';
        $page = Tools::PageByIdentifierCode(Page::IDENTIFIER_MY_ACCOUNT_HOLDER);
        if ($page instanceof Page) {
            $link = $page->Link("removefromproducthistory/{$productID}");
        }
        return $link;
    }
}