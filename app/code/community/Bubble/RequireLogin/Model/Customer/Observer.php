<?php
/**
 * @category    Bubble
 * @package     Bubble_RequireLogin
 * @version     1.0.0
 * @copyright   Copyright (c) 2014 BubbleShop (https://www.bubbleshop.net)
 */
class Bubble_RequireLogin_Model_Customer_Observer
{
    public function requireLogin($observer)
    {
        $helper = Mage::helper('bubble_requirelogin');
        $session = Mage::getSingleton('customer/session');

        if ($helper->isLoginRequired() && !$session->isLoggedIn()) {
            $controllerAction = $observer->getEvent()->getControllerAction();
            /* @var $controllerAction Mage_Core_Controller_Front_Action */
            $requestString = $controllerAction->getRequest()->getRequestString();

            if (!preg_match($helper->getWhitelist(), $requestString)) {
				$requestUrl = Mage::getUrl(ltrim($requestString, '/'));
                $session->setBeforeAuthUrl($requestUrl);
	            $session->setAfterAuthUrl($requestUrl);
	            $controllerAction->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
                $controllerAction->getResponse()->sendResponse();
                exit;
            }
        }
    }
}