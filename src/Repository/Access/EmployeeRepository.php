<?php
namespace App\Repository\Access;

use App\Entity\Access\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode=null, $lockVersion=null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[] findAll()
 * @method Employee[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employeeObject = null)
    {
        $this->_em->persist($employeeObject);
        $this->_em->flush();

        return $employeeObject;
    }

    public function delete(Employee $employeeObject)
    {
        $this->_em->remove($employeeObject);
        $this->_em->flush();
    }

    public function findAllEmployeesWithUserRoles()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.user', 'u') // 'user' é o nome do relacionamento na entidade Employee
            ->getQuery()
            ->getResult();
    }

    public function getEmployeeById(int $employee_id)
    {
        $query = "
                SELECT
                    *
                FROM access.employee
                INNER JOIN access.user USING(user_id)
                WHERE employee_id = {$employee_id}
      ";
        $stmt = $this->_em->getConnection()->prepare($query);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}