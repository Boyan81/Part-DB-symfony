<?php

declare(strict_types=1);

/**
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 * Copyright (C) 2019 - 2020 Jan Böhmer (https://github.com/jbtronics)
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

namespace App\Entity\LogSystem;

use App\Entity\Base\AbstractDBElement;
use App\Entity\Contracts\NamedElementInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ElementDeletedLogEntry extends AbstractLogEntry
{
    protected $typeString = 'element_deleted';

    public function __construct(AbstractDBElement $deleted_element)
    {
        parent::__construct();
        $this->level = self::LEVEL_INFO;
        $this->setTargetElement($deleted_element);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function setTargetElement(?AbstractDBElement $element): AbstractLogEntry
    {
        parent::setTargetElement($element);
        if ($element instanceof NamedElementInterface) {
            $this->setOldName($element->getName());
        }
        return $this;
    }

    public function setOldName(string $old_name): self
    {
        $this->extra['n'] = $old_name;
        return $this;
    }

    public function getOldName(): ?string
    {
        return $this->extra['n'] ?? null;
    }
}
