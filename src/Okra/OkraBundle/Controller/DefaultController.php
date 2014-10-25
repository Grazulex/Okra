<?php

namespace Okra\OkraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Okra\OkraBundle\Entity\OrdersItem;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('OkraBundle:Orders');
        $repositoryOthers = $this->getDoctrine()->getRepository('OkraBundle:Others');
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        
        $actifSession = $repositorySessions->getActiveSession();
        
        $newOrder = $repository->createNewOrder($actifSession);

        $form = $this->createFormBuilder($newOrder)
            ->setAction($this->generateUrl('okra_homepage'))
            ->setMethod('post')
            ->add('idTable', 'integer',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Table'))
            ->add('idOrderManual', 'integer',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Order','required'  => false))
            ->add('idUser', 'entity', array('attr'=> array('class'=>'form-control input-lg'),   'class' => 'OkraBundle:User', 'property' => 'username','label'  => 'User'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create table'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em->persist($newOrder);
            $em->flush();
        }

        $newBook = $repositoryOthers->createNewOther();
        $formBook = $this->createPersoForm($newBook, 'okra_book', 'Create book');                
        $formBook->handleRequest($request);   
        
        $newBuying = $repositoryOthers->createNewOther();
        $formBuying = $this->createPersoForm($newBuying, 'okra_buying', 'Create bying'); 
        $formBuying->handleRequest($request);   
        
        $newOthers = $repositoryOthers->createNewOther();
        $formOthers = $this->createPersoForm($newOthers, 'okra_others', 'Create others'); 
        $formOthers->handleRequest($request);   
        
        $newStarts = $repositoryOthers->createNewOther();
        $formStarts = $this->createPersoForm($newStarts, 'okra_starts', 'Create start'); 
        $formStarts->handleRequest($request);        
                
        
        $count = $repository->getNbClose($actifSession);
        $total = $repository->getTotalClose($actifSession);
        $totalBook = $repositoryOthers->getTotalBookTodayClose($actifSession);
        $totalBuying = $repositoryOthers->getTotalBuyingTodayClose($actifSession);
        $totalOthers = $repositoryOthers->getTotalOthersTodayClose($actifSession);
        $totalStart = $repositoryOthers->getTotalStartsTodayClose($actifSession);
               
        $Gtotal = $totalStart + $total + $totalBook - $totalBuying + $totalOthers;
        
        $Orders = $repository->getAllOpen();
        return $this->render('OkraBundle:Default:index.html.twig', array("actifsession"=>$actifSession, "locale"=>$this->get('request')->getLocale(), "orders"=>$Orders, "statsCount"=>$count, "statsTotal"=>$total, 'form' => $form->createView(), 'formBook' => $formBook->createView(), 'formBuying' => $formBuying->createView(), 'formOthers' => $formOthers->createView(), 'formStarts' => $formStarts->createView(),'totalStart'=>$totalStart, 'totalBook'=>$totalBook,'totalBuying'=>$totalBuying,'totalOthers'=>$totalOthers,'Gtotal'=>$Gtotal));
    }
    
    public function tableAction($tableId,Request $request) {        
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('OkraBundle:Orders');
        $order = $repository->find($tableId);
                        
        $newOrderItemsForm = new OrdersItem();
        $newOrderItemsForm->setIdOrder($order);
        
        $form = $this->createFormBuilder($newOrderItemsForm)
            ->setAction($this->generateUrl('okra_table',array('tableId'=>$tableId)))
            ->setMethod('post')
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Insert'))     
            ->getForm();        
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            if($datas = $request->request->get('quant')) {
                foreach ($datas as $data => $key) {
                    if ((int)$key > 0) {
                        $repository= $this->getDoctrine()->getRepository('OkraBundle:Item');
                        $itemForm = $repository->find($data);
                        
                        $repository = $this->getDoctrine()->getRepository('OkraBundle:OrdersItem');
                        $itemExists = $repository->findOneBy(array('idOrder'=>$order->getId(), 'idItem'=>$itemForm->getId()));                     
                        if ($itemExists) {
                            $itemExists->setQuantity($itemExists->getQuantity() + $key);
                            $em->persist($itemExists);
                        } else {
                            $newOrderItems = new OrdersItem();
                            $newOrderItems->setIdOrder($order);
                            $newOrderItems->setQuantity($key);
                            $newOrderItems->setIdItem($itemForm);
                            $newOrderItems->setPrice($itemForm->getPrice());
                            $em->persist($newOrderItems);
                        }
                    }
                }
                $em->flush();
                $repository = $this->getDoctrine()->getRepository('OkraBundle:OrdersItem');
                $orderItems = $repository->findBy(array('idOrder'=>$tableId));
                $total = 0;
                foreach ($orderItems as $orderItem) {
                    $total = $total + ($orderItem->getQuantity() * $orderItem->getPrice());
                }
                $order->setTotal($total);
                $em->persist($order);
                $em->flush();
            }
        }        
        
        $repository = $this->getDoctrine()->getRepository('OkraBundle:OrdersItem');
        $orderItems = $repository->findBy(array('idOrder'=>$tableId));
        
        $repository= $this->getDoctrine()->getRepository('OkraBundle:Category');
        $categories = $repository->findAllByLocale($this->get('request')->getLocale());
        
        $repository= $this->getDoctrine()->getRepository('OkraBundle:Item');
        $items = array();
        foreach ($categories as $category) {    
            $items[$category->getId()] = $repository->findAllByLocale($this->get('request')->getLocale(), $category->getId());
        }

        
        return $this->render('OkraBundle:Default:table.html.twig', array("order"=>$order, "orderitems"=>$orderItems, "categories"=>$categories, "items"=>$items, 'form' => $form->createView()));        
    }
    
    public function bookAction(Request $request) {
        $this->saveOther($request, 1);
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }
    
    public function buyingAction(Request $request) {
        $this->saveOther($request, 2);
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }
    
    public function othersAction(Request $request) {
        $this->saveOther($request, 3);
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }    

    public function startsAction(Request $request) {
        $this->saveOther($request, 4);
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }
    
    public function statscloseAction($sessionId) {
            return $this->statsAction(true);
    }
    
    public function closeAction($tableId) {  
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('OkraBundle:Orders');
        $order = $repository->find($tableId);
        
        $repository = $this->getDoctrine()->getRepository('OkraBundle:OrdersItem');
        $orderItems = $repository->findBy(array('idOrder'=>$tableId));        
        
        $order->SetIdStatus(2);
        $order->SetDateClose(new \DateTime('now'));
        $em->persist($order);
        $em->flush();    
        
        $html = $this->renderView('OkraBundle:Default:ticket.html.twig', array(
            'order'  => $order, "orderitems"=>$orderItems
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('page-size' => 'A5')),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );        
    }
    
    public function statsAction($printed = false) {       
        $repository= $this->getDoctrine()->getRepository('OkraBundle:Category');
        $categories = $repository->findAllByLocale($this->get('request')->getLocale());
        $repository= $this->getDoctrine()->getRepository('OkraBundle:Item');
        foreach ($categories as $category) {    
            $items[$category->getId()] = $repository->findAllByLocale($this->get('request')->getLocale(), $category->getId());
        }
        
        $repository = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        $session = $repository->getActiveSession();
        $dates[$session->getId()] = $repository->getDates($session->getId());
        foreach ($dates[$session->getId()] as $date) {
            $stats[$session->getId()][(int)$date['dateYear']][(int)$date['dateMonth']][(int)$date['dateDay']] = $repository->getStats($session->getId(),$date);
        }
        
        $total = $this->getDoctrine()->getRepository('OkraBundle:Orders')->getTotalClose($session);
        $totalBook = $this->getDoctrine()->getRepository('OkraBundle:Others')->getTotalBookTodayClose($session);
        $totalBuying = $this->getDoctrine()->getRepository('OkraBundle:Others')->getTotalBuyingTodayClose($session);
        $totalOthers = $this->getDoctrine()->getRepository('OkraBundle:Others')->getTotalOthersTodayClose($session);
        $totalStart = $this->getDoctrine()->getRepository('OkraBundle:Others')->getTotalStartsTodayClose($session);
               
        $Gtotal = $totalStart + $total + $totalBook - $totalBuying + $totalOthers;
        if ($printed === true) {
            $html = $this->renderView('OkraBundle:Default:pdfstats.html.twig', array(
                "categories"=>$categories, "items"=>$items, "session"=>$session, "statsTotal"=>$total, "stats"=>$stats, "dates"=>$dates,'totalStart'=>$totalStart, 'totalBook'=>$totalBook,'totalBuying'=>$totalBuying,'totalOthers'=>$totalOthers,'Gtotal'=>$Gtotal
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('page-size' => 'A4')),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="file.pdf"'
                )
            );        
        } else {
            return $this->render('OkraBundle:Default:stats.html.twig', array("categories"=>$categories, "items"=>$items, "session"=>$session, "statsTotal"=>$total, "stats"=>$stats, "dates"=>$dates,'totalStart'=>$totalStart, 'totalBook'=>$totalBook,'totalBuying'=>$totalBuying,'totalOthers'=>$totalOthers,'Gtotal'=>$Gtotal));        
        }
    }
    
    private function createPersoForm($entity, $url, $label)
    {
        return $this->createFormBuilder($entity)
            ->setAction($this->generateUrl($url))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => $label))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
    }
    
    private function saveOther(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        $repositoryOthers = $this->getDoctrine()->getRepository('OkraBundle:Others');     
        $newOthers = $repositoryOthers->createNewOther($repositorySessions->getActiveSession());
        $formOthers = $this->createPersoForm($newOthers, 'okra_homepage', 'Create others');  
        $formOthers->handleRequest($request);
        
        if ($formOthers->isValid()) {
            $newOthers->setIdType($type);
            $em->persist($newOthers);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('Starts is saved !!')
            );           
        }             
    }
}
