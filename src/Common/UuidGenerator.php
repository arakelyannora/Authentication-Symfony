<?php

namespace App\Common;

use Symfony\Component\Uid\Uuid as SymfonyUuid;

class UuidGenerator implements UuidGeneratorInterface
{
	public function getUuid(): UuidInterface
	{
		return new Uuid(SymfonyUuid::v4());
	}
}