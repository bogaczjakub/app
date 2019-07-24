<?php

class navigationModuleController
{
    public $html;
    public $navigation_model;
    public $categories;

    public function __constructor()
    { }

    public function buildCategoryTree()
    {
        $tools = new Tools();
        $this->navigation_model = $tools->getModuleModel('navigation', 'navigation');
        $this->categories = $this->navigation_model->getNavigationCategories();

        if (!empty($this->categories) && count($this->categories) > 0) {
            $category_name = $this->navigation_model->getCategoryNameByController(Url::$url['controller']);
            $this->html = '<div id="navigation">';
            $this->html .= '<div class="panel panel-default">';
            if (!empty($category_name)) {
                $this->html .= '<div class="panel-heading">' . ucfirst($category_name[0]->name) . '</div>';
            } else {
                $this->html .= '<div class="panel-heading"></div>';
            }
            $this->html .= '<div class="panel-body">';
            $this->html .= '<ul class="nav nav-pills nav-stacked">';
            $this->categoryLevelBuild(0);
            $this->html .= '</ul>';
            $this->html .= '</div>';
            $this->html .= '</div>';
            $this->html .= '</div>';
        }
        return $this->html;
    }

    public function categoryLevelBuild(int $parent_id = 0)
    {
        $categories = $this->navigation_model->getCategoryByParent($parent_id);
        $active_category = $this->getActiveCategoryForController(Url::$url['controller']);
        foreach ($categories as $category) {
            $subcategories = $this->navigation_model->getCategoryByParent($category->id);
            if ($category->id != 1) {
                $is_current = ($category->controller == $active_category[0]['controller']);
                $allow_display = $this->navigation_model->categoryAllowedToDisplay($category->id);
                if ($allow_display[0]['display']) {
                    $this->html .= '<li ' . ($is_current ? 'class="active"' : '');
                    $this->html .= ($subcategories ? ' class="parent"' : '');
                    $this->html .= 'role="presentation"><a target="_self" href="' . $this->buildCategoryLink($category->controller) . '">';
                    if (!empty($category->icon) && $category->level == 1) {
                        $this->html .= '<span class="glyphicon ' . $category->icon . '" aria-hidden="true"></span>';
                    }
                    $this->html .= '<span class="category-name">' . ucfirst($category->name) . '</span>';
                    $badges = Alerts::getAlertsCount($category->controller);
                    if ($badges[0]['count'] > 0) {
                        $this->html .= '<span class="badge">' . $badges[0]['count'] . '</span>';
                    }
                    if ($subcategories) {
                        $this->html .= '<span class="caret-container badge">';
                        $this->html .= '<span class="caret"></span>';
                        $this->html .= '</span>';
                        $this->html .= '</a>';
                        $this->html .= '<ul class="nav nav-pills nav-stacked subcategory-nav">';
                        $this->categoryLevelBuild($category->id);
                        $this->html .= '</ul>';
                    } else {
                        $this->html .= '</a>';
                        $this->html .= '</li>';
                    }
                }
            } else {
                $this->categoryLevelBuild($category->id);
            }
        }
    }

    private function buildCategoryLink(string $controller)
    {
        if (!empty($controller)) {
            return $link = Url::$url['path'] . '?controller=' . $controller . '&action=index';
        }
    }

    private function getActiveCategoryForController(string $controller_name)
    {
        $tools = new Tools();
        $this->navigation_model = $tools->getModuleModel('navigation', 'navigation');
        return $this->navigation_model->getActiveCategoryForController($controller_name);
    }
}
