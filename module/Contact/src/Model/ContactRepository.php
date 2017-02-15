<?php

namespace Contact\Model;

use Contact\Entity\ContactInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\HydratorInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var ContactInterface
     */
    private $contactPrototype;

    /**
     * ZendDbSqlRepository constructor.
     *
     * @param AdapterInterface $db
     * @param HydratorInterface $hydrator
     * @param ContactInterface $contact
     */
    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        ContactInterface $contact
    )
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->contactPrototype = $contact;
    }


    /**
     * @inheritDoc
     */
    public function findAllContacts()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('contact');

        $resultSet = new HydratingResultSet($this->hydrator, $this->contactPrototype);

        $adapter = new DbSelect($select, $this->db, $resultSet);
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * @inheritDoc
     */
    public function findContact($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('contact');
        $select->where(['contact_id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new \RuntimeException(sprintf(
                'Failed retrieving contact with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->contactPrototype);
        $resultSet->initialize($result);
        $contact = $resultSet->current();

        if (! $contact) {
            throw new \InvalidArgumentException(sprintf(
                'Contact with identifier "%s" not found.',
                $id
            ));
        }

        return $contact;
    }
}