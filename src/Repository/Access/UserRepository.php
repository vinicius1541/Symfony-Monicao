<?php
namespace App\Repository\Access;

use App\Entity\Access\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode=null, $lockVersion=null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $userObject = null)
    {
        $this->_em->persist($userObject);
        $this->_em->flush();

        return $userObject;
    }

    public function delete(User $userObject)
    {
        $this->_em->remove($userObject);
        $this->_em->flush();
    }

    public function findUsersWithEmployees()
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.employee', 'e')
            ->where('e.user IS NULL')
            ->getQuery()
            ->getResult();
    }
}