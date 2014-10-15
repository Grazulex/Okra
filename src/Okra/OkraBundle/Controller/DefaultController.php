<?php

namespace Okra\OkraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Okra\OkraBundle\Entity\Orders;
use Okra\OkraBundle\Entity\OrdersItem;
use Okra\OkraBundle\Entity\Item;
use Okra\OkraBundle\Entity\Others;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('OkraBundle:Orders');
        $repositoryOthers = $this->getDoctrine()->getRepository('OkraBundle:Others');
        $repositoryOrdersItem = $this->getDoctrine()->getRepository('OkraBundle:OrdersItem');
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        
        $actifSession = $repositorySessions->getActiveSession();
        
        $newOrder = new Orders();
        $newOrder->setIdStatus(1);
        $newOrder->setDateCreate(new \DateTime('now'));
        $newOrder->setTotal(0);
        $newOrder->setIdSession($actifSession);
        
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

        $newBook = new Others();
        $newBook->setDateCreate(new \DateTime('now'));
        $formBook = $this->createFormBuilder($newBook)
            ->setAction($this->generateUrl('okra_book'))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create book'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
                

        $newBuying = new Others();
        $newBuying->setDateCreate(new \DateTime('now'));
        $formBuying = $this->createFormBuilder($newBuying)
            ->setAction($this->generateUrl('okra_buying'))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create bying'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
        $formBuying->handleRequest($request);
        
        
        $newOthers = new Others();
        $newOthers->setDateCreate(new \DateTime('now'));
        $formOthers = $this->createFormBuilder($newOthers)
            ->setAction($this->generateUrl('okra_others'))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create others'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
        $formOthers->handleRequest($request);
                
        
        $count = $repository->getNbClose($actifSession);
        $total = $repository->getTotalClose($actifSession);
        $totalBook = $repositoryOthers->getTotalBookTodayClose($actifSession);
        $totalBuying = $repositoryOthers->getTotalBuyingTodayClose($actifSession);
        $totalOthers = $repositoryOthers->getTotalOthersTodayClose($actifSession);
        
        $stats = $repositoryOrdersItem->getStats();
        
        $Gtotal = $total + $totalBook - $totalBuying + $totalOthers;

        //$Orders = $repository->findBy(array('idStatus'=>1),array("idTable"=>"asc", "idOrderManual"=>"asc"));
        $Orders = $repository->createQueryBuilder('o')
            ->select(array('o.id, o.idTable, o.idOrderManual, u.username, o.dateCreate, o.total, timediff(CURRENT_TIMESTAMP(), o.dateCreate) as diffhour, datediff(CURRENT_TIMESTAMP(), o.dateCreate) as diffday'))    
                ->innerJoin('o.idUser','u')
            ->where('o.idStatus = :Status')
            ->setParameter('Status', 1)
            ->orderBy('o.idTable', 'ASC')  
                ->addOrderBy('o.idOrderManual', 'ASC')                
            ->getQuery()
            ->getResult()
        ;
        return $this->render('OkraBundle:Default:index.html.twig', array("actifsession"=>$actifSession, "locale"=>$this->get('request')->getLocale(), "orders"=>$Orders, "statsCount"=>$count, "statsTotal"=>$total, 'form' => $form->createView(), 'formBook' => $formBook->createView(), 'formBuying' => $formBuying->createView(), 'formOthers' => $formOthers->createView(), 'totalBook'=>$totalBook,'totalBuying'=>$totalBuying,'totalOthers'=>$totalOthers,'Gtotal'=>$Gtotal));
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
                            $em->flush();    
                        } else {
                            $newOrderItems = new OrdersItem();
                            $newOrderItems->setIdOrder($order);
                            $newOrderItems->setQuantity($key);
                            $newOrderItems->setIdItem($itemForm);
                            $newOrderItems->setPrice($itemForm->getPrice());
                            $em->persist($newOrderItems);
                            $em->flush();                            
                        }
                    }
                }
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
        $em = $this->getDoctrine()->getManager();
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        $newBook = new Others();
        $newBook->setDateCreate(new \DateTime('now'));
        $newBook->setIdSession($repositorySessions->getActiveSession());
        $formBook = $this->createFormBuilder($newBook)
            ->setAction($this->generateUrl('okra_homepage'))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create book'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
        $formBook->handleRequest($request);
        
        if ($formBook->isValid()) {
            $newBook->setIdType(1);
            $em->persist($newBook);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('Book is saved !!')
            );
        }      
        
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }
    
    public function buyingAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        $newBuying = new Others();
        $newBuying->setDateCreate(new \DateTime('now'));
        $newBuying->setIdSession($repositorySessions->getActiveSession());
        $formBuying = $this->createFormBuilder($newBuying)
            ->setAction($this->generateUrl('okra_homepage'))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create bying'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
        $formBuying->handleRequest($request);
        
        if ($formBuying->isValid()) {
            $newBuying->setIdType(2);
            $em->persist($newBuying);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('Buying is saved !!')
            );            
        }        
        
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }
    
    public function othersAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        $newOthers = new Others();
        $newOthers->setDateCreate(new \DateTime('now'));
        $newOthers->setIdSession($repositorySessions->getActiveSession());
        $formOthers = $this->createFormBuilder($newOthers)
            ->setAction($this->generateUrl('okra_homepage'))
            ->setMethod('post')
            ->add('price', 'number',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Price'))
            ->add('comment', 'text',array('attr'=> array('class'=>'form-control input-lg'),'label'  => 'Comment'))
            ->add('save', 'submit', array('attr'=> array('class'=>'btn btn-primary'), 'label' => 'Create others'))
            ->add('button', 'button', array('attr'=> array('class'=>'btn btn-default', 'data-dismiss' => 'modal'), 'label' => 'Cancel'))    
            ->getForm();
        $formOthers->handleRequest($request);
        
        if ($formOthers->isValid()) {
            $newOthers->setIdType(3);
            $em->persist($newOthers);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('Others is saved !!')
            );           
        }        
        
        return $this->redirect($this->generateUrl('okra_homepage'));
        
    }    
    
    public function closeAction($tableId) {  
        $em = $this->getDoctrine()->getManager();
        $repositorySessions = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
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
    
    public function statsAction() {
        $em = $this->getDoctrine()->getManager();

        $repository= $this->getDoctrine()->getRepository('OkraBundle:Category');
        $categories = $repository->findAllByLocale($this->get('request')->getLocale());
        
        $repository= $this->getDoctrine()->getRepository('OkraBundle:Item');
        $items = array();
        foreach ($categories as $category) {    
            $items[$category->getId()] = $repository->findAllByLocale($this->get('request')->getLocale(), $category->getId());
        }
        
        $repository = $this->getDoctrine()->getRepository('OkraBundle:Sessions');
        $sessions = $repository->findBy(array("dateStop"=> null));
        foreach ($sessions as $session) {    
            $stats[$session->getId()] = $repository->getStats($session->getId());
        }
        
        
        return $this->render('OkraBundle:Default:stats.html.twig', array("categories"=>$categories, "items"=>$items, "sessions"=>$sessions, "stats"=>$stats));        
    }
    
}
