<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Managers\TagManager;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tags")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/", name="tag_index", methods={"GET"})
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tag_new", methods={"GET","POST"})
     */
    public function new(Request $request, TagManager $tagManager): Response
    {
        $tag = $tagManager->newObject();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $tagManager->create($tag);

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/form.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tag_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tag $tag, TagManager $tagManager): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $tagManager->update($tag);

            return $this->redirectToRoute('tag_index', [
                'id' => $tag->getId(),
            ]);
        }

        return $this->render('tag/form.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_delete", methods={"GET"})
     */
    public function delete(Request $request, Tag $tag, TagManager $tagManager): Response
    {
        if ($tag->getIssues()->count() == 0) {
            $tagManager->delete($tag);
        } else {
            $this->addFlash(
                'error',
                'No se puede borrar el tag al tener incidencias asociadas.'
            );
        }

        return $this->redirectToRoute('tag_index');
    }
}
