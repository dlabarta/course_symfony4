<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Form\IssueSearchType;
use App\Form\IssueType;
use App\Managers\IssueManager;
use App\Repository\IssueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/issues")
 */
class IssueController extends AbstractController
{
    /**
     * @Route("/", name="issue_index", methods={"GET", "POST"})
     */
    public function index(Request $request, IssueRepository $issueRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $formSearch = $this->createForm(IssueSearchType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $categorySearch = $formSearch->getData()['categorySearch'];
            $titleSearch = $formSearch->getData()['titleSearch'];
            if ($this->isGranted('ROLE_ADMIN')) {
                $userSearch = null;
            } else {
                $userSearch = $user;
            }
            $issues = $issueRepository->findByCategoryAndTitle($userSearch, $categorySearch, $titleSearch);
        } else {
            if ($this->isGranted('ROLE_ADMIN')) {
                $issues = $issueRepository->findBy([], ['createdAt' => 'DESC']);
            } else {
                $issues = $issueRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);
            }
        }

        $paginator = $paginator->paginate(
            $issues,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('issue/index.html.twig', [
            'formSearch' => $formSearch->createView(),
            'issues' => $paginator,
        ]);
    }

    /**
     * @Route("/new", name="issue_new", methods={"GET","POST"})
     */
    public function new(Request $request, IssueManager $issueManager): Response
    {
        $issue = $issueManager->newObject();
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->getUser();
                $issue = $issueManager->create($issue, $user);

                $this->addFlash(
                    'success',
                    'Se ha creado correctamente'
                );

                return $this->redirectToRoute('issue_show', [
                    'id' => $issue->getId(),
                ]);
            } else {
                $this->addFlash(
                    'notice',
                    'Se han producido errores, revise el formulario.'
                );
            }
        }

        return $this->render('issue/form.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="issue_show", methods={"GET"})
     */
    public function show(Issue $issue): Response
    {
        $this->denyAccessUnlessGranted('view', $issue);

        return $this->render('issue/show.html.twig', [
            'issue' => $issue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="issue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Issue $issue, IssueManager $issueManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $issue);

        $issueManager->setOldVersion($issue); // Usado para comparar si ha cambiado el archivo y borrarlo.

        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $issue = $issueManager->update($issue);

                $this->addFlash(
                    'success',
                    'Se ha modificado correctamente'
                );

                return $this->redirectToRoute('issue_show', [
                    'id' => $issue->getId(),
                ]);
            } else {
                $this->addFlash(
                    'notice',
                    'Se han producido errores, revise el formulario.'
                );
            }
        }

        return $this->render('issue/form.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="issue_delete", methods={"GET"})
     */
    public function delete(Request $request, Issue $issue, IssueManager $issueManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $issue);

        $issueManager->delete($issue);

        return $this->redirectToRoute('issue_index');
    }
}
