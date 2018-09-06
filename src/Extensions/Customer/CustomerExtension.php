<?php

namespace SilverCart\ProductHistory\Extensions\Customer;

use SilverCart\Dev\Tools;
use SilverCart\ProductHistory\Model\Product\ProductHistory;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;

/**
 * 
 * @package SilverCart
 * @subpackage ProductHistory_Extensions_Customer
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 05.09.2018
 * @copyright 2018 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class CustomerExtension extends DataExtension
{
    /**
     * Has many relations.
     *
     * @var array
     */
    private static $has_many = [
        'ProductHistories' => ProductHistory::class,
    ];
    /**
     * Casted attributes.
     *
     * @var array
     */
    private static $casting = [
        'ProductHistory' => DataList::class,
    ];
    
    /**
     * Updates the field labels.
     * 
     * @param array &$labels Labels to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public function updateFieldLabels(&$labels)
    {
        $labels = array_merge(
                $labels,
                Tools::field_labels_for(self::class)
        );
    }
    
    /**
     * Returns the customers product history sorted by the LastView date.
     * 
     * @return \SilverCart\ORM\DataList
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public function ProductHistory()
    {
        return $this->owner->ProductHistories()->sort('LastView', 'DESC');
    }
}