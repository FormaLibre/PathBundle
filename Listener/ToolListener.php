<?php

namespace Innova\PathBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\DisplayToolEvent;

use Innova\PathBundle\Entity\Path;

class ToolListener extends ContainerAware
{
    public function onWorkspaceOpen(DisplayToolEvent $event)
    {
        $id = $event->getWorkspace()->getId();
        $subRequest = $this->container->get('request')->duplicate(array('id' => $id), array(), array("_controller" => 'innova_path.controller.path:fromWorkspaceAction'));
        $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        $event->setContent($response->getContent());
    }

    public function onPathOpen(OpenResourceEvent $event)
    {
        $path = $event->getResource();
        $route = $this->container
            ->get('router')
            ->generate(
                'innova_path_player_index',
                array(
                    'workspaceId' => $path->getResourceNode()->getWorkspace()->getId(),
                    'pathId' => $path->getId(),
                    'stepId' => $path->getRootStep()->getId()
                )
            );
        
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

    public function onPathCreate(CreateResourceEvent $event)
    {
        // Create form
        $form = $this->container->get('form.factory')->create('innova_path', new Path());
        
        // Try to prcess form
        $request = $this->container->get('request');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $path = $form->getData();

            $properties = array (
                'name' => $path->getName(),
                'description' => $path->getDescription(),
            );

            $path->setStructure(json_encode($properties));
            $event->setResources(array ($path));
        }
        else {
            $content = $this->container->get('templating')->render(
                'ClarolineCoreBundle:Resource:createForm.html.twig',
                array(
                    'form' => $form->createView(),
                    'resourceType' => 'innova_path'
                )
            );

            $event->setErrorFormContent($content);
        }
        
        $event->stopPropagation();
    }

    public function onPathCreateForm(CreateFormResourceEvent $event)
    {
        // Create form
        $form = $this->container->get('form.factory')->create('innova_path', new Path());
        
        $content = $this->container->get('templating')->render(
            'ClarolineCoreBundle:Resource:createForm.html.twig',
            array(
                'form' => $form->createView(),
                'resourceType' => 'innova_path'
            )
        );

        $event->setResponseContent($content);
        $event->stopPropagation();
    }

    public function onNonDigitalResourceOpen(OpenResourceEvent $event)
    {
        $nonDigitalResource = $event->getResource();
        $route = $this->container
            ->get('router')
            ->generate(
                'innova_nondigitalresource_player',
                array(
                    'nonDigitalResourceId' => $nonDigitalResource->getId(),
                )
            );
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

    public function onPathDelete(DeleteResourceEvent $event)
    {
        $event->stopPropagation();
    }

    public function onNonDigitalResourceDelete(DeleteResourceEvent $event)
    {
        $ndr = $event->getResource();

        $em = $this->container->get('doctrine.orm.entity_manager');
        $step2resourceNodes = $em->getRepository('InnovaPathBundle:Step2ResourceNode')->findByResourceNode($ndr->getResourceNode());

        if(count($step2resourceNodes) > 0){
            throw new \Exception('The resource you want to delete is used.');
           }
    }
}
