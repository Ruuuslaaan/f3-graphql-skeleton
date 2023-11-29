<?php
declare(strict_types=1);

namespace App\Code\Repository;

use App\Code\Entity\Customer;
use Doctrine\ORM\EntityRepository;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[] findAll()
 * @method Customer[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends EntityRepository
{
}
