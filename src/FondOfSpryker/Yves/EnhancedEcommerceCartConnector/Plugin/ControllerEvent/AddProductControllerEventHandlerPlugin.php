<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Plugin\ControllerEvent;

use Exception;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory getFactory()
 */
class AddProductControllerEventHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    public const CONTROLLER_NAME = 'Yves\CartPage\Controller\CartController';
    public const METHOD_NAME = 'addAction';

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return bool
     */
    public function isApplicable(FilterControllerEvent $event): bool
    {
        try {
            $controller = get_class($event->getController()[0]);
        } catch (Exception $exception) {
            return false;
        }

        if (strpos($controller, static::CONTROLLER_NAME) === false) {
            return false;
        }

        if (!isset($event->getController()[1]) || $event->getController()[1] !== static::METHOD_NAME) {
            return false;
        }

        return true;
    }

    /**
     * Specification:
     * - Subscribes for symfony ControllerEvent
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function handle(ControllerEvent $event): void
    {
        if (!$this->isApplicable($event)) {
            return;
        }

        $this->getFactory()
            ->createProductSessionHandler()
            ->addProduct($event);
    }
}
