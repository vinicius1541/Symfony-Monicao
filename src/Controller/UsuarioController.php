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
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request, UsuarioRepository $usuarioRepository)
    {
        $users = $usuarioRepository->findAll();
        $roles = Usuario::getAcceptedRoles();

        return $this->render("user/index.html.twig", ["users" => $users, "roles" => $roles]);
    }
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, UsuarioRepository $usuarioRepository)
    {
        $roles = Usuario::getAcceptedRoles();
        if ($request->isMethod("POST")) {
            try {
                $usuarioObject = new Usuario();
                $usuarioObject->setNome($request->request->get('nome'));
                $usuarioObject->setLogin($request->request->get('login'));
                $usuarioObject->setEmail($request->request->get('email'));
                $usuarioObject->setPassword($this->passwordEncoder->encodePassword($usuarioObject, $request->request->get('senha')));
                $usuarioObject->setActive($request->request->get('active') === "on");
                $profiles = array();
                if( $request->request->get('profile') ) {
                    foreach ( $request->request->get('profile')['perfil'] AS $profile) {
                        $profiles[] = $profile;
                    }
                    $usuarioObject->setProfile($profiles);
                } else {
                    $usuarioObject->setProfile(array(""));
                }
                $usuarioObject->setDataAdd();
                $usuarioObject->setForceUpdate(false);

                $usuarioRepository->save($usuarioObject);
                $this->addFlash("success", "Usuário salvo!");
            } catch (\Exception $ex) {
                $this->addFlash("error", "Ocorreu um erro ao tentar salvar este usuário!");
            }
        }
        return $this->render("user/add.html.twig", ['roles' => $roles]);
    }
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, $id, UsuarioRepository $usuarioRepository)
    {

        $roles = Usuario::getAcceptedRoles();
        $user = $usuarioRepository->find($id);
        if( $user !== null) {
            if( $request->isMethod("POST") ) {
                $user->setNome($request->request->get('nome'));
                $user->setLogin($request->request->get('login'));
                $user->setEmail($request->request->get('email'));
                $user->setActive($request->request->get('active') === "on");
                $user->cleanProfile();
                $profiles = array();
                if( $request->request->get('profile') ) {
                    foreach ( $request->request->get('profile')['perfil'] AS $profile) {
                        $profiles[] = $profile;
                    }
                    $user->setProfile($profiles);
                } else {
                    $user->setProfile(array(""));
                }
                $user->setDataAdd($user->getDataAdd());
                $user->setDataUpd();

                try {
                    $usuarioRepository->save(null, true);
                    $this->addFlash("success", "Usuário editado com sucesso!");
                } catch (\Exception $ex) {
                    $this->addFlash("error", "Erro ao tentar editar este usuário!: {$ex->getMessage()}");
                }
            }
        } else {
            $this->addFlash("error", "Usuário não existe!");
            return $this->redirectToRoute('user_default');
        }

        return $this->render("user/edit.html.twig", ['user' => $user, 'roles' => $roles]);
    }
    /**
     * @Route("/delete/{id}",name="delete")
     */
    public function delete(Request $request, $id, UsuarioRepository $usuarioRepository): Response
    {
        $user = $usuarioRepository->find($id);

        if ( $user !== null ) {
            try {
                $usuarioRepository->delete($user);
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