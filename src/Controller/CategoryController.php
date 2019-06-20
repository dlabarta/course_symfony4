<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Managers\CategoryManager;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategoryManager $categoryManager): Response
    {
        $category = $categoryManager->newObject();
        $form = $this->createForm('App\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryManager->create($category);

            return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
        }

        return $this->render('category/form.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryManager->update($category);

            return $this->redirectToRoute('category_index', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('category/form.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"GET"})
     */
    public function delete(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        if ($category->getIssues()->count() == 0) {
            $categoryManager->delete($category);
        } else {
            $this->addFlash(
                'error',
                'No se puede borrar la categoría al tener incidencias asociadas.'
            );
        }

        return $this->redirectToRoute('category_index');
    }
}
