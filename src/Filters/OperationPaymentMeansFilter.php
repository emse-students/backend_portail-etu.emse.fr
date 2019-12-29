<?php


namespace App\Filters;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

final class OperationPaymentMeansFilter extends AbstractContextAwareFilter
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

        if ( $property != 'paymentMeans' ) {
            return;
        }

        $payment_means_ids = explode(',', $value);

        $query = '';
        $length = count($payment_means_ids);
        for ($i = 0; $i < $length; $i++) {
            if ($i > 0) {
                $query = $query.' OR ';
            }
            $query = $query.'p.id = ?'.$i;
        }

        $queryBuilder
            ->join('o.paymentMeans', 'p')
            ->andWhere($query);

        for ($i = 0; $i < $length; $i++) {
            $queryBuilder->setParameter($i, $payment_means_ids[$i]);
        }


    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];

        $description["search"] = [
            'property' => 'paymentMeans',
            'type' => 'integer',
            'required' => false,
            'swagger' => [
                'description' => '',
                'name' => 'Or where paymentMeansId',
                'type' => 'Filter',
            ],
        ];


        return $description;
    }
}