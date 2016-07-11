<?php
/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    custom_ext_code
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Pdfgenerator\Model\ResourceModel;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Pdfgenerator extends AbstractDb
{

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param DateTime $dateTime
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        DateTime $dateTime,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
    }

    /**
     * Init resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('eadesign_pdf_templates', 'template_id');
    }

    /**
     * Assign $template to store views
     *
     * @param AbstractModel | \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveStoreRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $templateId
     * @return array
     */
    public function lookupStoreIds($templateId)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('eadesign_pdf_store'),
            'store_id'
        )->where(
            'template_id = ?',
            (int)$templateId
        );

        return $adapter->fetchCol($select);
    }

    /**
     * @param AbstractModel $template
     * @return $this
     */
    protected function saveStoreRelation(AbstractModel $template)
    {

        $oldStores = $this->lookupStoreIds($template->getId());
        $newStores = (array)$template->getStoreId();
        if (empty($newStores)) {
            $newStores = (array)$template->getStoreId();
        }
        $table = $this->getTable('eadesign_pdf_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = [
                'template_id = ?' => (int)$template->getId(),
                'store_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'template_id' => (int)$template->getId(),
                    'store_id' => (int)$storeId
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }
        return $this;
    }

}