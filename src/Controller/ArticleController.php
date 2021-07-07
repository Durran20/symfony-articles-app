<?php
    namespace App\Controller;

    use App\Entity\Articles;

    use Symfony\Component\HttpFoundation\Response;
    
    // So we can use Request
    use Symfony\Component\HttpFoundation\Request;

    // So that Twig and other bundles work
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    
    // This will help us use Annotations for Routing
    use Symfony\Component\Routing\Annotation\Route;

    // This allows us to specify or restrict certain methods (GET, POST, PUT)
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; 

    // So we can use forms
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;


    class ArticleController extends AbstractController {
        /**
         * @Route("/", name="article_list", methods={"GET"})
         */
        public function index()
        {
            // return new Response('<html><body>Hello World</body></html>');

            // $articles = ['Article 1', 'Article 2', 'Article 3', 'Article 4'];

            // FETCHING THE ARTICLES FROM THE DATABASE
            $articles = $this->getDoctrine()->getRepository(Articles::class)->findAll();

            // The stuff we wanna render using twig
            return $this->render('articles/index.html.twig', array('name' => 'Brad', 'articles' => $articles));
        }


        // Creating a new article
        /**
         * @Route("/article/new", name="article_new", methods={"GET", "POST"})
         */
        public function new(Request $request)
        {
            $article = new Articles();

            // Making the form
            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array(
                    'attr' => array('class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline')
                ))
                ->add('body', TextareaType::class, array(
                    'attr' => array('class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline')
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Create',
                    'attr' => array('class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mt-3')
                ))
                ->getForm();

            // Submitting the form
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $article = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('article_list');

            }

            // Rendering the form
            return $this->render('articles/new.html.twig', array(
                'form' => $form->createView()
            ));
        }

        // Routes to show individual articles
        /**
         * @Route("/article/{id}", name="article_show")
         */
        public function show($id)
        {
            $article = $this->getDoctrine()->getRepository(Articles::class)->find($id);

            return $this->render('articles/show.html.twig', array('article' => $article));
        }

        // Route to delete
        /**
         * @Route("/article/delete/{id}", name="article_delete", methods={"DELETE"})
         */
        public function delete(Request $request, $id) {
            $article = $this->getDoctrine()->getRepository(Articles::class)->find($id);
      
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
      
            $response = new Response();
            $response->send();
        }


        // /**
        //  * @Route("/article/save")
        //  */
        // public function save()
        // {
        //     $entityManager = $this->getDoctrine()->getManager();

        //     $article = new Articles();

        //     $article->setTitle('Article Two');
        //     $article->setBody('This is the body for article two');

        //     $entityManager->persist($article);

        //     $entityManager->flush();

        //     return new Response('Saved an article with the id of '.$article->getId());
        // }

    }