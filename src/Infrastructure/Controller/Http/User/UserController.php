<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Controller\Http\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControlBundle\Application\Presenters\User\ChangeAdminPermissionsType;
use ukickeru\AccessControl\UseCase\ChangeAdminPermissionsDTO;
use ukickeru\AccessControl\UseCase\UserRepositoryInterface;
use ukickeru\AccessControlBundle\Application\Presenters\User\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ukickeru\AccessControl\UseCase\AccessControlUseCase;
use ukickeru\AccessControl\UseCase\UserDTO;
use ukickeru\AccessControlBundle\Model\User;

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
     * @Route(
     *     ApplicationRoutesContainer::USER_INDEX_PATH,
     *     name=ApplicationRoutesContainer::USER_INDEX_NAME,
     *     methods={"GET"}
     * )
     */
    public function index(): Response
    {
        $users = $this->useCase->getAllUsers();

        return $this->render('@access-control-bundle/User/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route(
     *     ApplicationRoutesContainer::USER_NEW_PATH,
     *     name=ApplicationRoutesContainer::USER_NEW_NAME,
     *     methods={"GET","POST"}
     * )
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

            $this->addFlash('notice','Пользователь был успешно создан!');
            return $this->redirectToRoute(ApplicationRoutesContainer::USER_INDEX_NAME);
        }

        return $this->render('@access-control-bundle/User/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     ApplicationRoutesContainer::USER_SHOW_PATH,
     *     name=ApplicationRoutesContainer::USER_SHOW_NAME,
     *     methods={"GET"}
     * )
     * @param string $id
     * @return Response
     */
    public function show(string $id): Response
    {
        try {
            $user = $this->useCase->getUser($id);
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute(ApplicationRoutesContainer::USER_INDEX_NAME);
        }

        return $this->render('@access-control-bundle/User/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route(
     *     ApplicationRoutesContainer::USER_EDIT_PATH,
     *     name=ApplicationRoutesContainer::USER_EDIT_NAME,
     *     methods={"GET","POST"}
     * )
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

            $this->addFlash('notice','Пользователь был успешно отредактирован!');
            return $this->redirectToRoute(ApplicationRoutesContainer::USER_INDEX_NAME);
        }

        return $this->render('@access-control-bundle/User/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     ApplicationRoutesContainer::USER_DELETE_PATH,
     *     name=ApplicationRoutesContainer::USER_DELETE_NAME,
     *     methods={"DELETE"}
     * )
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
            return $this->redirectToRoute(
                ApplicationRoutesContainer::USER_EDIT_NAME,
                [
                    'request' => $request,
                    'id' => $id
                ]
            );
        }

        $this->addFlash('notice','Пользователь был успешно удалён!');
        return $this->index();
    }

    /**
     * @Route(
     *     ApplicationRoutesContainer::CHANGE_ADMIN_PATH,
     *     name=ApplicationRoutesContainer::CHANGE_ADMIN_NAME,
     *     methods={"GET","POST"}
     * )
     * @param Request $request
     * @return Response
     */
    public function changeAdmin(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $turnAdministrativePermissionsDTO = new ChangeAdminPermissionsDTO();

        $form = $this->createForm(ChangeAdminPermissionsType::class, $turnAdministrativePermissionsDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->useCase->turnAdministrativePermissionsToAnotherUser($turnAdministrativePermissionsDTO);
            } catch (\Exception $exception) {
                $this->addFlash('error',$exception->getMessage());
                return $this->render('@access-control-bundle/User/change_admin.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }

            $this->addFlash('notice','Права администратора были успешно переданы!');
            return $this->redirectToRoute(ApplicationRoutesContainer::USER_INDEX_NAME);
        }

        return $this->render('@access-control-bundle/User/change_admin.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
