<?php

namespace VBcom\TaskServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use VBcom\TaskServerBundle\Entity\Task;
use VBcom\TaskServerBundle\Form\TaskType;

/**
 * Task controller.
 *
 */
class TaskController extends Controller
{
    /**
     * Find all tasks
     * @return type 
     */
    private function findAll()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('VBcomTaskServerBundle:Task')->findAll();
        
        return $entities;
    }
  
    /** 
     * Return 1 task entity
     */
    private function show($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('VBcomTaskServerBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }
        
        return $entity;
    }
    
    /**
     * Converts $entity object to array
     * 
     * @param type $entity
     * @return mixed
     */
    private function entityToArray($entity)
    {
        return array(
          'id'          => $entity->getId(),
          'title'       => $entity->getTitle(),
          'description' => $entity->getDescription(),
          'start_date'  => $entity->getStartDate(),
          'finish_date' => $entity->getFinishDate(),
          'created_at'  => $entity->getCreatedAt(),
          'updated_at'  => $entity->getUpdatedAt(),
          'done'        => $entity->getDone(),
          'priority'    => $entity->getPriority()
        );
    }
    
    /**
     * Lists all Task entities.
     *
     */
    public function indexAction()
    {
        $entities = $this->findAll();
        return $this->render('VBcomTaskServerBundle:Task:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Lists all Task entities in json format.
     *
     */    
    public function indexJsonAction()
    {
        $entities = $this->findAll();
        $tasks = array();
        foreach ($entities as $entity)
        {
          $task = $this->entityToArray($entity);
          $tasks[] = $task;
        }

        $response = new Response(json_encode($tasks));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction($id)
    {
        $entity = $this->show($id);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VBcomTaskServerBundle:Task:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Task entity in json format.
     *
     */    
    public function  showJsonAction($id)
    {
        $task = $this->entityToArray($this->show($id));
        
        $response = new Response(json_encode($task));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }


    /**
     * Displays a form to create a new Task entity.
     *
     */
    public function newAction()
    {
        $entity = new Task();
        $form   = $this->createForm(new TaskType(), $entity);

        return $this->render('VBcomTaskServerBundle:Task:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Task entity.
     *
     */
    public function createAction()
    {
        $entity  = new Task();
        $request = $this->getRequest();
        $form    = $this->createForm(new TaskType(), $entity);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
                
            }
        }

        return $this->render('VBcomTaskServerBundle:Task:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('VBcomTaskServerBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VBcomTaskServerBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Task entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('VBcomTaskServerBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm   = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $editForm->bindRequest($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('task_edit', array('id' => $id)));
            }
        }

        return $this->render('VBcomTaskServerBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository('VBcomTaskServerBundle:Task')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Task entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('task'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
