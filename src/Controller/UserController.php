<?php

namespace App\Controller;

use App\Entity\Access\User;
use App\Repository\Access\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $userRepository;
    private static TranslatorInterface $translator;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ){
        $this->passwordEncoder = $passwordEncoder;
        self::$translator = $translator;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request): Response
    {
        $users = $this->userRepository->findUsersWithEmployees();
        $roles = User::getAcceptedRoles();

        return $this->render("user/index.html.twig", ["users" => $users, "roles" => $roles]);
    }
    /**
     * Method to save a new user or update a user that already exists
     * @Route("/add", name="add", methods={"POST", "GET"})
     * @Route("/edit/{user_id}", name="edit")
     *
     * @param Request $request
     * @param int|null $user_id
     *
     * @return Response
     */
    public function save(Request $request, int $user_id = null): Response
    {
        $is_edit = "user_edit" === $request->attributes->get('_route');
        $roles = User::getAcceptedRoles();
        $locale = $request->getLocale();

        if ( $is_edit AND is_null($user_id) ) {
            $this->addFlash("error", self::$translator->trans('text.user.error-messages.not-found', [],'messages', $locale));
            return $this->redirectToRoute("user_default");
        }
        if ( $is_edit ) {
            $user = $this->userRepository->findOneBy(["user_id" => $user_id]);
            $user->setDateUpd();
        } else {
            $user = new User();
            $user->setDateAdd();
        }

        if ($request->isMethod("POST")) {
            try {
                $content = $request->request->all();
                $user->setName($content['nome']);
                $user->setLogin($content['login']);
                $user->setEmail($content['email']);
                if ( !$is_edit ) {
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $content['senha']));
                }
                $isActive = isset($content['active']) && $content['active'] === "on";
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

                $this->userRepository->save($user);

                $this->addFlash("success", self::$translator->trans('text.user.success-messages.save', [],'messages', $locale));
                return $this->redirectToRoute("user_default");
            } catch (\Exception $ex) {
                $this->addFlash("error", self::$translator->trans('text.user.error-messages.save', [],'messages', $locale));
                return $this->redirectToRoute("user_default");
            }
        }

        return $this->render('user/save.html.twig', [
            "user" => $user,
            "roles" => $roles
        ]);
    }
    /**
     * @Route("/delete/{id}",name="delete")
     */
    public function delete(Request $request, $id): Response
    {
        $user = $this->userRepository->find($id);
        $locale = $request->getLocale();
        if ( $user !== null ) {
            try {
                $this->userRepository->delete($user);
                $this->addFlash("success", self::$translator->trans('text.user.success-messages.delete', [],'messages', $locale));
            } catch (\Exception $ex) {
                $this->addFlash("error", self::$translator->trans('text.user.error-messages.delete', [],'messages', $locale));
            }
        } else {
            $this->addFlash("error", self::$translator->trans('text.user.error-messages.not-found', [],'messages', $locale));
        }
        return $this->redirectToRoute("user_default");
    }
}