<?php

namespace Contact\Model;


use Contact\Entity\CountryInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\HydratorInterface;

class CountryModel implements CountryModelInterface
{
    const TABLE_NAME = 'country';

    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var CountryInterface
     */
    private $countryPrototype;

    /**
     * CountryModel constructor.
     * @param AdapterInterface $db
     * @param HydratorInterface $hydrator
     * @param CountryInterface $countryPrototype
     */
    public function __construct(AdapterInterface $db, HydratorInterface $hydrator, CountryInterface $countryPrototype)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->countryPrototype = $countryPrototype;
    }

    /**
     * @inheritDoc
     */
    public function fetchAllCountries()
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \DomainException('Cannot find countries');
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->countryPrototype);
        $resultSet->initialize($result);

        if (!$resultSet) {
            throw new \RuntimeException('Something went wrong processing the country data');
        }
        return $resultSet;
    }

    /**
     * @inheritDoc
     */
    public function findCountryByIso($iso)
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);
        $select->where(['iso = ?' => $iso]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \DomainException('Cannot find countries');
        }

        $countryRow = $result->current();
        if (false === $countryRow) {
            return $this->countryPrototype;
        }
        return $this->hydrator->hydrate($result->current(), $this->countryPrototype);
    }

}