<?php

namespace App\Controller;

use App\Entity\Access\Employee;
use App\Entity\Access\User;
use App\Repository\Access\EmployeeRepository;
use App\Repository\Access\UserRepository ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/employee", name="employee_")
 */
class EmployeeController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private static TranslatorInterface $translator;
    private EmployeeRepository $employeeRepository;
    private UserRepository $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $trans,
        EmployeeRepository $employeeRepository,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        self::$translator = $trans;
        $this->employeeRepository = $employeeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request): Response
    {
        $employees = $this->employeeRepository->findAllEmployeesWithUserRoles();

        $roles = User::getAcceptedRoles();
        return $this->render("employee/index.html.twig", [
            'employees' => $employees,
            'roles' => $roles
        ]);
    }
    /**
     * Method to save a new employee or update an employee that already exists
     * @Route("/add", name="add", methods={"POST", "GET"})
     * @Route("/edit/{employee_id}", name="edit")
     *
     * @param Request $request
     * @param int|null $employee_id
     *
     * @return Response
     */
    public function save(Request $request, int $employee_id = null): Response
    {

        $is_edit = "employee_edit" === $request->attributes->get('_route');
        $locale = $request->getLocale();
        $roles = User::getAcceptedRoles();
        if ( $is_edit AND is_null($employee_id) ) {
            $this->addFlash("error", self::$translator->trans('text.employee.error-messages.not-found', [],'messages', $locale));
            return $this->redirectToRoute("employee_default");
        }
        if ( $is_edit ) {
            $employee = $this->employeeRepository->findOneBy(["employee_id" => $employee_id]);
            $employeeArray = $this->employeeRepository->getEmployeeById($employee_id);
            $user = $this->userRepository->findOneBy(["user_id" => $employee->getUser()->getUserId()]);

            $employee->setDateUpd();
            $user->setDateUpd();
        } else {
            $employee = new Employee();
            $user = new User();
            $employee->setDateAdd();
            $user->setDateAdd();
        }

        if ($request->isMethod("POST")) {
            try {
                $content = $request->request->all();
                $isActive = isset($content['active']) && $content['active'] === "on";

                # Creating a new user using the employee's data
                $user->setName($content['name']);
                $user->setLogin($content['login']);
                $user->setEmail($content['email']);
                if ( !$is_edit ) {
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $content['senha']));
                }
                $user->setActive($isActive);
                $user->cleanProfile();
                $profiles = array();
                if( isset($content['profile']) ) {
                    foreach ( $content['profile']['perfil'] AS $key => $profile) {
                        if ( $profile === "true" ) {
                            $profiles[] = $key;
                        }
                    }
                    $user->setProfile($profiles);
                } else {
                    $user->setProfile(array(""));
                }
                $user->setForceUpdate(false);
                $userObject = $this->userRepository->save($user);

                # Setting data to employee's object
                $employee->setName($content['name']);
                $employee->setCpf($content['cpf']);
                $employee->setPhone($content['phone']);
                $employee->setEmail($content['email']);
                $employee->setUser($userObject);
                $employee->setActive($isActive);

                $this->employeeRepository->save($employee);

                $this->addFlash("success", self::$translator->trans('text.employee.success-messages.save', [],'messages', $locale));
                return $this->redirectToRoute("employee_default");
            } catch (\Exception $ex) {
                $this->addFlash("error", $ex->getMessage());
                return $this->redirectToRoute("employee_default");
            }
        }

        return $this->render('employee/save.html.twig', [
            "employee" => $employeeArray ?? null,
            'roles' => $roles
        ]);
    }
    /**
     * @Route("/delete/{employee_id}", name="delete")
     */
    public function delete(Request $request, $employee_id): Response
    {
        $employee = $this->employeeRepository->find($employee_id);
        $user = $this->userRepository->findOneBy(["user_id" => $employee->getUser()->getUserId()]);
        $locale = $request->getLocale();
        if ( $user === null ) {
            $this->addFlash("error", self::$translator->trans('text.user.error-messages.not-found', [],'messages', $locale));
        }
        if ( $user->getUserId() !== null ) {
            try {
                $this->employeeRepository->delete($employee);
                $this->userRepository->delete($user);

                $this->addFlash("success", self::$translator->trans('text.employee.success-messages.delete', [],'messages', $locale));
            } catch (\Exception $ex) {
                $this->addFlash("error", self::$translator->trans('text.employee.error-messages.delete', [],'messages', $locale));
            }
        } else {
            $this->addFlash("error", self::$translator->trans('text.employee.error-messages.not-found', [],'messages', $locale));
        }
        return $this->redirectToRoute("employee_default");
    }
}