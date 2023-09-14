<?php

namespace App\Controller;

use App\Entity\Acesso\Usuario;
use App\Repository\Acesso\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user_")
 */
class UsuarioController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UsuarioRepository $usuarioRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UsuarioRepository $usuarioRepository
    ){
        $this->passwordEncoder = $passwordEncoder;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request): Response
    {
        $users = $this->usuarioRepository->findAll();
        $roles = Usuario::getAcceptedRoles();

        return $this->render("user/index.html.twig", ["users" => $users, "roles" => $roles]);
    }
    /**
     * Method to save a new user or update a user that already exists
     * @Route("/add", name="add", methods={"POST", "GET"})
     * @Route("/edit/{id_usuario}", name="edit")
     *
     * @param Request $request
     * @param int|null $id_usuario
     *
     * @return Response
     */
    public function save(Request $request, int $id_usuario = null): Response
    {
        $is_edit = "user_edit" === $request->attributes->get('_route');
        $roles = Usuario::getAcceptedRoles();

        if ( $is_edit AND is_null($id_usuario) ) {
            $this->addFlash("error", "Usuário não existe!");
            return $this->redirectToRoute("user_default");
        }
        if ( $is_edit ) {
            $user = $this->usuarioRepository->findOneBy(["id_usuario" => $id_usuario]);
            $user->setDataUpd();
        } else {
            $user = new Usuario();
            $user->setDataAdd();
        }

        if ($request->isMethod("POST")) {
            try {
                $content = $request->request->all();
                $user->setNome($content['nome']);
                $user->setLogin($content['login']);
                $user->setEmail($content['email']);
                if ( !$is_edit ) {
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $content['senha']));
                }
                $user->setActive($content['active'] === "on");
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

                if ( $is_edit ) {
                    $this->usuarioRepository->save($user, true);
                } else {
                    $this->usuarioRepository->save($user);
                }
                $this->addFlash("success", "Usuário salvo!");
                return $this->redirectToRoute("user_default");
            } catch (\Exception $ex) {
                $this->addFlash("error", "Ocorreu um erro ao tentar salvar este usuário!");
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
        $user = $this->usuarioRepository->find($id);

        if ( $user !== null ) {
            try {
                $this->usuarioRepository->delete($user);
                $this->addFlash("success", "Usuário deletado com sucesso!");
            } catch (\Exception $ex) {
                $this->addFlash("error", "Erro ao deletar usuário");
            }
        } else {
            $this->addFlash("error", "Usuário não existe");
        }
        return $this->redirectToRoute("user_default");
    }
}