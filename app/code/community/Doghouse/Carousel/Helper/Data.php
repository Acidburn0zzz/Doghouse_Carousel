<?php
/**
 * Doghouse Carousel Helper file.
 *
 * @category  Doghouse
 * @package   Doghouse_Carousel
 * @author    Doghouse <support@dhmedia.com.au>
 * @copyright 2015 Doghouse Media (http://doghouse.agency)
 * @license   https://github.com/DoghouseMedia/Doghouse_Carousel/blob/master/LICENSE  The MIT License (MIT)
 * @link      https://github.com/DoghouseMedia/Doghouse_Carousel
 */
/**
 * Doghouse_Carousel_Helper_Data
 *
 * @category  Doghouse
 * @package   Doghouse_Carousel
 * @author    Doghouse <support@dhmedia.com.au>
 * @copyright 2015 Doghouse Media (http://doghouse.agency)
 * @license   https://github.com/DoghouseMedia/Doghouse_Carousel/blob/master/LICENSE  The MIT License (MIT)
 * @link      https://github.com/DoghouseMedia/Doghouse_Carousel
 */
class Doghouse_Carousel_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Carousel Items live.
     */
    const MEDIA_DIR = 'dhcarousel/';

    /**
     * With http:// and everything
     *
     * @return string
     */
    public function getImageUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . self::MEDIA_DIR;
    }

    /**
     * Get the directory, so /media/carousel/
     *
     * @return string
     */
    public function getFullImagesDir()
    {
        return Mage::getBaseDir('media') . DS . self::MEDIA_DIR;
    }

    /**
     * Does some saving action
     *
     * @param string $name image name
     *
     * @return $this
     */
    public function saveImage($name)
    {
        $path = $this->getFullImagesDir();

        try {
            $uploader = new Mage_Core_Model_File_Uploader($name);
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($path);

            return $result['file'];

        } catch (Exception $e) {
            if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
                Mage::logException($e);
            }

            return $this;
        }
    }

    /**
     * Formats a nicely formatted Image url
     *
     * @param Doghouse_Carousel_Model_Item $item item to save
     *
     * @return string
     */
    public function getImage(Doghouse_Carousel_Model_Item $item)
    {
        return $this->getImageUrl() . $item->getImage();
    }

    /**
     * Formats a nicely formatted url. Aw yeah.
     *
     * @param Doghouse_Carousel_Model_Item $item carousel item
     *
     * @return string
     */
    public function getUrl(Doghouse_Carousel_Model_Item $item)
    {
        return Mage::getUrl($item->getUrl());
    }

    /**
     * Clear out the cache entries for the given tags
     *
     * @param array $tags cache tags
     *
     * @return $this
     */
    public function clearCache(array $tags = array())
    {
        $cache = Mage::app()->getCacheInstance();
        if (is_object($cache)) {
            $cache->clean($tags);
            if (Mage::app()->useCache('full_page')) {
                Mage::dispatchEvent(
                    'adminhtml_cache_refresh_type', array('type' => Doghouse_Carousel_Block_Carousel::CACHE_GROUP)
                );
            }
        }

        return $this;
    }

}
