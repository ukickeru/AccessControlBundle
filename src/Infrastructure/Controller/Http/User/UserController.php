<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Controller\Http\User;

use ukickeru\AccessControlBundle\Application\Presenters\User\ChangeAdminPermissionsType;
use ukickeru\AccessControl\UseCase\ChangeAdminPermissionsDTO;
use ukickeru\AccessControl\UseCase\UserRepositoryInterface;
use ukickeru\AccessControlBundle\Application\Presenters\User\UserType;
use ukickeru\AccessControlBundle\Infrastructure\Controller\Http\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ukickeru\AccessControl\UseCase\AccessControlUseCase;
use ukickeru\AccessControl\UseCase\UserDTO;

class UserController extends AbstractController
{
    /**
     * @var AccessControlUseCase
     */
    private $useCase;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(
        AccessControlUseCase $useCase,
        UserRepositoryInterface $userRepository
    )
    {
        $this->useCase = $useCase;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->useCase->getAllUsers();

        return $this->render('@access-control-bundle/User/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/users/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new UserDTO();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->useCase->createUser($user);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@access-control-bundle/User/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_show", methods={"GET"})
     * @param string $id
     * @return Response
     */
    public function show(string $id): Response
    {
        try {
            $user = $this->useCase->getUser($id);
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute('group_index');
        }

        return $this->render('@access-control-bundle/User/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function edit(Request $request, string $id): Response
    {
        try {
            $user = $this->useCase->getUser($id);
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute('group_index');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->useCase->editUser($user);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@access-control-bundle/User/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function delete(Request $request, string $id): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
                $this->useCase->removeUser($id);
            }
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute('user_index');
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/change_admin", name="change_admin", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function changeAdmin(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeAdminPermissionsType::class, new ChangeAdminPermissionsDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAdmin = $form->get('newAdmin');
            $confirmed = $request->get('confirmed');
            $turnAdministrativePermissionsDTO = new ChangeAdminPermissionsDTO($newAdmin,$confirmed);

            try {
                $this->useCase->turnAdministrativePermissionsToAnotherUser($turnAdministrativePermissionsDTO);
            } catch (\Exception $exception) {
                $this->addFlash('error',$exception->getMessage());
            }

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('@access-control-bundle/User/change_admin.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
