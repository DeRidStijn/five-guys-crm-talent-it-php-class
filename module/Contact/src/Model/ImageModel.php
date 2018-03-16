<?php

namespace Contact\Model;


use Contact\Entity\ImageInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\HydratorInterface;

class ImageModel implements ImageModelInterface
{
    const TABLE_NAME = 'contact_image';

    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var ImageInterface
     */
    private $imagePrototype;

    /**
     * ImageModel constructor.
     * @param AdapterInterface $db
     * @param HydratorInterface $hydrator
     * @param ImageInterface $imagePrototype
     */
    public function __construct(AdapterInterface $db, HydratorInterface $hydrator, ImageInterface $imagePrototype)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->imagePrototype = $imagePrototype;
    }

    /**
     * @inheritDoc
     */
    public function fetchAllImages($contactId)
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);
        $select->where(['contact_id = ?' => $contactId]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \DomainException('Cannot find images for given contact');
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->imagePrototype);
        $resultSet->initialize($result);

        if (!$resultSet) {
            throw new \RuntimeException('Cannot process image data for this contact');
        }

        return $resultSet;
    }

    /**
     * @inheritDoc
     */
    public function findImageById($contactId, $imageId)
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);
        $select->where([
            'contact_id = ?' => $contactId,
            'contact_image_id = ?' => $imageId,
        ]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \DomainException('Cannot find images for given contact');
        }
        return $this->hydrator->hydrate($result->current(), $this->imagePrototype);
    }

    /**
     * @inheritDoc
     */
    public function saveImage($contactId, ImageInterface $image)
    {
        $imageData = $this->hydrator->extract($image);
        unset($imageData['contact_image_id']);

        $insert = new Insert(self::TABLE_NAME);
        $insert->values($imageData);

        $sql = new Sql($this->db);
        $stmt = $sql->prepareStatementForSqlObject($insert);
        $result = $stmt->execute();

        $imageId = $result->getGeneratedValue();
        $imageData['contact_image_id'] = $imageId;
        $newImageEntity = clone $this->imagePrototype;

        return $this->hydrator->hydrate($imageData, $newImageEntity);
    }

    /**
     * @inheritDoc
     */
    public function deleteImage(ImageInterface $image)
    {
        // TODO: Implement deleteImage() method.
    }

}