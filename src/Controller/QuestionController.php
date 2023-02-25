<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\User;
use App\Form\PostFormType;
use App\Command\CreatePostCommand;
use App\Command\CreatePostHandler;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Service\CommandBus;
use App\Infrastructure\Persistence\EventStore\PostRepository;;

/**
 * Class QuestionController
 * @package App\Controller
 * @Route("/questions")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route(name="api_questions_collection_get", methods={"GET"})
     * @param QuestionRepository $questionRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(QuestionRepository $questionRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($questionRepository->findAll(), "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_questions_item_get", methods={"GET"})
     * @param Question $question
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    /*public function item(?Question $question, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($question, "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }*/

    /**
     * @Route(name="api_questions_collection_post", methods={"POST"})
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $urlGenerator
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function post(
        Question $question,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ): JsonResponse {
        $question->setAuthor($entityManager->getRepository(User::class)->findOneBy([]));

        $errors = $validator->validate($question);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->persist($question);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($question, "json", ["groups" => "get"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_questions_item_get", ["id" => $question->getId()])],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_questions_item_put", methods={"PUT"})
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function put(
        Question $question,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $errors = $validator->validate($question);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}", name="api_questions_item_delete", methods={"DELETE"})
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(
        Question $question,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($question);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api_post", name="api_post")
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function create(Request $request, CommandBus $cmdBus, PostRepository $postRep): Response {

        $aPostCommand = new CreatePostCommand(
            'Write a blog post',
            'The Post title'
        );
        
        $form = $this->createForm(PostFormType::class, $aPostCommand)
                ->add('title', TextType::class)
                ->add('content', TextareaType::class)
                ->add('save', SubmitType::class)
            ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //return $this->render('new_posts.html.twig', ['form' => $form->createView()]);
            $data = $form->getData();

            $aPostCommand = new CreatePostCommand(
                $data->getContent(),
                $data->getTitle()
            );
            $cmdBus->register(new CreatePostHandler($postRep));
            $cmdBus->handle($aPostCommand);
            echo '<pre>';var_dump($cmdBus);echo '</pre>';
            
            
            die('eeeee');
        }

        return $this->render('new_post.html.twig', ['form' => $form->createView()]);
        /*$form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('content', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('save', 'submit')
            ->getForm();
            /*$form = $app['form.factory']->createBuilder('form', $aPostCommand)
                ->add('title', 'text')
                ->add('content', 'textarea')
                ->add('save', 'submit')
                ->getForm()
            ;*/

            //return $this->render('new_post.html.twig', ['form' => $form->createView()]);
        //$postForm = $this->createForm(NoteFormType::class, $note);
        /*$noteForm->handleRequest($request);

        if ($noteForm->isSubmitted() && $noteForm->isValid()) {

            die();
            $data = $noteForm->getData();
            $message = $data->message;
            $created = $data->created->format('Y-m-d h:i:s');

            return $this->redirectToRoute('success',
                ['message' => $message, 'created' => $created]);
        }*/
        /*$entityManager->remove($question);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);*/
        /*$form = $app['form.factory']->createBuilder('form', $aPostCommand)
        ->add('title', 'text')
        ->add('content', 'textarea')
        ->add('save', 'submit')
        ->getForm()
    ;

    return $app['twig']->render('new_post.html.twig', ['form' => $form->createView()]);*/
    }
    

    
}