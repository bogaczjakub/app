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
            $this->html .= '<div id="collapse-container" class="collapse navbar-collapse">';
            $this->html .= '<ul id="navigation" class="nav navbar-nav navbar-right">';
            $this->categoryLevelBuild(0);
            $this->html .= '</ul>';
            $this->html .= '</div>';
        }
        return $this->html;
    }

    public function categoryLevelBuild(int $parent_id = 0)
    {
        $categories = $this->navigation_model->getCategoryByParent($parent_id);
        foreach ($categories as $category) {
            $subcategories = $this->navigation_model->getCategoryByParent($category->id);
            if ($category->id != 1) {
                $active_category = ($category->controller == Url::$url['controller']);
                $allow_display = $this->navigation_model->categoryAllowedToDisplay($category->id);
                if ($allow_display[0]['display']) {
                    $this->html .= '<li ';
                    $this->html .= (count($subcategories) > 0 ? ($active_category ? 'class="active parent dropdown"' : 'class="parent dropdown"') : ($active_category ? 'class="active"' : 'class=""'));
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
                        $this->html .= '<span class="caret"></span>';
                        $this->html .= '</a>';
                        $this->html .= '<ul class="subcategory-nav dropdown-menu">';
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
}
