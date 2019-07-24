<?php

class Blocks
{
    public $blocks;
    public static $collection;

    public function __construct()
    { }

    public function getBlock(array $blocks)
    {
        $blocks_array = [];
        $type = Page::$collection['type'];
        $db = new Db();
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $results = $db->select("*")->from("{$type}_theme_blocks")->where("name='{$block['name']}'")->execute("assoc");
                if (!empty($results[0]['content'])) {
                    $blocks_array[$block['name']] = $results[0]['content'];
                } else {
                    $blocks_array[$block['name']] = Tools::getStaticMessage('block_content_empty');
                }
                if (!empty($results[0]['styles'])) {
                    $this->addBlockStyles($results[0]['styles'], $block['name']);
                }
            }
        }
        return $blocks_array;
    }

    public function getGapBlocks(string $gap)
    {
        if (!empty($gap)) {
            $type = Page::$collection['type'];
            $db = new Db();
            return $db->select("{$type}_theme_blocks.name")->from("{$type}_theme_blocks")->innerJoin("{$type}_blocks_associations")->on("{$type}_theme_blocks.id={$type}_blocks_associations.block_id")->innerJoin("system_gaps")->on("{$type}_blocks_associations.gap_id=system_gaps.id")->where("system_gaps.name='{$gap}' && {$type}_theme_blocks.is_active=1")->execute("assoc");
        }
    }

    private function addBlockStyles(string $styles, string $block_name)
    {
        $tools = new Tools();
        Page::$collection['head_styles'][$block_name] = $tools->blocksHeadStyles($styles);
    }
}
