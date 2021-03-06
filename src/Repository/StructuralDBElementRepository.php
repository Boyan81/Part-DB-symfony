<?php

declare(strict_types=1);

/**
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 * Copyright (C) 2019 Jan Böhmer (https://github.com/jbtronics)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 */

namespace App\Repository;

use App\Entity\Base\AbstractStructuralDBElement;
use App\Helpers\Trees\StructuralDBElementIterator;
use App\Helpers\Trees\TreeViewNode;
use RecursiveIteratorIterator;

class StructuralDBElementRepository extends NamedDBElementRepository
{
    /**
     * Finds all nodes without a parent node. They are our root nodes.
     *
     * @return AbstractStructuralDBElement[]
     */
    public function findRootNodes(): array
    {
        return $this->findBy(['parent' => null], ['name' => 'ASC']);
    }

    /**
     * Gets a tree of TreeViewNode elements. The root elements has $parent as parent.
     * The treeview is generic, that means the href are null and ID values are set.
     *
     * @param AbstractStructuralDBElement|null $parent The parent the root elements should have.
     *
     * @return TreeViewNode[]
     */
    public function getGenericNodeTree(?AbstractStructuralDBElement $parent = null): array
    {
        $result = [];

        $entities = $this->findBy(['parent' => $parent], ['name' => 'ASC']);
        foreach ($entities as $entity) {
            /** @var AbstractStructuralDBElement $entity */
            //Make a recursive call to find all children nodes
            $children = $this->getGenericNodeTree($entity);
            $node = new TreeViewNode($entity->getName(), null, $children);
            //Set the ID of this entity to later be able to reconstruct the URL
            $node->setId($entity->getID());
            $result[] = $node;
        }

        return $result;
    }

    /**
     * Gets a flattened hierarchical tree. Useful for generating option lists.
     *
     * @param AbstractStructuralDBElement|null $parent This entity will be used as root element. Set to null, to use global root
     *
     * @return AbstractStructuralDBElement[] A flattened list containing the tree elements.
     */
    public function toNodesList(?AbstractStructuralDBElement $parent = null): array
    {
        $result = [];

        $entities = $this->findBy(['parent' => $parent], ['name' => 'ASC']);

        $elementIterator = new StructuralDBElementIterator($entities);
        $recursiveIterator = new RecursiveIteratorIterator($elementIterator, RecursiveIteratorIterator::SELF_FIRST);
        //$result = iterator_to_array($recursiveIterator);

        //We can not use iterator_to_array here or we get only the parent elements
        foreach ($recursiveIterator as $item) {
            $result[] = $item;
        }

        return $result;
    }
}
