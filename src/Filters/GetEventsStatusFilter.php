<?php


namespace App\Filters;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

final class GetEventsStatusFilter extends AbstractContextAwareFilter
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
        if ( $property != 'status') {
            return;
        }
        $this->logger->info('value :'.$value);
        $status = explode('|',$value);
        $queryBuilder
            ->andWhere($queryBuilder->expr()->in('o.status', ':status'))
            ->setParameter('status', $status);
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];

        $description["time"] = [
            'property' => 'time',
            'type' => 'string',
            'required' => false,
            'swagger' => [
                'description' => 'Return all the events of the 3 weeks around the timestamp given',
                'name' => 'Weekly time filter on Events',
                'type' => 'Filter',
            ],
        ];


        return $description;
    }
}