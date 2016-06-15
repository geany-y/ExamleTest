<?php

/*
 * This file is part of the ExampleTest
 *
 * Copyright (C) 2016 LockOn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ExampleTest\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class ExampleTestController
{
    /**
     * ExampleTest画面
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {
        $installDate = $app['eccube.plugin.service.example']->getPluginInstallDateFormatJa('ExampleTest');

        if (!$installDate) {
            $installDate = '';
        }

        return $app->render(
            'ExampleTest/Resource/template/index.twig',
            array(
                'installDate' => $installDate,
            )
        );
    }

}
