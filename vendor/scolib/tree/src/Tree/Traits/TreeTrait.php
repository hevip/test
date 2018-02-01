<?php

namespace Sco\Tree\Traits;

use ArrayAccess;
use BadMethodCallException;
use Illuminate\Support\Collection;
use InvalidArgumentException;

trait TreeTrait
{
    private $allNodes = null;

    /**
     * 数据主ID名.
     *
     * @return string
     */
    protected function getTreeNodeIdName()
    {
        return property_exists($this, 'treeNodeIdName') ? $this->treeNodeIdName : 'id';
    }

    /**
     * 数据父ID名.
     *
     * @return string
     */
    protected function getTreeNodeParentIdName()
    {
        return property_exists($this, 'treeNodeParentIdName') ? $this->treeNodeParentIdName
                                                              : 'parent_id';
    }

    protected function getTreeSpacer()
    {
        return property_exists($this, 'treeSpacer') ? $this->treeSpacer : '&nbsp;&nbsp;&nbsp;';
    }

    protected function getTreeFirstIcon()
    {
        return property_exists($this, 'treeFirstIcon') ? $this->treeFirstIcon
                                                       : '&nbsp;&nbsp;&nbsp;│ ';
    }

    protected function getTreeMiddleIcon()
    {
        return property_exists($this, 'treeMiddleIcon') ? $this->treeMiddleIcon
                                                        : '&nbsp;&nbsp;&nbsp;├─ ';
    }

    protected function getTreeLastIcon()
    {
        return property_exists($this, 'treeLastIcon') ? $this->treeLastIcon
                                                      : '&nbsp;&nbsp;&nbsp;└─ ';
    }

    /**
     * 获取待格式树结构的节点数据.
     *
     * @return mixed
     */
    final protected function getAllNodes()
    {
        if ($this->allNodes) {
            return $this->allNodes;
        }

        if (!method_exists($this, 'getTreeAllNodes')) {
            throw new BadMethodCallException('Method [getTreeAllNodes] does not exist.');
        }

        $data = $this->getTreeAllNodes(); // 由use的class来实现

        if (!$data instanceof ArrayAccess) {
            throw new InvalidArgumentException('tree data must be a collection');
        }
        // 重置键值
        $this->allNodes = collect([]);
        foreach ($data as $item) {
            $this->allNodes->put($item->{$this->getTreeNodeIdName()}, $item);
        }

        return $this->allNodes;
    }

    /**
     * 设置 所有节点.
     *
     * @param \Illuminate\Support\Collection $nodes
     */
    public function setAllNodes(Collection $nodes)
    {
        $this->allNodes = $nodes;
    }

    /**
     * 获取子级（仅子代一级）.
     *
     * @param mixed $parentId
     *
     * @return array
     */
    public function getSubLevel($parentId)
    {
        $data = $this->getAllNodes();

        $childList = collect([]);
        foreach ($data as $val) {
            if ($val->{$this->getTreeNodeParentIdName()} == $parentId) {
                $childList->put($val->{$this->getTreeNodeIdName()}, $val);
            }
        }

        return $childList;
    }

    /**
     * 获取指定节点的所有后代.
     *
     * @param mixed  $parentId
     * @param int    $depth
     * @param string $adds
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDescendants($parentId, $depth = 0, $adds = '')
    {
        static $array;
        if (!$array instanceof ArrayAccess || $depth == 0) {
            $array = collect([]);
        }
        $number = 1;
        $child = $this->getSubLevel($parentId);
        if ($child) {
            $nextDepth = $depth + 1;
            $total = $child->count();
            foreach ($child as $val) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->getTreeLastIcon();
                    $k = $this->getTreeSpacer();
                } else {
                    $j .= $this->getTreeMiddleIcon();
                    $k = $adds ? $this->getTreeFirstIcon() : '';
                }

                $val->spacer = $adds ? ($adds.$j) : '';

                $val->depth = $depth;
                $array->put($val->{$this->getTreeNodeIdName()}, $val);
                $this->getDescendants(
                    $val->{$this->getTreeNodeIdName()},
                    $nextDepth,
                    $adds.$k.$this->getTreeSpacer()
                );
                ++$number;
            }
        }

        return $array;
    }

    /**
     * 获取指定节点的所有后代（分层级）.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLayerOfDescendants($id)
    {
        $child = $this->getSubLevel($id);
        $data = collect([]);
        if ($child) {
            foreach ($child as $val) {
                $val->child = $this->getLayerOfDescendants($val->{$this->getTreeNodeIdName()});
                $data->put($val->{$this->getTreeNodeIdName()}, $val);
            }
        }

        return $data;
    }

    /**
     * 获取指定id的数据.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function getSelf($id)
    {
        $data = $this->getAllNodes();

        return $data->get($id);
    }

    /**
     * 获取父一级节点.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function getParent($id)
    {
        $node = $this->getSelf($id);
        if ($node) {
            $parentId = $node->{$this->getTreeNodeParentIdName()};

            return $this->getSelf($parentId);
        }
    }

    /**
     * 获取节点的所有祖先.
     *
     * @param int $id
     * @param int $depth
     *
     * @return array
     */
    public function getAncestors($id, $depth = 0)
    {
        static $array;
        if (!$array instanceof ArrayAccess || $depth == 0) {
            $array = collect([]);
        }
        $parent = $this->getParent($id);
        if ($parent) {
            $nextDepth = $depth + 1;
            $array->prepend($parent);   // 添加到开头
            $this->getAncestors($parent->{$this->getTreeNodeIdName()}, $nextDepth);
        }

        return $array;
    }
}
