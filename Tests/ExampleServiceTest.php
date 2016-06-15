<?php

/*
 * This file is part of the ExampleTest
 *
 * Copyright (C) 2016 LockOn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ExampleTestPlugin\Tests\Service;

use Eccube\Tests\EccubeTestCase;
use Plugin\ExampleTestPlugin\ServiceProvider\ExampleTestServiceProvider;

class ExampleServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * プラグインのインストール時間取得メソッド失敗パターンのテスト
     * ・インストールされていないコードをサービスに渡す
     * ・戻り値としてfalseが返却される
     */
    public function testGetPluginInstallDateFormatJaFromErrorCode()
    {
        $errorCode = 'Test';
        $this->actual = $this->app['eccube.plugin.service.example']->getPluginInstallDateFormatJa($errorCode);

        $this->assertFalse($this->actual);
    }

    /**
     * プラグインのインストール時間取得メソッド成功パターンのテスト
     * ・インストールされているコードをサービスに渡す
     * ・事前にメソッドと同じ条件でデーターベースからインストール日付を取得しておく
     * ・戻り値としてインストール日付が返却される
     */
    public function testGetPluginInstallDateFormatJaFromSuccessCode()
    {
        $successCode = 'ExampleTest';

        $qb = $this->app['orm.em']->createQueryBuilder();
        $qb->select('p.create_date')
            ->from('\Eccube\Entity\Plugin', 'p')
            ->where('p.code = :Code')
            ->setParameter('Code', $successCode);

        try {
            $date = $qb->getQuery()->getSingleResult();
            $this->expected = $date['create_date']->format('Y年m月d日 H時i分s秒');
        } catch (\NoResultException $e) {
            throw new \NoResultException();
        }

        $this->actual = $this->app['eccube.plugin.service.example']->getPluginInstallDateFormatJa($successCode);

        $this->assertEquals($this->actual, $this->expected);
    }
}
