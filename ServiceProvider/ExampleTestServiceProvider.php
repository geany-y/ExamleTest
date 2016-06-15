<?php

/*
 * This file is part of the ExampleTest
 *
 * Copyright (C) 2016 LockOn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ExampleTest\ServiceProvider;

use Eccube\Application;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Plugin\ExampleTest\Form\Type\ExampleTestConfigType;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;


class ExampleTestServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        // プラグイン用設定画面
        $app->match(
            '/'.$app['config']['admin_route'].'/plugin/ExampleTest/config',
            'Plugin\ExampleTest\Controller\ConfigController::index'
        )->bind('plugin_ExampleTest_config');

        // 独自コントローラ
        $app->match('/plugin/ExampleTest/hello', 'Plugin\ExampleTest\Controller\ExampleTestController::index')->bind(
            'plugin_ExampleTest_hello'
        );

        // Form
        $app['form.types'] = $app->share(
            $app->extend(
                'form.types',
                function ($types) use ($app) {
                    $types[] = new ExampleTestConfigType($app);

                    return $types;
                }
            )
        );

        // Repository

        // Service
        $app['eccube.plugin.service.example'] = $app->share(
            function () use ($app) {
                return new \Plugin\ExampleTest\Service\ExampleService($app);
            }
        );


        // ログファイル設定
        $app['monolog.ExampleTest'] = $app->share(
            function ($app) {

                $logger = new $app['monolog.logger.class']('plugin.ExampleTest');

                $file = $app['config']['root_dir'].'/app/log/ExampleTest.log';
                $RotateHandler = new RotatingFileHandler($file, $app['config']['log']['max_files'], Logger::INFO);
                $RotateHandler->setFilenameFormat(
                    'ExampleTest_{date}',
                    'Y-m-d'
                );

                $logger->pushHandler(
                    new FingersCrossedHandler(
                        $RotateHandler,
                        new ErrorLevelActivationStrategy(Logger::INFO)
                    )
                );

                return $logger;
            }
        );

    }

    public function boot(BaseApplication $app)
    {
    }
}
