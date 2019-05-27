<?php

class Blocks
{
    public $blocks;
    public static $collection;

    public function __construct(array $collection)
    {
        self::$collection = $collection;
    }

    public function getBlock(array $blocks)
    {
        if (!empty($blocks)) {
            $type = self::$collection['type'];
            $db = new Db();
        }
    }

    public function getGapBlocks(string $gap)
    {
        if (!empty($gap)) {
            $type = self::$collection['type'];
            $db = new Db();
            return $db->select("theme_blocks_name")->from("{$type}_theme_blocks")->innerJoin("{$type}_blocks_associations")->on("{$type}_theme_blocks.id={$type}_blocks_associations.theme_blocks_id")->innerJoin("system_gaps")->on("{$type}_blocks_associations.gaps_id=system_gaps.id")->where("system_gaps.gaps_name='{$gap}'")->execute("bool");
        }
    }
}
