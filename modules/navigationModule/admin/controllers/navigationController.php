<?php

class navigationController
{
    public $html;
    public $navigation_model;
    public $categories;

    public function __constructor()
    {

    }

    public function buildCategoryTree()
    {
        $model = new Model();
        $this->navigation_model = $model->getModuleModel('navigation', 'navigation');
        $this->categories = $this->navigation_model->getNavigationCategories();

        if (!empty($this->categories) && count($this->categories) > 0) {
            $this->html = '<div id="navigation">';
            $this->html .= '<div class="panel panel-default">';
            $this->html .= '<div class="panel-heading">Dashboard</div>';
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
        foreach ($categories as $category) {
            $subcategories = $this->navigation_model->getCategoryByParent($category->id);
            if ($category->id != 1) {
                $this->html .= '<li ' . ($category->category_controller == Url::$url['controller'] ? 'class="active"' : '');
                $this->html .= ($subcategories ? ' class="parent"' : '');
                $this->html .= 'role="presentation"><a target="_self" href="' . $this->buildCategoryLink($category->category_controller) . '">';
                if (!empty($category->category_icon) && $category->category_level == 1) {
                    $this->html .= '<span class="glyphicon ' . $category->category_icon . '" aria-hidden="true"></span>';
                }
                $this->html .= '<span class="category-name">' . ucfirst($category->category_name) . '</span>';
                if ($subcategories) {
                    $this->html .= '<span class="caret"></span>';
                    $this->html .= '</a>';
                    $this->html .= '<ul class="nav nav-pills nav-stacked subcategory-nav">';
                    $this->categoryLevelBuild($category->id);
                    $this->html .= '</ul>';
                } else {
                    $this->html .= '</a>';
                    $this->html .= '</li>';
                }
            } else {
                $this->categoryLevelBuild($category->id);
            }
        }
    }

    private function buildCategoryLink($category_controller)
    {
        if (!empty($category_controller)) {
            return $link = Url::$url['path'] . '?controller=' . $category_controller . '&action=index';
        }
    }

}
