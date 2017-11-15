<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Form\Telephone;
use Application\Form\UserInfoForm;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Service\RequestFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Session\Validator;


class Module implements ConfigProviderInterface, FormElementProviderInterface
{
    const VERSION = '3.0.3-dev';

    const DB_LIST = ["db1", "db2"];

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\UserTable::class => function (ServiceManager $container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    $tableGateway = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                    $tableGatewayList = array("default" => $tableGateway);
                    foreach (Module::DB_LIST as $db) {
                        $dbAdapter = $container->get($db);
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                        $tableGatewayTmp = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                        $tableGatewayList[$db] = $tableGatewayTmp;
                    }
                    return new Model\UserTable($tableGateway, $tableGatewayList);
                },
                Model\UserMappingTable::class => function (ServiceManager $container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserMapping());
                    $tableGateway = new TableGateway('userMapping', $dbAdapter, null, $resultSetPrototype);
                    $tableGatewayList = array("default" => $tableGateway);
                    foreach (Module::DB_LIST as $db) {
                        $dbAdapter = $container->get($db);
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Model\UserMapping());
                        $tableGatewayTmp = new TableGateway('userMapping', $dbAdapter, null, $resultSetPrototype);
                        $tableGatewayList[$db] = $tableGatewayTmp;
                    }
                    return new Model\UserMappingTable($tableGateway, $tableGatewayList);
                },
                Model\UserTokenTable::class => function (ServiceManager $container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserToken());
                    $tableGateway = new TableGateway('userToken', $dbAdapter, null, $resultSetPrototype);
                    $tableGatewayList = array("default" => $tableGateway);
                    foreach (Module::DB_LIST as $db) {
                        $dbAdapter = $container->get($db);
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Model\UserToken());
                        $tableGatewayTmp = new TableGateway('userToken', $dbAdapter, null, $resultSetPrototype);
                        $tableGatewayList[$db] = $tableGatewayTmp;
                    }
                    return new Model\UserTokenTable($tableGateway, $tableGatewayList);
                },
                Model\SmsCodeTable::class => function (ServiceManager $container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\SmsCode());
                    $tableGateway = new TableGateway('smsCode', $dbAdapter, null, $resultSetPrototype);
                    $tableGatewayList = array("default" => $tableGateway);
                    foreach (Module::DB_LIST as $db) {
                        $dbAdapter = $container->get($db);
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Model\SmsCode());
                        $tableGatewayTmp = new TableGateway('smsCode', $dbAdapter, null, $resultSetPrototype);
                        $tableGatewayList[$db] = $tableGatewayTmp;
                    }
                    return new Model\SmsCodeTable($tableGateway, $tableGatewayList);
                },
                UserInfoForm::class => function (ContainerInterface $container) {
                    $formManager = $container->get('FormElementManager');
                    return $formManager->get(UserInfoForm::class);
                },
                SessionManager::class => function (ServiceManager $container) {
                    $config = $container->get('config');
                    if (!isset($config['session'])) {
                        $sessionManager = new SessionManager();
                        Container::setDefaultManager($sessionManager);
                        return $sessionManager;
                    }

                    $session = $config['session'];

                    $sessionConfig = null;
                    if (isset($session['config'])) {
                        $class = isset($session['config']['class'])
                            ? $session['config']['class']
                            : SessionConfig::class;

                        $options = isset($session['config']['options'])
                            ? $session['config']['options']
                            : [];

                        $sessionConfig = new $class();
                        $sessionConfig->setOptions($options);
                    }

                    $sessionStorage = null;
                    if (isset($session['storage'])) {
                        $class = $session['storage'];
                        $sessionStorage = new $class();
                    }

                    $sessionSaveHandler = null;
                    if (isset($session['save_handler'])) {
                        // class should be fetched from service manager
                        // since it will require constructor arguments
                        $sessionSaveHandler = $container->get($session['save_handler']);
                    }

                    $sessionManager = new SessionManager(
                        $sessionConfig,
                        $sessionStorage,
                        $sessionSaveHandler
                    );

                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
            ],
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
    }

    public function bootstrapSession(MvcEvent $e)
    {
        /**
         * @var SessionManager $session
         * @var ServiceManager $serviceManager
         * @var RequestFactory $request
         **/
        $session = $e->getApplication()
            ->getServiceManager()
            ->get(SessionManager::class);
        $session->start();

        $container = new Container('initialized');

        if (isset($container->init)) {
            return;
        }

        $serviceManager = $e->getApplication()->getServiceManager();
        $request = $serviceManager->get('Request');

        $session->regenerateId(true);
        $container->init = 1;
        $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
        $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

        $config = $serviceManager->get('Config');
        if (!isset($config['session'])) {
            return;
        }

        $sessionConfig = $config['session'];

        if (!isset($sessionConfig['validators'])) {
            return;
        }

        $chain = $session->getValidatorChain();

        foreach ($sessionConfig['validators'] as $validator) {
            switch ($validator) {
                case Validator\HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                case Validator\RemoteAddr::class:
                    $validator = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }

            $chain->attach('session.validate', array($validator, 'isValid'));
        }
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function (ServiceManager $container) {
                    return new Controller\IndexController(
                        $container->get(Model\UserTable::class)
                    );
                },
                Controller\UserController::class => function (ServiceManager $container) {
                    return new Controller\UserController(
                        $container->get(Model\UserTable::class), $container->get(Model\UserMappingTable::class), $container->get(Model\UserTokenTable::class), $container->get(Model\SmsCodeTable::class), $container->get(UserInfoForm::class)
                    );
                },
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function getFormElementConfig()
    {
        return [
            'aliases' => [
                'phone' => Telephone::class,
                'telephone' => Telephone::class,
            ],
            'factories' => [

            ],
        ];
    }

}
