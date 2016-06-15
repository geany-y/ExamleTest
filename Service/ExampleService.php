<?php

/*
 * This file is part of the ExampleTest
 *
 * Copyright (C) 2016 LockOn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ExampleTest\Service;

use Doctrine\ORM\NoResultException;
use Eccube\Application;

class ExampleService
{
    /** @var \Eccube\Application */
    public $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * プラグインがインストールされた日付を西暦の漢字表現で返却
     *
     * @param $code
     * @return bool
     */
    public function getPluginInstallDateFormatJa($code)
    {
        $qb = $this->app['orm.em']->createQueryBuilder();

        $qb->select('p.create_date')
            ->from('\Eccube\Entity\Plugin', 'p')
            ->where('p.code = :Code')
            ->setParameter('Code', $code);
        try {
            $Date = $qb->getQuery()->getSingleResult();

            return $Date['create_date']->format('Y年m月d日 H時i分s秒');
        } catch (NoResultException $e) {
            return false;
        }
    }
}
