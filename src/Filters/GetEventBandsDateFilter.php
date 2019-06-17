<?php


namespace App\Filters;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

final class GetEventBandsDateFilter extends AbstractContextAwareFilter
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
        if ( $property != 'time') {
            return;
        }

        if ($value == 'now') {
            $curentDate = new \DateTime();
            $lastMonday = new \DateTime();
            $nextMonday = new \DateTime();

        } else {
            $curentDate = new \DateTime();
            $lastMonday = new \DateTime();
            $nextMonday = new \DateTime();
            $curentDate->setTimestamp($value);
            $lastMonday->setTimestamp($value);
            $nextMonday->setTimestamp($value);
        }

        $lastMonday->modify("last monday");
        if ($curentDate->format('N') == 1) {
            $lastMonday->modify("next monday");
        }
        $nextMonday->modify("next monday");
        $nextMonday->modify("next monday");
        $nextMonday->modify("next monday");
        $nextMonday->modify("next monday");
        $this->logger->info('lastMonday date : '. $lastMonday->format('j/n/Y G:i:s'));
        $this->logger->info('thirdNextMonday date : '. $nextMonday->format('j/n/Y G:i:s'));

        $queryBuilder
            ->andWhere('o.endingDate >= ?1')
            ->andWhere('o.startingDate < ?2')
            ->setParameter(1, $lastMonday)
            ->setParameter(2, $nextMonday);
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
                'description' => 'Return all the EventBands of the 3 weeks around the timestamp given',
                'name' => 'Weekly time filter on EventBand',
                'type' => 'Filter',
            ],
        ];


        return $description;
    }
}