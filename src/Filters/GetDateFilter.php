<?php


namespace App\Filters;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

final class GetDateFilter extends AbstractContextAwareFilter
{
    /**
     * UserAllFilter constructor.
     */
    public function __construct(LoggerInterface $logger, ManagerRegistry $managerRegistery)
    {
        parent::__construct($managerRegistery);
        $this->logger = $logger;
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ( $property != 'startDate' and $property != 'endDate') {
            return;
        }
        $date = new DateTime();
        $date->setTimestamp($value);
//        $this->logger->info('date :'.$date->format('j/n/Y G:i:s'));

        if ($resourceClass == 'App\Entity\EventBand') {
            if ($property == 'startDate') {
                $sql = 'o.endingDate >= :'.$property;
            } else {
                $sql = 'o.startingDate < :'.$property;
            }
        } else {
            if ($property == 'startDate') {
                $sql = 'o.date >= :'.$property.' OR o.shotgunStartingDate >= :'.$property;
            } else {
                $sql = 'o.date < :'.$property.' OR o.shotgunStartingDate < :'.$property;
            }
        }

        $queryBuilder
            ->andWhere($sql)
            ->setParameter($property, $date);
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];

        $description["time"] = [
            'property' => 'startDate or endDate',
            'type' => 'timestamp',
            'required' => false,
            'swagger' => [
                'description' => 'Return the object with a date filter',
                'name' => 'Time filter on Events',
                'type' => 'Filter',
            ],
        ];


        return $description;
    }
}