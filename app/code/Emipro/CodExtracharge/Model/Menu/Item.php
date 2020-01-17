<?php

namespace Emipro\CodExtracharge\Model\Menu;

use Magento\Backend\Model\Menu;
use Magento\Store\Model\ScopeInterface;

class Item extends \Magento\Backend\Model\Menu\Item
{
    /**
     * Menu item id
     *
     * @var string
     */
    protected $id;

    /**
     * Menu item title
     *
     * @var string
     */
    protected $title;

    /**
     * Module of menu item
     *
     * @var string
     */
    protected $moduleName;

    /**
     * Menu item sort index in list
     *
     * @var string
     */
    protected $sortIndex = null;

    /**
     * Menu item action
     *
     * @var string
     */
    protected $action = null;

    /**
     * Parent menu item id
     *
     * @var string
     */
    protected $parentId = null;

    /**
     * Acl resource of menu item
     *
     * @var string
     */
    protected $resource;

    /**
     * Item tooltip text
     *
     * @var string
     */
    protected $tooltip;

    /**
     * Path from root element in tree
     *
     * @var string
     */
    protected $path = '';

    /**
     * Acl
     *
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $acl;

    /**
     * Module that item is dependent on
     *
     * @var string|null
     */
    protected $dependsOnModule;

    /**
     * Global config option that item is dependent on
     *
     * @var string|null
     */
    protected $dependsOnConfig;

    /**
     * Submenu item list
     *
     * @var Menu
     */
    protected $submenu;

    /**
     * @var \Magento\Backend\Model\MenuFactory
     */
    protected $menuFactory;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $urlModel;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Backend\Model\Menu\Item\Validator
     */
    protected $validator;

    /**
     * Serialized submenu string
     *
     * @var string
     * @deprecated 100.2.0
     */
    protected $serializedSubmenu;

    /**
     * Module list
     *
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $moduleList;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * Menu item target
     *
     * @var string|null
     */
    private $target;

    /**
     * @param Item\Validator $validator
     * @param \Magento\Framework\AuthorizationInterface $authorization
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Backend\Model\MenuFactory $menuFactory
     * @param \Magento\Backend\Model\UrlInterface $urlModel
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Model\Menu\Item\Validator $validator,
        \Magento\Framework\AuthorizationInterface $authorization,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\Model\MenuFactory $menuFactory,
        \Magento\Backend\Model\UrlInterface $urlModel,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->validator = $validator;
        $this->validator->validate($data);
        $this->moduleManager = $moduleManager;
        $this->acl = $authorization;
        $this->scopeConfig = $scopeConfig;
        $this->menuFactory = $menuFactory;
        $this->urlModel = $urlModel;
        $this->moduleList = $moduleList;
        $this->populateFromArray($data);
    }
    /**
     * Retrieve argument element, or default value
     *
     * @param array $array
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    protected function _getArgument(array $array, $key, $defaultValue = null)
    {
        return isset($array[$key]) ? $array[$key] : $defaultValue;
    }

    /**
     * Retrieve item id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retrieve item target
     *
     * @return string|null
     * @since 100.2.0
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Check whether item has subnodes
     *
     * @return bool
     */
    public function hasChildren()
    {
        return (null !== $this->submenu) && (bool) $this->submenu->count();
    }

    /**
     * Retrieve submenu
     *
     * @return Menu
     */
    public function getChildren()
    {
        if (!$this->submenu) {
            $this->submenu = $this->menuFactory->create();
        }
        return $this->submenu;
    }

    /**
     * Retrieve menu item url
     *
     * @return string
     */
    public function getUrl()
    {
        if ((bool) $this->action) {
            return $this->urlModel->getUrl((string) $this->action, ['_cache_secret_key' => true]);
        }
        return '#';
    }

    /**
     * Retrieve menu item action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set Item action
     *
     * @param string $action
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setAction($action)
    {
        $this->validator->validateParam('action', $action);
        $this->action = $action;
        return $this;
    }

    /**
     * Check whether item has javascript callback on click
     *
     * @return bool
     */
    public function hasClickCallback()
    {
        return $this->getUrl() == '#';
    }

    /**
     * Retrieve item click callback
     *
     * @return string
     */
    public function getClickCallback()
    {
        if ($this->getUrl() == '#') {
            return 'return false;';
        }
        return '';
    }

    /**
     * Retrieve tooltip text title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Item title
     *
     * @param string $title
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setTitle($title)
    {
        $this->validator->validateParam('title', $title);
        $this->title = $title;
        return $this;
    }

    /**
     * Check whether item has tooltip text
     *
     * @return bool
     */
    public function hasTooltip()
    {
        return (bool) $this->tooltip;
    }

    /**
     * Retrieve item tooltip text
     *
     * @return string
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * Set Item tooltip
     *
     * @param string $tooltip
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setTooltip($tooltip)
    {
        $this->validator->validateParam('toolTip', $tooltip);
        $this->tooltip = $tooltip;
        return $this;
    }

    /**
     * Set Item module
     *
     * @param string $module
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setModule($module)
    {
        $this->validator->validateParam('module', $module);
        $this->moduleName = $module;
        return $this;
    }

    /**
     * Set Item module dependency
     *
     * @param string $moduleName
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setModuleDependency($moduleName)
    {
        $this->validator->validateParam('dependsOnModule', $moduleName);
        $this->dependsOnModule = $moduleName;
        return $this;
    }

    /**
     * Set Item config dependency
     *
     * @param string $configPath
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setConfigDependency($configPath)
    {
        $this->validator->validateParam('dependsOnConfig', $configPath);
        $this->dependsOnConfig = $configPath;
        return $this;
    }

    /**
     * Check whether item is disabled. Disabled items are not shown to user
     *
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->moduleManager->isOutputEnabled(
            $this->moduleName
        ) || !$this->_isModuleDependenciesAvailable() || !$this->_isConfigDependenciesAvailable();
    }

    /**
     * Check whether module that item depends on is active
     *
     * @return bool
     */
    protected function _isModuleDependenciesAvailable()
    {
        if ($this->dependsOnModule) {
            $module = $this->dependsOnModule;
            return $this->moduleList->has($module);
        }
        return true;
    }

    /**
     * Check whether config dependency is available
     *
     * @return bool
     */
    protected function _isConfigDependenciesAvailable()
    {
        if ($this->dependsOnConfig) {
            return $this->scopeConfig->isSetFlag((string) $this->dependsOnConfig, ScopeInterface::SCOPE_STORE);
        }
        return true;
    }

    /**
     * Check whether item is allowed to the user
     *
     * @return bool
     */
    public function isAllowed()
    {
        try {
            return $this->acl->isAllowed((string) $this->resource);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get menu item data represented as an array
     *
     * @return array
     * @since 100.2.0
     */
    public function toArray()
    {
        return [
            'parent_id' => $this->parentId,
            'module' => $this->moduleName,
            'sort_index' => $this->sortIndex,
            'dependsOnConfig' => $this->dependsOnConfig,
            'id' => $this->id,
            'resource' => $this->resource,
            'path' => $this->path,
            'action' => $this->action,
            'dependsOnModule' => $this->dependsOnModule,
            'toolTip' => $this->tooltip,
            'title' => $this->title,
            'target' => $this->target,
            'sub_menu' => isset($this->submenu) ? $this->submenu->toArray() : null,
        ];
    }

    /**
     * Populate the menu item with data from array
     *
     * @param array $data
     * @return void
     * @since 100.2.0
     */
    public function populateFromArray(array $data)
    {
        $this->parentId = $this->_getArgument($data, 'parent_id');
        $this->moduleName = $this->_getArgument($data, 'module', 'Magento_Backend');
        $this->sortIndex = $this->_getArgument($data, 'sort_index');
        $this->dependsOnConfig = $this->_getArgument($data, 'dependsOnConfig');
        $this->id = $this->_getArgument($data, 'id');
        $this->resource = $this->_getArgument($data, 'resource');
        $this->path = $this->_getArgument($data, 'path', '');
        $this->action = $this->_getArgument($data, 'action');
        $this->dependsOnModule = $this->_getArgument($data, 'dependsOnModule');
        $this->tooltip = $this->_getArgument($data, 'toolTip');
        $this->title = $this->_getArgument($data, 'title');
        $this->target = $this->_getArgument($data, 'target');
        if (isset($data['sub_menu'])) {
            $menu = $this->menuFactory->create();
            $menu->populateFromArray($data['sub_menu']);
            $this->submenu = $menu;
        } else {
            $this->submenu = null;
        }
    }
}
