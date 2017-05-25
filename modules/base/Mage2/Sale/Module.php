<?php
/**
 * Mage2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ind.purvesh@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://mage2.website for more information.
 *
 * @author    Purvesh <ind.purvesh@gmail.com>
 * @copyright 2016-2017 Mage2
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3.0
 */
namespace Mage2\Sale;

use Illuminate\Support\Facades\View;
use Mage2\Framework\AdminMenu\Facades\AdminMenu;
use Mage2\Framework\Auth\Facades\Permission;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Module\Facades\Module as ModuleFacade;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class Module extends BaseModule {

    /**
     *
     * Module Name Variable
     * @var name
     *
     */
    protected $name = NULL;

    /**
     *
     * Module Odentifier  Variable
     * @var identifier
     *
     */
    protected $identifier = NULL;
    /**
     *
     * Module Description Variable
     * @var description
     *
     */
    protected $description = NULL;


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->registerModule();
        $this->registerAdminMenu();
        $this->registerTranslationPath();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->registerModuleYamlFile();
        $this->mapWebRoutes();
        $this->registerViewPath();
        $this->registerPermissions();


    }

    /*
     *
     * Registered basic details of modules
     *
     *
     */
    public function registerModuleYamlFile() {

        $yamlFileContent = File::get(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'module.yaml');
        $moduleConfig = Yaml::parse($yamlFileContent);
        $this->setName($moduleConfig['name']);
        $this->setIdentifier($moduleConfig['identifier']);
        $this->setDescription($moduleConfig['description']);
    }

    protected function registerTranslationPath() {
        $this->loadTranslationsFrom(__DIR__. "/views/lang", "mage2sale");
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function mapWebRoutes() {
        require __DIR__ . '/routes/web.php';
    }

    protected function registerViewPath() {
        $this->loadViewsFrom(__DIR__. '/views', 'mage2sale');
        View::addLocation(__DIR__ . DIRECTORY_SEPARATOR .'views');
    }

    public function registerAdminMenu() {
        $adminMenu = ['sale' => [ 'submenu' => [ 'gift-coupon' => [
            'label' => 'Gift Coupon',
            'route' => 'admin.gift-coupon.index',
        ]]]];
        AdminMenu::registerMenu('mage2-order', $adminMenu);
    }

    /**
     *  Register Permission for the roles
     *
     * @return void
     */
    protected function registerPermissions() {

        /*
        $permissions = [
            ['title' => 'Gift Coupon List', 'routes' => 'admin.order.index'],
            ['title' => 'Order View, Send Email Invoice to Customer', 'routes' => "admin.order.view,admin.order.send-email-invoice"],
            ['title' => 'Order Update Status', 'routes' => "admin.order.change-status,admin.order.update-status"],
        ];


        foreach ($permissions as $permission) {
            Permission::add($permission);
        }
        */
    }

    public function registerModule() {
        ModuleFacade::put($this->getIdentifier(), $this, $type = 'system');
    }

    public function getNameSpace() {
        return __NAMESPACE__;
    }

    public function getPath() {
        return __DIR__;
    }
}