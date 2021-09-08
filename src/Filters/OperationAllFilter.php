<?php


namespace App\Filters;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

final class OperationAllFilter extends AbstractContextAwareFilter
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

        if ( $property != 'search' ) {
            return;
        }

        $queryBuilder
            ->join('o.user', 'u')
            ->andWhere('u.firstname LIKE :search OR u.lastname LIKE :search OR o.type LIKE :search OR CONCAT(u.firstname, \' \', u.lastname) LIKE :search OR u.type LIKE :search OR u.promo LIKE :search OR o.reason LIKE :search')
            ->setParameter('search', '%'.$value.'%');
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];

        $description["search"] = [
            'property' => 'search',
            'type' => 'string',
            'required' => false,
            'swagger' => [
                'description' => '',
                'name' => 'Search Operation',
                'type' => 'Filter',
            ],
        ];


        return $description;
    }
}