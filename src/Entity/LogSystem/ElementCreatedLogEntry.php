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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ElementCreatedLogEntry extends AbstractLogEntry
{
    protected $typeString = 'element_created';

    public function __construct(AbstractDBElement $new_element)
    {
        parent::__construct();
        $this->level = self::LEVEL_INFO;
        $this->setTargetElement($new_element);
    }

    /**
     * Gets the instock when the part was created.
     *
     * @return string|null
     */
    public function getCreationInstockValue(): ?string
    {
        return $this->extra['i'] ?? null;
    }

    /**
     * Checks if a creation instock value was saved with this entry.
     *
     * @return bool
     */
    public function hasCreationInstockValue(): bool
    {
        return null !== $this->getCreationInstockValue();
    }
}
