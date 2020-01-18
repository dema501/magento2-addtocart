<?php
namespace Liftmode\Addtocart\Controller\Product;

class Index extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\Product $product,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->cart = $cart;
        $this->product = $product;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $params = array();
            $params['qty'] = '1';//product quantity
            /* Get product id from a URL like /addtocart/product?id=1,2,3 */
            $_pIds = explode(',', $_GET['id']);

            if (sizeof($_pIds) > 0) {
                $this->cart->addProductsByIds($_pIds);
                $this->cart->save();

                if (sizeof($this->cart->getItems()) > 0 && $this->messageManager->hasMessages() === false) {
                    $this->messageManager->addSuccess(__('Add to cart successfully.'));
                } 
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addException($e,__('%1', $e->getMessage()) );
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('error.'));
        }

        /*cart page*/
        $this->getResponse()->setRedirect('/checkout/cart/index');
    }
}
