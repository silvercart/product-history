<?php

namespace SilverCart\ProductHistory\Model\Product;

use SilverCart\Dev\Tools;
use SilverCart\Model\Product\Product;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;

/**
 * DataObject to track a customers visited products.
 * 
 * @package SilverCart
 * @subpackage ProductHistory_Model_Product
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 05.09.2018
 * @copyright 2018 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class ProductHistory extends DataObject
{
    use \SilverCart\ORM\ExtensibleDataObject;
    
    const SESSION_KEY = 'SilverCart.ProductHistory';
    
    /**
     * The maximum amount of product history entries.
     *
     * @var int
     */
    private static $max_history_amount = 40;
    /**
     * Property to mark a running session merge as in progress.
     *
     * @var bool
     */
    protected static $merge_in_progress = false;
    
    /**
     * Returns the history for the given member.
     * If no member is given, the currently logged in user will be used as
     * context.
     * 
     * @param Member $member Member
     * 
     * @return \SilverStripe\ORM\SS_List
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public static function get_history($member = null)
    {
        if (!($member instanceof Member)
         || !$member->exists()
        ) {
            $member = Security::getCurrentUser();
        }
        if ($member instanceof Member
         && $member->exists()
        ) {
            $history = $member->ProductHistory();
            self::merge_with_session($history);
        } else {
            $history = self::from_session();
        }
        return $history;
    }
    
    /**
     * Returns the history for the given member.
     * If no member is given, the currently logged in user will be used as
     * context.
     * 
     * @param Product $product  Product
     * @param Member  $member   Member
     * @param string  $lastView Last view date in format "yyyy-mm-dd hh:mm:ss"
     * @param string  $created  Creation date in format "yyyy-mm-dd hh:mm:ss"
     * 
     * @return ProductHistory
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public static function update_history($product, $member = null, $lastView = null, $created = null)
    {
        $history = self::get_history($member);
        if (!($member instanceof Member)
         || !$member->exists()
        ) {
            $member = Security::getCurrentUser();
        }
        if ($member instanceof Member
         && $member->exists()
         && $product instanceof Product
         && $product->exists()
        ) {
            $existing = $history->filter('ProductID', $product->ID)->first();
            if ($existing instanceof ProductHistory
             && $existing->exists()
            ) {
                $existing->LastView = is_null($lastView) ? date('Y-m-d H:i:s') : $lastView;
                $existing->write();
            } else {
                $new = ProductHistory::create();
                $new->ProductID = $product->ID;
                $new->MemberID  = $member->ID;
                $new->LastView  = is_null($lastView) ? date('Y-m-d H:i:s') : $lastView;
                if (!is_null($created)) {
                    $new->Created = $created;
                }
                $new->write();
                $history->add($new);
            }
        } else {
            $history = self::update_session_history($product);
        }
        return $history;
    }
    
    /**
     * Removes the given product from history.
     * 
     * @param Product $product Product
     * @param Member  $member  Member
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public static function remove_from_history($product, $member = null)
    {
        $removed         = false;
        $historyToRemove = self::get_history($member)->filter('ProductID', $product->ID);
        if ($historyToRemove->exists()) {
            $removed = true;
            foreach ($historyToRemove as $entry) {
                $entry->delete();
            }
        }
        return $removed;
    }
    
    /**
     * Returns the history from session (for not logged in customers).
     * 
     * @return ArrayList
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public static function from_session()
    {
        $history      = ArrayList::create();
        $sessionStore = Tools::Session()->get(self::SESSION_KEY);
        if (is_array($sessionStore)) {
            foreach ($sessionStore as $historyData) {
                $history->add(ArrayData::create([
                    'Created'   => $historyData['Created'],
                    'LastView'  => $historyData['LastView'],
                    'ProductID' => $historyData['ProductID'],
                    'Product'   => Product::get()->byID($historyData['ProductID']),
                ]));
            }
        }
        return $history->sort('LastView', 'DESC');
    }
    
    /**
     * Writes the given ArrayList history to session.
     * 
     * @param ArrayList $history History
     * 
     * @return ArrayList
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public static function to_session($history)
    {
        $sessionStore = [];
        foreach ($history as $historyData) {
            $sessionStore[] = [
                'Created'   => $historyData->Created,
                'LastView'  => $historyData->LastView,
                'ProductID' => $historyData->ProductID,
            ];
        }
        Tools::Session()->set(self::SESSION_KEY, $sessionStore);
        Tools::saveSession();
        return $history;
    }
    
    /**
     * Updates the history in session (for not logged in customers).
     * 
     * @param Product $product Product
     * 
     * @return ArrayList
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 05.09.2018
     */
    public static function update_session_history($product)
    {
        $history = self::from_session();
        if ($product instanceof Product
         && $product->exists()
        ) {
            $existing = $history->filter('ProductID', $product->ID)->first();
            if (is_null($existing)) {
                $history->add(ArrayData::create([
                    'Created'   => date('Y-m-d H:i:s'),
                    'LastView'  => date('Y-m-d H:i:s'),
                    'ProductID' => $product->ID,
                ]));
            } else {
                $existing->LastView = date('Y-m-d H:i:s');
            }
            self::to_session($history);
        }
        return $history->sort('LastView', 'DESC');
    }
    
    /**
     * Merges the session history with the given history.
     * 
     * @param \SilverCart\ORM\DataList $history History to merge session into
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public static function merge_with_session($history)
    {
        if (self::$merge_in_progress) {
            return;
        }
        self::$merge_in_progress = true;
        $sessionHistory = self::from_session();
        if ($sessionHistory->exists()
         && $history instanceof \SilverStripe\ORM\DataList) {
            foreach ($sessionHistory as $historyData) {
                self::update_history($historyData->Product, null, $historyData->LastView, $historyData->Created);
            }
            Tools::Session()->set(self::SESSION_KEY, null);
            Tools::saveSession();
        }
        self::$merge_in_progress = false;
    }
    
    /**
     * Table name.
     *
     * @var string
     */
    private static $table_name = 'SilvercartProductHistory';
    /**
     * Has one relations.
     *
     * @var array
     */
    private static $db = [
        'LastView' => DBDatetime::class,
    ];
    /**
     * Has one relations.
     *
     * @var array
     */
    private static $has_one = [
        'Product' => Product::class,
        'Member'  => Member::class,
    ];
    
    /**
     * Returns the singular name.
     * 
     * @return string
     */
    public function singular_name()
    {
        return Tools::singular_name_for($this);
    }
    
    /**
     * Returns the plural name.
     * 
     * @return string
     */
    public function plural_name()
    {
        return Tools::plural_name_for($this);
    }
    
    /**
     * Field labels.
     * 
     * @param bool $includerelations include relations?
     * 
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $this->beforeUpdateFieldLabels(function (&$labels) {
            $labels = array_merge(
                    $labels,
                    Tools::field_labels_for(static::class),
                    [
                        'LastView'               => _t(self::class . '.LastView', 'Last view'),
                        'NoHistory'              => _t(self::class . '.NoHistory', 'Your history is empty.'),
                        'RecentlyViewed'         => _t(self::class . '.RecentlyViewed', 'Recently viewed'),
                        'RecentlyViewedProducts' => _t(self::class . '.RecentlyViewedProducts', 'Recently viewed products'),
                        'Remove'                 => _t(self::class . '.Remove', 'Remove'),
                        'ShowAll'                => _t(self::class . '.ShowAll', 'Edit or show history'),
                    ]
            );
        });
        return parent::fieldLabels($includerelations);
    }
    
    /**
     * Returns the product.
     * If the related product does not exist, the history entry will be deleted.
     * 
     * @return Product
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 06.09.2018
     */
    public function Product()
    {
        $product = $this->getComponent('Product');
        if (!$product->exists()) {
            $this->delete();
        }
        return $product;
    }
}