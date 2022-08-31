<?php

namespace Azuriom\Plugin\WeChatPayment\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\WeChatPayment\WeChatMethod;

class WeChatPaymentServiceProvider extends BasePluginServiceProvider
{
/**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        // $this->registerMiddleware();

        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        if (! plugins()->isEnabled('shop')) {
            logger()->warning('This plugin need the shop plugin to work !');
   
            return;
        }

        
	    $this->loadViews();
	    payment_manager()->registerPaymentMethod('wechat-business', WeChatMethod::class);
    }

}
