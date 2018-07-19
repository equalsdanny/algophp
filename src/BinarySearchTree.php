<?php

namespace App;

use Exceptions\NodeDoesNotExistException;
use Exceptions\ValueNotFoundException;

class BinarySearchTree
{
    /**
     * @var stdClass|null
     */
    private $root;

    private static function createNode($value, $left, $right, $parent)
    {
        return (object)[
            'value' => $value,
            'left' => $left,
            'right' => $right,
            'parent' => $parent
        ];
    }


    public function add($value)
    {
        $this->assertLinksAreConsistent();

        if ($this->root === null) {
            $this->root = self::createNode($value, null, null, null);
            return;
        }

        $current = $this->root;
        while (true) {
            if ($value <= $current->value) {
                if ($current->left === null) {
                    $current->left = self::createNode($value, null, null, $current);
                    break;
                } else {
                    $current = $current->left;
                }
            } else {
                if ($current->right === null) {
                    $current->right = self::createNode($value, null, null, $current);
                    break;
                } else {
                    $current = $current->right;
                }
            }
        }

        $this->assertLinksAreConsistent();
    }

    public function has($value)
    {
        try {
            $this->search($value);
        } catch (ValueNotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $value
     * @throws ValueNotFoundException
     * @throws Exception if the tree's state is inconsistent
     */
    public function delete($value)
    {
        $this->assertLinksAreConsistent();

        $node = $this->search($value);
        $isRoot = $node === $this->root;
        $leftOrRight = $isRoot ? null : ($node === $node->parent->left ? 'left' : 'right');
        $hasLeftChild = $node->left !== null;
        $hasRightChild = $node->right !== null;

        if (!$hasLeftChild && !$hasRightChild) {
            // The node has no children

            if ($isRoot) {
                $this->root = null;

                $this->assertLinksAreConsistent();
                return;
            }

            // Making the node's parent forget about the node
            $node->parent->{$leftOrRight} = null;

            $this->assertLinksAreConsistent();
            return;
        }

        if (!($hasRightChild && $hasLeftChild)) {
            // The node has exactly one child
            $child = $hasLeftChild ? $node->left : $node->right;

            if ($isRoot) {
                $child->parent = null;
                $this->root = $child;

                $this->assertLinksAreConsistent();
                return;
            }

            $child->parent = $node->parent;
            $node->parent->{$leftOrRight} = $child;

            $this->assertLinksAreConsistent();
            return;
        }

        // The node has two children
        try {
            $successor = self::successorNode($node);
        } catch (NodeDoesNotExistException $e) {
            // This node has the right child, so this is impossible
            throw new Exception();
        }

        $successorLeftOrRight = $successor->parent->left === $successor ? 'left' : 'right';

        // Moving the target's left child as the successor's left child
        $this->transplant($node->left, $successor, 'left');

        if ($successor !== $node->right) {
            // If the successor is not the right child
            // Then we need to move the target's right child as the successor's right child

            if ($successor->right !== null) {
                // If the successor has the right child
                // Then we need to move the successor's right child as the successor's parent child
                $this->transplant($successor->right, $successor->parent, $successorLeftOrRight);
            } else {
                // Since we did not move the successor's right child
                // We will remove the successor from its parent
                // So that after this block is done, the successor's old parent forgot about it
                $successor->parent->{$successorLeftOrRight} = null;
            }

            // Moving the target's right child as the successor's right child
            $this->transplant($node->right, $successor, 'right');
        }

        // We are done with moving subtrees
        // Time to update the node itself
        if ($isRoot) {
            // Updating the link from new parent to successor
            $this->root = $successor;
            // Updating the link from successor to new parent
            $this->root->parent = null;
        } else {
            // Updating the link from parent to successor
            $node->parent->{$leftOrRight} = $successor;
            // Updating the link from successor to parent
            $node->parent->{$leftOrRight}->parent = $node->parent;
        }

        $this->assertLinksAreConsistent();
    }

    private function transplant($target, $newParent, $leftOrRight)
    {
        // Removing the target from its old parent (unless the target was root)
        if ($target->parent !== null) {
            $oldLeftOrRight = $target->parent->left === $target ? 'left' : 'right';
            $target->parent->{$oldLeftOrRight} = null;
        }

        // Letting the new parent know about its new child
        if ($newParent === null) {
            $this->root = $target;
        } else {
            $newParent->{$leftOrRight} = $target;
        }

        // Letting the target know about its new parent
        $target->parent = $newParent;
    }

    /**
     * @param $node
     * @return stdClass
     */
    private static function minimumNode($node)
    {
        if ($node === null) {
            throw new LengthException();
        }

        $current = $node;
        while ($current->left !== null) {
            $current = $current->left;
        }

        return $current;
    }

    private static function maximumNode($node)
    {
        if ($node === null) {
            throw new LengthException();
        }

        $current = $node;
        while ($current->right !== null) {
            $current = $current->right;
        }

        return $current;
    }

    /**
     * @param $node stdClass
     * @return stdClass
     * @throws NodeDoesNotExistException if there is no successor
     */
    private static function successorNode($node)
    {
        if ($node->right === null) {
            throw new NodeDoesNotExistException();
        }

        return self::minimumNode($node->right);
    }

    /**
     * @param $value
     * @return mixed
     * @throws ValueNotFoundException
     */
    private function search($value)
    {
        $current = $this->root;
        while ($current !== null) {
            if ($current->value === $value) {
                return $current;
            } else if ($value <= $current->value) {
                // If this is null, the loop will end
                $current = $current->left;
            } else {
                // If this is null, the loop will end
                $current = $current->right;
            }
        }

        // We could not find the value
        throw new ValueNotFoundException();
    }

    /**
     * @return mixed
     */
    public function minimum()
    {
        return self::minimumNode($this->root)->value;
    }

    /**
     * @return mixed
     */
    public function maximum()
    {
        return self::maximumNode($this->root)->value;
    }

    private function assertLinksAreConsistent()
    {
        $visited = [];

        self::traverse($this->root, function ($node) {
            // Checking that only root has no parent
            if ($node->parent === null && $this->root !== $node) {
                throw new Exception('Found node with no parent that is not root');
            }

            // Checking that this node is not its own parent
            if ($node->parent === $node) {
                throw new Exception('Found node that is its own parent');
            }

            if ($node->parent !== null) {
                // Checking that the parent knows about this node
                if ($node->parent->left !== $node && $node->parent->right !== $node) {
                    throw new Exception('Found a node whose parent does not know about its child');
                }

                // Checking that this node is in the correct subtree of its parent
                if ($node->parent->left === $node && $node->value > $node->parent->value) {
                    throw new Exception('Found node in the wrong subtree of its parent');
                } elseif ($node->parent->right === $node && $node->value <= $node->parent->value) {
                    throw new Exception('Found node in the wrong subtree of its parent');
                }
            }
        }, $visited);
    }

    /**
     * @param $node
     * @param $handler
     * @param $visited
     * @throws Exception
     */
    private static function traverse($node, $handler, &$visited)
    {
        if ($node === null) {
            // Nothing to do
            return;
        }

        if (in_array($node, $visited)) {
            throw new Exception('Found cyclic tree');
        }

        $handler($node);
        $visited[] = $node;

        self::traverse($node->left, $handler, $visited);
        self::traverse($node->right, $handler, $visited);
    }

    private static function recursiveSortedArray($node)
    {
        if ($node === null) {
            return [];
        }

        $left = self::recursiveSortedArray($node->left);
        $self = [$node->value];
        $right = self::recursiveSortedArray($node->right);
        return array_merge($left, $self, $right);
    }

    public function state()
    {
        return [$this->recursiveState($this->root)];
    }

    private function recursiveState($node)
    {
        if ($node === null) {
            return [];
        }

        return [
            'name' => (string) $node->value,
            'children' => array_values(array_filter([
                $this->recursiveState($node->left),
                $this->recursiveState($node->right)
            ]))
        ];
    }

    public function toSortedArray()
    {
        return self::recursiveSortedArray($this->root);
    }
}