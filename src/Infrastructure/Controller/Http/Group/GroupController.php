<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Controller\Http\Group;

use ukickeru\AccessControlBundle\Application\Presenters\Group\GroupType;
use ukickeru\AccessControlBundle\UseCase\GroupRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ukickeru\AccessControlBundle\UseCase\AccessControlUseCase;
use ukickeru\AccessControlBundle\UseCase\GroupDTO;

/**
 * @Route("/groups")
 */
class GroupController extends AbstractController
{
    /**
     * @var AccessControlUseCase
     */
    private $useCase;

    /**
     * @var \ukickeru\AccessControlBundle\UseCase\GroupRepositoryInterface
     */
    private $groupRepository;

    public function __construct(
        AccessControlUseCase $useCase,
        GroupRepositoryInterface $groupRepository
    )
    {
        $this->useCase = $useCase;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @Route("/", name="group_index", methods={"GET"})
     */
    public function index(): Response
    {
        $groups = $this->useCase->getAllGroups();

        return $this->render('@access-control-bundle/Group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/new", name="group_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $groupDTO = new GroupDTO();
        $form = $this->createForm(GroupType::class, $groupDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->useCase->createGroup($groupDTO);

            return $this->redirectToRoute('group_index');
        }

        return $this->render('@access-control-bundle/Group/new.html.twig', [
            'group' => $groupDTO,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_show", methods={"GET"})
     * @param string $id
     * @return Response
     */
    public function show(string $id): Response
    {
        try {
            $group = $this->useCase->getGroup($id);
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute('group_index');
        }

        return $this->render('@access-control-bundle/Group/show.html.twig', [
            'group' => $group,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_edit", methods={"GET","POST"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function edit(Request $request, string $id): Response
    {
        try {
            $group = $this->useCase->getGroup($id);
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute('group_index');
        }

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->useCase->editGroup($group);

            return $this->redirectToRoute('group_index');
        }

        return $this->render('@access-control-bundle/Group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_delete", methods={"DELETE"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function delete(Request $request, string $id): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
                $this->useCase->removeGroup($id);
            }
        } catch (\Exception $exception) {
            $this->addFlash('error',$exception->getMessage());
            return $this->redirectToRoute('group_index');
        }

        return $this->redirectToRoute('group_index');
    }
}
