<?php

namespace App\Common;

interface UuidGeneratorInterface
{
	public function getUuid(): UuidInterface;
}